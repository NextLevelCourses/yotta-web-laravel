

# Yotta Aksara IoT Dashboard

[Quick Start](#quick-start-docker) | [Manual Install](#alternative-manual-local-installation) | [Services & Ports](#services-and-ports) | [Troubleshooting](#troubleshooting) | [License](#license)


An IoT dashboard built with Laravel 12, Livewire, and Vite for Yotta Aksara Energi. This project provides real-time monitoring and management for air quality, soil tests, and user administration, integrating MQTT for device communication.
## Services and Ports

| Service      | Description                | Host Port | Container Port | Notes                       |
|--------------|---------------------------|-----------|---------------|-----------------------------|
| dashboard    | Laravel app (main)        | 8002      | 8002          | http://localhost:8002       |
| mysql        | MySQL database            | 4406      | 3306          | DB: yotta_db, user: yotta   |
| phpmyadmin   | DB Admin UI               | 8001      | 80            | http://localhost:8001       |

---

## Features

- Real-time device data via MQTT
- Air Quality and Soil Test monitoring
- User Management (Admin & User roles)
- Action Logging
- Livewire-powered interactive UI
- Dockerized for easy deployment
- Vite for modern frontend asset building


## Requirements

- **PHP**: >= 8.2
- **Composer**: Latest version
- **Node.js**: >= 18.x (for frontend build)
- **Docker** & **Docker Compose** (optional, for containerized setup)
- **PHP Extensions** (minimum required):
    - openssl
    - bcmath
    - curl
    - json
    - mbstring
    - mysql
    - tokenizer
    - xml
    - zip
    - grpc




## Quick Start (Docker)

The recommended way to run this project is using Docker. This ensures all dependencies and services are set up automatically.

1. **Clone the repository:**
    ```bash
    git clone https://github.com/teguhafrianda/yottaaksara-iot-dahsboard.git
    cd yottaaksara-iot-dahsboard
    ```


2. **Environment file:**
    - By default, the Docker build will copy `.env.example` to `.env` if `.env` does not exist. You can override or edit `.env` as needed before building.
    - To override environment variables (e.g., ports, DB credentials), edit `.env` or set them in `docker-compose.yml` under the `environment:` section for each service.
## Troubleshooting

- **Port already in use:**
    - Make sure ports 8002 (app), 8001 (phpMyAdmin), and 4406 (MySQL) are free or change them in `docker-compose.yml`.
- **Database connection errors:**
    - Ensure the `mysql` service is running and the credentials in `.env` match those in `docker-compose.yml`.
- **File permission issues:**
    - You may need to adjust permissions for `storage/` and `bootstrap/cache/`:
      ```bash
      sudo chown -R $USER:$USER storage bootstrap/cache
      chmod -R 775 storage bootstrap/cache
      ```
- **Composer memory errors:**
    - The Dockerfile uses `COMPOSER_MEMORY_LIMIT=-1` to avoid memory issues. If you see errors, try rebuilding the containers.

3. **Place your Firebase credentials:**
    ```bash
    cp firebase_credentials.json storage/app/
    ```

4. **Start the application:**
    ```bash
    docker-compose up --build
    ```

5. **Access the app:**
    - Visit http://localhost:8002 (or as configured in `docker-compose.yml`).
    - The app service is named `dashboard` in Docker Compose.
    - The default `APP_URL` is set to https://dev-yotta.quantummute.com (see `docker-compose.yml`).

6. **phpMyAdmin (optional):**
    - Access phpMyAdmin at http://localhost:8001 (user: `yotta`, password: `yotta_pass`).

7. **Database credentials (default):**
    - Host: `mysql`
    - Port: `3306` (internal), `4406` (host)
    - Database: `yotta_db`
    - Username: `yotta`
    - Password: `yotta_pass`

8. **Run migrations and seed database (in another terminal):**
    ```bash
    docker-compose exec dashboard php artisan migrate --seed
    ```

9. **Run tests (optional):**
    ```bash
    docker-compose exec dashboard php artisan test
    ```

---

## Alternative: Manual (Local) Installation

If you prefer not to use Docker, follow these steps:

1. **Install PHP, Composer, Node.js, and required PHP extensions**
2. **Clone the repository:**
    ```bash
    git clone https://github.com/teguhafrianda/yottaaksara-iot-dahsboard.git
    cd yottaaksara-iot-dahsboard
    ```
3. **Install PHP dependencies:**
    ```bash
    composer install
    ```
4. **Install Node.js dependencies & build frontend:**
    ```bash
    npm install
    npm run build
    ```
5. **Copy .env and set up environment:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
6. **Place your Firebase credentials:**
    ```bash
    cp firebase_credentials.json storage/app/
    ```
7. **Run migrations and seed database:**
    ```bash
    php artisan migrate --seed
    ```
8. **Run the app:**
    ```bash
    php artisan serve
    ```
9. **Run tests (optional):**
    ```bash
    php artisan test
    ```



## Performance Optimization (Manual Install)

If running locally (not Docker), optimize performance with:
```bash
php artisan optimize
php artisan route:cache
php artisan view:clear
php artisan cache:clear
```

## Frontend Development

For development with hot reload (manual install):
```bash
npm run dev
```