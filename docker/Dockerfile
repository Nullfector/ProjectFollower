FROM php:8.2-apache

# PDO dla PostgreSQL
RUN apt-get update \
 && apt-get install -y libpq-dev \
 && docker-php-ext-install pdo_pgsql \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

# Włącz mod_rewrite (często potrzebne w MVC)
RUN a2enmod rewrite

# Ustaw DocumentRoot na /var/www/app/public (jeśli Twoim webrootem jest public/)
ENV APACHE_DOCUMENT_ROOT=/var/www/app

# Przepnij konfigurację Apache na nowy DocumentRoot
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/app