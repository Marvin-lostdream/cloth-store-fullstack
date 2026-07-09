FROM php:8.3-apache

# تحديث الحزم وتثبيت المتطلبات
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# تثبيت ملحقات PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd pdo_mysql zip bcmath mbstring exif pcntl

# تمكين Apache mod_rewrite
RUN a2enmod rewrite

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# نسخ الملفات
COPY . .

# إزالة vendor وإعادة التثبيت
RUN rm -rf vendor bootstrap/cache/*.php
RUN composer install --no-dev --no-scripts --ignore-platform-req=php --ignore-platform-req=ext-*
RUN composer dump-autoload --optimize

# إعداد الصلاحيات
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# إعداد Apache
COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
