# Builder Image, fpm-debian base required!
FROM php:7.1-fpm as builder
ENV INFORMIXDIR=/opt/ibm/informix
ENV LD_LIBRARY_PATH=/opt/ibm/informix/lib:/opt/ibm/informix/lib/esql:/opt/ibm/informix/lib/client:/opt/ibm/informix/lib/cli:$LD_LIBRARY_PATH

# EOL, enable Archive
RUN sed -i 's|http://deb.debian.org|http://archive.debian.org|g' /etc/apt/sources.list \
    && sed -i '/security/d' /etc/apt/sources.list
    
# Copy required Informix files
COPY --from=ibmcom/informix-innovator-c:12.10.FC12W1IE /opt/ibm/informix/lib /opt/ibm/informix/lib
COPY --from=ibmcom/informix-innovator-c:12.10.FC12W1IE /opt/ibm/informix/incl /opt/ibm/informix/incl
COPY --from=ibmcom/informix-innovator-c:12.10.FC12W1IE /opt/ibm/informix/gls /opt/ibm/informix/gls
COPY --from=ibmcom/informix-innovator-c:12.10.FC12W1IE /opt/ibm/informix/sdk_license /opt/ibm/informix/sdk_license

# Install pdo_informix
ARG PDO_FILE=PDO_INFORMIX-1.3.6
ARG PDO_URL=https://pecl.php.net/get/${PDO_FILE}.tgz
RUN curl -fsSL ${PDO_URL} -o ${PDO_FILE}.tgz \
    && tar -xf ${PDO_FILE}.tgz -C /tmp/ \
    && rm ${PDO_FILE}.tgz \
    && cd /tmp/${PDO_FILE} \
    && docker-php-ext-configure /tmp/${PDO_FILE} --with-pdo-informix=${INFORMIXDIR} \
    && docker-php-ext-install /tmp/${PDO_FILE}

# Create final image, fpm-debian base required!
FROM php:7.1-fpm
ENV INFORMIXDIR=/opt/ibm/informix
ENV LD_LIBRARY_PATH=/opt/ibm/informix/lib:/opt/ibm/informix/lib/esql:/opt/ibm/informix/lib/client:/opt/ibm/informix/lib/cli:$LD_LIBRARY_PATH

# Copy required informix files
COPY --from=builder /opt/ibm/informix /opt/ibm/informix

# Copy extension
COPY --from=builder /usr/local/etc/php/conf.d/docker-php-ext-pdo_informix.ini /usr/local/etc/php/conf.d/docker-php-ext-pdo_informix.ini
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/

# Install packages and extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        zip \
        unzip \
        gnupg2 \
        apt-transport-https \
        libc6 \
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
    && pecl install xdebug-2.9.8 \
    && docker-php-ext-enable xdebug \
    && apt-get clean \
    && rm -rf /var/cache/apt/lists \
    && rm -rf /var/tmp/* /tmp/*
