# 🚀 Demo Aksara Yotta Deployment — Sequence Flow

```mermaid
sequenceDiagram
    autonumber
    actor Dev as 👨‍💻 Developer
    participant GH as ⚙️ GitHub Actions
    participant FTP as 📤 FTP Server (46.202.138.81)
    participant SSH as 🖥️ Laravel App Server

    Dev->>GH: Push to master (with code changes)
    GH->>GH: Trigger workflow (🚀 Demo Aksara Yotta Deployment)

    GH->>GH: Step 1️⃣ Checkout repository
    GH->>GH: Step 2️⃣ Verify repo contents (ls -lah)

    GH->>FTP: Step 3️⃣ Upload files via FTP
    alt ✅ FTP success
        FTP-->>GH: Upload finished
        GH->>SSH: Step 4️⃣ SSH into server
        SSH->>SSH: Run php artisan migrate --force
        SSH->>SSH: Clear caches (config, cache, route, view)
        SSH->>SSH: Rebuild caches (config, route, view)
        SSH-->>GH: ✅ Deployment finished successfully
    else ❌ FTP failed
        FTP-->>GH: FTP login/upload failed
        GH->>Dev: 🔴 Stop workflow with error log
    end
