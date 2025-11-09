# Quick Start Guide

Get your Interactive Giveaway System up and running in 10 minutes!

## Prerequisites

Before you begin, ensure you have:
- ✅ PHP 8.1 or higher installed
- ✅ Composer installed
- ✅ Node.js & NPM installed
- ✅ MySQL or PostgreSQL database
- ✅ Git installed

## Installation Steps

### 1. Clone the Repository

```bash
git clone <your-repository-url>
cd laravel-giveaway-system
```

### 2. Quick Setup (Recommended)

Run the automated setup script:

```bash
chmod +x setup.sh
./setup.sh
```

This will:
- Install all dependencies
- Create .env file
- Generate application key
- Set up directories
- Build frontend assets

### 3. Manual Setup (Alternative)

If you prefer manual setup:

```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Build assets
npm run build
```

### 4. Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=giveaway_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Create the database:

```bash
# MySQL
mysql -u root -p
CREATE DATABASE giveaway_system;
exit;
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Database

This creates:
- Admin user
- 500 QR codes (seat 001 to 500)

```bash
php artisan db:seed
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit: http://localhost:8000

## Default Credentials

### Admin Login
- URL: http://localhost:8000/admin/login
- Email: `admin@giveaway.com`
- Password: `admin123`

⚠️ **Important**: Change these credentials in production!

## Testing the System

### Test User Submission

1. Visit: http://localhost:8000/form?id=001
2. Fill in the form:
   - Name: John Doe
   - Phone: +92 300 1234567
   - Email: john@example.com
   - Date of Birth: 1990-01-01
3. Submit and view your personalized joke!

### Test Admin Dashboard

1. Login at: http://localhost:8000/admin/login
2. View statistics
3. See recent submissions
4. Export CSV
5. Reset QR codes

## Optional: Configure External APIs

### Google Sheets (For Real-Time Data Sync)

1. Follow instructions in `API_SETUP.md`
2. Update `.env`:
```env
GOOGLE_SHEETS_SPREADSHEET_ID=your_spreadsheet_id
GOOGLE_APPLICATION_CREDENTIALS=/path/to/credentials.json
```

### OpenAI (For AI-Generated Jokes)

1. Get API key from https://platform.openai.com/
2. Update `.env`:
```env
OPENAI_API_KEY=sk-your_api_key_here
```

**Note**: System works without these APIs using fallback methods!

## QR Code URLs

Each QR code should point to:
```
https://yourdomain.com/form?id=001
https://yourdomain.com/form?id=002
...
https://yourdomain.com/form?id=500
```

Generate QR codes using any QR code generator with these URLs.

## Common Issues

### Issue: "composer: command not found"
**Solution**: Install Composer from https://getcomposer.org/

### Issue: "npm: command not found"
**Solution**: Install Node.js from https://nodejs.org/

### Issue: "SQLSTATE[HY000] [1045] Access denied"
**Solution**: Check database credentials in `.env`

### Issue: "File permissions error"
**Solution**: 
```bash
chmod -R 775 storage bootstrap/cache
```

### Issue: "Mix manifest not found"
**Solution**: 
```bash
npm run build
```

## Development Workflow

### Running Development Server

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Watch assets (optional)
npm run dev
```

### Clearing Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Resetting Database

```bash
php artisan migrate:fresh --seed
```

## Production Deployment

See `DEPLOYMENT.md` for detailed production deployment instructions.

Quick checklist:
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Use strong database password
- [ ] Configure SSL certificate
- [ ] Set up cron jobs
- [ ] Configure backups
- [ ] Change admin credentials

## Project Structure

```
laravel-giveaway-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Controllers
│   │   └── Livewire/        # Livewire components
│   ├── Models/              # Database models
│   └── Services/            # Business logic
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/             # Data seeders
├── resources/
│   ├── views/               # Blade templates
│   ├── css/                 # Stylesheets
│   └── js/                  # JavaScript
├── routes/
│   └── web.php              # Web routes
├── public/                  # Public assets
├── storage/                 # File storage
└── .env                     # Configuration
```

## Useful Commands

```bash
# View logs
tail -f storage/logs/laravel.log

# Enter tinker (Laravel REPL)
php artisan tinker

# Generate new QR codes
php artisan db:seed --class=QRCodeSeeder

# Reset expired QR codes
php artisan qr:reset

# Export submissions
# (Use admin dashboard export button)
```

## Getting Help

### Documentation
- `README.md` - Overview and installation
- `API_SETUP.md` - External API configuration
- `DEPLOYMENT.md` - Production deployment
- `ARCHITECTURE.md` - System architecture
- `CONTRIBUTING.md` - Contributing guidelines

### Support Channels
- GitHub Issues: Report bugs or request features
- Documentation: Read the docs first
- Community: Check existing issues

## Next Steps

1. ✅ System is running!
2. 📱 Generate QR codes for your seats
3. 🎭 Customize branding and messages
4. 📊 Configure Google Sheets integration
5. 🤖 Set up OpenAI API for jokes
6. 🚀 Deploy to production
7. 📈 Monitor analytics in admin dashboard

## Security Reminder

Before going live:
- [ ] Change default admin password
- [ ] Use HTTPS in production
- [ ] Set strong database password
- [ ] Protect .env file
- [ ] Enable firewall
- [ ] Regular backups
- [ ] Monitor logs

---

**Congratulations!** 🎉 Your Interactive Giveaway System is ready to use!

For detailed information, see:
- Production Deployment → `DEPLOYMENT.md`
- API Configuration → `API_SETUP.md`
- System Architecture → `ARCHITECTURE.md`
