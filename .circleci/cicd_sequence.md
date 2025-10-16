```mermaid
    sequenceDiagram
        participant Developer
        participant CI_System
        participant DockerHub
        participant Staging_Server
        participant MySQL_Container
        participant Dashboard_Container

        Developer->>CI_System: Push to 🧩 development branch 
        CI_System->>CI_System: Checkout code
        CI_System->>CI_System: Setup remote Docker
        CI_System->>CI_System: Create .env file
        CI_System->>MySQL_Container: Start MySQL service (docker-compose)
        CI_System->>Dashboard_Container: Start Dashboard service (docker-compose)
        CI_System->>MySQL_Container: Wait MySQL ready
        CI_System->>Dashboard_Container: Run php artisan package:discover
        CI_System->>Dashboard_Container: Generate Laravel APP_KEY
        CI_System->>Dashboard_Container: Laravel optimize (clear & cache)
        CI_System->>Dashboard_Container: Run Laravel migrations
        CI_System->>Dashboard_Container: Health check /health endpoint
        alt Tests successful and tag matches
            CI_System->>DockerHub: Docker login
            CI_System->>CI_System: Build Docker image with tag and latest
            CI_System->>DockerHub: Push Docker image
            CI_System->>Staging_Server: SSH connect
            Staging_Server->>DockerHub: Pull latest Docker image
            Staging_Server->>Staging_Server: Stop old containers
            Staging_Server->>Staging_Server: Start new dashboard container
            Staging_Server->>Dashboard_Container: Copy .env to container
            Staging_Server->>Dashboard_Container: Run php artisan package:discover
            Staging_Server->>Dashboard_Container: Run Laravel optimize
            Staging_Server->>Dashboard_Container: Run Laravel migrations
            Staging_Server->>Staging_Server: Check public domain APP_URL
            Staging_Server->>CI_System: Deployment success
        else Tests failed or not version tag
            CI_System->>Developer: Pipeline failed or stopped
        end
