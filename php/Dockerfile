FROM php:8.0-apache-slim
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && apt-get upgrade -y


# Enable Apache proxy modules
RUN a2enmod proxy proxy_http