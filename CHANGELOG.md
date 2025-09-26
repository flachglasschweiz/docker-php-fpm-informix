
# 2025.09.26 - PS

- Korrekte Zeitzone eingefügt.
- Es wird nur noch Version 8.1 erstellt, alle anderen Versionen deaktivert.
- Version 8.2 ist zu neu, nicht kompatibel.
- CI/CD eingerichtet, image wird direkt auf https://hub.docker.com/repository/docker/fgch gepushed
- Image konnte nicht geneiert werden mit "php:8.2-fpm" vermutlich wird debian nicht meher standardmässig verwendet. Funktioniert: "php:8.1-fpm-bullseye"