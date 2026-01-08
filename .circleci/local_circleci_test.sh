#!/usr/bin/env bash
# .circleci/local_circleci_test.sh
#
# Local CI simulation:
# - Validate CircleCI config (optional)
# - Prepare .env like CI (backup existing .env)
# - docker compose up (NO BUILD by default)
# - Force recreate dashboard (so env_file changes apply!)
# - Wait MySQL readiness (mysqladmin ping)
# - Wait MySQL reachable from dashboard (PDO probe)  <-- IMPORTANT FIX
# - Copy .env -> /var/www/.env (artisan convenience)
# - Run migrate + optimize:clear
# - Health check inside dashboard container
#
# Usage:
#   chmod +x .circleci/local_circleci_test.sh
#   ./.circleci/local_circleci_test.sh
#
# Optional env overrides:
#   BUILD=1                     # enable build
#   CLEANUP_ON_EXIT=1
#   KEEP_ENV=1
#   INTERNAL_PORT=8002
#   HEALTH_PATH=/health
#   DASHBOARD_CONTAINER=yotta-dashboard
#   MYSQL_CONTAINER=yotta-mysql
#
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

CONFIG_PATH=".circleci/config.yml"
ENV_EXAMPLE=".env.example"
ENV_FILE=".env"
ENV_BACKUP=""

HEALTH_PATH="${HEALTH_PATH:-/health}"
INTERNAL_PORT="${INTERNAL_PORT:-8002}"

DASHBOARD_CONTAINER="${DASHBOARD_CONTAINER:-yotta-dashboard}"
MYSQL_CONTAINER="${MYSQL_CONTAINER:-yotta-mysql}"

log()  { printf "\n\033[1m%s\033[0m\n" "$*"; }
warn() { printf "\033[33m[WARN]\033[0m %s\n" "$*"; }
die()  { printf "\033[31m[ERR]\033[0m %s\n" "$*"; exit 1; }

# GNU sed vs BSD sed compatibility
sed_inplace() {
  local expr="$1" file="$2"
  if sed --version >/dev/null 2>&1; then
    sed -i "$expr" "$file" || true
  else
    sed -i '' "$expr" "$file" || true
  fi
}

ensure_env_kv() {
  local key="$1" value="$2" file="$3"
  if grep -qE "^${key}=" "$file"; then
    sed_inplace "s|^${key}=.*|${key}=${value}|" "$file"
  else
    echo "${key}=${value}" >> "$file"
  fi
}

restore_env() {
  if [[ "${KEEP_ENV:-0}" == "1" ]]; then
    warn "KEEP_ENV=1 set: not restoring .env backup."
    return 0
  fi
  if [[ -n "${ENV_BACKUP}" && -f "${ENV_BACKUP}" ]]; then
    log "Restoring original .env from backup: ${ENV_BACKUP}"
    mv -f "${ENV_BACKUP}" "${ENV_FILE}" || true
  fi
}

cleanup() {
  if [[ "${CLEANUP_ON_EXIT:-0}" == "1" ]]; then
    log "Cleanup: docker compose down -v --remove-orphans"
    docker compose down -v --remove-orphans || true
  fi
  restore_env
}
trap cleanup EXIT

container_id_by_name() {
  local name="$1"
  docker ps -qf "name=^/${name}$" | head -n 1 || true
}

require_running_container() {
  local name="$1"
  local cid
  cid="$(container_id_by_name "$name")"
  [[ -n "$cid" ]] || die "Container not running: ${name}. Check: docker ps"
  echo "$cid"
}

gen_app_key() {
  command -v openssl >/dev/null 2>&1 || die "openssl not found (needed to generate APP_KEY). Install openssl."
  printf "base64:%s" "$(openssl rand -base64 32 | tr -d '\n')"
}

wait_mysql_reachable_from_dashboard() {
  local dash_cid="$1"
  local tries="${2:-40}"

  log "Step 5b/7: Wait for MySQL reachable from dashboard (PDO probe)"
  for i in $(seq 1 "$tries"); do
    if docker exec "$dash_cid" sh -lc 'php -r "
      \$host=getenv(\"DB_HOST\")?:\"mysql\";
      \$port=getenv(\"DB_PORT\")?:\"3306\";
      \$db=getenv(\"DB_DATABASE\")?:\"yotta_db\";
      \$u=getenv(\"DB_USERNAME\")?:\"yotta\";
      \$p=getenv(\"DB_PASSWORD\")?:\"yotta_pass\";
      try {
        new PDO(\"mysql:host={\$host};port={\$port};dbname={\$db}\", \$u, \$p, [
          PDO::ATTR_TIMEOUT => 2,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        exit(0);
      } catch (Exception \$e) {
        exit(1);
      }
    "' >/dev/null 2>&1; then
      echo "✅ MySQL reachable from dashboard"
      return 0
    fi
    echo "⏳ DB not reachable yet... (try $i/$tries)"
    sleep 2
  done

  warn "DB still not reachable from dashboard after retries."
  docker logs --tail=200 "$MYSQL_CONTAINER" || true
  return 1
}

log "Step 0/7: Pre-checks"
command -v docker >/dev/null || die "docker not found"
docker compose version >/dev/null || die "docker compose v2 not found"
[[ -f "$CONFIG_PATH" ]] || die "CircleCI config not found at $CONFIG_PATH"
[[ -f "$ENV_EXAMPLE" ]] || die ".env.example not found at $ENV_EXAMPLE"
[[ -f "docker-compose.yml" ]] || die "docker-compose.yml not found in repo root"

log "Step 1/7: Validate CircleCI config (optional)"
if command -v circleci >/dev/null 2>&1; then
  circleci config validate "$CONFIG_PATH"
else
  warn "circleci CLI not installed - skipping validate."
fi

log "Step 2/7: Prepare .env (simulate CI job, with backup if .env exists)"
if [[ -f "$ENV_FILE" ]]; then
  ENV_BACKUP=".env.backup.$(date +%Y%m%d_%H%M%S)"
  log "Backing up existing .env -> ${ENV_BACKUP}"
  cp -f "$ENV_FILE" "$ENV_BACKUP"
fi

cp "$ENV_EXAMPLE" "$ENV_FILE"

# Align DB settings to docker compose service name "mysql"
ensure_env_kv "DB_HOST" "mysql" "$ENV_FILE"
ensure_env_kv "DB_PORT" "3306" "$ENV_FILE"
ensure_env_kv "DB_DATABASE" "yotta_db" "$ENV_FILE"
ensure_env_kv "DB_USERNAME" "yotta" "$ENV_FILE"
ensure_env_kv "DB_PASSWORD" "yotta_pass" "$ENV_FILE"
ensure_env_kv "MYSQL_ROOT_PASSWORD" "root" "$ENV_FILE"

# Ensure APP_KEY is valid & non-empty (prevents MissingAppKeyException)
APP_KEY_VALUE="$(gen_app_key)"
ensure_env_kv "APP_KEY" "$APP_KEY_VALUE" "$ENV_FILE"

log "Step 3/7: docker compose up (NO BUILD by default) + force recreate dashboard (apply env_file changes)"
# Start mysql first
docker compose up -d mysql

# Recreate dashboard so new env (.env) is applied into container env vars
if [[ "${BUILD:-0}" == "1" ]]; then
  log "BUILD=1 -> docker compose up -d --build --force-recreate dashboard"
  docker compose up -d --build --force-recreate dashboard
else
  docker compose up -d --no-build --force-recreate dashboard
fi

docker compose ps

log "Step 4/7: Wait for MySQL readiness (container: $MYSQL_CONTAINER)"
MYSQL_CID="$(require_running_container "$MYSQL_CONTAINER")"

READY=0
for i in {1..40}; do
  if docker exec "$MYSQL_CID" sh -lc 'mysqladmin ping -h localhost -p"$MYSQL_ROOT_PASSWORD" --silent' >/dev/null 2>&1; then
    READY=1
    echo "✅ MySQL is ready"
    break
  fi
  echo "⏳ MySQL not ready yet... (try $i/40)"
  sleep 2
done

if [[ "$READY" -ne 1 ]]; then
  warn "MySQL did not become ready in time. Showing logs..."
  docker logs --tail=200 "$MYSQL_CONTAINER" || true
  die "MySQL readiness check failed"
fi

log "Step 5/7: Ensure /var/www/.env exists inside dashboard container (artisan convenience)"
DASH_CID="$(require_running_container "$DASHBOARD_CONTAINER")"
docker cp "$ENV_FILE" "$DASH_CID:/var/www/.env"

# IMPORTANT FIX: ensure mysql is actually reachable from dashboard before migrate
wait_mysql_reachable_from_dashboard "$DASH_CID" 40 || die "DB not reachable from dashboard"

log "Step 6/7: Laravel validate (migrate + optimize:clear)"
docker exec "$DASH_CID" sh -lc 'php -r "echo \"APP_KEY=\".(getenv(\"APP_KEY\")?:\"<empty>\").PHP_EOL; echo \"DB_HOST=\".(getenv(\"DB_HOST\")?:\"<empty>\").PHP_EOL;"' || true
docker exec "$DASH_CID" sh -lc 'php artisan migrate --force --no-interaction'
docker exec "$DASH_CID" sh -lc 'php artisan optimize:clear || true'

log "Step 7/7: Health check inside dashboard container"
for i in {1..12}; do
  STATUS="$(docker exec "$DASH_CID" sh -lc "curl -s -o /dev/null -w '%{http_code}' http://localhost:${INTERNAL_PORT}${HEALTH_PATH} || echo 000")"
  if [[ "$STATUS" == "200" ]]; then
    echo "✅ Health check OK: http://localhost:${INTERNAL_PORT}${HEALTH_PATH} (200)"
    echo
    echo "DONE ✅ Local CI simulation succeeded."
    echo
    echo "Tips:"
    echo "  - enable build:          BUILD=1 ./.circleci/local_circleci_test.sh"
    echo "  - auto cleanup:          CLEANUP_ON_EXIT=1 ./.circleci/local_circleci_test.sh"
    echo "  - keep generated .env:   KEEP_ENV=1 ./.circleci/local_circleci_test.sh"
    exit 0
  fi
  echo "⏳ Health status=$STATUS (try $i/12)"
  sleep 5
done

warn "Health check failed. Showing diagnostics..."
docker exec "$DASH_CID" sh -lc "curl -i http://localhost:${INTERNAL_PORT}${HEALTH_PATH} || true" || true
docker exec "$DASH_CID" sh -lc 'tail -n 200 storage/logs/laravel.log || true' || true
docker logs --tail=200 "$DASHBOARD_CONTAINER" || true
die "Health check failed after retries"
