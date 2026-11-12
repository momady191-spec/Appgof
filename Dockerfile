FROM php:8.3-apache
WORKDIR /var/www/html
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/data && chmod -R 775 /var/www/html/data
EXPOSE 80
