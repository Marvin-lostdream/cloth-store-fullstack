# استخدام صورة PHP 8.4 مع Apache
FROM php:8.4-apache

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

# تثبيت Composer (أحدث إصدار)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# نسخ جميع الملفات
COPY . .

# حذف vendor للتثبيت النظيف
RUN rm -rf vendor composer.lock

# تثبيت dependencies مع PHP 8.4
RUN composer install --no-dev --no-scripts

# إعداد صلاحيات المجلدات
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# نسخ إعدادات Apache
COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
