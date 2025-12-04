@echo off
cd /d "C:\Users\Admin\DACN"
php artisan schedule:run >> storage\logs\scheduler.log 2>&1
