#!/bin/bash

# Interactive Giveaway System - Quick Setup Script

echo "╔════════════════════════════════════════════╗"
echo "║  Interactive Giveaway System Setup         ║"
echo "║  Laravel + Livewire                        ║"
echo "╚════════════════════════════════════════════╝"
echo ""

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    echo "   Visit: https://getcomposer.org/download/"
    exit 1
fi

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ NPM is not installed. Please install Node.js and NPM first."
    echo "   Visit: https://nodejs.org/"
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.1 or higher."
    exit 1
fi

echo "✓ Prerequisites check passed"
echo ""

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install
if [ $? -ne 0 ]; then
    echo "❌ Failed to install Composer dependencies"
    exit 1
fi

# Install NPM dependencies
echo "📦 Installing NPM dependencies..."
npm install
if [ $? -ne 0 ]; then
    echo "❌ Failed to install NPM dependencies"
    exit 1
fi

# Copy .env file
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✓ .env file created"
else
    echo "⚠️  .env file already exists, skipping..."
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Create storage directories
echo "📁 Setting up storage directories..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
chmod -R 775 storage bootstrap/cache

# Build assets
echo "🎨 Building frontend assets..."
npm run build

echo ""
echo "╔════════════════════════════════════════════╗"
echo "║  Setup Instructions                        ║"
echo "╚════════════════════════════════════════════╝"
echo ""
echo "Next steps:"
echo ""
echo "1. Configure your database in .env file:"
echo "   DB_CONNECTION=mysql"
echo "   DB_DATABASE=your_database"
echo "   DB_USERNAME=your_username"
echo "   DB_PASSWORD=your_password"
echo ""
echo "2. Run database migrations:"
echo "   php artisan migrate"
echo ""
echo "3. Seed the database (creates admin & QR codes):"
echo "   php artisan db:seed"
echo ""
echo "4. Start the development server:"
echo "   php artisan serve"
echo ""
echo "5. Visit: http://localhost:8000"
echo ""
echo "Default admin credentials:"
echo "   Email: admin@giveaway.com"
echo "   Password: admin123"
echo ""
echo "⚠️  Don't forget to configure Google Sheets and OpenAI API"
echo "   See API_SETUP.md for detailed instructions"
echo ""
echo "✨ Setup complete! Happy coding!"
