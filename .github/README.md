# 🏗️ Yotta Aksara Production Deployment

This GitHub Actions workflow automatically deploys the Laravel application to the **production server** whenever you push to the `main` branch.  

It uploads files via **FTP/FTPS** and runs **Laravel optimization commands** via SSH.  

---

## ⚡ Features

- Automatically triggered by push to `main` branch  
- FTP/FTPS upload of Laravel project files  
- Excludes non-production files: `tests`, `node_modules`, `logs`, `README.md`, etc.  
- Runs Laravel optimization via SSH: `config:cache`, `route:cache`, `view:cache`, etc.  
- Optional: run `php artisan migrate --force` (commented by default)  

---

## 🚀 Deployment Sequence

```mermaid
sequenceDiagram
    autonumber
    actor Dev as Developer
    participant GitHub as GitHub Actions
    participant FTP as Production FTP Server
    participant Prod as Production Laravel Server

    Dev->>GitHub: Push to `main` branch
    GitHub->>GitHub: Detect push & relevant paths changed

    GitHub->>FTP: Connect via FTPS
    GitHub->>FTP: Upload project files
    FTP-->>GitHub: Confirm upload

    alt FTP Failed
        GitHub->>Dev: ❌ Deployment failed
    else FTP Success
        GitHub->>Dev: ✅ Files uploaded
        GitHub->>Prod: SSH into server
        Prod->>Prod: Clear caches
        Prod->>Prod: Rebuild config, route, view caches
        %% Optional migrations
        %% Prod->>Prod: php artisan migrate --force
        Prod-->>GitHub: ✅ Deployment finished
        GitHub->>Dev: 🎉 Production deployment successful
    end
````

---

## 🛠 How it Works

1. **Trigger**: Push to `main` branch
2. **FTP Deployment**: Upload project files to production server
3. **SSH Laravel Optimization**: Clear & rebuild caches
4. **Optional Migration**: Uncomment migration step if needed
5. **Result**: Logs confirm success or failure

---

## 🔑 Required Secrets

| Secret           | Purpose                             |
| ---------------- | ----------------------------------- |
| `FTP_SERVER`     | FTP server hostname or IP           |
| `FTP_USERNAME`   | FTP username                        |
| `FTP_PASSWORD`   | FTP password                        |
| `FTP_SERVER_DIR` | Server path to deploy Laravel files |
| `SSH_HOST`       | SSH host / server IP                |
| `SSH_USERNAME`   | SSH user                            |
| `SSH_PASSWORD`   | SSH password                        |
| `SSH_PORT`       | SSH port (default 22)               |

---

## ✅ Notes

* FTPS is recommended for secure file transfer; fallback to plain FTP if server does not support TLS
* Ensure the production server has **PHP, Composer, and required extensions** installed
* Adjust `exclude` paths in workflow if needed to avoid uploading unnecessary files
* Uncomment migration step only if schema changes need to be applied
* Workflow only triggers on **main branch**, keeping production stable