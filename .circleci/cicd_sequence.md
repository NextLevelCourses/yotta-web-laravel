# 🌀 CircleCI CI/CD Pipeline – Yotta Dashboard

Diagram berikut menggambarkan urutan proses otomatis dari file `config.yml` di atas menggunakan **Mermaid Sequence Diagram**:

```mermaid
sequenceDiagram
    autonumber
    actor Dev as Developer
    participant CI as CircleCI
    participant Docker as Docker Engine
    participant Hub as Docker Hub
    participant Staging as Staging Server

    Dev->>CI: Push ke branch `master`
    Note right of CI: Workflow `ci-cd` dimulai<br>Trigger: push ke branch master

    %% ===== Testing Stage =====
    CI->>CI: Job `Testing` dimulai
    CI->>Docker: setup_remote_docker (enable Docker-in-Docker)
    CI->>CI: Generate `.env` via `create-env`
    CI->>Docker: docker compose up mysql & dashboard
    Docker->>Docker: Start containers yotta-mysql & yotta-dashboard
    CI->>Docker: Wait for MySQL to be ready
    CI->>Docker: php artisan package:discover
    CI->>Docker: generate APP_KEY via artisan key:generate
    CI->>Docker: laravel-optimize (clear & cache configs)
    CI->>Docker: laravel-migrate (migrate database)
    Docker->>CI: ✅ Laravel ready
    CI->>Docker: Health check /health endpoint
    CI->>Dev: ✅ Job `Testing` selesai sukses

    %% ===== Push-To-Docker Stage =====
    CI->>CI: Job `Push-To-Docker` dimulai
    CI->>Hub: docker login (DOCKERHUB_USERNAME + TOKEN)
    CI->>Docker: Build image tagged with latest Git tag
    Docker->>Hub: Push image → ${DOCKERHUB_REPO}:<TAG> & latest
    Hub->>CI: ✅ Push sukses
    CI->>Dev: ✅ Docker image uploaded

    %% ===== Deploy-Staging Stage =====
    CI->>Staging: SSH ke server staging
    Staging->>Staging: docker pull latest image
    Staging->>Staging: stop/remove old containers
    Staging->>Staging: docker compose up dashboard
    Staging->>Staging: copy .env ke container
    Staging->>Staging: php artisan package:discover
    Staging->>Staging: make dy-laravel-optimize-all
    Staging->>Staging: php artisan migrate --force
    Staging->>Staging: curl ${APP_URL} for health check
    Staging->>CI: ✅ Deployment to STAGING completed
    CI->>Dev: 🎉 Pipeline sukses sepenuhnya
