# Aplicación simple con estructura precaria php

En esta parte del tema vamos a crear una aplicación simple con una estructura de carpetas precaria, que nos servirá para aprender a desplegar aplicaciones web en un servidor remoto. La aplicación será una simple página que muestra información del servidor y de la base de datos.

Para esta aplicación no es necesario utilizar los contenedorres, solo vamos a tener 3 archivos PHP, están verificados y veremos que funcionan correctamente en remoto (no hace falta probar en local). La idea de esta práctica es aprender a desplegar aplicaciones web en un servidor remoto, y enfrentarnos a los problemas propios del despiegue no a los problemas de la aplicación en sí.

Vamos a utilizar esta aplicación como ejemplo para aprender a desplegar aplicaciones web en un servidor remoto donde tendremos que modificar la estructura de carpetas para que se adapte a las limitaciones del servidor.

Lo aprendido en este tema nos servirá para comprender el despliegue de `laravel` y otras aplicaciones web más complejas, ya que la mayoría de los servidores (licencias gratuitas) tienen limitaciones similares y es importante conocer cómo adaptarse a ellas.

InfinityFree

Vamos a utilizar un servidor gratuito como `InfinityFree` para realizar el despliegue, pero los conceptos son aplicables a cualquier servidor web. En este caso, vamos a utilizar `GitHub Actions` para automatizar el proceso de despliegue y aprender a configurar un flujo de trabajo básico. Por tanto es indispensable que tengamos una cuenta en GitHub, que tengamos conocimientos básicos de Git y GitHub, y que tengamos el repositorio creado para seguir esta práctica.

## Estructura de carpetas en local

```
phpDeployTest/
├── src/
│   ├── config/
│       ├── db.php
│       ├── server.php
│   ├── public/
│       ├── index.php
```

El fichero `index.php` es el punto de entrada de la aplicación y se encargará de cargar la configuración y mostrar información del servidor. Los archivos `db.php` y `server.php` son archivos de configuración que se cargarán en el `index.php`. Nuestro script `index.php` detectará si se está ejecutando en local o en producción y cargará la configuración adecuada.

Contenido del archivo `index.php`:

|  |  |
| --- | --- |
| ```  1  2  3  4  5  6  7  8  9 10 11 12 13 14 15 ``` | ``` <?php if (file_exists(__DIR__ . '/../config/db.php')) {     echo "ejecución en local <br>";     $configPath = __DIR__ . '/../config/'; } elseif (file_exists(__DIR__ . '/src/config/db.php')) {     echo "ejecución en producción <br>";     $configPath = __DIR__ . '/src/config/'; } else {     die("Error: No se encontró el directorio config."); }  require_once $configPath . 'db.php'; require_once $configPath . 'server.php';  phpinfo(); ``` |

Contenido del archivo `db.php`:

```
<?php
echo("cargado db.php... <br>");
```

Contenido del archivo `server.php`:

```
<?php
echo("cargado server.php... <br>");
```

## Primer despliegue, primer contacto con GitHub Actions

Ahora tenemos que desplegar la aplicación anterior en un servidor compartido remoto, donde se nos obliga a que todas las aplicaciones se suban a una carpeta llamada `htdocs`. Como habíamos avisado, vamos a utilizar un servidor gratuito de `InfinityFree`.

Lo más sencillo sería utilizar FTP y subir los archivos a la carpeta `htdocs`. Pero para aprender a automatizar el proceso de despliegue, vamos a utilizar GitHub Actions para realizar el despliegue de manera automática.

```
phpDeployTest/
├── htdocs/
│   ├── src/
│       ├── config/
│           ├── db.php
│           ├── server.php
│       ├── index.php
```

Como podemos ver el contenido de `/src` va a una carpeta `/src` dentro de `/htdocs` ya que el servidor no nos permite subir ficheros fuera de `/htdocs`.

La carpeta `public` que tiene los archivos que se exponen al público, en este caso `index.php`, se ha movido a `htdocs` para que sea accesible desde la web.

Tenemos que crear la carpeta `.github/workflows` en la raíz del proyecto para que GitHub Actions pueda detectar el flujo de trabajo. Dentro de esta carpeta creamos el archivo `deploy.yml` que contendrá la configuración del flujo de trabajo. Cada vez que hagamos un push a la rama `main`, se ejecutará el flujo de trabajo y se desplegará la aplicación en el servidor remoto. (si queremos que sea otra rama, tenemos que modificar el archivo `deploy.yml`)

Todo esto lo tiene que hacer el `deploy.yml` de manera automática. El contenido del archivo `deploy.yml` es el siguiente:

.github/workflows/deploy.yaml

|  |  |
| --- | --- |
| ```  1  2  3  4  5  6  7  8  9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31 32 33 34 35 36 37 38 39 ``` | ``` name: Deploy to InfinityFree  on:   push:     branches:       - main  jobs:   ftp-deploy:     runs-on: ubuntu-latest     steps:       - name: Checkout code         uses: actions/checkout@v3        - name: Upload public files to htdocs/         uses: SamKirkland/FTP-Deploy-Action@4.1.0         with:           server: ${{ secrets.FTP_SERVER }}           username: ${{ secrets.FTP_USERNAME }}           password: ${{ secrets.FTP_PASSWORD }}           local-dir: ./src/public/           server-dir: /htdocs/        - name: Upload src files (excluding public) to htdocs/src/         run: |           # Crear carpeta temporal para copiar sin public           mkdir temp_src           shopt -s extglob           cp -r src/!(public) temp_src/         shell: bash        - name: Upload src (except public) to htdocs/src         uses: SamKirkland/FTP-Deploy-Action@4.1.0         with:           server: ${{ secrets.FTP_SERVER }}           username: ${{ secrets.FTP_USERNAME }}           password: ${{ secrets.FTP_PASSWORD }}           local-dir: ./temp_src/           server-dir: /htdocs/src/ ``` |

Hemos tenido que crear los secretos `FTP_SERVER`, `FTP_USERNAME` y `FTP_PASSWORD` en GitHub para que la acción de despliegue pueda acceder al servidor FTP. (los datos son ejemplos de ìnfinityfree.net`)

* secrets.FTP\_SERVER: Dirección del servidor FTP. ( `ftpupload.net`)
* secrets.FTP\_USERNAME: Nombre de usuario para acceder al servidor FTP. (`if0_39075476`)
* secrets.FTP\_PASSWORD: Contraseña para acceder al servidor FTP. (`*********`)

El apartado `secrets` en GitHub Actions nos permite almacenar información sensible de forma segura, como contraseñas o claves de acceso, sin exponerlas directamente en el código. Lo podemos encontrar en la sección de `Settings` del repositorio, en la pestaña `Secrets and variables`.

La información la podéis en contrar en el panel de control de `InfinityFree`, en la sección `FTP Details`.

Nota

![FTP Details en InfinityFree](../img/imagen03.png)

La contraseña la tenéis en la página principal, pero lo mejor es copiarla cuando se crea la cuenta.

## Despliegue en producción

Si todo ha ido bien, al hacer un push a la rama `main`, se ejecutará el flujo de trabajo de GitHub Actions y se desplegará la aplicación en el servidor remoto. Podemos comprobar el estado del flujo de trabajo en la pestaña `Actions` del repositorio en GitHub. Existe un apartado de `logs` donde podemos ver el registro de la ejecución del flujo de trabajo y detectar posibles errores.

Una vez ha terminado tenemos que verificar que la aplicación funciona correctamente accediendo a la URL del servidor remoto.

URL del servidor remoto

En este caso, la URL del servidor remoto será la que nos proporciona `InfinityFree`, que suele ser algo como `https://laravel-simple-test.wuaze.com/` o similar. Esta URL es la que debemos utilizar para acceder a la aplicación desplegada.

En mi ejemplo: <https://laravel-simple-test.wuaze.com/>, pero puede no estar disponible en este momento.

Error `git push`

Es posible que al hacer el `git push` nos encontremos con un error de autenticación. Esto se debe a que GitHub ha cambiado la forma de autenticarse y ya no permite el uso de contraseñas para acceder a los repositorios.
Otro error puede ser que el repositorio detecte que estamos subiendo un archivo en `.github/workflows` y debemos estar autenticados con un token de acceso personal (PAT), con permisos para ejecutar `workflows`.

EL primer problema se puede solucionar buscando como conectar vsCode con GitHub utilizando un token de acceso personal (PAT). El segundo problema se soluciona dando a este token permisos para poder ejecutar `workflows`.

Para ver la configuración de los permisos de un token de acceso personal (PAT) podemos ir a `Settings` -> `Developer settings` -> `Personal access tokens` -> `Tokens (classic)` y editar el token que estamos utilizando para darle permisos de `workflow`.

Settings

Tenemos que acceder al `settings` que aparece al pulsar sobre el icono de nuestro perfil en la esquina superior derecha de GitHub. No en el `settings` del repositorio.

Permiso activado

![Settings en GitHub](../img/imagen04.png)

El comando de git para poner o modificar el `PAT`es:

```
git remote set-url origin https://<TOKEN>@github.com/usuario/repositorio.git
# ejemplo
git remote set-url origin https://jbeteta-ies:ghp_1234567890ABCDEF1234567890ABCDEF1234@github.com/jbeteta-ies/phpSimpleDeployTest.git
```

Podemos comprobar que el token se ha puesto correctamente con:

```
git remote -v
```

Debe comtestar algo como:

```
origin  https://jbeteta-ies:ghp_1234567890ABCDEF1234567890ABCDEF1234@github.com/jbeteta-ies/phpSimpleDeployTest.git (fetch)
origin  https://jbeteta-ies:ghp_1234567890ABCDEF1234567890ABCDEF1234@github.com/jbeteta-ies/phpSimpleDeployTest.git (push)
```

Para evitar tener que escribir el token más veces, podemos escribir:

```
#En Windows:
git config --global credential.helper manager-core

#En macOS:
git config --global credential.helper osxkeychain

#En Linux:
git config --global credential.helper store
```

Con todo esto, ya deberíamos poder hacer `git push` sin problemas y se ejecutará el flujo de trabajo de `GitHub Actions` para desplegar la aplicación en el servidor remoto. Luego nos queda acudir a `actions` y verificar el flujo de trabajo. Si encontramos errores podemos ver los `logs` para detectar el problema.

### Verificación

Después de hacer el push, podemos verificar que la aplicación se ha desplegado correctamente.
Para ello tenemos dos vías:

1. Consultar en `GitHub Actions` el estado del flujo de trabajo. Si todo ha ido bien, debería aparecer como `Completed` y `Success`.

   GitHub Actions

   ![GitHub Actions](../img/imagen05.png)

   En la imgen podemos ver el flujo de trabajo, si todo ha ido bien debe estar en verde.
   Si queremos más información podemos pulsar sobre el flujo de trabajo y ver los detalles de cada paso.

   Logs

   ![Logs en GitHub Actions](../img/imagen06.png)

   Incluso podemos deplegar cada paso y ver los logs de la ejecución, lo que nos puede ayudar a detectar posibles errores.
2. Acceder a la URL del servidor remoto y verificar que la aplicación funciona correctamente. Si todo ha ido bien, deberíamos ver la página de información del servidor y de la base de datos.

   Página desplegada

   ![Página desplegada](../img/imagen07.png)

   Vemos que se ha cargado correctamente el archivo `db.php` y `server.php`, y se muestra la información del servidor. (no se ve `phpinfo()` como estamos acostumbrados porque `InfinityFree` lo desactiva por seguridad, y solo muestra la información básica del servidor)

## Seguridad y protección de la aplicación

Ahora mismo tenemos un grave problema de seguridad, ya que al poner todos los archivos dentro de htdocs, todos quedan expuestos. La solución en otros servidores es solo exponer la carpeta `public` y el resto de archivos quedan fuera del alcance del público, pero este servidor (como muchos otros gratuitos) no permite subir archivos fuera de `htdocs`, por lo que tendremos que buscar una solución alternativa. Una posible solución es crear un archivo `.htaccess` en la carpeta `htdocs` para restringir el acceso a ciertos archivos o carpetas, o poner un archivo `.htaccess` en todas las carpetas que queramos proteger.

## Resumen

En este tutorial hemos creado una aplicación simple con una estructura de carpetas precaria, hemos realizado un primer despliegue en un servidor remoto y hemos configurado GitHub Actions para automatizar el proceso de despliegue. Aunque hemos encontrado un problema de seguridad al exponer todos los archivos en `htdocs`, hemos aprendido a configurar un flujo de trabajo básico para desplegar aplicaciones PHP en servidores remotos utilizando `GitHub Actions`.

En el próximo punto del tema aprovecharemos lo aprendido para desplegar una aplicación `laravel` completa en un servidor remoto, adaptando la estructura de carpetas y utilizando `GitHub Actions` para automatizar el proceso de despliegue.
