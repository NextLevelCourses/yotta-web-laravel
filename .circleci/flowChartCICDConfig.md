```mermaid
flowchart TD

%% === CI/CD PIPELINE FLOW ===
    A([🚀 Push ke branch development]) --> B[⚙️ CircleCI Workflow ci-cd]

%% --- Testing Job ---
    B --> C1([🧪 Job: Testing])
    C1 --> C2[Generate .env file]
    C2 --> C3[Docker Compose up - MySQL and Dashboard]
    C3 --> C4[Wait MySQL readiness]
    C4 --> C5[php artisan package:discover]
    C5 --> C6[Generate APP_KEY]
    C6 --> C7[Laravel Optimize - cache configs]
    C7 --> C8[Laravel Migrate --force]
    C8 --> C9{Health Check /health OK?}
    C9 -- Yes --> D1([✅ Testing Passed])
    C9 -- No --> D2([❌ Stop pipeline])

%% --- Push Image Job ---
    D1 --> E1([🐳 Job: Push-To-Docker])
    E1 --> E2[Docker Login]
    E2 --> E3[Build Image - tag + latest]
    E3 --> E4[Push image to DockerHub]
    E4 --> F1([✅ Image pushed successfully])

%% --- Deploy Staging Job ---
    F1 --> G1([☁️ Job: Deploy-Staging])
    G1 --> G2[SSH to Staging Server]
    G2 --> G3[Prepare directories and logs]
    G3 --> G4[Check running containers]
    G4 --> G5{Dashboard container running?}
    G5 -- Yes --> G6[Stop and remove old container]
    G5 -- No --> G7[Proceed with fresh setup]
    G6 --> G8[Pull latest image from DockerHub]
    G7 --> G8
    G8 --> G9[docker compose up -d dashboard]
    G9 --> G10[Copy .env to container]
    G10 --> G11[php artisan package:discover]
    G11 --> G12[Laravel Optimize]
    G12 --> G13{New migrations found?}
    G13 -- Yes --> G14[Run migrate --force]
    G14 --> G15{Migration success?}
    G15 -- Yes --> G17[✅ Migrations OK]
    G15 -- No --> G16[Rollback and retry migration]
    G16 --> G17
    G13 -- No --> G17
    G17 --> G18[Auto update APP_URL and APP_NAME]
    G18 --> G19[Health check /health]
    G19 --> G20{HTTP 200 OK?}
    G20 -- Yes --> H1([🎉 Deployment Success])
    G20 -- No --> H2([⚠️ Check logs for failure])

%% --- END ---
    H1 --> Z([🏁 Pipeline Complete])
    H2 --> Z

