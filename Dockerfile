# استخدام صورة PHP 8.3 مع Apache
FROM php:8.3-apache

# تحديث قائمة الحزم وتثبيت المتطلبات الأساسية
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

# تثبيت ملحقات PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql zip bcmath mbstring

# تمكين Apache mod_rewrite
RUN a2enmod rewrite

# تثبيت Composer 2.7 (إصدار مستقر مع Laravel 13)
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# تثبيت dependencies مع تجاهل القيود
RUN composer install --no-dev --ignore-platform-req=php --ignore-platform-req=ext-* --no-scripts

# إعداد صلاحيات المجلدات
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
