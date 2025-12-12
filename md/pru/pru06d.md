# Despliegue en PaaS **Render**

En este apartado hemos elegido **Render** como plataforma de despliegue para nuestra aplicación. **Render** es una plataforma de alojamiento que permite desplegar aplicaciones web, bases de datos y servicios de forma sencilla y rápida.

Principales características de **Render**:

| Característica | Descripción |
| --- | --- |
| Despliegue automático | **Render** permite desplegar aplicaciones automáticamente desde un repositorio de GitHub o GitLab. |
| Escalabilidad | **Render** permite escalar aplicaciones fácilmente, ajustando los recursos según las necesidades. |
| Soporte para múltiples lenguajes | **Render** soporta una amplia variedad de lenguajes de programación y frameworks. |
| Integración con bases de datos | **Render** permite crear y gestionar bases de datos de forma sencilla. |
| Seguridad | **Render** ofrece características de seguridad como HTTPS, firewall y protección DDoS. |
| Precios competitivos | **Render** ofrece precios competitivos y un plan gratuito para proyectos pequeños. |
| Soporte para contenedores | **Render** permite desplegar aplicaciones en contenedores Docker. |
| Monitorización y logs | **Render** proporciona herramientas de monitorización y acceso a logs para depurar aplicaciones. |

Las dos principales características que nos interesan son el despliegue automático y la integración con bases de datos. Y la posibilidad de crear una cuenta gratuita para proyectos pequeños.

Render

En nuestro caso, para aprovechar el despliegue automático, es necesario que el repositorio de la aplicación esté en GitHub. Si no tienes una cuenta en GitHub, puedes crear una de forma gratuita.

Por tanto para empezar tenemos un proyecto de Laravel (recien creado, sin modificaciones), si desplegamos este proyecto, el despliegue nos servirá para cualquier proyecto de Laravel que tengamos, ya que el proceso de despliegue es el mismo. Eso sí, lo tenemos que tener en GitHub.

Podemos utilizar el proyecto creado para el curso, que está en el siguiente repositorio:

```
https://github.com/jbeteta-ies/phpDeployTest
```

En este caso el repositorio es privado (cada alumno debe tener su propio repositorio ya sea púbico o privado). Lo conveniente sería clonar el repositorios y subirlo a una cuenta de GitHub propia, será necesario para poder hacer el despliegue.

## Creación de la cuenta en **Render**

Para crear una cuenta en **Render**, sigue estos pasos:

1. Accede a la página de [**Render**](https://**Render**.com/).
2. Haz clic en el botón "Sign Up" (Registrarse) en la esquina superior derecha.
3. Puedes registrarte con tu cuenta de GitHub, GitLab o correo electrónico. En este caso, selecciona "Sign up with GitHub" (Registrarse con GitHub).
4. Autoriza a **Render** para acceder a tu cuenta de GitHub.
5. Completa el proceso de registro siguiendo las instrucciones en pantalla.
6. Una vez registrado, serás redirigido al panel de control de **Render**.

Yo he creado mi cuenta con GitHub, por lo que me ha redirigido a la página de autorización de **Render** en GitHub. He autorizado a **Render** para acceder a mi cuenta de GitHub y he completado el registro.

Cuenta gratuita

**Render** ofrece múltiples opciones. Nosotros vamos a centrarnos en el despliegue de nuestra aplicación, utilizando las mínimas opciones de ***Render***. Existen otros cursos de *Despliegue* de aplicaciones que seguro que profundizan más en las características de este tipo de servidores.

## Creación de la base de datos

Lo primero que vamos a hacer es crear la base de datos que vamos a utilizar en nuestra aplicación. Recordemos que nuestra aplicación utiliza una base de datos MySQL. Pero **Render** es su versión gratuita solo nos permite crear bases de datos PostgreSQL. Por tanto, vamos a crear una base de datos PostgreSQL.

Opciones seleccionadas:

| Opción | Descripción | Valor |
| --- | --- | --- |
| Name | Nombre de la instancia Postgress | `pg-laravel` |
| Database | Nombre de la base de datos | `laravel` |
| Username | Nombre de usuario de la base de datos | `alumno` |
| Region | Región de la base de datos | `Europe (Frankfurt)` |
| Postgres Version | Versión de PostgreSQL | `16` |
| Datalog Region | Región para enviar datos al Datalog | `EU` |
| Instance Type | Tipo de instancia | `Free` |

Una vez seleccionadas las opciones, hacemos clic en el botón "Create Database" (Crear base de datos). **Render** creará la base de datos y nos proporcionará las credenciales de acceso.

Región

La base de datos se creará en la región de Europa (Frankfurt) por defecto. Si necesitas cambiar la región, puedes hacerlo en las opciones avanzadas.

Plan gratuito

En el plan gratuito las base de datos caducan a los 30 días. Por tanto tenemos este tiempo para realizar el despliegue de nuestra aplicación y probarla. Posteriormente hay que volver a crear la base de datos o actualizar a un plan de pago.

Una vez creada, la aplicación nos mostrará las credenciales de acceso a la base de datos. Estas credenciales son necesarias para configurar nuestra aplicación Laravel.

| Clave | Valor |
| --- | --- |
| Host | `dpg-d0qdmsjuibrs73ehedmg-a` |
| Port | `5432` |
| Database | `laravel_6idw` |
| Username | `alumno` |
| Password | `p3DmIdbZEBtmzcuMflpDHoEpxp8uToLh` |
| URL Externa | `postgresql://alumno:p3DmIdbZEBtmzcuMflpDHoEpxp8uToLh@dpg-d0qdmsjuibrs73ehedmg-a.frankfurt-postgres.**Render**.com/laravel\_6idw' |
| PSQL Command | `PGPASSWORD=p3DmIdbZEBtmzcuMflpDHoEpxp8uToLh psql -h dpg-d0qdmsjuibrs73ehedmg-a.frankfurt-postgres.**Render**.com -U alumno laravel_6idw` |

Nosotros necesitaremos el `Host`, `Port`, `Database`, `Username` y `Password` para configurar nuestra aplicación Laravel.

## Configuración de la aplicación Laravel

Antes de comenzar con la creación del servicio en **Render**, debemos preparar nuestra aplicación para que funcione correctamente en el entorno de **Render**. Para ello, debemos realizar los siguientes

1. Crear una rama `Render` esta rama será la que utilicemos para accionar el desplegue automático.
2. Configurar el archivo `.env` de Laravel.
3. Crear el archivo `Dockerfile` para la aplicación.
4. Crear el archivo `src/public/migrate.php` para ejecutar las migraciones de la base de datos.

Para ello y para no mezclar el código de la aplicación con el código de `Render` vamos a crear una carpeta llamada `/Render` en la raíz de nuestro proyecto. En esta carpeta vamos a crear los archivos necesarios para el despliegue en **Render**.

### Crear la rama `Render`

El nombre de `Render` es completamenta aleatorio. Simplemente es tener una rama sobre la que guardar los cambios necesarios y activar el despliegue automático. Por ejemplo, en nuesta aplicación podemos trabajar con una rama `develop` y una rama `main`. Durante el desarrlllo y pruebebas de aplicaciones podemos trabajar en la rama `develop` y cuando tengamos una versión estable, la pasamos a `main`.

Cuando queramos desplegar la aplicación, simplemente hacemos un `merge` de `develop` a `Render` y **Render** se encargará de desplegar la aplicación automáticamente.

Yo voy a trabajar directamente en la rama `Render`. El manejo de ramas es algo que dejo para cursos más específicos sobre control de versiones y Git.

Lo primero que tenemos que hacer antes de crear la rama es tener nustro proyecto limpio, es decir, hemos hecho `commit` de todos los cambios y no tenemos cambios pendientes.

Crear la rama `Render` es sencillo, simplemente ejecutamos el siguiente comando en la terminal:

```
git checkout -b Render
```

Esto creará una nueva rama llamada `Render` y nos cambiará a esa rama. A partir de ahora, todos los cambios que realicemos se guardarán en esta rama. Y si estamos en local y queremos subir los cambios a GitHub, simplemente hacemos un `push`:

```
git push -u origin Render
```

Para los que manejan vsCode si tenemos nuestro editor conectado podemos manejar las ramas y las actualizaiones desde el propio editor. En la parte inferior izquierda de la pantalla, podemos ver el nombre de la rama actual y al hacer clic en ella, podemos cambiar de rama o crear una nueva. Pero yo voy a dar las explicaiones utilizano la terminal.

Ahora creamos la carpeta `Render` en la raíz de nuestro proyecto:

```
mkdir Render
```

Y dentro de esta carpeta vamos a crear los archivos necesarios para el despliegue en **Render**.

### Configuración del archivo `.env`

Como ya sabremos el fichero `.env` es el archivo de configuración de nuestra aplicación Laravel. En este archivo vamos a configurar la conexión a la base de datos que hemos creado en **Render**. Pero si tenemos bien configurado nuestro `.gitignore`, este archivo no se subirá al repositorio. Por tanto, vamos a crear un archivo `.env.**Render**` dentro de la carpeta `Render` que será el que subamos al repositorio y que **Render** utilizará para configurar la aplicación. De esta manera también podemos tener un `.env` local para nuestro desarrollo y un `.env.**Render**` para el despliegue en **Render**.

Creamos el archivo `.env.**Render**` dentro de la carpeta `Render`:

```
touch Render/.env.Render
```

Y editamos el archivo para configurar la conexión a la base de datos. Vamos a utilizar las credenciales que nos ha proporcionado **Render** al crear la base de datos. El contenido del archivo `.env.Render` será el siguiente:

.env.Render

```
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:F3DJSvM2eGVkAdV2XLhKoUuwYvsLHCUzzVifDqh53h0=
APP_DEBUG=false
APP_URL=https://phpdeploytest-sh8f.on**Render**.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=dpg-d0qdmsjuibrs73ehedmg-a
DB_PORT=5432
DB_DATABASE=laravel_6idw
DB_USERNAME=alumno
DB_PASSWORD=p3DmIdbZEBtmzcuMflpDHoEpxp8uToLh

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

.env.Render

Los valores utilizados en el archivo `.env.Render` son los que nos ha proporcionado **Render** al crear la base de datos. Cada alumno debe utilizar sus propias credenciales. Asegúrate de sustituir los valores por los que te ha proporcionado **Render** al crear tu base de datos.

Secretos

Como vimos ya en el tema anterior, sería recomendabla modificar el acrivo anterior y utilizar `secretos` para las credenciales de la base de datos. Pero para simplificar el proceso, vamos a utilizar el archivo `.env.Render` directamente. En puntos anteriores ya hemos visto como utilizar secretos en GitHub Actions.

### Creación del archivo `Dockerfile`

**Render** utiliza Docker para desplegar aplicaciones. Por tanto, necesitamos crear un archivo `Dockerfile` que contenga las instrucciones para construir la imagen de nuestra aplicación Laravel. Este archivo para que no tenga conflictos con otros ficheros de nuestra aplicación, lo vamos a crear también dentro de la carpeta `Render`.

```
touch Render/Dockerfile
```

Creamos el archivo `Dockerfile` dentro de la carpeta `Render`:

```
touch Render/Dockerfile
```

Y editamos el archivo para configurar la imagen de Docker. El contenido del archivo `Dockerfile` será el siguiente:

Dockerfile

|  |  |
| --- | --- |
| ```  1  2  3  4  5  6  7  8  9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31 32 33 34 35 36 37 38 39 40 41 42 43 44 45 46 ``` | ``` FROM php:8.4-fpm  # Instalar dependencias necesarias para MongoDB, etc. RUN apt-get update && apt-get install -y \     git \     unzip \     curl \     libzip-dev \     libpng-dev \     libxml2-dev \     libonig-dev \     libssl-dev \     libpq-dev \     libmongoc-1.0-0 \     libjemalloc2 \     && rm -rf /var/lib/apt/lists/*  # Instalar extensiones PHP necesarias RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql xml ctype zip  # Instalar MongoDB extension # RUN pecl install mongodb && docker-php-ext-enable mongodb  # Instalar Xdebug RUN pecl install xdebug && docker-php-ext-enable xdebug  # Instalar Composer RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer  # Establecer directorio de trabajo WORKDIR /var/www/html  # Copiar el código Laravel desde /src del repo al contenedor COPY src/ /var/www/html/  # Copiar .env.**Render** COPY **Render**/.env.**Render** /var/www/html/.env  # Ejecutar composer install sin dependencias de desarrollo RUN composer install --optimize-autoloader --no-dev  # Exponer puerto 1000 (o el que uses) EXPOSE 1000  # Comando para arrancar Laravel con servidor embebido PHP CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=1000"] ``` |

Analicemos paso a paso el contenido del `Dockerfile`:

```
FROM php:8.4-fpm
```

Esta línea indica que estamos utilizando la imagen base de PHP 8.4 con FPM (FastCGI Process Manager). Esta es una imagen oficial dePHP que incluye las extensiones necesarias para ejecutar aplicaciones Laravel.

```
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    libonig-dev \
    libssl-dev \
    libpq-dev \
    libmongoc-1.0-0 \
    libjemalloc2 \
    && rm -rf /var/lib/apt/lists/*
```

Esta sección instala las dependencias necesarias para nuestra aplicación Laravel. Incluye herramientas como `git`, `unzip`, `curl` y las bibliotecas necesarias para las extensiones de PHP que vamos a utilizar. También limpia la caché de apt para reducir el tamaño de la imagen. En este caso ya hemos añadido las dependencias necesarias para `MongoDB`y `PostgreSQL`. Si en nuestro proyecto local utiliamos más dependencias, las debemos añadir aquí. Recordad que este Docker debe ser igual al que tenemos en nuestro proyecto local, para que no tengamos problemas de dependencias al desplegar la aplicación.

```
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql xml ctype zip
```

En este caso he mantenido las extensiones para mysql y pgsql. Seguramente ya que en `Render` vamos a utilizar `PostgreSQL`, podemos eliminar la extensión `pdo_mysql` si no la vamos a utilizar en el proyecto. Pero si la tenemos en nuestro proyecto local, es mejor dejarla para evitar problemas de dependencias.

```
# Instalar MongoDB extension
# RUN pecl install mongodb && docker-php-ext-enable mongodb
```

Esta línea está comentada, pero si en nuestro proyecto utilizamos MongoDB, debemos descomentarla para instalar la extensión de MongoDB. Si no la utilizamos, podemos eliminar esta línea.

```
# Instalar Xdebug
# RUN pecl install xdebug && docker-php-ext-enable xdebug
```

Esta línea también está comentada, no es normal que utilicemos `Xdebug` en producción.

```
# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
```

Esta línea instala Composer, el gestor de dependencias de PHP. Esto nos permitirá instalar las dependencias de nuestra aplicación Laravel. Recordad que la carpeta `vendor` no se sube al repositorio, por lo que debemos instalar las dependencias en el contenedor.

```
# Establecer directorio de trabajo
WORKDIR /var/www/html
```

Esta línea establece el directorio de trabajo dentro del contenedor. Todas las siguientes instrucciones se ejecutarán en este directorio.

```
# Copiar el código Laravel desde /src del repo al contenedor
COPY src/ /var/www/html/
```

Esta línea copia el código de nuestra aplicación Laravel desde la carpeta `src` del repositorio al directorio de trabajo del contenedor. Asegúrate de que tu código Laravel está en la carpeta `src` del repositorio. 'src\' es la carpeta donde debemos tener nuestro proyecto de Laravel. Si hemos cambiado el nombre de la carpeta, debemos cambiar esta línea para que apunte a la carpeta correcta.

```
# Copiar .env.Render
COPY Render/.env.Render /var/www/html/.env
```

Esta línea copia el archivo `.env.Render` que hemos creado anteriormente al directorio de trabajo del contenedor y lo renombra a `.env`. Esto es necesario para que Laravel pueda leer la configuración de la base de datos y otras variables de entorno.

Seguridad

Estamos pasando datos con información sensible al contenedor. Esto normalmente no se hace así, se deben crear unas variables de entorno en el servidor y no pasar los datos directamente al contenedor. Pero de momento elegimos la opción más simple.

```
# Ejecutar composer install sin dependencias de desarrollo
RUN composer install --optimize-autoloader --no-dev
```

Esta línea ejecuta Composer para instalar las dependencias de nuestra aplicación Laravel. Utiliza la opción `--optimize-autoloader` para optimizar el autoloading y `--no-dev` para no instalar las dependencias de desarrollo. Esto es importante para reducir el tamaño de la imagen y mejorar el rendimiento en producción.

```
# Exponer puerto 1000 (o el que uses)
EXPOSE 1000
```

Esta línea expone el puerto 1000 del contenedor. Este es el puerto en el que Laravel servirá la aplicación. Asegúrate de que este puerto está configurado correctamente en tu aplicación Laravel.

Puerto

1000 es el puerto que pide `Render` para exponer la aplicación, no está elegido al azar. Para modificar el puerto debes mirar la documentación de **Render** y ver si se puede modificar.

```
# Comando para arrancar Laravel con servidor embebido PHP
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=1000"]
```

Esta línea define el comando que se ejecutará cuando se inicie el contenedor. En este caso, estamos utilizando el servidor embebido de Laravel para servir la aplicación en el puerto 1000. Esto es suficiente para desplegar una aplicación Laravel en **Render**.

### Creación del archivo `migrate.php`

Nuestro servidor, como nos pasará en la mayoría de los servidores gratuitos, no permite ejecutar comandos de Artisan directamente. Por tanto, debemos crear un archivo PHP que se encargue de ejecutar las migraciones de la base de datos. Este archivo lo vamos a llamar `migrate.php` y lo vamos a crear dentro de la carpeta `src/public/`. Es importante que esté en esta carpeta para que podamos acceder desde el navegador y ejecutar las migraciones.

De momento este fichero solo nos permite ejecutar las migraciones de la base de datos, hacer un `fresh` o un `reset`. Si fuera necesario ampliarlo, por ejemplo para ejecutar `db:seed` o `queue:work`, deberíamos añadir las opciones necesarias en el fichero.

Creamos el archivo `migrate.php` dentro de la carpeta `src/public/`:

migrate.php

|  |  |
| --- | --- |
| ```  1  2  3  4  5  6  7  8  9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31 32 33 34 35 36 37 38 39 40 41 42 43 44 45 46 47 48 49 50 51 52 53 54 55 56 57 58 59 60 61 62 63 ``` | ``` <?php ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);  use Illuminate\Foundation\Application; use Illuminate\Console\Application as ArtisanConsole;  $basePath = __DIR__ . '/../';  require $basePath . 'vendor/autoload.php';  // Definir la clave para acceder define('SECRET_KEY', '123456');  // Validar key y acción $key = $_GET['key'] ?? ''; $action = $_GET['action'] ?? '';  if ($key !== SECRET_KEY) {     http_response_code(403);     exit('Acceso denegado.'); }  $allowedActions = ['migrate', 'reset', 'fresh'];  if (!in_array($action, $allowedActions)) {     http_response_code(400);     exit('Acción no permitida.'); }  // Bootstrap de la aplicación Laravel $app = require_once $basePath . '/bootstrap/app.php';  $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);  // Capturar la salida ob_start();  try {     switch ($action) {         case 'migrate':             $exitCode = $kernel->call('migrate', ['--force' => true]);             break;         case 'reset':             $exitCode = $kernel->call('migrate:reset', ['--force' => true]);             break;         case 'fresh':             $exitCode = $kernel->call('migrate:fresh', ['--force' => true]);             break;     } } catch (Exception $e) {     ob_end_clean();     http_response_code(500);     exit("Error ejecutando comando Artisan: " . $e->getMessage()); }  $output = ob_get_clean();  // Enviar resultado header('Content-Type: text/plain; charset=utf-8'); echo "Ejecutado comando: $action\n\n"; echo $output; ``` |

Le hemos añadido una clave secreta para evitar que cualquier persona pueda ejecutar las migraciones. Para ejecutar las migraciones, debemos acceder a la URL

```
https://phpdeploytest-sh8f.on**Render**.com/migrate.php?key=123456&action=migrate
```

Si queremos hacer un `fresh` o un `reset`, simplemente cambiamos el valor de `action` a `fresh` o `reset`.

## Creación del servicio en **Render**

Ahora que tenemos nuestra aplicación configurada y lista para desplegar, vamos a crear el servicio en **Render**. Para ello, sigue estos pasos:

Primero en el panel de control de **Render**, haz clic en el botón "New" (Nuevo) y selecciona "Web Service" (Servicio web).

En la siguiente página me debe dejar seleccionar el repositorio de GitHub donde tengo mi proyecto. En este caso, selecciono el repositorio `phpDeployTest` que he creado anteriormente.
Si no ves tu repositorio, asegúrate de que has autorizado a **Render** para acceder a tu cuenta de GitHub y que el repositorio es público o tienes acceso a él.

Una vez seleccionado el repositorio, **Render** te pedirá que configures el servicio. Aquí debes completar los siguientes campos:

| Campo | Descripción | Valor |
| --- | --- | --- |
| Name | Nombre del servicio | `phpDeployTest` |
| Language | Lenguaje de programación (PHP no está) | `Docker` |
| Branch | Rama del repositorio que se utilizará para el despliegue | `Render` |
| Region | Región donde se desplegará el servicio | `Europe (Frankfurt)` |
| Root Directory | Directorio raíz del servicio | `.` |
| Dockerfile Path | Ruta al archivo Dockerfile | `./**Render**/Dockerfile` |
| Intace type | Tipo de instancia | `Free` |

En la parte inferior de la página, encontrarás una sección llamada "Environment Variables" (Variables de entorno). Aquí podemos añadir las variables de entorno, incluso nos permite subirlas desde un archico `.env`. En nuestro caso, vamos a subir el archivo `.env.**Render**` que hemos creado anteriormente. Dejamos esta opción vacía y para pruebas posteriores ya que es más segura que tener nuestro archivo con datos sensibles en el repositorio.

Una vez completados todos los campos, haz clic en el botón "Create Web Service" (Crear servicio web). **Render** comenzará a construir la imagen de Docker y desplegará tu aplicación.

Nos pueden aparecer otros campos como:

| Campo | Descripción | Valor |
| --- | --- | --- |
| Docker Command | Comando para construir la imagen de Docker | `` |
| Pre deploy Command | Comando que se ejecutará antes de desplegar la aplicación | `` |
| Auto deploy | Activar el despliegue automático al hacer push a la rama seleccionada | `on Commit` |
| Deploy hook | URL del webhook para el despliegue automático | `https://api.**Render**.com/deploy/srv-xxxxxx` |

Principalmente no hay que tocar nada de esto. **Render** se encargará de construir la imagen de Docker y desplegar tu aplicación automáticamente. Como en la últia línea de `Dockerfile` hemos indicado que se ejecute el comando `php artisan serve`, **Render** iniciará el servidor embebido de Laravel en el puerto 1000.

## Puesta en marcha del despliegue

Tenemos dos opcioenes para iniciar el despliegue de nuestra aplicación:

1. Despliegue manual: Para ello, en el panel de control de **Render**, haz clic en el botón "Manual Deploy" (Despliegue manual) y selecciona la rama `Render`. **Render** comenzará a construir la imagen de Docker y desplegará tu aplicación.
2. Despliegue automático: Si has activado el despliegue automático, **Render** desplegará tu aplicación automáticamente cada vez que hagas un push a la rama `Render`. Para ello, simplemente haz un commit y push a la rama `Render` y **Render** se encargará del resto.

Despliegue automático

El primer despliegue puede tardar un poco más de lo habitual, ya que **Render** debe construir la imagen de Docker y desplegar tu aplicación por primera vez. Los siguientes despliegues serán más rápidos, ya que **Render** utilizará la caché de Docker.

Durante el despliegue, **Render** mostrará el progreso en tiempo real en el panel de control. Una vez que el despliegue se haya completado, podrás acceder a tu aplicación Laravel en la URL proporcionada por **Render**.

![**Render** Deployment](../img/imagen01.png)

En la imagen anterior podemos ver el progreso del despliegue de nuestra aplicación. Una vez que el despliegue se haya completado, **Render** nos proporcionará una URL para acceder a nuestra aplicación.

Cuando el despliegue termine (podemos seguirlo en los logs), **Render** nos mostrará la URL de nuestra aplicación. En mi caso, la URL es:

```
https://phpdeploytest-sh8f.on**Render**.com
```

![Imagen de la aplicación desplegada](../img/imagen02.png)

Esta es la imagen del proyecto inicial de Laravel que hemos desplegado en **Render**. Si accedemos a la URL, veremos la página de bienvenida de Laravel.

## Primera migración

Una vez que hemos desplegado nuestra aplicación, es importante realizar la primera migración de la base de datos para crear las tablas necesarias. Para ello, debemos acceder al archivo `migrate.php` que hemos creado anteriormente.

Para ejecutar las migraciones, simplemente accedemos a la URL:

```
https://phpdeploytest-sh8f.onRender.com/migrate.php?key=123456&action=migrate
```

password

Recuerda que debes sustituir `123456` por la clave secreta que has definido en el archivo `migrate.php`. Si no lo haces, obtendrás un error de acceso denegado.

Si todo ha ido bien, deberías ver un mensaje indicando que se ha ejecutado el comando `migrate` y por tanto las tablas necesarias se han creado en la base de datos. Si hay algún error, se mostrará un mensaje de error con los detalles del problema.
