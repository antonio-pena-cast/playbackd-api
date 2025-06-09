Para levantar el servicio API se debe ejecutar el siguiente comando una vez la máquina virtual esté encendida: php -S 0.0.0.0:8000 -t public/

Para configurar el IP al que apunta y las credenciales de la base de datos se debe acceder al archivo .env y cambiar los siguientes campos por los correctos:

DB_CONNECTION=mysql
DB_HOST=192.168.56.103
DB_PORT=3306
DB_DATABASE=playbackd
DB_USERNAME=root
DB_PASSWORD=root
