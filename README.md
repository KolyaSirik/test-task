# 🌐 Domain Monitoring System

A premium, modern web application built with **Laravel 13**, **Vue 3**, and **Inertia.js** for real-time domain availability monitoring.

## ✨ Features

-   **Domain Management**: Add, edit, and delete domains with custom monitoring settings.
-   **Automated Monitoring**: Background checks for HTTP status (UP/DOWN) with configurable intervals and timeouts.
-   **Detailed Logs**: Comprehensive history of every check including response codes, times, and error messages.
-   **Real-time Notifications**: 
    -   In-app notification bell with real-time status updates.
    -   Email alerts when a domain status changes.
-   **Premium UI/UX**: Built with a sleek, responsive design using Tailwind CSS, Radix UI (Reka UI), and Lucide icons.
-   **Type-safe Routing**: Integrated with **Laravel Wayfinder** for seamless frontend/backend route synchronization.

## 🚀 Getting Started

### Prerequisites

-   **PHP 8.5+**
-   **Node.js 22+**
-   **Docker & Docker Compose**
-   **Composer**

### Quick Setup

If you have `make` installed, you can set up everything with a single command:

```bash
make setup
```

This will install dependencies, set up `.env`, start Docker containers, and run migrations.

### Manual Installation

2.  **Install dependencies**:
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Start Infrastructure (Docker)**:
    ```bash
    docker compose up -d
    ```

5.  **Database Migration**:
    ```bash
    php artisan migrate
    ```

## 🛠 Development

To start the development server (Vite, Laravel, Queue, and Scheduler):

```bash
composer run dev
```

This command runs the following services concurrently:
-   **Server**: `php artisan serve`
-   **Queue**: `php artisan queue:listen`
-   **Scheduler**: `php artisan schedule:work` (for automated domain checks)
-   **Logs**: `php artisan pail`
-   **Vite**: `npm run dev`

## 🤖 Automated Checks

To manually trigger a check of all domains:

```bash
php artisan app:check-domains
```

The system is configured to use **PostgreSQL** interval logic for accurate check timing.

## 📦 Tech Stack

-   **Backend**: Laravel 13 (Fortify for Auth)
-   **Frontend**: Vue 3, Inertia.js v3
-   **Database**: PostgreSQL 16 (via Docker)
-   **Styling**: Tailwind CSS v4
-   **Routing**: Laravel Wayfinder
-   **Icons**: Lucide Vue Next

---
*Built as a test task with focus on clean architecture and premium user experience.*