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
-   **Node.js 20+**
-   **Composer**
-   **SQLite** (or any supported database)

### Installation

1.  **Clone the repository**:
    ```bash
    git clone <repository-url>
    cd test-task
    ```

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

4.  **Database Migration**:
    ```bash
    php artisan migrate
    ```

## 🛠 Development

To start the development server (both Vite and Laravel):

```bash
composer run dev
```

This command runs both the PHP server and the Vite development server concurrently, ensuring Hot Module Replacement (HMR) for the frontend.

## 🤖 Automated Checks

To manually trigger a check of all domains:

```bash
php artisan app:check-domains
```

For production, this command is already scheduled to run every minute in `routes/console.php`.

## 📦 Tech Stack

-   **Backend**: Laravel 13 (Fortify for Auth)
-   **Frontend**: Vue 3, Inertia.js v3
-   **Styling**: Tailwind CSS v4
-   **Routing**: Laravel Wayfinder
-   **Icons**: Lucide Vue Next

---
*Built as a test task with focus on clean architecture and premium user experience.*