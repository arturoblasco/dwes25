# Ejercicios propuestos



## Ejercicio 1

Crea un proyecto Laravel llamado **blog**. 

Pon en marcha dicho proyecto con el comando **`php artisan serve`** e intenta acceder a él desde el navegador. 

Captura la pantalla del navegador, de modo que incluya tanto la página de inicio que se muestra como la URL de la barra de direcciones. Guarda la captura como **`captura01`**, en formato JPG o PNG.

<hr />

## Ejercicio 2

Sobre el proyecto del ejercicio anterior, configura un *virtual host* en *Apache* con el mismo nombre de host (*blog*), y asócialo a una IP local (la que tú quieras, por ejemplo, 127.0.0.6). Prueba a acceder a la página de inicio de este nuevo proyecto (con *http://blog* o con *http://127.0.0.6*). 

Captura la pantalla del navegador, mostrando la página de inicio y la URL de la barra de direcciones. Guarda la captura como **`captura02`**, en formato JPG o PNG. 

!!!note "Proyecto desde Laragon"
	Ten en cuenta que, si está utilizando *Laragon*  para tus proyectos *Laravel*, no hará falta realizar este ejercicio 2; pues  este sistema ya crea un host virtual para cada uno de sus proyectos (por ejemplo: **`http://blog.test`**).

<hr />

## Ejercicio 3

Sobre el proyecto **blog**, edita el fichero **`routes/web.php`** y añade:

&nbsp;&nbsp;&nbsp;&nbsp; a) Una nueva ruta a la URL `posts`. Al acceder a esta ruta (*http://blog.test/posts*), deberemos ver un mensaje con el texto “*Listado de posts*”.

&nbsp;&nbsp;&nbsp;&nbsp; b) Una nueva ruta a la URL `fecha`. Al acceder a esta ruta (*http://blog.test/fecha*), deberemos ver un mensaje con el día y la hora actual.

<hr />

## Ejercicio 4

Sobre el proyecto **blog**, vamos a añadir estos dos cambios:

&nbsp;&nbsp;&nbsp;&nbsp; a) Añade una nueva ruta parametrizada a **`posts/{id}`**, de manera que el parámetro `id` sea numérico (es decir, sólo contenga dígitos del *0* al *9*) y obligatorio. Haz que la ruta devuelva el mensaje “*Ficha del post XXXX*”, siendo *XXXX* el id que haya recibido como parámetro. Si no se inserta id deberá mostrar el id = *1*.

&nbsp;&nbsp;&nbsp;&nbsp; b) Pon un nombre a las tres rutas que hay definidas hasta ahora: a la página de inicio ponle el nombre “*inicio*”, a la del listado la llamaremos “*posts_listado*” y a la de ficha que acabas de crear, la llamaremos “*posts_ficha*”.


<hr /> 

## Ejercicio 5

Continuamos con el proyecto **blog**. En este caso vamos a definir una plantilla y una serie de vistas que la utilicen.

&nbsp;&nbsp;&nbsp;&nbsp; a) Definir una plantilla llamada **`plantilla1.blade.php`** en la carpeta de vistas del proyecto (`resources/views`). Define una cabecera con una sección `yield` para el título, y otra para el contenido de la página, como la del ejemplo que hemos visto anteriormente.

&nbsp;&nbsp;&nbsp;&nbsp; b) Define en un archivo aparte en la subcarpeta **`partials`** de nombre: **`nav.blade.php`** una barra de navegación que nos permita acceder a estas direcciones de momento:

&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; - Página de inicio <br />
&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; - Listado de posts

&nbsp;&nbsp;&nbsp;&nbsp; c)Incluye la barra de navegación en la plantilla base que has definido antes.

&nbsp;&nbsp;&nbsp;&nbsp; d)A partir de la plantilla base, define otras dos vistas en una subcarpeta `posts`, llamadas `posts/listado.blade.php` y `posts/ficha.blade.php`. Como título de cada página pon un breve texto de lo que son (por ejemplo, “*Listado posts*” y “*Ficha post*”), y como contenido de momento deja un encabezado `h1` que indique la página en la que estamos: “*Listado de posts*” o “*Ficha del post XXXX*”, donde *XXXX* será el identificador del post que habremos pasado por la URL (y que deberás pasar a la vista). Haz que las rutas correspondientes de `routes/web.php` que ya has definido rendericen estas vistas en lugar de devolver texto plano.

 <hr />

## Ejercicio 6

Sobre el mismo proyecto **blog** que venimos desarrollando, incorpora ahora los estilos de Bootstrap siguiendo los pasos vistos en estos apuntes:

&nbsp;&nbsp;&nbsp;&nbsp; a) Instala con *composer* la librería `laravel/ui`, y utilízala para incorporar Bootstrap al proyecto.<br />
&nbsp;&nbsp;&nbsp;&nbsp; b) Descarga Bootstrap con `npm install`, y actualiza los archivos CSS y JavaScript con `npm run dev`.<br />
&nbsp;&nbsp;&nbsp;&nbsp; c) Incorpora los estilos `/css/app.css` a la plantilla base del proyecto, para que los utilicen todas las vistas que heredan de ella.<br />
&nbsp;&nbsp;&nbsp;&nbsp; d) Edita el archivo `partials/nav.blade.php` para modificar la barra de navegación y dejarla con un estilo particular de Bootstrap. Puedes consultar [esta página](https://getbootstrap.com/docs/4.5/components/navbar/){:target="_blank"} para tomar ideas de algunos diseños que puedes aplicar en la barra de navegación.<br />
&nbsp;&nbsp;&nbsp;&nbsp; e) Renombra el archivo `welcome.blade.php` a `inicio.blade.php` y cámbialo para que también herede de la plantilla base. Añade algún texto introductorio como contenido. Puede quedarte más o menos así (la barra de navegación superior puede variar en función del estilo que hayas querido darle).

<div style="text-align: center;"><figure><img src="../../img/ut07/07_ac_blog_inicio.png" alt="blog_inicio" style="zoom:50%; border: 2px solid #fff2c9;" /><figcaption style="font-size: 13px; color: #bd8f04;">Blog de inicio del ejercicio 6.</figcaption></figure></div>


!!!ies "¿Qué entregar en estos ejercicios?"
	Como entrega de esta sesión deberás comprimir el proyecto *blog* con todos los cambios incorporados, y eliminando las carpetas `vendor` y `node_modules` como se explicó en las sesiones anteriores. Renombra el archivo comprimido a **`blog_07.zip`**.
