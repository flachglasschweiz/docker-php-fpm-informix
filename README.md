# PHP-FPM-Informix Docker Image

This image is based on the official php:php-fpm-debian image.

Dieses image wird für das BDE verwendet, es dient als Basisimage.
Dieses image wird auf https://hub.docker.com/repository/docker/fgch gehostet.
BDE holt dieses image wenn ci/cd ausgeführt wird.

### PHP Modules
```
Core
ctype
curl
date
dom
fileinfo
filter
ftp
gd
hash
iconv
intl
json
ldap
libxml
mbstring
mysqlnd
openssl
pcre
PDO
pdo_informix
pdo_mysql
pdo_sqlite
Phar
posix
readline
Reflection
session
SimpleXML
sodium
SPL
sqlite3
standard
tokenizer
xdebug
xml
xmlreader
xmlwriter
Zend OPcache
zip
zlib
```

### Zend Modules
```
Xdebug
Zend OPcache
```

### Compiling Error
```
/tmp/PDO_INFORMIX-1.3.6/informix_driver.c:91:37: error: passing argument
2 of 'pdo_parse_params' from incompatible pointer type [-Wincompatible-pointer-types]
   91 |         rc = pdo_parse_params(stmt, (char *) stmt_string,
```

Solution:
Use bullseye or bookworm Debian as baseimage.  
`FROM php:8.2-fpm-bullseye as builder`  
