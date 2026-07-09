# استخدام صورة PHP 8.3 الرسمية مع Apache
FROM php:8.3-apache

# تثبيت الملحقات اللازمة لـ Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip bcmath mbstring exif

# تمكين Apache mod_rewrite
RUN a2enmod rewrite

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تعيين مجلد العمل داخل الحاوية
WORKDIR /var/www/html

# نسخ جميع الملفات أولاً
COPY . .

# تثبيت dependencies مع عرض التفاصيل
RUN composer install --optimize-autoloader --no-dev -v

# إعداد صلاحيات المجلدات
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# نسخ إعدادات Apache المخصصة
COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
