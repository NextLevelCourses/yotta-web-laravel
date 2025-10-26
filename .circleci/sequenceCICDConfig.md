```mermaid
sequenceDiagram
    autonumber

    participant Dev as 👨‍💻 Developer
    participant CircleCI as 🌀 CircleCI Pipeline
    participant DockerHub as 🐳 DockerHub
    participant Staging as ☁️ Staging Server
    participant Laravel as ⚙️ Laravel App (Container)
    participant MySQL as 🗄️ MySQL Container

    %% === TRIGGER ===
    Dev->>CircleCI: Push ke branch `development` atau tag `vX.Y.Z`
    Note over CircleCI: Trigger Workflow `ci-cd`

    %% === JOB 1: TESTING ===
    CircleCI->>CircleCI: Job 1️⃣ **Testing**
    CircleCI->>CircleCI: Generate .env (create-env)
    CircleCI->>DockerHub: Setup Remote Docker
    CircleCI->>MySQL: docker compose up -d mysql
    CircleCI->>Laravel: docker compose up -d dashboard
    CircleCI->>MySQL: Wait for readiness (SELECT 1)
    CircleCI->>Laravel: php artisan package:discover
    CircleCI->>Laravel: php artisan key:generate --show
    CircleCI->>Laravel: Optimize & Cache (config, route, view)
    CircleCI->>Laravel: Run php artisan migrate --force
    CircleCI->>Laravel: Health Check (GET /health)
    Note over CircleCI: ✅ Jika sukses → lanjut ke Push-To-Docker

    %% === JOB 2: PUSH IMAGE ===
    CircleCI->>CircleCI: Job 2️⃣ **Push-To-Docker**
    CircleCI->>DockerHub: docker login
    CircleCI->>CircleCI: Build Image dengan tag versi dan latest
    CircleCI->>DockerHub: Push ${DOCKERHUB_REPO}:version
    CircleCI->>DockerHub: Push ${DOCKERHUB_REPO}:latest
    Note over DockerHub: ✅ Image terbaru tersimpan di registry

    %% === JOB 3: DEPLOY STAGING ===
    CircleCI->>Staging: Job 3️⃣ **Deploy-Staging** via SSH
    Note over Staging: Setup logging + cleanup log lama
    Staging->>Staging: Siapkan direktori (html, storage, logs, dsb)
    Staging->>DockerHub: docker pull ${DOCKERHUB_REPO}:latest
    alt 🚀 Jika container dashboard sudah ada
        Staging->>Staging: Stop & Remove old container
        Staging->>Staging: Run new container (rolling update)
    else 🆕 Jika fresh setup
        Staging->>Staging: Run dashboard pertama kali
    end

    %% === POST DEPLOY STEPS ===
    Staging->>Laravel: Copy .env → container
    Staging->>Laravel: php artisan package:discover
    Staging->>Laravel: make dy-laravel-optimize-all
    Staging->>Laravel: php artisan migrate --force
    alt Jika migrate gagal
        Staging->>Laravel: migrate:rollback + migrate ulang
    else
        Note right of Laravel: ✅ Migration sukses
    end

    %% === SMART MIGRATION CHECK ===
    Staging->>Laravel: Cek difference FS vs DB migrations
    alt Ada migration baru
        Staging->>Laravel: Jalankan migrate --force
    else
        Note right of Laravel: Skip migration
    end

    %% === AUTO SERVER CONFIG ===
    Staging->>Laravel: Update APP_URL & APP_NAME otomatis
    Staging->>Laravel: php artisan config:clear + config:cache

    %% === HEALTH CHECK ===
    Staging->>Laravel: curl $APP_URL/health
    alt HTTP 200
        Staging->>CircleCI: ✅ Deployment sukses!
    else
        Staging->>CircleCI: ❌ Health check failed
    end

    Note over Dev,Staging: 🔄 Pipeline: Testing → Push → Deploy (Auto Smart Deploy)
