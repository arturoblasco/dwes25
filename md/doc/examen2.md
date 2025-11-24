---
title: Examen 2 Laravel
---
# Examen 2 Laravel

> **Laravel (backend, MVC, configuración y acceso a datos)**

> Desarrollo Web en Entorno Servidor (DWES)

> Nivel: 2º de Ciclo Formativo de Grado Superior (DAW)  
> Proyecto: Gestor de incidencias en un centro educativo

---

## Parte A. Cuestionario – Preguntas cortas

### 1. Entorno de programación en servidor (0,5 pt)

a. Enumera al menos **4 componentes de software** necesarios para desarrollar y ejecutar una aplicación web con Laravel en local (por ejemplo: intérprete, servidor, SGBD, herramientas…).

???questionlaravel "Respuesta 1.a"
    - Intérprete **PHP** (por ejemplo, PHP 8.x).
    - Servidor web **Apache** o **Nginx**.
    - **SGBD**: MySQL / MariaDB.
    - **Composer** (gestor de dependencias de PHP).  
    *(Opcionalmente: Node.js + npm para compilar assets frontend.)*

b. Explica brevemente qué papel tiene **PHP** dentro de ese conjunto.
???questionlaravel "Respuesta 1.b"
    El servidor web llama a PHP para ejecutar el código de la aplicación (controladores, modelos, vistas Blade compiladas, etc.) y generar la respuesta HTML/JSON que se envía al navegador.

---

### 2. Composer y ecosistema PHP (0,5 pt)

a. Explica con tus palabras qué es **Composer** y qué problema resuelve en proyectos PHP modernos:

???questionlaravel "Respuesta 2.a"
    Permite declarar en `composer.json` las librerías que necesita un proyecto (Laravel, paquetes de terceros, etc.) y se encarga de **descargarlas, actualizarlas y autoload** (carga automática de clases).

b. ¿Cómo crearías un proyecto laravel?
???questionlaravel "Respuesta 2.b"
    ```bash
    composer create-project laravel/laravel incidents
    ```

    (o bien, si tienes el instalador global):

    ```bash
    laravel new incidents
    ```

---

### 3. Estructura básica de un proyecto Laravel (0,5 pt)
Indica la finalidad principal de cada uno de los siguientes elementos:

- **`app/`**  
???questionlaravel "Respuesta 3.a"
    Contiene el código principal de la aplicación: modelos, controladores, middleware, etc. Es la lógica de negocio.

- **`resources/views/`**  
???questionlaravel "Respuesta 3.b"
    Carpeta donde se guardan las vistas Blade (`.blade.php`), es decir, las plantillas que generan el HTML.

- **`routes/web.php`**  
???questionlaravel "Respuesta 3.c"
    Fichero donde se definen las rutas web (GET/POST…) que devuelven vistas HTML y llaman a controladores.

- **`database/migrations/`**  
???questionlaravel "Respuesta 3.d"
    Contiene las migraciones que describen la estructura de las tablas de la base de datos (creación, modificación, etc.).

- **`.env`**  
???questionlaravel "Respuesta 3.e"
    Fichero de **configuración de entorno** (no se sube al repositorio): credenciales de BD, APP_URL, APP_ENV, claves, etc.

---

### 4. Vistas, plantillas Blade y separación de responsabilidades (0,5 pt)

a. Explica la diferencia entre **una vista Blade** individual y **una plantilla (layout)** en Laravel.

???questionlaravel "Respuesta 4.a"
    
    - Una **vista Blade individual** es un archivo que muestra una página concreta (por ejemplo, `incidents/index.blade.php`).
    
    - Una **plantilla (layout)** (`layouts/app.blade.php`) define la estructura común (HTML base, cabecera, menú, pie) que luego las vistas hijas extienden.

b. ¿Qué ventajas ofrece Blade para **separar lógica y presentación**? Nombra al menos dos (por ejemplo, herencia de vistas, secciones, directivas, etc.).

???questionlaravel "Respuesta 4.b"
    - Permite **herencia de vistas** (`@extends`) y **secciones** (`@section`, `@yield`) para reutilizar estructura.
    - Tiene **directivas** (`@if`, `@foreach`, `@auth`, etc.) que simplifican la lógica mínima en las vistas.
    - Escapa automáticamente los datos (`{{ }}`) ayudando a la seguridad (prevención de XSS).

c. Indica cómo se pasaría desde un controlador una colección de objetos `$products` a una vista `products/index.blade.php` (menciona el retorno del controlador, no hace falta escribir la vista).

???questionlaravel "Respuesta 4.c"
    ```php
    public function index()
    {
        $products = Product::all();

        return view('products.index', [
            'products' => $products,
        ]);
        // o: return view('products.index', compact('products'));
    }
    ```

---

### 5. Rutas y controladores (0,5 pt)

a. Explica brevemente la diferencia entre definir una ruta con una **función anónima** y definirla apuntando a un **método de controlador**.
???questionlaravel "Respuesta 5.a"
    - Ruta con **función anónima**:
    
    ```php
    Route::get('/hola', function () {
        return 'Hola';
    });
    ```
    
    Se usa para cosas simples, la lógica queda dentro del fichero de rutas.
    
    - Ruta apuntando a un **método de controlador**:
    
    ```php
    Route::get('/courses', [CourseController::class, 'index']);
    ```
    
    La lógica va en la clase controlador, más organizado, reutilizable y testeable.

b. Escribe la línea de ruta que registrarías en `routes/web.php` para gestionar un recurso `Course` mediante un controlador `CourseController` para mostrar un solo curso usando el sistema de recursos de Laravel.
???questionlaravel "Respuesta 5.b"
    ```php
    Route::resource('courses', CourseController::class);
    ```

    Esta línea registra, entre otros, la ruta para mostrar un curso concreto (`courses.show` → GET `/courses/{course}`).


c. Nombra **cuatro métodos** típicos que genera un controlador de recurso y explica en una frase la finalidad de cada uno.
???questionlaravel "Respuesta 5.c"
    - `index()` → Muestra el listado de recursos.
    - `create()` → Muestra el formulario para crear un nuevo recurso.
    - `store()` → Recibe el formulario de creación y guarda en BD.
    - `show()` → Muestra un recurso concreto.
    - `edit()` → Muestra el formulario para editar un recurso existente.
    - `update()` → Actualiza en BD un recurso existente.
    - `destroy()` → Elimina un recurso.
    
    
    (Cualquiera 4 de estos con su explicación.)

---

### 6. Migraciones, modelos y seeders (0,5 pt)

a. ¿Qué es una **migración** en Laravel y qué ventaja aporta en un proyecto de equipo?  
???questionlaravel "Respuesta 6.a"
    Una **migración** es un archivo PHP que describe cambios en la estructura de la BD (crear tablas, añadir columnas…).  
    Permite tener la BD **versionada y sincronizada** entre todos los miembros del equipo (`php artisan migrate` crea la misma estructura en todos los entornos).

b. ¿Crea una migración de creación de la tabla `courses`?
???questionlaravel "Respuesta 6.b"
    Para ejecutar la migración que crea la tabla `courses`:

    ```bash
    php artisan make:migration create_courses_table
    ```

    (tras haber generado la migración y configurado la BD en `.env`).

c. ¿Cómo se ejecuta la migración anterior?
???questionlaravel "Respuesta 6.c"
    ```bash
    php artisan migrate
    ```

d. Explica qué representa un **modelo Eloquent** y cómo se relaciona con las tablas de la base de datos.
???questionlaravel "Respuesta 6.d"
    Un **modelo Eloquent** representa una **tabla de la BD** (por convención `Course` ↔ `courses`).  
    Cada instancia del modelo representa una fila, y Eloquent ofrece métodos para **consultar, insertar, actualizar y borrar** registros, además de manejar relaciones (`hasMany`, `belongsTo`, etc.).

e. ¿Para qué sirven los **seeders**? Indica un ejemplo de cuándo es útil usarlos (en qué fase del desarrollo / pruebas).
???questionlaravel "Respuesta 6.e"
    Los **seeders** sirven para **rellenar la BD con datos iniciales o de prueba** (usuarios de ejemplo, categorías, etc.).  
    Son muy útiles al comenzar un proyecto o para entornos de desarrollo/test, para poder trabajar con datos sin tener que introducirlos a mano cada vez.

---

## PARTE B. Desarrollo – Práctico

**Contexto**
Vas a diseñar una aplicación Laravel para gestionar **incidencias de soporte técnico** en un centro educativo.

- Los **usuarios** (tabla `users` ya existente) pueden crear **incidencias**.
- Cada incidencia pertenece a una categoría (por ejemplo, *“Hardware”*, *“Software”*, *“Red”*).
- Las incidencias tienen un **estado** (*abierta*, *en_proceso*, *resuelta*) y una **prioridad**.

Tablas que vas a trabajar:

- `users` (ya creada por Laravel)
- `categories`  
- `incidents`

Relaciones:

- Un **usuario** puede crear muchas incidencias.  
- Una **incidencia** pertenece a un usuario.  
- Una **categoría** tiene muchas incidencias.  
- Una **incidencia** pertenece a una categoría.



### B.1. Creación del proyecto (1 pt)

Crea un proyecto laravel de nombre `incidents` desde Composer.

???questionlaravel "Respuesta B1"
    ```bash
    composer create-project laravel/laravel incidents
    cd incidents
    php artisan serve
    ```
    (O bien: `laravel new incidents` si tienes el instalador de Laravel.)

---

### B.2. Definición de migraciones (2 puntos)

#### B.2.1. Migración de `categories` (0,75 pt)

a. Crea la migración para `categories`.
???questionlaravel "Respuesta B2.1.a"
    ```bash
    php artisan make:migration create_categories_table
    ```

b. Escribe el código del método `up()` de la migración que crea la tabla `categories` con los campos:
    - `id` 	  (autoincremental)  
    - `name`  (string, único)  
    - `description`   (texto opcional)  
    - `created_at`  y  `updated_at`  (timestamps)
  
dentro de `Schema::create()`:
    
<div style="text-align: center;"><img src="../../img/upl/examen2_imag1.png" alt="ut03" style="max-width: 40%;" /></div>


???questionlaravel "Respuesta B2.1.b"
    ```php
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();                               // id autoincremental
            $table->string('name')->unique();           // nombre único
            $table->text('description')->nullable();    // descripción opcional
            $table->timestamps();                       // created_at y updated_at
        });
    }
    ```

---

#### B.2.2. Migración de `incidents` (1,25 pt)
Escribe el código del método `up()` de la migración que crea la tabla `incidents` con los campos:

- `id`  
- `title`  (string)  
- `description` (texto largo/opcional)  
- `status` (enum, por ejemplo: abierta, en_proceso, resuelta)  
- `priority` (enum o integer, por ejemplo: baja, media, alta – por defecto baja)
- `user_id` (clave foránea a users)  
- `category_id` (clave foránea a categories)  
- `created_at` y `updated_at`

Incluye las **claves foráneas** adecuadas (foreignId o similar) y sus referencias.

???questionlaravel "Respuesta B2.2"
    ```php
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            $table->string('title');                    // título
            $table->text('description')->nullable();    // descripción larga opcional

            $table->enum('status', ['abierta', 'en_proceso', 'resuelta'])
                ->default('abierta');                   // estado

            $table->enum('priority', ['baja', 'media', 'alta'])
                ->default('baja');                      // prioridad por defecto baja

            // Claves foráneas
            $table->foreignId('user_id')
                ->constrained()                         // por defecto a tabla users, id
                ->onDelete('cascade');

            $table->foreignId('category_id')
                ->constrained()                         // tabla categories, id
                ->onDelete('cascade');

            $table->timestamps();                       // created_at, updated_at
        });
    }
    ```

---

### B.3. Modelos y relaciones Eloquent (1,5 puntos)

#### B.3.1. Modelo `Category` (0,5 pt)

- Crea el modelo `Category` y escribe la clase mínima indicando:
- `Namespace` correcto.  
- Uso de `HasFactory` (si lo consideras).  
- El array de campo rellenables masivamente.
- Método de relación para obtener todas las incidencias (`incidents()`).

???questionlaravel "Respuesta B.3.1"
    ```php
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Category extends Model
    {
        use HasFactory;

        // Campos asignables masivamente
        protected $fillable = [
            'name',
            'description',
        ];

        // Una categoría tiene muchas incidencias
        public function incidents()
        {
            return $this->hasMany(Incident::class);
        }
    }
    ```

---

#### B.3.2. Modelo `Incident` (1 pt)

> Usa las funciones de relación adecuadas (hasOne, hasMany, belongsTo, belongsToMany, etc.) y nómbralas de forma coherente.

- Crea el modelo `Incident`  y escribe la clase mínima indicando:
- `Namespace` correcto (`App\Models`).  
- El array de campo rellenables masivamente.
- Relación para acceder a su **usuario creador**.  
- Relación para acceder a su **categoría**.  

???questionlaravel "Respuesta B.3.2"
    ```php
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Incident extends Model
    {
        use HasFactory;

        protected $fillable = [
            'title',
            'description',
            'status',
            'priority',
            'user_id',
            'category_id',
        ];

        // Incidencia pertenece a un usuario
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        // Incidencia pertenece a una categoría
        public function category()
        {
            return $this->belongsTo(Category::class);
        }
    }
    ```

---

### B.4. Seeders para datos iniciales (1 punto)
Queremos rellenar la base de datos con datos de ejemplo usando **arrays** y **bucles** en PHP.

#### B.4.1. `CategorySeeder` (0,5 pt)

En el método `run()` del seeder `CategorySeeder`:

- Crea el seeder.
- Declara un **array PHP** con los nombres de las categorías: "*Hardware*", "*Software*", "*Red*".
- Recorre el array con un `foreach` e inserta cada categoría en la tabla `categories` (puedes usar el modelo `Category::create()`).

???questionlaravel "Respuesta B.4.1"
    ```bash
    php artisan make:seeder CategorySeeder
    ```

    ```php
    <?php

    namespace Database\Seeders;

    use App\Models\Category;
    use Illuminate\Database\Seeder;

    class CategorySeeder extends Seeder
    {
        public function run(): void
        {
            $names = ['Hardware', 'Software', 'Red'];

            foreach ($names as $name) {
                Category::create([
                    'name'        => $name,
                    'description' => 'Incidencias de ' . strtolower($name),
                ]);
            }
        }
    }
    ```

---

#### B.4.2. `IncidentSeeder` (0,5 pt)

En el método `run()` del seeder `IncidentSeeder`:
1.	Crea el seeder.
2.	Declara un array de incidencias (cada elemento puede ser un array asociativo con *title*, *status*, *priority*, *user_id*, *category_id*, *etc*.).  
3.	Recorre el array con un `foreach` y crea los registros en la tabla `incidents`.  


???questionlaravel "Respuesta B.4.2"
    ```bash
    php artisan make:seeder IncidentSeeder
    ```

    ```php
    <?php

    namespace Database\Seeders;

    use App\Models\Incident;
    use Illuminate\Database\Seeder;

    class IncidentSeeder extends Seeder
    {
        public function run(): void
        {
            $incidents = [
                [
                    'title'       => 'El ordenador del aula 1 no enciende',
                    'description' => 'Parece fallo de fuente de alimentación',
                    'status'      => 'abierta',
                    'priority'    => 'alta',
                    'user_id'     => 1,   // admin
                    'category_id' => 1,   // Hardware
                ],
                [
                    'title'       => 'No funciona el proyector en el aula 3',
                    'description' => 'Problema con drivers de la tarjeta gráfica',
                    'status'      => 'en_proceso',
                    'priority'    => 'media',
                    'user_id'     => 1,
                    'category_id' => 1,   // Hardware
                ],
                [
                    'title'       => 'El programa de notas se cierra solo',
                    'description' => 'Error de aplicación al abrir informes',
                    'status'      => 'abierta',
                    'priority'    => 'media',
                    'user_id'     => 1,
                    'category_id' => 2,   // Software
                ],
                [
                    'title'       => 'Sin conexión a Internet en secretaría',
                    'description' => 'Posible problema de switch o cableado',
                    'status'      => 'resuelta',
                    'priority'    => 'alta',
                    'user_id'     => 1,
                    'category_id' => 3,   // Red
                ],
            ];

            foreach ($incidents as $data) {
                Incident::create($data);
            }
        }
    }
    ```

---

### B.5. Rutas, controlador y acciones básicas (1,5 puntos)

#### B.5.1. Rutas de recurso (0,5 pt)

a. Escribe la línea de ruta resource que añadirías en `routes/web.php` para registrar todas las rutas CRUD del controlador `IncidentController`.

???questionlaravel "Respuesta B.5.1.a"
    ```php
    use App\Http\Controllers\IncidentController;

    Route::resource('incidents', IncidentController::class);
    ```

b. Indica **dos ejemplos** de URL y método HTTP que generará esa ruta de recurso (por ejemplo, ver el listado de incidencias, mostrar una incidencia concreta).

???questionlaravel "Respuesta B.5.1.b"
    Dos ejemplos de rutas generadas por el resource:

    - **Listado de incidencias**  

        - Método HTTP: `GET`  
        - URL: `/incidents`  
        - Acción: `IncidentController@index`

    - **Mostrar una incidencia concreta**  

        - Método HTTP: `GET`  
        - URL: `/incidents/{id}` (por ejemplo, `/incidents/5`)  
        - Acción: `IncidentController@show`

    - Otras posibles:

        - `POST /incidents` → `store`, 
        - `PUT/PATCH /incidents/{incident}` → `update`, 
        - `DELETE /incidents/{incident}` → `destroy`.

---

#### B.5.2. Método `index()` con paginación (0,5 pt)

Escribe el cuerpo aproximado del método `index()` de `IncidentController` que:

- Obtenga todas las incidencias,  
- Cargue también su categoría y su usuario creador,  
- Pagina los resultados de 10 en 10,  
- Devuelva la vista `incidents.index` pasando la colección paginada.
  
> No es necesario escribir la vista Blade, solo el método del controlador.

???questionlaravel "Respuesta B.5.2"
    ```php
    <?php

    namespace App\Http\Controllers;

    use App\Models\Incident;

    class IncidentController extends Controller
    {
        public function index()
        {
            // Cargar incidencias con su usuario y categoría, paginadas de 10 en 10
            $incidents = Incident::with(['category', 'user'])
                                ->paginate(10);

            return view('incidents.index', compact('incidents'));
        }

        // ...
    }
    ```

---

#### B.5.3. Método `store()` con validación (0,5 pt)

Escribe el método `store()` de `IncidentController` que:

- Valide los campos del formulario (a través de `$request`):

    - title: obligatorio, mínimo 3 caracteres.  
    - category_id: obligatorio y debe existir en categories.  
    - priority: obligatorio.  

- Redirija al listado de incidencias con un **mensaje de éxito** (por ejemplo, usando `with('success', '...')`).


???questionlaravel "Respuesta B.5.3"
    ```php
    <?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Incident;

    class IncidentController extends Controller
    {
        public function store(Request $request)
        {
            // 1. Validar campos
            $validated = $request->validate([
                'title'       => 'required|min:3',
                'category_id' => 'required|exists:categories,id',
                'priority'    => 'required',
                'description' => 'nullable',
            ]);

            // Asignar valores adicionales
            $validated['status']   = 'abierta';           // estado inicial
            $validated['user_id']  = auth()->id();        // usuario logueado (creador)

            // 2. Crear la incidencia
            Incident::create($validated);

            // 3. Redirigir con mensaje de éxito
            return redirect()
                ->route('incidents.index')
                ->with('success', 'Incidencia creada correctamente.');
        }
    }
    ```

---

### B.6. Vistas y plantillas Blade (1 punto)

Queremos estructurar las vistas usando **plantillas Blade**.

#### B.6.1. Plantilla base `layouts/app.blade.php` (0,5 pt)

a. Explica brevemente qué papel tiene `layouts/app.blade.php` en un proyecto Laravel.

???questionlaravel "Respuesta B.6.1.a"
    `layouts/app.blade.php` suele ser la **plantilla base** de la aplicación: contiene el HTML general (cabecera, `<head>`, `<body>`, menú, etc.).   
    El resto de vistas **extienden** este layout y rellenan la sección `@yield('content')`, evitando repetir la misma estructura.

b. Escribe un fragmento simplificado de Blade que contenga:

    - La declaración de un `<!DOCTYPE html>` y etiquetas `<html>`, `<head>`, `<body>`.  
    - Una sección donde se muestre un título general del sitio (por ejemplo, “Gestor de incidencias”).  
    - Una llamada a `@yield('content')` para que las vistas hijas inserten su contenido.

???questionlaravel "Respuesta B.6.1.b"
    Fragmento simplificado:

    ```blade
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Gestor de incidencias</title>
    </head>
    <body>
        <header>
            <h1>Gestor de incidencias</h1>
        </header>

        <main>
            @yield('content')
        </main>
    </body>
    </html>
    ```

---

#### B.6.2. Vista `incidents/index.blade.php` (0,5 pt)

Escribe un fragmento simplificado de Blade para la vista `incidents/index.blade.php` que:

- **Extienda** de `layouts/app.blade.php`.
- Defina una sección `content` donde:  
  
    - Se recorra la colección de incidencias (`$incidents`) con un `@foreach`.
    - Por cada incidencia se muestre el título, la categoría y el estado. 

> No hace falta HTML detallado, basta con la estructura Blade básica.


???questionlaravel "Respuesta B.6.2"
    ```blade
    {{-- resources/views/incidents/index.blade.php --}}
    @extends('layouts.app')

    @section('content')
        <h2>Listado de incidencias</h2>

        @foreach($incidents as $incident)
            <div>
                <strong>{{ $incident->title }}</strong><br>
                Categoría: {{ $incident->category->name }}<br>
                Estado: {{ $incident->status }}
            </div>
            <hr>
        @endforeach

        {{-- Si estamos usando paginación --}}
        {{ $incidents->links() }}
    @endsection
    ```
