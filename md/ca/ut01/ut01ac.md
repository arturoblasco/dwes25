---
title: Exercicis
---
# Exercicis

## Ejercicio 101

Busca en Internet cuáles son los tres *frameworks PHP* más utilizados, e indica:

- Nombre y URL
- Año de creación
- Última versión


## Ejercicio 102

Busca tres ofertas de trabajo de *desarrollo de software* en *Infojobs* en la Comunidad Valenciana que citen PHP y anota:

- Empresa + puesto + frameworks PHP + requisitos + sueldo + enlace a la oferta.

## Ejercicio 103

Instala (mediante XAMPP o Docker) la dupla Apache+Php2.

Utiliza el puerto 8082 para Apache (en el fichero *docker-compose.yml*).

Estructura del contenedor:

```bash
ApachePhp2/
│
├── docker-compose.yml
└── .docker/    # Carpeta de configuración
│  └── php.ini  # Fichero configuración php
└── src/	    # Carpeta del proyecto
```

## Ejercicio 104

Instala (mediante Docker) la dupla Nginx+Php2.

Utiliza el puerto 8083 para Nginx (en el fichero *docker-compose.yml*).

```bash
ApachePhp2/
│
├── docker-compose.yml
└── .docker/    # Carpeta de configuración
│  └── nginx    
│     └── conf.d
│        └── default.conf # Fichero configuración de servidor Nginx
└── src/	    # Carpeta del proyecto
```

## Ejercicio 105

Si finalmente has instalado mediante *dockers* los dos servidores web, instala también el *docker Portainer* y echa un vistazo (haciendo varias capturas).

```bash
sudo docker volume create portainer_data
sudo docker run -d -p 8000:8000 -p 9443:9443 --name=portainer --restart=always -v /var/run/docker.sock:/var/run/docker.sock -v portainer_data:/data portainer/portainer-ce:latest
```

## Ejercicio 106

Una vez arrancado el servicio PHP (mediante XAMPP o Docker), crea el archivo `info.php` y añade el siguiente fragmento de código:

```php
<?php phpinfo() ?>
```

Anota los valores de: 

- Versión de PHP
- `Loaded Configuration File` 
- `memory_limit`
- `DOCUMENT_ROOT`



