# Sử dụng image php:8.0-fpm trên nền tảng ARM64
FROM php:8.0-fpm-alpine

# Cài đặt các gói phụ thuộc
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip

# Cài đặt các extension PHP
RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Sao chép mã nguồn Laravel vào container
COPY . .

# Cài đặt các dependency của Laravel bằng Composer
RUN composer install

# Thiết lập quyền cho storage và bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Expose port 80
EXPOSE 80

# Khởi chạy server PHP-FPM
CMD php-fpm
