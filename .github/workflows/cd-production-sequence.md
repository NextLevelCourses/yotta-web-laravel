```mermaid

sequenceDiagram
    participant Dev as Developer
    participant GitHub as GitHub Actions
    participant FTP as FTP Server
    participant SSH as Production Server

    Dev->>GitHub: Push to main branch
    GitHub->>GitHub: Check path filters
    GitHub->>GitHub: Checkout repository
    GitHub->>SSH: Detect Laravel installation

    alt Fresh Server
        GitHub->>FTP: Full FTP Upload (All files)
    else Existing Server
        GitHub->>FTP: Incremental FTP Upload (Sync changes)
    end

    FTP-->>GitHub: Upload success
    GitHub->>SSH: Connect via SSH
    SSH->>SSH: Check migration consistency

    alt New migrations detected
        SSH->>SSH: Backup database (mysqldump)
        SSH->>SSH: Run php artisan migrate --force
    else No migration needed
        SSH->>SSH: Skip migration
    end

    SSH->>SSH: Clear caches
    SSH->>SSH: Rebuild config, route, view cache
    SSH-->>GitHub: Deployment complete
    GitHub-->>Dev: Notify success




