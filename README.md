# Interactive Giveaway System

Laravel 10 + Livewire 3 giveaway management system.

## Quick Install

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm run build
php artisan serve
```

## Admin Login
- URL: http://localhost:8000/admin/login
- Email: admin@giveaway.com
- Password: admin123

## Features
- 500 QR codes
- Mobile form
- AI jokes (OpenAI)
- Google Sheets sync
- Admin dashboard
