FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql zip bcmath mbstring

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --ignore-platform-req=php --ignore-platform-req=ext-* --no-scripts

RUN composer dump-autoload --optimize --no-interaction

# 🔥 إنشاء ملف .env
RUN echo "APP_ENV=production" > .env && \
    echo "APP_DEBUG=false" >> .env && \
    echo "APP_KEY=$(php artisan key:generate --show)" >> .env && \
    echo "DB_CONNECTION=mysql" >> .env && \
    echo "DB_HOST=mysql-9de32b0-marvinwork001-4e00.e.aivencloud.com" >> .env && \
    echo "DB_PORT=20655" >> .env && \
    echo "DB_DATABASE=defaultdb" >> .env && \
    echo "DB_USERNAME=avnadmin" >> .env && \
    echo "DB_PASSWORD=your_password" >> .env

# 🔥 تشغيل فقط الأوامر الأساسية مع تجاهل الأخطاء
RUN php artisan config:clear || true
RUN php artisan migrate --force || true
RUN php artisan storage:link || true

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
