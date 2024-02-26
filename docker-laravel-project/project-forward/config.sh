#!/bin/bash
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Clear Laravel caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:clear

#Start apache2
apache2-foreground
