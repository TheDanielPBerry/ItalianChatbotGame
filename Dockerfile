FROM php:8.2-apache AS php-apache-composer 

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y curl sqlite3

RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
RUN HASH=`curl -sS https://composer.github.io/installer.sig`
RUN php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y git

RUN apt-get install -y vim
RUN apt-get install libyaml-dev -y
RUN pecl install yaml && echo "extension=yaml.so" > /usr/local/etc/php/conf.d/ext-yaml.ini 
RUN docker-php-ext-enable yaml

COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/danielberry.tech.conf /etc/apache2/sites-available/danielberry.tech.conf


RUN find . -type d -exec chmod 755 {} \;
RUN find . -type f -exec chmod 644 {} \;
RUN chown -R www-data:www-data ./

COPY ./certs/ /certs


RUN a2enmod rewrite
RUN a2ensite danielberry.tech
RUN a2enmod ssl

RUN service apache2 start
CMD ["apachectl", "-D", "FOREGROUND"]
