# Instalación voyager

Aquí tienes los pasos detallados para instalar **Laravel Voyager** en tu proyecto Laravel:

### 1. Requisitos Previos

Asegúrate de tener un proyecto de *Laravel* configurado y una base de datos conectada. También verifica que el proyecto esté en una versión compatible con *Voyager* (*Laravel 6* o superior).

> Cuando vayas a crear el proyecto Laravel (`laravel new nombre_proyecto`) es preferible crearlo `breeze`.

### 2. Instalar el Paquete Voyager

Ejecuta el siguiente comando en el terminal en el directorio de tu proyecto para instalar Voyager usando *Composer*:

```sh
composer require tcg/voyager
```

### 3. Instalar Voyager

Después de instalar el paquete, ejecuta el comando de instalación de Voyager. Puedes optar por incluir datos de ejemplo si lo deseas, usando la opción `--with-dummy`:

```sh
php artisan voyager:install
```

> **Si quieres incluir datos de ejemplo** para probar el funcionamiento de Voyager, usa:
>
> ```bash
> php artisan voyager:install --with-dummy
> ```

### 4. Crear un Usuario Administrador

Voyager requiere un usuario administrador para acceder al panel de control. Si aún no tienes un usuario creado, puedes generar uno ejecutando este comando:

```sh
php artisan voyager:admin tu_correo@ejemplo.com --create
```

Durante este proceso, se te pedirá que ingreses un nombre y una contraseña para el usuario administrador.

> **Si produce ERROR**:
>
> 1. Abre *Tinker* ejecutando:
>
>    ```
>    php artisan tinker
>    ```
>
> 2. Crea un nuevo usuario y asígnale el rol de administrador (suponiendo que el `id` del rol de `admin` es `1`):
>
>    ```
>    $user = new \App\Models\User;
>    $user->name = 'dwes';
>    $user->email = 'arturoblasco@iesmre.com';
>    $user->password = bcrypt('tu_contraseña');  // Reemplaza 'tu_contraseña' con la contraseña deseada
>    $user->role_id = 1; // ID del rol de admin en la tabla roles
>    $user->save();
>    ```
>
> 3. Sal de Tinker:
>
>    ```
>    exit
>    ```
>
> 
>Este método te permite configurar manualmente el usuario con el rol adecuado si el comando `voyager:admin` no asigna el `role_id` de forma automática.

### 5. Migrar la Base de Datos

Voyager crea tablas adicionales en la base de datos para manejar usuarios, roles, y permisos. Ejecuta el siguiente comando para migrar estas tablas a tu base de datos:

```sh
php artisan migrate
```

### 6. Acceder al Panel de Control de Voyager

Una vez completados los pasos anteriores, puedes acceder al panel de administración de Voyager visitando la URL `/admin` en tu navegador:

```sh
http://tudominio.com/admin
```

> Nota: Antes, crear el Modelo:
>
> ```bas
> php artisan make:model Producto
> ```

Ingresa las credenciales del usuario administrador que creaste para iniciar sesión.



### Resumen de Comandos para Instalar Voyager

```sh
composer require tcg/voyager
php artisan voyager:install
php artisan voyager:admin your-email@example.com --create
php artisan migrate
```

Voyager ahora estará listo y configurado en tu proyecto Laravel. ¡Puedes comenzar a crear y gestionar CRUD y usuarios desde el panel de administración!



## Ejemplo crear CRUD

Aquí tienes un ejemplo paso a paso para crear un CRUD en Voyager para una tabla llamada `productos`. Supongamos que queremos gestionar una lista de productos en nuestro panel de administración.

### Paso 1: Crear la Tabla `productos`

Primero, crea una migración para la tabla `productos` en tu proyecto Laravel. En el terminal, ejecuta:

```
php artisan make:migration create_productos_table --create=productos
```

Edita la migración para que tenga los campos necesarios. Por ejemplo, podríamos añadir los campos `nombre`, `descripcion`, `precio`, y `cantidad`:

```
public function up()
{
    Schema::create('productos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->text('descripcion')->nullable();
        $table->decimal('precio', 8, 2);
        $table->integer('cantidad');
        $table->timestamps();
    });
}
```

Luego, ejecuta las migraciones para crear la tabla en la base de datos:

```
php artisan migrate
```

### Paso 2: Agregar el CRUD para la Tabla `productos` en Voyager

1. Accede al panel de administración de Voyager en `http://tudominio.com/admin` e inicia sesión.
2. En el menú lateral, selecciona **Tools (Herramientas)** y luego haz clic en **Database (Base de datos)**.
3. Localiza la tabla `productos` en la lista de tablas y haz clic en el botón **Add BREAD to this table** junto a ella.

### Paso 3: Configurar el CRUD en Voyager

Al hacer clic en **Add BREAD to this table**, verás la pantalla de configuración. Configura los siguientes campos:

- **Display Name Singular**: Producto
- **Display Name Plural**: Productos
- **Slug**: productos
- **Model Name**: App\Models\Producto (si tienes un modelo para la tabla, agrégalo aquí; de lo contrario, Voyager generará uno automáticamente)
- **Generate Permissions**: Marca esta casilla para crear permisos automáticamente para este CRUD.

### Paso 4: Configurar los Campos del CRUD

En la parte inferior, verás una lista de los campos en la tabla `productos`. Aquí puedes configurar cómo se mostrará cada campo en el panel de administración:

1. **ID**: Mantenlo como está, generalmente solo visible.
2. Nombre:
   - Display Name: Nombre
   - Input Type: Texto
   - Marcado para "Browse", "Read", "Edit", y "Add".
3. Descripcion:
   - Display Name: Descripción
   - Input Type: Área de texto (textarea)
   - Marcado para "Read", "Edit", y "Add".
4. Precio:
   - Display Name: Precio
   - Input Type: Número (number)
   - Marcado para "Browse", "Read", "Edit", y "Add".
5. Cantidad:
   - Display Name: Cantidad
   - Input Type: Número (number)
   - Marcado para "Browse", "Read", "Edit", y "Add".
6. Timestamps  (`created_at`  y  `updated_at`):
   - Puedes dejarlos sin marcar o solo en "Read" para ver la fecha de creación y última actualización del registro.

Una vez que hayas configurado los campos, haz clic en **Submit** para guardar el CRUD.

### Paso 5: Probar el CRUD

En el menú lateral del panel de Voyager, verás una nueva sección llamada **Productos**. Al hacer clic allí, podrás:

- **Listar** los productos (ver registros en la tabla).
- **Agregar** nuevos productos.
- **Editar** productos existentes.
- **Eliminar** productos.

Este CRUD te permite gestionar la tabla `productos` desde el panel de administración de Voyager, sin necesidad de escribir controladores, vistas o rutas manualmente.

<hr>



# Instalar Filament Admin

Instalar **Filament Admin** en un proyecto Laravel es bastante sencillo. Aquí tienes los pasos básicos para instalarlo y configurar un CRUD básico.

### 1. Requisitos Previos

- Tener **Laravel 8 o superior** ya instalado y configurado.
- Contar con **PHP 8 o superior**.

### 2. Instalación de Filament Admin

1. **Instalar el paquete** usando *Composer*:

   ```bash
   composer require filament/filament
   ```

   > Si la anterior orden produce error, puede deberse que `filament/support` requiere la extensión `ext-intl` de PHP, la cual no está habilitada en tu sistema. La extensión `intl` es necesaria para manejar internamente formatos de idioma, moneda y otras configuraciones regionales, algo que *Filament* necesita para funcionar correctamente. Vamos a solucionarlo en unos pasos.
   >
   > **Solución**: **Habilitar la Extensión `intl` en PHP**
   >
   > - Abre el archivo de configuración de PHP (`php.ini`). En tu caso, parece que está en `C:\xampp\php\php.ini`.
   >
   > - Busca la línea que contiene: 
   >
   >   ```ini
   >   ;extension=intl
   >   ```
   >
   >    y quita el punto y coma (`;`) al inicio de la línea para habilitar la extensión:
   >
   >   ```ini
   >   extension=intl
   >   ```
   >
   > - Guarda el archivo y reinicia el servidor de Apache en XAMPP para que los cambios surtan efecto.

2. **Publicar los archivos de configuración y migraciones de *Filament***: Esto es opcional, pero permite personalizar aspectos como la URL de acceso al panel de administración.

   ```bash
   php artisan vendor:publish --tag=filament-config
   ```

3. **Configurar la ruta de *Filament* (opcional)**: En el archivo `config/filament.php`, puedes personalizar la URL del panel de administración. Por defecto, estará en `/admin`.

4. **Crear un usuario administrador**: Si aún no tienes un sistema de autenticación en tu proyecto, puedes generarlo con:

   ```bash
   php artisan make:auth
   ```

   Para crear un usuario que pueda acceder a *Filament*, ejecuta:

   ```bash
   php artisan filament:install --panels
   php artisan make:filament-user
   ```

   Esto te pedirá ingresar un email, contraseña y nombre para el usuario *admin*.

### 3. Ejemplo de CRUD con Filament

Supongamos que quieres crear un CRUD para gestionar productos en tu aplicación.

1. **Crear un modelo y migración de productos**:

   ```bash
   php artisan make:model Product -m
   ```

2. **Definir la estructura de la tabla de productos** en el archivo de migración `database/migrations/xxxx_xx_xx_create_products_table.php`:

   ```bash
   public function up()
   {
       Schema::create('products', function (Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->text('description')->nullable();
           $table->decimal('price', 8, 2);
           $table->integer('stock');
           $table->timestamps();
       });
   }
   ```

3. **Ejecutar la migración**:

   ```bash
   php artisan migrate
   ```

4. **Crear un Resource para el CRUD en Filament**: Filament facilita la creación de CRUDs mediante Resources. Ejecuta el siguiente comando para generar un resource de productos:

   ```bash
   php artisan make:filament-resource Product
   ```

   Esto creará una clase en `App\Filament\Resources\ProductResource` que contiene:

   - Formularios de creación y edición.
   - Tabla de productos.
   - Opciones para ver, editar, y eliminar productos.

5. **Configurar el Resource**: Abre el archivo `App\Filament\Resources\ProductResource.php` y verás métodos para definir los campos de formulario y las columnas de la tabla. Aquí hay un ejemplo básico:

   ```bash
   use Filament\Forms;
   use Filament\Tables;
   use Filament\Resources\Resource;
   use Filament\Resources\Form;
   use Filament\Resources\Table;
   use App\Models\Product;
   
   class ProductResource extends Resource
   {
       protected static ?string $model = Product::class;
   
       public static function form(Form $form): Form
       {
           return $form
               ->schema([
                   Forms\Components\TextInput::make('name')->required(),
                   Forms\Components\Textarea::make('description'),
                   Forms\Components\TextInput::make('price')->numeric()->required(),
                   Forms\Components\TextInput::make('stock')->numeric()->required(),
               ]);
       }
   
       public static function table(Table $table): Table
       {
           return $table
               ->columns([
                   Tables\Columns\TextColumn::make('name'),
                   Tables\Columns\TextColumn::make('price')->money('USD'),
                   Tables\Columns\TextColumn::make('stock'),
                   Tables\Columns\TextColumn::make('created_at')->dateTime(),
               ])
               ->filters([
                   //
               ]);
       }
   }
   ```

6. **Acceder al CRUD en Filament**: Ahora puedes acceder al CRUD de productos entrando a tu panel de administración en `/admin` (o la ruta que hayas configurado) e iniciando sesión con el usuario *admin* que creaste. Una vez dentro, deberías ver la sección de **Products** en el menú.

### 4. Personalización Adicional

*Filament* te permite agregar más personalización a los formularios y las tablas, como agregar filtros, opciones de búsqueda, permisos específicos, y otros elementos visuales.

