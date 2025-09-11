# Laravel 12 Base Project IOT Documentation : YOTTA AKSARA ENERGI

## Requirements

-   **PHP**: >= 8.2
-   **Composer**: Latest version
-   **PHP Extensions** (minimum required):
    -   openssl
    -   bcmath
    -   curl
    -   json
    -   mbstring
    -   mysql
    -   tokenizer
    -   xml
    -   zip
    -   grpc

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/teguhafrianda/yottaaksara-iot-dahsboard.git
    cd yottaaksara-iot-dahsboard.git

    ```

2. **Install composer:**
    ```bash
    composer install
    ```

## Database

1. **Migrate:**

    ```bash
    php artisan migrate
    ```

2. **Seed:**

    ```bash
    php artisan db:seed
    ```

## Credential

1. **Import .env:**

    ```bash
    cp .env.example .env #copy all env documentation to .env file
    ```

2. **Key generate:**

    ```bash
    php artisan key:generate
    ```

## Fix Performance

1. **Import .env:**

    ```bash
    php artisan optimize
    php artisan route:cache
    php artisan view:clear
    php artisan cache:clear
    ```

## Load JSON Firebase

```bash
copy firebase_credentials.json > storage/app
```
