FROM php:7.2-apache

RUN apt-get update \
	&& apt-get install -y \
		unzip \
		libgpgme11-dev \
	&& apt-get clean && rm -rf /var/lib/apt/lists/*

#Setup - expecting code to be bind mounted in for development
COPY ./src/composer.json ./src/composer.lock ./composer-stable.phar ./php.ini /var/www/
WORKDIR /var/www/

RUN mv "php.ini" "$PHP_INI_DIR/php.ini" \
	&& pecl install gnupg && docker-php-ext-enable gnupg \
	&& php ./composer-stable.phar install && rm ./composer-stable.phar

EXPOSE 80