FROM php:8.3-fpm-alpine

LABEL org.opencontainers.image.authors="https://jdsantos.github.io"

LABEL laradocker.version="1.1.0"

# Set working directory
WORKDIR /var/www/html

# Install additional packages
RUN apk --no-cache add \
    nginx \
    supervisor \
    npm

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
COPY conf.d/nginx/default.conf /etc/nginx/nginx.conf

# Copy PHP configuration
COPY conf.d/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

COPY conf.d/php/php.ini /usr/local/etc/php/conf.d/php.ini

COPY conf.d/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copy Supervisor configuration
COPY conf.d/supervisor/supervisord.conf /etc/supervisord.conf

# Copy Laravel application files
COPY . /var/www/html

# Set up permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# ::: Scheduler setup :::

# Create a log file
RUN touch /var/log/cron.log

# Add cron job directly to crontab
RUN echo "* * * * * /usr/local/bin/php /var/www/html/artisan schedule:run >> /var/log/cron.log 2>&1" | crontab -

# ::: --- :::

# Expose ports
EXPOSE 80

# Declare image volumes
VOLUME /var/www/html/storage

# Define a health check
HEALTHCHECK --interval=30s --timeout=15s --start-period=15s --retries=3 CMD curl -f http://localhost/up || exit 1

# Add up the entrypoint
ADD entrypoint.sh /root/entrypoint.sh

# Ensure the entrypoint script has executable permissions
RUN chmod +x /root/entrypoint.sh

# ... and declare it as the entrypoint
ENTRYPOINT ["/root/entrypoint.sh"]
