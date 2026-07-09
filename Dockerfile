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

# تثبيت Composer (أحدث إصدار)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# نسخ جميع الملفات
COPY . .

# حذف vendor و composer.lock للتثبيت النظيف
RUN rm -rf vendor composer.lock

# تثبيت dependencies مع تجاهل القيود
RUN composer install --no-dev --ignore-platform-req=php --ignore-platform-req=ext-* --no-scripts

# إنشاء مجلدات التخزين إذا لم توجد
RUN mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions

# إعداد صلاحيات المجلدات
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# إنشاء ملف .env مؤقت لتشغيل artisan
RUN echo "APP_KEY=" > .env

# تشغيل أوامر Laravel
RUN composer dump-autoload --optimize --no-interaction || true
RUN php artisan key:generate --force || true
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# حذف ملف .env المؤقت
RUN rm -f .env

COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
