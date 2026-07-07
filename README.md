# Payroll Engine

A Simple Laravel payroll calculator that calculates regular and overtime pay based on location-specific rules.
It includes a JSON API and an Inertia + Vue frontend.

## Tech Stack

- **Backend:** PHP 8.4, Laravel 13
- **Frontend:** Vue 3, Inertia v3, Tailwind CSS v4, Vite
- **Testing & quality:** Pest, PHPStan (Larastan), Laravel Pint, ESLint, Prettier

## Requirements

- PHP 8.3+
- Composer
- Node.js 22+
- SQLite (default) or another Laravel-supported database

## Getting Started

### Quick Setup

```bash
composer setup
```

### Manual Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
npm install
npm run build
```

## Development

```bash
composer dev
```

## Running Tests

```bash
composer test
```
