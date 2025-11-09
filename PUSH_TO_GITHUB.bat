@echo off
echo ================================================================
echo     Push to GitHub - Windows Script
echo ================================================================
echo.

REM Check if Git is installed
where git >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Git is not installed!
    echo Please install Git from: https://git-scm.com/download/win
    pause
    exit /b 1
)

echo [OK] Git is installed
echo.

REM Get repository URL
set /p REPO_URL="Enter your GitHub repository URL: "
if "%REPO_URL%"=="" (
    echo [ERROR] Repository URL cannot be empty!
    pause
    exit /b 1
)

echo.
echo [INFO] Setting up Git repository...

REM Initialize git if needed
if not exist .git (
    git init
    echo [OK] Git repository initialized
) else (
    echo [OK] Git repository already exists
)

REM Configure git user
echo.
set /p GIT_NAME="Enter your name for Git commits (press Enter to skip): "
set /p GIT_EMAIL="Enter your email for Git commits (press Enter to skip): "

if not "%GIT_NAME%"=="" (
    git config user.name "%GIT_NAME%"
    echo [OK] Git user name set
)

if not "%GIT_EMAIL%"=="" (
    git config user.email "%GIT_EMAIL%"
    echo [OK] Git user email set
)

REM Add all files
echo.
echo [INFO] Adding all files to Git...
git add .

REM Create commit
echo.
echo [INFO] Creating commit...
git commit -m "Initial commit: Complete Interactive Giveaway System - Laravel 10 + Livewire 3 application - QR code management system (500 seats) - Mobile-optimized submission form - AI-powered joke generation - Google Sheets integration - Professional admin dashboard - Complete documentation"

if %ERRORLEVEL% NEQ 0 (
    echo [WARNING] Commit may have failed, but continuing...
)

REM Add remote
echo.
echo [INFO] Adding GitHub remote...
git remote remove origin 2>nul
git remote add origin "%REPO_URL%"
echo [OK] Remote added: %REPO_URL%

REM Set branch to main
git branch -M main
echo [OK] Branch set to main

REM Push to GitHub
echo.
echo [INFO] Pushing to GitHub...
echo You may be asked for your GitHub credentials
echo Use Personal Access Token as password (not your GitHub password)
echo.

git push -u origin main

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ================================================================
    echo.
    echo     [SUCCESS] Code pushed to GitHub! 
    echo.
    echo ================================================================
    echo.
    echo Your repository: %REPO_URL%
    echo.
    echo Next steps:
    echo 1. Visit your GitHub repository to verify
    echo 2. Check all files are uploaded
    echo 3. Set up repository description and topics
    echo.
    echo See GITHUB_SETUP.md for more details
    echo.
) else (
    echo.
    echo [ERROR] Failed to push to GitHub
    echo.
    echo Common solutions:
    echo.
    echo 1. Authentication Error:
    echo    - Use Personal Access Token instead of password
    echo    - Get token from: https://github.com/settings/tokens
    echo.
    echo 2. Repository doesn't exist:
    echo    - Create the repository on GitHub first
    echo    - Then run this script again
    echo.
    echo 3. Permission denied:
    echo    - Check you have write access to the repository
    echo.
    echo See CONNECT_TO_GITHUB.md for detailed help
    echo.
)

pause
