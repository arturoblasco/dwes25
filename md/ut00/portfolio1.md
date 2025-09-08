---
title: portfolio1
---

# Portfolio 1

## 1. Instalación de Docker

El primer paso para configurar el entorno de desarrollo es la instalación de **Docker**.

> En realidad podríamos preparar el entorno de diferentes formas (directamente instalando en la máquina local, mediante máquina virtual...); pero la utilización de *Docker* se ve justificada por su fácil utilización, la profundización en esta tecnología en otros módulos de este curso (*Despliegue de aplicaciones web*), y su extensa utilización en la industria (en todo tipo de entornos).

Para la Instalación de **Docker** es recomendable seguir la documentación oficial:

* [Docker for **Windows**](https://docs.docker.com/desktop/windows/install/){target=_blank rel="noopener noreferrer"}
* [Docker for **Ubuntu**](https://docs.docker.com/engine/install/ubuntu/){target=_blank rel="noopener noreferrer"}
* [Docker for **Mac**](https://docs.docker.com/desktop/mac/install/){target=_blank rel="noopener noreferrer"}

Si aún no tienes instalado Docker en tu máquina, **¡es el momento de hacerlo!**

## 2. Estructura de directorios

Una vez tengas instalado *Docker*, crea la siguiente **estructura de directorios** en la ruta en la que vayas a crear el proyecto:

<div style="text-align: center;"><figure><img src="../../img/ut01/portfolio1_01.png" alt="estructura de directorios" style="zoom:130%; border: 2px solid #fff2c9;" /><figcaption style="font-size: 13px; color: #bd8f04;">Estructura de directorios</figcaption></div>

Por el momento la carpeta **src** está vacía. Vamos a crear el fichero **docker-compose.yml** (==está a la misma altura que la carpeta *src*==) con el siguiente contenido:

<div style="text-align: center;"><figure><img src="../../img/ut01/portfolio1_02.png" alt="estructura de directorios" style="zoom:100%; border: 2px solid #fff2c9;" /><figcaption style="font-size: 13px; color: #bd8f04;">Contenido del fichero docker-compose.yml</figcaption></div>

Para crear la indentación (espacios a la izquierda) pulsa el tabulador tantas veces como sea necesario (la indentación es importante en el fichero *docker-compose.yml*).

Pues ya lo tendríamos todo para poder ejecutar nuestro código, pero... ¿*cómo es posible*? Este fichero *docker-compose.yml* va a lanzar un contenedor preconfigurado (como si fuese una pequeña máquina virtual) con PHP 8.2 y el servidor web Apache. Además, va a exponer el puerto 8081 y lo va a hacer corresponder con el puerto 80 del host (nuestra máquina local); por último, todo lo que haya en el directorio *src* va a estar montado en la ruta */var/www/html* (donde recoge *Apache *el código a ejecutar) del sistema de archivos del contenedor, con lo cual lo único que vamos a tener que hacer es incluir todo nuestro código en la carpeta ​*src*​, y el contenedor se encargará del resto.

**Prueba**:

Vamos a hacer un prueba. Vamos a la ruta donde esté el fichero *docker-compose.yml* y ejecutamos el siguiente comando:

```bash
docker-compose up
```

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/100000010000055100000206DC7C3C7AAE2D2F50.png)

En primer lugar vemos que se han realizado una serie de descargas para conformar el contenedor (estas descargas solo se van a realizar la primera vez, a no ser que hagamos un purgado de *Docker*). Una vez hecho esto se lanza el contenedor (web\_1) y se empiezan a mostrar las líneas de log de *Apache*. Si ahora consultamos en el navegador la dirección **127.0.0.1:80** (equivalente a introducir ​*localhost*​, que es un alias configurado en el fichero /etc/hosts), vemos el resultado:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/10000001000001C3000000D71C6184927B436CDC.png)

Si consultamos en log de *Apache* en la consola:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/100000010000054E0000008A966FF3A2AEB459BA.png)

Vemos que sí se ha registrado actividad, pero: ¿*por qué no se muestra nada en el navegador*? La razón es que aún no hemos introducido ningún fichero en *src* que Apache pueda tomar para interpretar. En el propio *log *lo dice:

> *​Cannot serve directory /var/www/html/: No matching DirectoryIndex (​*​​*index.php,index.html*​*) found, and server-generated directory index forbidden by Options directive*

Por tanto vamos a introducir un fichero **index.php** con el típico "*Hola mundo*":

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/10000001000001CD000000A0A0938E64DB853D46.png)

Refrescamos el navegador y vemos la salida:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/100000010000012300000064DDF677C550B6056F.png)

Borramos el fichero *index.php*, y *src *vuelve a estar vacío.

Podemos decir que estamos preparados para empezar nuestro proyecto.

## PROYECTO - PREPARACIÓN DE LA INTERFAZ

Junto con el presente documento se proporciona también el punto de partida del proyecto, que va a consistir en un documento HTML, llamado [index.html](https://manu-perez-alfonso.github.io/modulos/01-Servidor/ud3_estaticos/) (pincha para descargarlo). Se han utilizado las siguientes librerías para conformar la interfaz de usuario:

* [Bootstrap 5.2](https://getbootstrap.com/){target=_blank rel="noopener noreferrer"}, para el diseño de interfaces responsivas.
* Tema [Flatly](https://www.bootstrapcdn.com/bootswatch/){target=_blank rel="noopener noreferrer"} basado en bootstrap, que proporciona la hoja de estilos de la interfaz.
* [Font awesome 6.2](https://fontawesome.com/){target=_blank rel="noopener noreferrer"}, para la utilización de iconos.

Si nos fijamos en el contenido del fichero index.html proporcionado no se diferencia en nada (salvo en la utilización de las librerías anteriores) de una de las páginas estáticas programadas durante el primer curso del ciclo:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/1000000100000559000002828DE3B7424C010DA5.png)

Vamos a empezar a incrustar PHP en el código HTML.

### Utilización de plantillas

El primer paso que vamos a realizar es trocear el código HTML proporcionado. ¿Por qué? porque hay determinadas partes que siempre se repiten en cada una de las páginas: la cabecera y el pié de página.

Así, vamos a crear una carpeta "templates" (plantillas, en inglés) dentro de "src", para almacenar todos aquellos fragmentos de código HTML que podemos reutilizar de una página a otra.

Dentro de templates vamos a crear dos archivos: header.php y footer.php. También vamos a crear un archivo "index.php", pero esta vez dentro de "src" (​**NO dentro de "templates"!**​). La estructura, por el momento, queda del siguiente modo:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/10000001000001120000017188DF838454706765.png)

​***ACTIVIDAD***​: En clase, vamos a analizar index.html y debatir cómo segmentar el código. A continuación reorganizamos el código de index.html entre footer.php y header.php.

Una vez configuradas las plantillas, queda muy poco código HTML en index.html. Vamos ahora a poner el restante del código HTML en index.php y vaciar por completo index.html (tras lo cual eliminamos este fichero). index.php queda del siguiente modo:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/100000010000013F0000004B1A069B528EF03557.png)

¿Cómo podemos ahora volver a estructurar la página de inicio con las plantillas? Esto se verá en el siguiente apartado.

### Conformación de las páginas de la aplicación

Para poder estructurar las páginas de la aplicación basándonos en las plantillas, podemos utilizar la instrucción [include](https://www.php.net/manual/es/function.include.php){target=_blank rel="noopener noreferrer"}. Modificando el fichero index.php, quedaría del siguiente modo:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/10000001000001AB000000707087E66FC920E92C.png)

Ahora, con el contenedor Docker iniciado, si refrescamos la página, volveremos a obtener el mismo resultado que con la página web estática:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/10000001000005530000028696367F9909F8C2BE.png)

Parece que no haya cambiado nada, pero PHP ya está trabajando para nosotros. Hemos pasado de una página web estática, recogida en un solo documento, a una página modular generada con PHP. En un sitio web con multitud de páginas esto puede ser una gran ventaja.

ATENCIÓN: el orden en que han sido cargados los ficheros es importante. Si escribiésemos el siguiente fichero index.php:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/100000010000019D000000803A39D370EC32B6B8.png)

Obtendríamos el siguiente resultado:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/100000010000055300000281A67DC9A149798866.png)

El HTML generado ni siguiera sería correcto, pero los navegadores web modernos son capaces de tomar un documento HTML incorrecto sintácticamente y representar la mejor aproximación.

Volvemos a la versión anterior de index.php tras esta pequeña prueba.

Ya tendríamos lo básico del proyecto hecho. Hemos visto la función include, pero somos programadores con mucha curiosidad, y nos preguntamos si "include" nos sirve para todos los casos o existen otras posibilidades para incluir ficheros en PHP. En el próximo apartado vamos a profundizar en esto.

### Inclusión de ficheros en PHP

La idea de utilizar diferentes ficheros es la reutilización del código, lo que conlleva una mayor modularidad y un mejor mantenimiento del mismo. Un fichero no tiene por qué ser una plantilla, como hemos visto hasta ahora, sino que también podría ser código PHP que pudiésemos llegar a utilizar en diferentes partes de la aplicación. También se pueden dar diferentes circunstancias, y es por ello que disponemos de varias opciones:

* include(ruta/archivo); include\_once(ruta/archivo);
* require(ruta/archivo); require\_once(ruta/archivo);

NOTA: Si el archivo se encuentra a la misma altura (en el sistema de archivos) que el fichero en el cual se incluye, entonces solo es necesario especificar el nombre del archivo a incluir; si los dos archivos no se encuentran a la misma altura, entonces es posible especificar la ruta ([absoluta o relativa](https://es.itsfoss.com/ruta-absoluta-relativa-linux/#:~:text=La%20ruta%20absoluta%20siempre%20comienza,%2Fscripts%2Fmi_script.sh.){target=_blank rel="noopener noreferrer"}) del fichero a incluir.

Las particularidades de cada instrucción son:

* require: lanza un error fatal si no encuentra el archivo.
* include: si no encuentra el archivo, emite una advertencia (warning)
* Las funciones \_once sólo se cargan una vez, si ya ha sido incluida previamente, no lo vuelve a hacer, evitando bucles.

Por ejemplo, colocamos las siguientes funciones en el archivo biblioteca.php:

```
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

```
<?php

include_once("biblioteca.php");

echo suma(10,20);

echo resta(40,20);

?>
```

### Completamos el resto de páginas

En este apartado vamos a completar las páginas y las plantillas de la aplicación para poder añadirles posteriormente código PHP.

index.php

Vamos a introducir un proyecto de prueba para que se pueda visualizar en la página de inicio, quedaría del siguiente modo:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/100000010000077A000003D78BB3D494D59C522A.png)

Vamos a utilizar una imagen para cada proyecto. Para poder hacer esto hemos de almacenar las imágenes en la estructura de directorios, por eso creamos una carpeta "static" dentro de src, y dentro de static crearemos otra llamada "images":

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/100000010000010400000195066A36530A4CF26E.png)

> NOTA: si has de descargar imágenes de Internet, asegúrate que la licencia de dichas imágenes te lo permite. Utiliza, si es necesario, un sitio como [pixabay.com](http://pixabay.com/){target=_blank rel="noopener noreferrer"} para descargar imágenes con licencia libre.

Descárgate una imagen en la carpeta "images", renómbrala a "proyecto1.jpg" y completa el fichero "index.php" con el siguiente código:

```
<?php include("templates/header.php"); ?>

<div class="container">
    <a href="#">
        <div class="card" style="width: 18rem;">
            <img class="card-img-top" src="static/images/proyecto1.jpg" alt="Proyecto 1">
            <div class="card-body">
                <h5 class="card-title">Proyecto 1</h5>
                <p class="card-text">Descripción del proyecto 1.</p>
            </div>
        </div>
    </a>
</div>

<?php include("templates/footer.php"); ?>
```

Ahora debería aparecer un proyecto en la página principal.

proyecto.php

Creamos este nuevo fichero a la altura de index.php y añadimos el siguiente contenido:

```
<?php include("templates/header.php"); ?>

<div class="container">
    <h2>Título de muestra</h2>
    <h4><a href="#">Año</a></h4>
    <span>Categorías: </span>
    <a href="#"><button class="btn btn-sm btn-default">Categoría 1</button></a>
    <br> <br>
    <div class="row">
        <div class="col-sm">
            <img src="static/images/proyecto1.jpg" alt="Proyecto 1" class="img-responsive"><br>
        </div>
        <div class="col-sm">Descripción</div>
    </div>
</div>

<?php include("templates/footer.php"); ?>
```

Para consultar el resultado, introducimos en la barra de navegación la URL "localhost/proyecto.php":

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/1000000100000775000003DA2E430A8F54CA1022.png)

contacto.php

Descargamos esta [imagen](https://cdn.pixabay.com/photo/2014/04/03/10/32/businessman-310819_960_720.png){target=_blank rel="noopener noreferrer"} en el directorio correspondiente (con el nombre businessman.png), creamos contacto.php a la altura de index.php, e insertamos el siguiente código:

```
<?php include("templates/header.php"); ?>

<div class="container">
    <h2 class="mb-5">Contacto</h2>
    <div class="row">
        <div class="col-md">
            <img src="static/images/businessman.png" class="img-fluid rounded">
        </div>
        <div class="col-md">
            <h3>Nombre y apellidos</h3>
            <p>Ciclo Superior DAW.</p>
            <p>Apasionado del mundo de la programación en general, y de las tecnologías web en particular.</p>
            <p>Si tienes cualquier tipo de pregunta, contacta conmigo por favor.</p>
            <p>Teléfono: 87654321</p>
        </div>
    </div>
</div>

<?php include("templates/footer.php"); ?>
```

El resultado, al consultar la URL localhost/contacto.php, debería ser el siguiente:

![](https://manu-perez-alfonso.github.io/modulos/01-Servidor/Pictures/ud3/1000000100000775000003DAA9790CDEBCC7547E.png)

En estos momentos tenemos ya muchas cosas preparadas para poder empezar a añadir dinamismo a nuestra aplicación. En los siguientes apartados vamos a ver las diferentes estructuras que nos lo van a permitir.

