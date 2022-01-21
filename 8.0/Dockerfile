# Builder Image
FROM php:8.0-fpm as builder
ENV INFORMIXDIR=/opt/ibm/informix
ENV LD_LIBRARY_PATH=/opt/ibm/informix/lib:/opt/ibm/informix/lib/esql:/opt/ibm/informix/lib/client:/opt/ibm/informix/lib/cli:$LD_LIBRARY_PATH

# Copy required Informix files
COPY --from=ibmcom/informix-innovator-c:12.10.FC12W1IE /opt/ibm/informix/lib /opt/ibm/informix/lib
COPY --from=ibmcom/informix-innovator-c:12.10.FC12W1IE /opt/ibm/informix/incl /opt/ibm/informix/incl
COPY --from=ibmcom/informix-innovator-c:12.10.FC12W1IE /opt/ibm/informix/gls /opt/ibm/informix/gls
COPY --from=ibmcom/informix-innovator-c:12.10.FC12W1IE /opt/ibm/informix/sdk_license /opt/ibm/informix/sdk_license

# Install packages and extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        zip \
        unzip \
        gnupg2 \
        apt-transport-https \
        libc6 \
        g++ \
        libssl1.0 \
        libldap2-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-configure \
        ldap --with-libdir=lib/x86_64-linux-gnu \
    && docker-php-ext-configure \
        gd --with-jpeg --with-freetype \
    && docker-php-ext-configure \
        intl \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        ldap \
        gd \
        zip \
        intl \
        opcache \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install pdo_informix with multibyte patch
ARG PDO_FILE=PDO_INFORMIX-1.3.4
ARG PDO_URL=https://pecl.php.net/get/${PDO_FILE}.tgz
#ARG PDO_PATCH_URL=https://git.php.net/?p=pecl/database/pdo_informix.git;a=patch;h=a88390f3b5df685da21c40f24b0fc70740e5b56f

RUN curl -fsSL ${PDO_URL} -o ${PDO_FILE}.tgz \
    && tar -xf ${PDO_FILE}.tgz -C /tmp/ \
    && rm ${PDO_FILE}.tgz \
    && cd /tmp/${PDO_FILE} \
#    && curl $PDO_PATCH_URL | git apply -v \
    && docker-php-ext-configure /tmp/${PDO_FILE} --with-pdo-informix=${INFORMIXDIR} \
    && docker-php-ext-install /tmp/${PDO_FILE}

# Create final image
FROM php:8.0-fpm
ENV INFORMIXDIR=/opt/ibm/informix
ENV LD_LIBRARY_PATH=/opt/ibm/informix/lib:/opt/ibm/informix/lib/esql:/opt/ibm/informix/lib/client:/opt/ibm/informix/lib/cli:$LD_LIBRARY_PATH

# Copy required informix files
COPY --from=builder /opt/ibm/informix /opt/ibm/informix

# Extensions kopieren
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install required libraries
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
    && apt-get clean \
    && rm -rf /var/cache/apt/lists \
    && rm -rf /var/tmp/* /tmp/*
