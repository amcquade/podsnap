# Use the PHP image with Apache support
FROM php:8.3-apache

# Install additional PHP extensions if needed
RUN apt-get update && apt-get install -y \
    zip unzip curl libpng-dev libonig-dev libxml2-dev

# Enable Apache modules
RUN a2enmod rewrite

# Copy application code to the Apache document root
COPY www/ /var/www/html

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Set working directory
WORKDIR /var/www/html

# Expose the default HTTP port
EXPOSE 80

# Start Apache in the foreground to keep the container alive
CMD ["apache2-foreground"]
