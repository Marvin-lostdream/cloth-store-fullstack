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
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql zip bcmath mbstring

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --ignore-platform-req=php --ignore-platform-req=ext-* --no-scripts

RUN composer dump-autoload --optimize --no-interaction

# 🔥 تثبيت dependencies الخاصة بـ Node.js وبناء الأصول
RUN npm install
RUN npm run build

# 🔥 إنشاء مجلدات التخزين إذا لم تكن موجودة
RUN mkdir -p storage/app/public \
    storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# 🔥 إنشاء رابط التخزين
RUN php artisan storage:link || true

CMD bash -c "php artisan migrate --force || true && apache2-foreground"
