FROM laravelsail/php83-composer:latest AS builder

ARG WWWGROUP

WORKDIR /var/www/html

RUN curl -fsSL https://bun.sh/install | bash

COPY . .

COPY ./docker/final_stage.dockerignore .dockerignore

RUN composer install \
    # --no-dev \
    --no-interaction \
    --no-scripts \
    --ignore-platform-reqs
RUN ~/.bun/bin/bun i --no-dev
RUN ~/.bun/bin/bun run build

FROM php:8.3-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install additional packages
RUN apk --no-cache add nginx supervisor

# Enable opcache extension
RUN docker-php-ext-enable opcache

# Install PHP extensions for MySQL
RUN apk --no-cache add mysql-client && docker-php-ext-install pdo pdo_mysql

# Install PHP extension for Redis. see: https://stackoverflow.com/a/31369892
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis.so

# Install PHP extension PCNTL
RUN docker-php-ext-install pcntl && docker-php-ext-enable pcntl

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy Nginx configuration
COPY docker/conf.d/nginx/default.conf /etc/nginx/nginx.conf

# Copy PHP configuration
COPY docker/conf.d/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

COPY docker/conf.d/php/php.ini /usr/local/etc/php/conf.d/php.ini

COPY docker/conf.d/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copy Supervisor configuration
COPY docker/conf.d/supervisor/supervisord.conf /etc/supervisord.conf

# Copy application files
COPY --from=builder /var/www/html /var/www/html

# Set up permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Expose ports
EXPOSE 80

# Declare image volumes
VOLUME /var/www/html/storage

# Define a health check
HEALTHCHECK --interval=30s --timeout=15s --start-period=15s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

# Add up the entrypoint
ADD docker/entrypoint.sh /root/entrypoint.sh

# Ensure the entrypoint script has executable permissions
RUN chmod +x /root/entrypoint.sh

# ... and declare it as the entrypoint
ENTRYPOINT ["/root/entrypoint.sh"]
