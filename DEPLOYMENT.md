# Deployment Guide

## Server Requirements

- PHP >= 8.1
- MySQL >= 5.7 or PostgreSQL >= 10
- Composer
- Node.js >= 16.x
- NPM or Yarn

## Production Deployment Steps

### 1. Server Setup

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y
```

### 2. Clone and Setup Application

```bash
# Clone repository
cd /var/www
git clone <your-repository-url> giveaway-system
cd giveaway-system

# Set permissions
sudo chown -R www-data:www-data /var/www/giveaway-system
sudo chmod -R 755 /var/www/giveaway-system/storage
sudo chmod -R 755 /var/www/giveaway-system/bootstrap/cache

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 3. Environment Configuration

```bash
# Copy and configure environment
cp .env.example .env
nano .env

# Set these production values:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password

# Google Sheets
GOOGLE_SHEETS_SPREADSHEET_ID=your_spreadsheet_id
GOOGLE_APPLICATION_CREDENTIALS=/path/to/credentials.json

# OpenAI
OPENAI_API_KEY=your_api_key
```

### 4. Application Setup

```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed database (creates admin and QR codes)
php artisan db:seed --force

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 5. Web Server Configuration

#### Nginx Configuration

Create `/etc/nginx/sites-available/giveaway`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/giveaway-system/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/giveaway /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 6. SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### 7. Setup Cron Jobs

Add to crontab (`crontab -e`):

```bash
# Laravel Scheduler
* * * * * cd /var/www/giveaway-system && php artisan schedule:run >> /dev/null 2>&1

# Reset expired QR codes daily at 3 AM
0 3 * * * cd /var/www/giveaway-system && php artisan qr:reset >> /dev/null 2>&1
```

### 8. Setup Queue Worker (Optional)

If using queues:

```bash
# Create supervisor config
sudo nano /etc/supervisor/conf.d/giveaway-worker.conf
```

Add:
```ini
[program:giveaway-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/giveaway-system/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/giveaway-system/storage/logs/worker.log
```

Start worker:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start giveaway-worker:*
```

## Google Sheets Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project
3. Enable Google Sheets API
4. Create Service Account credentials
5. Download JSON key file
6. Share your spreadsheet with the service account email
7. Copy spreadsheet ID from URL
8. Update `.env` with credentials path and spreadsheet ID

## OpenAI Setup

1. Sign up at [OpenAI Platform](https://platform.openai.com/)
2. Create API key
3. Add to `.env` file

## Security Checklist

- [ ] Change default admin credentials
- [ ] Set APP_DEBUG=false in production
- [ ] Use strong database passwords
- [ ] Enable firewall (UFW)
- [ ] Regular backups configured
- [ ] SSL certificate installed
- [ ] File permissions set correctly
- [ ] .env file protected
- [ ] Keep Laravel and packages updated

## Backup Strategy

### Database Backup

```bash
# Manual backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# Automated daily backup (add to crontab)
0 2 * * * mysqldump -u username -p'password' database_name > /backups/db_$(date +\%Y\%m\%d).sql
```

### File Backup

```bash
# Backup storage and uploads
tar -czf backup_files_$(date +%Y%m%d).tar.gz /var/www/giveaway-system/storage
```

## Monitoring

### Check Application Status

```bash
# Check PHP-FPM
sudo systemctl status php8.1-fpm

# Check Nginx
sudo systemctl status nginx

# Check logs
tail -f /var/www/giveaway-system/storage/logs/laravel.log
tail -f /var/nginx/error.log
```

## Troubleshooting

### Permission Issues

```bash
sudo chown -R www-data:www-data /var/www/giveaway-system
sudo chmod -R 755 /var/www/giveaway-system/storage
sudo chmod -R 755 /var/www/giveaway-system/bootstrap/cache
```

### Clear All Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Database Connection Issues

- Check database credentials in `.env`
- Verify MySQL is running: `sudo systemctl status mysql`
- Check database exists: `mysql -u root -p -e "SHOW DATABASES;"`

## Updating Application

```bash
# Pull latest changes
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Support

For issues or questions, please contact the development team.
