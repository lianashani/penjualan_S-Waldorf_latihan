@echo off
echo ========================================
echo   S^&Waldorf Setup Script
echo ========================================
echo.

echo [1/6] Installing Composer Dependencies...
call composer install
if errorlevel 1 (
    echo ERROR: Composer install failed!
    pause
    exit /b 1
)
echo.

echo [2/6] Copying Environment File...
if not exist .env (
    copy .env.example .env
    echo .env file created
) else (
    echo .env file already exists
)
echo.

echo [3/6] Generating Application Key...
call php artisan key:generate
echo.

echo [4/6] Running Migrations...
call php artisan migrate
if errorlevel 1 (
    echo.
    echo ERROR: Migration failed!
    echo Please make sure:
    echo 1. MySQL is running
    echo 2. Database 'swaldorf_db' exists
    echo 3. .env database credentials are correct
    echo.
    pause
    exit /b 1
)
echo.

echo [5/6] Seeding Sample Data...
call php artisan db:seed --class=SWaldorfSeeder
echo.

echo [6/6] Clearing Cache...
call php artisan cache:clear
call php artisan config:clear
echo.

echo ========================================
echo   Setup Complete! 
echo ========================================
echo.
echo Login Credentials:
echo   Email: admin@swaldorf.com
echo   Password: password
echo.
echo To start the server, run:
echo   php artisan serve
echo.
echo Then open: http://localhost:8000
echo.
pause
