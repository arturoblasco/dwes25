# Ejercicios propuestos

## Ejercicio 1

Sobre el proyecto **blog** de la sesión anterior, vamos a añadir estos cambios:

&nbsp;&nbsp;&nbsp;&nbsp; a) Crea un controlador de recursos (opción `-r`) llamado `PostController`, que nos servirá para gestionar toda la lógica de los posts del blog.

&nbsp;&nbsp;&nbsp;&nbsp; b) Asigna automáticamente con el método `resource` cada ruta a su función correspondiente del controlador, en el archivo `routes/web.php`. Limita con `only` las acciones sólo a las funciones de listado (`index`), ficha (`show`), creación (`create`) y edición (`edit`).

&nbsp;&nbsp;&nbsp;&nbsp; c) Utiliza el proveedor de servicios `AppServiceProvider` para “castellanizar” las rutas de creación y edición, como en el ejemplo que hemos visto de libros.

&nbsp;&nbsp;&nbsp;&nbsp; d) Renombra las *vistas* de listado y ficha de un post a `index.blade.php` y `show.blade.php`, dentro de su carpeta `posts`, y haz que los métodos correspondientes del controlador de posts rendericen estas vistas. Para los métodos `create` y `edit`, simplemente devuelve un texto plano indicando “*Nuevo post*” y “*Edición de post*”, por ejemplo.

&nbsp;&nbsp;&nbsp;&nbsp; e) Haz los cambios adicionales que sean convenientes (por ejemplo, en el menú de navegación) para que los enlaces sigan funcionando, y prueba que las cuatro rutas (listado, ficha, creación y edición) funcionan adecuadamente.

<hr />

## Ejercicio 2

Sobre el proyecto **blog**, se deberá añadir estos cambios:

&nbsp;&nbsp;&nbsp;&nbsp; a) Implementar las funciones de `create` y `edit` del controlador de *posts*, en lugar de mostrar un mensaje de texto plano indicando que ahí va un formulario, redirijan a la página de *inicio*, usando la instrucción `redirect`.

&nbsp;&nbsp;&nbsp;&nbsp; b) Crear un *helper*  en el proyecto que defina una función llamada `fechaActual`. Recibirá como parámetro un formato de fecha (por ejemplo, “*d/m/y*”) y sacará la fecha actual en dicho formato. Utilízalo para mostrar la fecha actual en formato “*d/m/Y*” en la plantilla base, bajo la barra de navegación, alineada a la derecha.



!!!ies "¿Qué entregar en estos ejercicios?"
	Como entrega de esta sesión deberás comprimir el proyecto **blog** con todos los cambios incorporados, y eliminando las carpetas `vendor` y `node_modules` como se explicó en las sesiones anteriores. <br /><br />
	Renombra el archivo comprimido a **`blog_08.zip`**.

