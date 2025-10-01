```mermaid
sequenceDiagram
    autonumber
    participant Dev as Developer
    participant Git as GitHub/GitLab
    participant CI as CircleCI
    participant DockerHub as Docker Hub
    participant Staging as Staging Server
    participant Prod as Production Server
    participant App as Laravel App (Container)

    Dev->>Git: Push commit / PR
    Git->>CI: Trigger workflow (ci-cd)

    %% ========== Testing Job ==========
    CI->>CI: Checkout repository
    CI->>CI: setup_remote_docker
    CI->>CI: Copy .env (from example if missing)
    CI->>CI: Run docker-compose up -d
    CI->>CI: Wait until MySQL service is ready
    CI->>CI: create-and-inject-env (generate APP_KEY)
    CI->>App: laravel-optimize (clear & cache config/routes/views)
    CI->>App: laravel-migrate (php artisan migrate --force)
    CI->>App: Health Check `/health` (max 30 attempts)
    CI-->>CI: ✅ Testing passed

    %% ========== Push-To-Docker ==========
    CI->>DockerHub: docker-login (with DOCKERHUB_TOKEN)
    CI->>CI: Fetch Git tags
    CI->>CI: Build Docker image (tag = latest + git tag)
    CI->>DockerHub: Push image to repo
    CI-->>CI: ✅ Docker image available

    %% ========== Deploy-Staging ==========
    CI->>Staging: SSH into server ($SERVER_USER@$SERVER_IP)
    Staging->>Staging: Ensure .env exists
    Staging->>DockerHub: Pull latest Docker image
    Staging->>App: Stop old container (docker compose stop/rm dashboard)
    Staging->>App: Start new container (docker compose up -d dashboard)
    Staging->>App: Health check `/health` (curl 200 OK)
    Staging->>App: Run `make dy-laravel-optimize-all`
    Staging->>App: Run `php artisan migrate --force`
    Staging->>App: Run `php artisan db:seed --force`
    Staging-->>CI: ✅ Deploy finished (Staging)

    %% ========== Deploy-Production ==========
    CI->>Prod: SSH into server ($SERVER_USER@$PROD_IP)
    Prod->>Prod: Ensure .env exists
    Prod->>DockerHub: Pull latest Docker image
    Prod->>App: Stop old container (docker compose stop/rm dashboard)
    Prod->>App: Start new container (docker compose up -d dashboard)
    Prod->>App: Health check `/health` (curl 200 OK)
    Prod->>App: Run `make dy-laravel-optimize-all`
    Prod->>App: Run `php artisan migrate --force`
    Prod->>App: Run `php artisan db:seed --force`
    Prod-->>CI: ✅ Deploy finished (Production)

    CI-->>Dev: 🎉 Pipeline completed (Staging & Production updated)