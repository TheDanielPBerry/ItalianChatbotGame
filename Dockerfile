FROM php:8.2-apache AS php-apache-composer 

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y curl sqlite3

RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
RUN HASH=`curl -sS https://composer.github.io/installer.sig`
RUN php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y git

RUN service apache2 start
CMD ["apachectl", "-D", "FOREGROUND"]
