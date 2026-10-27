FROM php:apache
RUN a2enmod rewrite
COPY img/ /var/www/html/img
COPY inc/ /var/www/html/inc
COPY flags/ /var/www/html/flags
COPY lightbox/ /var/www/html/lightbox
COPY index.php /var/www/html/index.php
COPY style.css /var/www/html/style.css
COPY sitemap.xml /var/www/html/sitemap.xml
COPY .htaccess /var/www/html/.htaccess

