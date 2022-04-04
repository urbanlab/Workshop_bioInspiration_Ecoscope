FROM php:8-apache
COPY src /var/www/html/
RUN chown -R www-data:www-data /var/www
EXPOSE 80