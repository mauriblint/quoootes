FROM php:7-fpm
# lumen packages
# RUN docker-php-ext-install mbstring tokenizer mysqli pdo_mysql
RUN docker-php-ext-install tokenizer mysqli pdo_mysql
RUN apt-get update && apt-get install -y zlib1g-dev libpng-dev libicu-dev g++ fontconfig libfontconfig1 libxrender1 libxext6 xvfb git zip unzip
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install exif
RUN docker-php-ext-install gd


RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
&& curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
# Make sure we're installing what we think we're installing!
&& php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
&& php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --snapshot \
&& rm -f /tmp/composer-setup.*