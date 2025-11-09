# Interactive Giveaway System - Laravel + Livewire

A complete interactive giveaway management system built with Laravel and Livewire.

## Features

- 500 unique QR codes for seat tracking
- Mobile-friendly landing page
- Real-time form validation
- Google Sheets integration
- AI-powered personalized jokes
- Admin dashboard with analytics
- WhatsApp opt-in management

## Installation

### Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL
- Node.js & NPM

### Setup Steps

1. Clone the repository:
```bash
git clone <your-repo-url>
cd laravel-giveaway-system
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=giveaway_system
DB_USERNAME=root
DB_PASSWORD=
```

7. Configure Google Sheets API:
```
GOOGLE_SHEETS_SPREADSHEET_ID=your_spreadsheet_id
GOOGLE_APPLICATION_CREDENTIALS=/path/to/credentials.json
```

8. Configure OpenAI API (for jokes):
```
OPENAI_API_KEY=your_openai_api_key
```

9. Run migrations:
```bash
php artisan migrate
```

10. Seed QR codes (creates 500 unique codes):
```bash
php artisan db:seed --class=QRCodeSeeder
```

11. Build assets:
```bash
npm run build
```

12. Start the development server:
```bash
php artisan serve
```

13. Visit: `http://localhost:8000`

## Admin Access

- URL: `/admin/dashboard`
- Default credentials will be created on first run

## QR Code URLs

Each QR code will point to:
```
https://yourdomain.com/form?id=001
https://yourdomain.com/form?id=002
...
https://yourdomain.com/form?id=500
```

## Deployment

For production deployment:

1. Set `APP_ENV=production` in `.env`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Set up your web server (Nginx/Apache)
6. Configure SSL certificate

## License

MIT License
