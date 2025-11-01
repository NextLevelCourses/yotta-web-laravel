```mermaid
flowchart TD
    A([Push to main branch]) --> B{Files match path filters?}
    B -->|No| Z([Skip deployment])
    B -->|Yes| C([Checkout repository])

    C --> D([Debug commit message])
    D --> E{Detect Laravel installation on server}

    E -->|Fresh server| F1([Full FTP Upload - All Files])
    E -->|Existing server| F2([Incremental FTP Upload - Changed Files])

    F1 --> G([Confirm FTP Upload])
    F2 --> G

    G --> H{FTP Upload Successful?}
    H -->|No| X([Stop deployment - FTP Error])
    H -->|Yes| I([SSH into Production Server])

    I --> J([Check Laravel migration consistency])
    J -->|New migrations detected| K([Backup Database])
    J -->|No new migrations| L([Skip migration])

    K --> M([Run php artisan migrate --force])
    L --> N([Clear Laravel caches])
    M --> N

    N --> O([Rebuild config, route, and view caches])
    O --> P([Deployment Completed Successfully])

