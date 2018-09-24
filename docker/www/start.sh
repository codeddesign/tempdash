#!/bin/bash
PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
export PATH

chown -R www-data:www-data /www

echo "Removing default www.conf from PHP FPM configurations..."
rm /etc/php/7.2/fpm/pool.d/www.conf
echo "Replacing it with the www.conf template..."
cp /www/docker/www/config_templates/php-fpm/www.conf /etc/php/7.2/fpm/pool.d/www.conf


if [ "$APP_ENV" = "local" ]; then
    echo "Updating xdebug.ini"
    rm /etc/php/7.2/mods-available/xdebug.ini
    cp /www/docker/www/config_templates/php-fpm/xdebug.ini /etc/php/7.2/mods-available/xdebug.ini
fi

if [ ! -e /etc/nginx/sites-available/web.conf ]; then
  echo "Configuring Nginx from config templates..."
  cp /www/docker/www/config_templates/nginx/web-development.conf /etc/nginx/sites-available/web.conf
fi

if [ ! -e /etc/nginx/sites-enabled/web.conf ]; then
  echo "Symlinking configuration in Nginx..."
  rm /etc/nginx/sites-enabled/default
  ln -s /etc/nginx/sites-available/web.conf /etc/nginx/sites-enabled/web.conf
fi

mkdir -p '/run/php'

if [ ! -e ~/.initialized ]; then
  if [ ! -e /www/.env ]; then
    echo "Creating .env file..."
    cp /www/.env.example .env
  fi

  echo "Running composer..."
  composer update
  php artisan key:generate

  echo "Running NPM install"
  if [ -e /www/package.json ]; then
    npm install
    npm run production
  fi

  touch ~/.initialized
fi

echo "Starting PHP and Nginx"
php-fpm7.2 &
nginx -g 'daemon off;'
