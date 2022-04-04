FROM php:8-apache
RUN apt-get update && apt-get upgrade -y && apt-get install -y --no-install-recommends apt-utils git
COPY src /var/www/html/
RUN chown -R www-data:www-data /var/www
EXPOSE 80