FROM php:7.2-apache

ENV APACHE_DOCUMENT_ROOT /var/www/chronicle/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
	&& sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
	&& apt-get update \
	&& apt-get install -y \
		libpq-dev \
		unzip \
	&& docker-php-ext-install pdo pdo_pgsql \
	&& apt-get clean && rm -rf /var/lib/apt/lists/*

#Setup chronicle
COPY ./src /var/www/chronicle
COPY ./composer-stable.phar /var/www/chronicle/
WORKDIR /var/www/chronicle/
RUN php ./composer-stable.phar install && rm ./composer-stable.phar \
	&& a2enmod rewrite

#Copy everything else
COPY ./startup.sh  /

EXPOSE 80

#Start everything
CMD /startup.sh