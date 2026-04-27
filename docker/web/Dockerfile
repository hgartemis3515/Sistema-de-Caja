FROM php:8.3-apache-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    $PHPIZE_DEPS \
    gcc g++ make pkg-config re2c \
    libpcre2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mysqli zip gd mbstring opcache \
    && pecl install phalcon \
    && docker-php-ext-enable phalcon \
    && apt-get purge -y $PHPIZE_DEPS gcc g++ make pkg-config re2c \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite headers

COPY apache-000-default.conf /etc/apache2/sites-available/000-default.conf
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /var/www/html

EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
