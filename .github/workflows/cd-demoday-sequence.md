```mermaid

sequenceDiagram
    autonumber
    actor Dev as Developer
    participant GH as GitHub Actions
    participant FTP as FTP Server
    participant SSH as Production Server

    Dev->>GH: Push to <code>master</code> branch
    Note right of GH: Trigger GitHub Action<br>based on `on.push.branches: master`

    GH->>GH: Checkout repository<br>using actions/checkout@v4
    GH->>GH: Verify repository contents (ls -lah)

    GH->>FTP: Deploy project via SamKirkland/FTP-Deploy-Action
    Note right of FTP: Upload only necessary files<br>Exclude: tests, node_modules,<br>dockerfile, README, etc.

    alt FTP upload failed
        GH->>Dev: ❌ "FTP login or upload failed"
    else FTP upload success
        GH->>Dev: ✅ "FTP upload finished"
        GH->>SSH: Connect via appleboy/ssh-action
        SSH->>SSH: Run Laravel optimization commands
        SSH->>SSH: (Optional) php artisan migrate --force
        SSH->>SSH: Clear & rebuild caches
        SSH->>Dev: ✅ "Deployment finished successfully!"
    end
