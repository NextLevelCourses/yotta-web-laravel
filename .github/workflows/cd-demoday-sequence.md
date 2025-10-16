```mermaid

sequenceDiagram
    participant Developer
    participant GitHubActions
    participant FTP_Server
    participant Production_Server

    Developer->>GitHubActions: Push to main branch (with specified paths)
    GitHubActions->>GitHubActions: Checkout repository
    GitHubActions->>GitHubActions: Verify repo contents (ls -lah)
    GitHubActions->>FTP_Server: Connect via FTPS and deploy files
    FTP_Server-->>GitHubActions: Upload success or failure
    alt Upload success
        GitHubActions->>Production_Server: SSH connect
        Production_Server->>Production_Server: cd to deployment directory
        Production_Server->>Production_Server: Run Laravel migrations (optional)
        Production_Server->>Production_Server: Clear Laravel caches (config, route, view)
        Production_Server->>Production_Server: Cache Laravel config, route, view
        Production_Server->>GitHubActions: Report deployment success
    else Upload failed
        GitHubActions->>Developer: Report FTP upload failure and stop
    end

