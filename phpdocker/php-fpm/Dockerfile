FROM phpdockerio/php:8.1-fpm
WORKDIR "/application"

RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        php8.1-dba \ 
        php8.1-mcrypt \ 
        php8.1-mysql \ 
        php8.1-odbc \ 
        php8.1-redis; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
