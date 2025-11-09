#!/bin/bash

echo "╔═══════════════════════════════════════════════════════════╗"
echo "║     🚀 Push to GitHub - Interactive Script                ║"
echo "╚═══════════════════════════════════════════════════════════╝"
echo ""

# Check if git is installed
if ! command -v git &> /dev/null; then
    echo "❌ Git is not installed. Please install Git first."
    echo "   Visit: https://git-scm.com/downloads"
    exit 1
fi

echo "✓ Git is installed"
echo ""

# Get GitHub repository URL
echo "📝 Please enter your GitHub repository URL:"
echo "   Example: https://github.com/yourusername/your-repo-name.git"
echo "   Or SSH: git@github.com:yourusername/your-repo-name.git"
echo ""
read -p "Repository URL: " REPO_URL

if [ -z "$REPO_URL" ]; then
    echo "❌ Repository URL cannot be empty!"
    exit 1
fi

echo ""
echo "🔧 Setting up Git repository..."

# Initialize git if not already initialized
if [ ! -d .git ]; then
    git init
    echo "✓ Git repository initialized"
else
    echo "✓ Git repository already exists"
fi

# Configure git user (optional)
echo ""
read -p "Enter your name for Git commits (press Enter to skip): " GIT_NAME
read -p "Enter your email for Git commits (press Enter to skip): " GIT_EMAIL

if [ ! -z "$GIT_NAME" ]; then
    git config user.name "$GIT_NAME"
    echo "✓ Git user name set to: $GIT_NAME"
fi

if [ ! -z "$GIT_EMAIL" ]; then
    git config user.email "$GIT_EMAIL"
    echo "✓ Git user email set to: $GIT_EMAIL"
fi

# Add all files
echo ""
echo "📦 Adding all files to Git..."
git add .

# Create commit
echo ""
echo "💾 Creating commit..."
git commit -m "Initial commit: Complete Interactive Giveaway System

- Laravel 10 + Livewire 3 application
- QR code management system (500 seats)
- Mobile-optimized submission form with validation
- AI-powered personalized joke generation
- Google Sheets real-time integration
- Professional admin dashboard with analytics
- Real-time charts and statistics
- CSV export functionality
- Complete documentation (10+ guides)
- Automated setup script
- CI/CD pipeline configured
- Security features implemented"

echo "✓ Commit created"

# Add remote
echo ""
echo "🔗 Adding GitHub remote..."
git remote remove origin 2>/dev/null
git remote add origin "$REPO_URL"
echo "✓ Remote added: $REPO_URL"

# Check current branch name
CURRENT_BRANCH=$(git branch --show-current)
if [ -z "$CURRENT_BRANCH" ]; then
    CURRENT_BRANCH="main"
    git branch -M main
fi

echo "✓ Current branch: $CURRENT_BRANCH"

# Push to GitHub
echo ""
echo "🚀 Pushing to GitHub..."
echo "   (You may be asked for your GitHub credentials)"
echo ""

git push -u origin $CURRENT_BRANCH

if [ $? -eq 0 ]; then
    echo ""
    echo "╔═══════════════════════════════════════════════════════════╗"
    echo "║                                                           ║"
    echo "║           ✅ SUCCESS! Code pushed to GitHub! 🎉            ║"
    echo "║                                                           ║"
    echo "╚═══════════════════════════════════════════════════════════╝"
    echo ""
    echo "📍 Your repository: $REPO_URL"
    echo ""
    echo "🎯 Next steps:"
    echo "   1. Visit your GitHub repository"
    echo "   2. Verify all files are uploaded"
    echo "   3. Set up GitHub Actions (already configured)"
    echo "   4. Add repository description and topics"
    echo "   5. Create your first release"
    echo ""
    echo "📚 See GITHUB_SETUP.md for more details"
    echo ""
else
    echo ""
    echo "❌ Failed to push to GitHub"
    echo ""
    echo "💡 Common solutions:"
    echo ""
    echo "1. Authentication Error:"
    echo "   - Make sure you're logged into GitHub"
    echo "   - Use Personal Access Token instead of password"
    echo "   - Or set up SSH keys"
    echo ""
    echo "2. Repository doesn't exist:"
    echo "   - Create the repository on GitHub first"
    echo "   - Then run this script again"
    echo ""
    echo "3. Permission denied:"
    echo "   - Check you have write access to the repository"
    echo ""
    echo "📖 For detailed help, see: GITHUB_SETUP.md"
fi
