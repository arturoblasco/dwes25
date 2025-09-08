# Ejercicios

## Ejercicio 101

Busca en Internet cuáles son los tres *frameworks PHP* más utilizados, e indica:

- Nombre y URL
- Año de creación
- Última versión

## Ejercicio 102

Busca tres ofertas de trabajo de *desarrollo de software* en *Infojobs* en la Comunidad Valenciana que citen PHP y anota:

- Empresa + puesto + frameworks PHP + requisitos + sueldo + enlace a la oferta.

## Ejercicio 103

Instala mediante Docker la dupla **Apache+Php2** (encontrarás la descarga en la sección *Descargas* de esta unidad).

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

Instala (mediante Docker) la dupla **Nginx+Php2**  (encontrarás la descarga en la sección *Descargas* de esta unidad).

Utiliza el puerto 8083 para Nginx (en el fichero *docker-compose.yml*).

```bash
NginxPhp/
│
├── docker-compose.yml
└── .docker/      # Carpeta de configuración
│  └── nginx/    
│     └── conf.d
│        └── default.conf # Fichero configuración de servidor 
└── src/          # Carpeta del proyecto
    └── img/
    └── index.php
    └── phpinfo.php
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

## Ejercicio 107

En el docker del *Ejercicio 103*:

* Modifica el fichero `index.html` a **`index_old.html`**.
* Descarga el siguiente fichero <a download="index.html" href="../../sources/">index.html</a> en la carpeta `src`.

> En el fichero *index.html* descargado se han utilizado las siguientes librerías para conformar la interfaz de usuario:
> 
> * [Bootstrap 5.2](https://getbootstrap.com/){target=_blank rel="noopener noreferrer"}, para el diseño de interfaces responsivas.
> * Tema [Flatly](https://www.bootstrapcdn.com/bootswatch/){target=_blank rel="noopener noreferrer"} basado en bootstrap, que proporciona la hoja de estilos de la interfaz.
> * [Font awesome 6.2](https://fontawesome.com/){target=_blank rel="noopener noreferrer"}, para la utilización de iconos.

Si nos fijamos en el contenido del fichero *index.html* proporcionado no se diferencia en nada (salvo en la utilización de las librerías anteriores) de una de las páginas estáticas programadas durante el primer curso del ciclo:

<div style="text-align: center;"><img src="../../img/ut01/ejercicio107a.png" alt="index.html" style="zoom:45%; border: 2px solid #fff2c9;" /></div>

Vamos a empezar a incrustar PHP en el código HTML.

### a) Utilización de plantillas

El primer paso que vamos a realizar es trocear el código HTML proporcionado. ¿*Por qué*? porque hay determinadas partes que siempre se repiten en cada una de las páginas: la **cabecera** y el **pie de página**.

Así, vamos a crear una carpeta **`templates`** (plantillas, en inglés) dentro de *src*, para almacenar todos aquellos fragmentos de código HTML que podemos reutilizar de una página a otra.

Dentro de templates vamos a crear dos archivos: **`header.php`** y **`footer.php`**.

La estructura, por el momento, queda del siguiente modo:

<div style="text-align: center;"><img src="../../img/ut01/ejercicio107b.png" alt="estructura de carpetas y ficheros" style="zoom:70%; border: 2px solid #fff2c9;" /></div>

> En **clase**: vamos a analizar *index.html* y debatir cómo segmentar el código. A continuación reorganizamos el código de index.html entre *footer.php* y *header.php*.

Una vez configuradas las plantillas, queda muy poco código HTML en *index.html*. Vamos ahora a poner el restante del código HTML en **index.php** y vaciar por completo *index.html* (tras lo cual eliminamos este fichero).

El fichero *index.php* quedaría del siguiente modo:

```html
<div class="container">

</div>
```

¿*Cómo podemos ahora volver a estructurar la página de inicio con las plantillas*? Esto se verá en el siguiente apartado.

### b) Conformación de las páginas de la aplicación

Para poder estructurar las páginas de la aplicación basándonos en las plantillas, podemos utilizar la instrucción [include](https://www.php.net/manual/es/function.include.php){target=_blank rel="noopener noreferrer"}. Modificando el fichero *index.php*, quedaría del siguiente modo:

```php
<?php  include("templates/header.php"); ?>

<div class="container">

</div>

<?php  include("templates/header.php"); ?>
```

Ahora, con el contenedor *Docker* iniciado, si refrescamos la página, volveremos a obtener el mismo resultado que con la página web estática:

<div style="text-align: center;"><img src="../../img/ut01/ejercicio107c.png" alt="Fichero index.php" style="zoom:45%; border: 2px solid #fff2c9;" /></div>

Parece que no haya cambiado nada, pero PHP ya está trabajando para nosotros. Hemos pasado de una página web estática, recogida en un solo documento, a una página modular generada con PHP. En un sitio web con multitud de páginas esto puede ser una gran ventaja.

> ATENCIÓN: el orden en que han sido cargados los ficheros es importante. Si escribiésemos el siguiente fichero index.php:

Ahora, como buenos programadores, nos preguntamos ¿*`include` nos sirve para todos los casos o existen otras posibilidades para incluir ficheros en PHP*? Lo vemos en el siguiente apartado.

### c) Inclusión de ficheros en PHP

La idea de utilizar diferentes ficheros es la reutilización del código, lo que conlleva una mayor modularidad y un mejor mantenimiento del mismo. Un fichero no tiene por qué ser una plantilla, como hemos visto hasta ahora, sino que también podría ser código PHP que pudiésemos llegar a utilizar en diferentes partes de la aplicación. También se pueden dar diferentes circunstancias, y es por ello que disponemos de varias opciones:

* `include(ruta/archivo);`   `include\_once(ruta/archivo);`
* `require(ruta/archivo);`   `require\_once(ruta/archivo);`

> NOTA: Si el archivo se encuentra a la misma altura (en el sistema de archivos) que el fichero en el cual se incluye, entonces solo es necesario especificar el nombre del archivo a incluir; si los dos archivos no se encuentran a la misma altura, entonces es posible especificar la ruta ([absoluta o relativa](https://desktop.arcgis.com/es/arcmap/latest/tools/supplement/pathnames-explained-absolute-relative-unc-and-url.htm){target=_blank rel="noopener noreferrer"}) del fichero a incluir.

Las particularidades de cada instrucción son:

* **require**: lanza un error fatal si no encuentra el archivo.
* **include**: si no encuentra el archivo, emite una advertencia (warning).
* Las funciones **\_once** sólo se cargan una vez, si ya ha sido incluida previamente, no lo vuelve a hacer, evitando bucles.

Por ejemplo, colocamos las siguientes funciones en el archivo biblioteca.php:

```php
<?php
  function suma(int $a, int $b) : int {
      return $a + $b;
  }

  function resta(int $a, int $b) : int {
      return $a - $b;
  }
?>
```

Y posteriormente en otro archivo incluimos el anterior:

```php
<?php

  include_once("biblioteca.php");

  echo suma(10,20);

  echo resta(40,20);

?>
```

### d) Completamos el resto de páginas

En este apartado vamos a completar las páginas y las plantillas de la aplicación para poder añadirles posteriormente código PHP.

#### **index.php**

Vamos a introducir un proyecto de prueba para que se pueda visualizar en la página de inicio, quedaría del siguiente modo:

<div style="text-align: center;"><img src="../../img/ut01/ejercicio107d.png" alt="Fichero index.php" style="zoom:45%; border: 2px solid #fff2c9;" /></div>

Vamos a utilizar una imagen para cada proyecto. Para poder hacer esto hemos de almacenar las imágenes en la estructura de directorios, por eso creamos una carpeta *static* dentro de src, y dentro de static crearemos otra llamada *images*:

<div style="text-align: center;"><img src="../../img/ut01/ejercicio107e.png" alt="Estructura del proyecto" style="zoom:70%; border: 2px solid #fff2c9;" /></div>

> NOTA: si has de descargar imágenes de Internet, asegúrate que la licencia de dichas imágenes te lo permite. Utiliza, si es necesario, un sitio como [pixabay.com](http://pixabay.com/){target=_blank rel="noopener noreferrer"} para descargar imágenes con licencia libre.

Descárgate una imagen en la carpeta *images*, renómbrala a **projecte1.png** (aquí tienes la imagen del ejemplo <a download="projecte1.png" href="../../sources/">projecte1.png</a> si deseas utilizar la misma) y completa el fichero *index.php* con el siguiente código:

```php
<?php include("templates/header.php"); ?>

<div class="container">
    <a href="#">
        <div class="card" style="width: 18rem;">
            <img class="card-img-top" src="static/images/projecte1.png" alt="Projecte 1">
            <div class="card-body">
                <h5 class="card-title">Projecte 1</h5>
                <p class="card-text">Descripció del projecte 1.</p>
            </div>
        </div>
    </a>
</div>

<?php include("templates/footer.php"); ?>
```

Ahora debería aparecer un proyecto en la página principal.

#### **proyecto.php**

Creamos este nuevo fichero a la altura de *index.php* y añadimos el siguiente contenido:

```php
<?php include("templates/header.php"); ?>

<div class="container">
    <h2>Títol de mostra</h2>
    <h4><a href="#">Any</a></h4>
    <span>Categories: </span>
    <a href="#"><button class="btn btn-sm btn-default">Categoria 1</button></a>
    <br> <br>
    <div class="row">
        <div class="col-sm">
            <img src="static/images/projecte1.png" alt="Projecte 1" class="img-responsive"><br>
        </div>
        <div class="col-sm">Descripció</div>
    </div>
</div>

<?php include("templates/footer.php"); ?>
```

Para consultar el resultado, introducimos en la barra de navegación la URL **localhost:8081/proyecto.php**:

<div style="text-align: center;"><img src="../../img/ut01/ejercicio107f.png" alt="Estructura del proyecto" style="zoom:45%; border: 2px solid #fff2c9;" /></div>

#### **contacto.php**

Descargamos esta <a download="contacte.png" href="../../sources/">contacte.png</a> en el directorio correspondiente (con el nombre businessman.png), creamos contacto.php a la altura de index.php, e insertamos el siguiente código:

```php
<?php include("templates/header.php"); ?>

<div class="container">
    <h2 class="mb-5">Contacte</h2>
    <div class="row">
        <div class="col-md">
            <img src="static/images/contacte.png" class="img-fluid rounded">
        </div>
        <div class="col-md">
            <h3>Nom i cognoms</h3>
            <p>Cicle Superior DAW.</p>
            <p>Apasionat del mon de la programació en general, i de les tecnologies web en particular.</p>
            <p>Si tens algun dubte contacta amb mí per favor.</p>
            <p>Telèfon: 123456789</p>
        </div>
    </div>
</div>

<?php include("templates/footer.php"); ?>
```

El resultado, al consultar la URL localhost/contacto.php, debería ser el siguiente:

<div style="text-align: center;"><img src="../../img/ut01/ejercicio107g.png" alt="Página contacto.php" style="zoom:45%; border: 2px solid #fff2c9;" /></div>

En estos momentos tenemos ya muchas cosas preparadas para poder empezar a añadir dinamismo a nuestra aplicación. En los siguientes apartados vamos a ver las diferentes estructuras que nos lo van a permitir.

