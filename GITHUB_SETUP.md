# 🚀 GitHub Setup Instructions

## Step 1: Create GitHub Repository

1. Go to [GitHub](https://github.com)
2. Click the **"+"** icon → **"New repository"**
3. Fill in details:
   - **Repository name**: `laravel-giveaway-system` (or your preferred name)
   - **Description**: Interactive Giveaway System built with Laravel and Livewire
   - **Visibility**: Public or Private (your choice)
   - **DO NOT** initialize with README, .gitignore, or license (we already have these)
4. Click **"Create repository"**

## Step 2: Upload Your Project to GitHub

Open terminal in your project directory and run:

```bash
# Navigate to your project
cd /path/to/laravel-giveaway-system

# Initialize git repository
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial commit: Complete Interactive Giveaway System

- Laravel 10 + Livewire 3 application
- QR code management system (500 seats)
- Mobile-optimized submission form
- AI-powered joke generation
- Google Sheets integration
- Professional admin dashboard
- Complete documentation"

# Add your GitHub repository as remote
# Replace YOUR_USERNAME and YOUR_REPO with your actual GitHub details
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git

# Push to GitHub
git branch -M main
git push -u origin main
```

### Using SSH (Alternative)

If you use SSH keys:

```bash
git remote add origin git@github.com:YOUR_USERNAME/YOUR_REPO.git
git branch -M main
git push -u origin main
```

## Step 3: Verify Upload

1. Go to your GitHub repository URL
2. Refresh the page
3. You should see all files and folders

## Step 4: Configure Repository Settings (Optional but Recommended)

### Add Repository Description

1. Go to repository settings
2. Add description: "Interactive Giveaway System - Laravel + Livewire | QR Code Management | Real-time Analytics"
3. Add topics: `laravel`, `livewire`, `php`, `qr-code`, `giveaway`, `tailwindcss`

### Add Repository Website

If you deploy this, add your website URL in repository settings.

### Enable GitHub Actions

GitHub Actions is already configured! The workflow will run automatically on push.

### Create Release

1. Go to "Releases" → "Create a new release"
2. Tag version: `v1.0.0`
3. Release title: "Initial Release - Complete Giveaway System"
4. Description:
```markdown
## 🎉 Initial Release

Complete Interactive Giveaway System built with Laravel and Livewire.

### Features
- ✅ 500 unique QR codes
- ✅ Mobile-optimized submission form
- ✅ AI-powered personalization
- ✅ Google Sheets integration
- ✅ Professional admin dashboard
- ✅ Real-time analytics
- ✅ Complete documentation

### Requirements
- PHP 8.1+
- MySQL/PostgreSQL
- Composer
- Node.js & NPM

### Quick Start
```bash
./setup.sh
php artisan migrate
php artisan db:seed
php artisan serve
```

See QUICKSTART.md for detailed instructions.
```

5. Click "Publish release"

## Step 5: Update README with Your Repository Info

Edit `README.md` and replace placeholder URLs with your actual repository URL:

```markdown
git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git
```

Commit and push the change:

```bash
git add README.md
git commit -m "Update repository URL in README"
git push
```

## Step 6: Set Up Branch Protection (Recommended for Teams)

1. Go to Settings → Branches
2. Add rule for `main` branch:
   - ☑️ Require pull request reviews before merging
   - ☑️ Require status checks to pass before merging
   - ☑️ Require branches to be up to date before merging

## Step 7: Add Collaborators (If Team Project)

1. Go to Settings → Collaborators
2. Click "Add people"
3. Enter GitHub usernames
4. Set appropriate permissions

## Common Git Commands

### Daily Workflow

```bash
# Pull latest changes
git pull origin main

# Create new feature branch
git checkout -b feature/your-feature-name

# Make changes and commit
git add .
git commit -m "Description of changes"

# Push to GitHub
git push origin feature/your-feature-name

# Create Pull Request on GitHub
```

### Useful Commands

```bash
# Check status
git status

# View commit history
git log --oneline

# View differences
git diff

# Undo changes
git checkout -- filename

# Update from main
git checkout main
git pull origin main
git checkout your-branch
git merge main
```

## GitHub Features to Enable

### 1. Issues

Enable for bug tracking and feature requests:
- Settings → Features → ☑️ Issues

### 2. Projects

Create a project board for task management:
- Projects tab → New project → Board template

### 3. Wiki

Enable for additional documentation:
- Settings → Features → ☑️ Wiki

### 4. Discussions

Enable for community questions:
- Settings → Features → ☑️ Discussions

## Security Best Practices

### 1. Never Commit Sensitive Data

Already handled! `.gitignore` includes:
- `.env` files
- Credentials
- API keys
- Database files

### 2. Use GitHub Secrets for CI/CD

If you add deployment automation:
1. Settings → Secrets and variables → Actions
2. Add secrets:
   - `SSH_PRIVATE_KEY`
   - `SERVER_HOST`
   - `SERVER_USER`
   - etc.

### 3. Enable Dependabot

1. Settings → Security → Dependabot
2. Enable:
   - ☑️ Dependabot alerts
   - ☑️ Dependabot security updates

## Cloning for New Development

When someone wants to work on this project:

```bash
# Clone repository
git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git
cd YOUR_REPO

# Install dependencies and setup
./setup.sh

# Or manual setup:
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm run build
php artisan serve
```

## Troubleshooting

### "Permission denied (publickey)"

**Solution**: Set up SSH keys or use HTTPS with personal access token

```bash
# Generate SSH key
ssh-keygen -t ed25519 -C "your_email@example.com"

# Add to GitHub: Settings → SSH and GPG keys
```

### "Failed to push some refs"

**Solution**: Pull latest changes first

```bash
git pull origin main --rebase
git push origin main
```

### Large File Warning

If you get warnings about large files:

```bash
# Check file sizes
git ls-files -z | xargs -0 du -h | sort -rh | head -20

# Remove large files
git rm --cached path/to/large/file
```

## Continuous Integration Status Badge

Add to README.md:

```markdown
![Laravel CI/CD](https://github.com/YOUR_USERNAME/YOUR_REPO/workflows/Laravel%20CI/CD/badge.svg)
```

## Repository Maintenance

### Weekly Tasks
- Review and merge pull requests
- Close resolved issues
- Update documentation
- Check for dependency updates

### Monthly Tasks
- Review security alerts
- Update Laravel and packages
- Check GitHub Actions usage
- Review contributor activity

## Need Help?

- [GitHub Docs](https://docs.github.com/)
- [Git Documentation](https://git-scm.com/doc)
- [Laravel Documentation](https://laravel.com/docs)

---

**Your project is now on GitHub! 🎉**

Share the repository URL with your team and start collaborating!
