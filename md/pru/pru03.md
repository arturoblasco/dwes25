# 3. Desarrollo de un CRUD

## 1. Introducción a CRUD

<p style="float: left; margin-left: 1rem;">
  <img src="../../img/laravel.svg"
       alt="Actividad en el aula virtual"
       width="150">
</p>
El término **CRUD** corresponde a las operaciones básicas que se realizan en la mayoría de las aplicaciones que gestionan datos:

* **C**reate (Crear): Insertar nuevos datos.
* **R**ead (Leer): Consultar y visualizar datos.
* **U**pdate (Actualizar): Modificar datos existentes.
* **D**elete (Eliminar): Borrar datos.

En Laravel, la implementación de un CRUD completo nos permite comprender cómo los **Modelos**, **Controladores** y **Vistas** interactúan entre sí para ofrecer una experiencia de usuario completa.

**Desarrollo de CRUDs**

Dominar el desarrollo de CRUDs es básico para cualquier programador web, ya que casi todas las aplicaciones web tienen que manejar datos de algún tipo, y estas son las acciones fundamentales que se realizan sobre esos datos.

Además, este tema nos va a servir para aprender el funcionamiento de los formularios en Laravel.

!!!tip "Recursos para realizar los ejemplos de este punto"
    - Fichero [`notes.sql`](../sources/notes.sql){:target="blank"} con 15 productos ficticios para importar en la tabla `notes`.
    - Iconos: [vista](../sources/view.svg){:target="blank"}, [editar](../sources/edit.svg){:target="blank"}, [eliminar](../sources/delete.svg){:target="blank"}, [añadir](../sources/add.svg){:target="blank"}.
    - Puedes mejorar la apariencia de tu aplicación utilizando CSS (ejemplo de estilos: [`style1.css`](../sources/style1.css){:target="blank"}).

## 2. Rutas Dinámicas y Controladores

### 2.1. Crear plantilla blade y partials

Antes de crear nuestras tablas, modelos, controladores y rutas, vamos a diseñar una plantilla de vista blade que nos sirva para todas las siguientes vistas que creemos (que "*extiendan*" de la plantilla):

**Crear plantilla/layout**

Crear un layout base en la ruta `resources/views/layouts/app.blade.php`:
???+examplelaravel "Layout"
    
    ``` 
    <!DOCTYPE html> 
    <html lang="es"> 
        <head>     
            <meta charset="UTF-8">     
            <title>
                @yield('title')
            </title> 
            <link rel="stylesheet" href="{{ asset('assets/css/style1.css') }}">
        </head> 
    <body>     
        <header>         
            <h1>Mi Aplicación de Notas</h1>         
            @include('partials.nav')
        </header>     
        <main>         
            @yield('content')     
        </main>
        @stack('scripts)
    </body> 
    </html>
    ```
De esta plantilla `app.blade.php` observamos:

- Tiene dos referencias `@yield` que después, en la vista que extienda, deberemos hacer referencia con su correspondiente `@section`.
- Tiene una hoja de estilo que apunta a un fichero `style1.css` de la carpeta pública `assets/img/` (se utiliza `{{ asset('assets/css/style1.css') }}`).
- Tiene una referencia `@stack` que utilizaremos para ir añadiendo código (en este caso scripts).
- Tiene código incluido `nav.blade.php` que se encuentra en la ruta `resources/views/partials`.

**Crear partial**

???+examplelaravel "Partial en `resources/views/partials/nav.blade.php`"
    ``` 
    <nav>             
        <a href="{{ route('notes.index') }}">Notas</a> |             
        <a href="{{ route('employee.byId') }}">Empleados</a>         
    </nav>  
    ```

### 2.2. Crear un circuito MVC rápido para rutas dinámicas

**1) Migración + tabla**

Utilizar la tabla `notes` con los campos:

* `id` (entero, autoincremental)
* `title` (string)
* `description` (text)
* `date_at` (date)
* `done` (boolean)

???+examplelaravel "Crear la migración de la nota y el controador:"
    
    Crear Migración y después el Modelo:
    
    ```
    php artisan make:migration create_notes_table
    php artisan make:model Note
    ```
    
    🔝 ó Crear el modelo, la migración y el controlador con un solo comando:
        
    ```
    php artisan make:model Note -mc
    ```
    De esta forma se crean estos tres recursos con la sintaxis adecuada (`Note`, `xxxx_xx_xx_xxxxxx_create_notes_table` y `NoteController`).

???+examplelaravel "Archivo de migración `database/migrations/xxxx_xx_xx_create_notes_table.php` y añadimos los campos:"

    ``` 
      public function up() {     
         Schema::create('notes', function (Blueprint $table) {         
            $table->id();         
            $table->string('title')->unique();      
            $table->text('description')->nullable();         
            $table->date('date_at');         
            $table->boolean('done')->default(false);         
            $table->timestamps();     
         }); 
      } 
      
      public function down() {     
         Schema::dropIfExists('notes'); 
      } 
    ```

Por último, ejecutamos la migración:

???+examplelaravel "Ejecutar Migración"
    
    ```
    php artisan migrate
    ```

!!!warning "Resetear Migraciones"
    Si tenemos algún problema porque no hemos creado la base de datos desde cero, podemos eliminar las migraciones anteriores con:

    ```
    php artisan migrate:reset
    ```

**2) Modelo `Note`**

Ahora vamos a implementar el modelo `Note`. Vamos a definir los campos que se pueden asignar masivamentes:

???+examplelaravel "Modelo"

    ``` 
    namespace App\Models; 

    use Illuminate\Database\Eloquent\Model;  

    class Note extends Model {     
      protected $fillable = ['title', 'description', 'date_at', 'done'];     
    } 
    ```
    
    * `$fillable`: define qué campos se pueden asignar en masa.
    * `$guarded`: define qué campos **no** se pueden asignar.

**3) Controlador `NoteController`**:

???+examplelaravel "Crear Controlador"
    Ejecuta solo si no se ha ejecutado la orden `php artisan make:model Note -mc`:
    ```
    php artisan make:controller NoteController
    ```

???+teolaravel "Ejemplo de codificación de un método en el controlador:"
    Codifación del método `show()` en el controlador `app/Http/Controllers/NoteController.php`:
    
    ```
    //...
    use App\Models\Note;

    //...
    public function show(Note $note)
    {
        return view('notes.show', compact('note'));
    }
    ```

**4) Ruta asociada:**

Ahora que ya tenemos el controlador y el método que manejará la ruta, y el modelo que se conectará con la base de datos, vamos a definir la ruta en `routes/web.php`:

???+teolaravel "Ejemplo de Ruta con Parámetro Dinámico y Controlador"
    
    ``` 
    //...
    use App\Http\Controllers\NoteController;  
    
    //...
    Route::get('notes/{note}', [NoteController::class, 'show'])->name('notes.show'); 
    ```

**5) Vista asociada:**

Por último nos queda crear la vista que mostrará la información de la nota con el ID recibido:

???+teolaravel "Ejemplo Vista `resources/views/notes/show.blade.php`"
    
    ``` 
    @extends('layouts.app')
    
    @section('title', "nota $note->id")
    
    @section('content')
        <h1>nota {{ $note->id }}</h1>
    @endsection
    ```    

> **Función `compact()`**
> 
> La función `compact('variable')` crea un array asociativo `['variable' => $variable]` que puede ser pasado a la vista. Es una forma rápida y limpia de pasar datos. Sólo la puedo utilizar si el valor de la clave es el mismo que el nombre de la variable.

### 2.3. Parámetros opcionales y valores por defecto

Podemos definir parámetros **opcionales** añadiendo un signo de interrogación **`?`**:

???+teolaravel "Ruta con Parámetro Opcional"
    
    ``` 
    Route::get('/saludo/{nombre?}', function ($nombre = 'Invitado') {
        return "Hola, $nombre"; 
    }); 
    ```
    
    * Si accedemos a `/saludo/Laura`, veremos "*Hola, Laura*".
    * Si accedemos a `/saludo`, veremos "*Hola, Invitado*".

!!!warning "Reglas para parámetros opcionales"
    
    * El parámetro opcional debe ser el último de la URL.
    * Hay que asignar un **valor por defecto** en la función.

### 2.4. Importancia del orden de las rutas

Laravel evalúa las rutas en el **orden en que se definen**.

???+teolaravel "Ejemplo de conflicto:"

    ``` 
    Route::get('/notes/nueva', function() { return 'Crear nueva nota'; }); 
    Route::get('/notes/{id}', function($id) { return "Nota ID: $id"; }); 
    ```

* Primero debe definirse `/notes/nueva` porque si no, Laravel intentará interpretar `nueva` como un `id`.
* El orden correcto es siempre de **rutas más específicas a más generales**.

!!!warning "Consejo en el orden de las rutas"
    
    Primero define todas las rutas fijas y luego las rutas con parámetros dinámicos.

## 3. Desarrollo del CRUD para notas

### 3.1. Listar todas las notas

Primero creamos la ruta y el método para listar todas las notas.

**1) Ruta:**

???+examplelaravel "Ruta para Listar Notas"
    
    ``` 
    //...
    use App\Http\Controllers\NoteController;  

    //...
    Route::get('/', [NoteController::class, 'index'])->name('notes.index'); 
    ```

**2) Controlador:**

???+examplelaravel "método index, para listar todas las notas"
    
    ```
    //...
    use App\Models\Note;

    //...
    public function index()
    {
        $notes = Note::all();
        return view('notes.index', compact('notes'));
    }
    ```

**3) Vista de Listado `resources/views/notes/index.blade.php`:**

???+examplelaravel "Listado de Notas"
    
    ```blade
    @extends('layouts.app')

    @section('title', 'listar notas')

    @section('content')
        <h1>LISTADO DE NOTAS</h1>

        <a href="{{ route('notes.create') }}" style="display:inline-block; text-decoration:none;">
            <img src="{{ asset('assets/img/add.svg') }}" alt="Añadir Nota" title="Añadir Nota">
        </a>

        @if ($notes->isEmpty())
            <p class="no-results">No hay notas que cumplan el criterio.</p>
        @else
            <table>
                <caption>Total de notas: {{ $notes->count() }}</caption>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>título</th>
                        <th>descripción</th>
                        <th>fecha</th>
                        <th>realizada</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notes as $item)
                        @php
                            $date = \Carbon\Carbon::parse($item->date_at);
                        @endphp
                        <tr class="{{ $item->done ? 'done-yes' : 'done-no' }}">
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $date->format('d-m-Y') }}</td>  
                            <td>{{ $item->done ? 'checked' : '' }}</td>       

                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('notes.show', $item) }}" style="display:inline-block; text-decoration:none;">
                                        <img src="{{ asset('assets/img/view.svg') }}" alt="Vista" title="Vista">
                                    </a>

                                    <a href="{{ route('notes.edit', $item) }}" style="display:inline-block; text-decoration:none;">
                                        <img src="{{ asset('assets/img/edit.svg') }}" alt="Editar" title="Editar">
                                    </a>

                                    <form action="{{ route('notes.destroy', $item) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="border: none; background: none; padding: 0; cursor: pointer;">
                                            <img src="{{ asset('assets/img/delete.svg') }}" alt="Eliminar" title="Eliminar">
                                        </button>
                                    </form>
                                </div>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endsection
    ```

**Explicación de @forelse vs @foreach:**

* `@foreach` se utiliza para recorrer elementos, pero no gestiona si el array está vacío.
* `@forelse` permite recorrer elementos y además definir qué hacer si no hay elementos (con cláusula `@empty`).

???+teolaravel "Ejemplo `@forelse`"
    ``` 
    @forelse ($notes as $item)     
        <p>{{ $item->title }}</p>
    @empty     
        <p>No hay notas disponibles.</p> 
    @endforelse 
    ```
    En el ejemplo anterior, si `$notes` está vacío, se mostrará "*No hay notas disponibles.*"

En este formulario vamos a adelantar algunas cosas que ampliaremos más adelante:

- Para poner el enlace a eliminar una nota, vamos a utilizar un formulario con el método `POST` y la directiva `@method('DELETE')`. Esta directiva simula el método HTTP DELETE en formularios HTML (que solo permiten GET y POST). Más adelante explicaremos esto con más detalle.
- También vemos que está la directiva `@csrf`. Esta directiva genera un campo oculto con un token que protege el formulario contra ataques CSRF (Cross-Site Request Forgery). En `laravel` esta protección es automática, pero hay que incluir la directiva `@csrf` en los formularios para que funcione correctamente.

<div class="figure-center">
  <figure>
    <img src="../../img/pru/laravel_vista_index1.png"
         alt="vista index"
         class="figure-img-highlight"
         style="max-width: 80%; height: auto;" />
    <figcaption class="figure-caption-small">
      vista notes/index.blade.php
    </figcaption>
  </figure>
</div>

---
### 3.2. Mostrar una Nota Individual

**1) Ruta para mostrar:**

???+examplelaravel "Ruta para Mostrar Nota"
    
    ```
    Route::get('/notes/show/{note}', [NoteController::class, 'show'])->name('notes.show');
    ```

**2) Controlador:**

En este caso también tenemos dos formas de recibir el parámetro `Note $note`. En esta primer caso recibimos la *nota* y `laravel` por inyección de modelo la busca por nosotros y en el segundo caso recibimos el *ID* y buscamos la nota nosotros.

???+examplelaravel "método `show`"

    - 🔝 Por inyección de modelo:
    
    ``` 
    public function show(Note $note) {     
        return view('notes.show', compact('note')); 
    } 
    ```

    - Buscando por ID:   

    ``` 
    public function show($id) {     
        $note = Note::findOrFail($id);     
        return view('notes.show', compact('note')); 
    } 
    ```



**3) Vista `resources/views/notes/show.blade.php` actualizada:**

???+examplelaravel "Mostrar Nota"
    ``` 
    @extends('layouts.app')
    
    @section('title')
        nota: {{ $note->id }}
    @endsection
    
    @section('content')
        <h1>nota {{ $note->id }}</h1>
    
        <table>
        <tbody>
            <tr>
                <td>título</td>
                <td>{{ $note->title }}</td>
            </tr>
            <tr>
                <td>descripción</td>
                <td>{{ $note->description }}</td>
            </tr>
            <tr>
                <td>fecha</td>
                <td>{{ $note->date_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>realizada</td>
                <td>{{ $note->done ? 'sí' : 'no' }}</td>
            </tr>
            </tbody>
        </table>
        <a href="{{ route('notes.index') }}"> <--Volver </a>
    @endsection
    ```
    Podemos observar que esta vista:
    
    - aplica la plantilla `app.blade.php`.
    - muestra en la pestaña del navegador el número de nota.
    - en el campo de tipo fecha se le aplica el método `format('d/m/Y)`.
    - muestra el valor del campo `done` (que es de tipo `boolean`) realiza una ternaria.
    - tiene un enlace, al final, para volver a la vista que lista notas y lo hace con el método `route` y el nombre de la ruta (`notes.index`).

Con esto, accediendo a `/notes/3` veremos:
    
<div class="figure-center">
  <figure>
    <img src="../../img/pru/laravel_vista_show1.png"
         alt="vista show"
         class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
      vista notes/show.blade.php
    </figcaption>
  </figure>
</div>

---

### 3.3. Crear una Nueva Nota

Para poder crear una nota necesitamos tres cosas:

**1) Ruta:**
???+examplelaravel "Ruta para Crear Nota"
    ```
    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
    ```

**2) Controlador:** 
???+examplelaravel "método create, para mostrar el formulario de creación"
    ``` 
    public function create() {     
        return view('notes.create'); 
    } 
    ```

**3) Vista de Creado `resources/views/notes/index.blade.php`:**

Una vista que contenga el formulario de creación: **`resources/views/notes/create.blade.php`**.
    
???+examplelaravel "Formulario de Crear Nota"
    ``` 
    @extends('layouts.app')
    
    @section('title', 'Crear Nota')
    
    @section('content')
        <h1>CREAR NUEVA NOTA</h1>
        
        <form action="{{ route('notes.store') }}" method="POST">
            @csrf
            <table>
            <caption>Formulario de Creación de Nota</caption>
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label for="title">Título:</label></td>
                    <td><input type="text" id="title" name="title" required></td>
                </tr>
                <tr>
                    <td><label for="description">Descripción:</label></td>
                    <td><textarea id="description" name="description" 
                                    placeholder="Escribe la descripción" required></textarea></td>
                </tr>
                <tr>
                    <td><label for="date">Fecha:</label></td>
                    <td><input type="date" id="date" name="date_at" required></td>
                </tr>
                <tr>
                    <td><label for="done">Completada:</label></td>
                    <td><input type="checkbox" id="done" name="done" value="1"></td>
                </tr>
            </tbody>
            </table>

            <div style="margin-top: 1rem;">
                <button type="submit">Guardar</button>

                <a href="{{ route('notes.index') }}" style="margin-left: 10px; text-decoration: none; color: blue;">Cancelar</a>
            </div>
        </form>
    @endsection
    ```

<div class="figure-center">
  <figure>
    <img src="../../img/pru/laravel_vista_create1.png"
         alt="vista create"
         class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
      vista notes/create.blade.php
    </figcaption>
  </figure>
</div>
---

### 3.4. Guardar la Nueva Nota

Al igual que en el caso anterior, para guardar la nota necesitamos dos cosas. Una ruta que maneje el envío del formulario y un método en el controlador que procese los datos y guarde la nota en la base de datos. Al final, redirigiremos a la lista de notas.

**1) Ruta para guardar:**

???+examplelaravel "Ruta para Guardar Nota"
    
    ``` 
    Route::post('/notes/store', [NoteController::class, 'store'])->name('notes.store'); 
    ```

**2) Controlador:**

Para guardar la nota, podemos usar diferentes métodos. 

Aquí mostramos dos formas:

???+examplelaravel "Guardar Nota"
    
    ``` 
    public function store(Request $request) {     
        $note = new Note();     
        $note->title = $request->input('title');     
        $note->description = $request->input('description');     
        $note->date = $request->input('date_at');     
        $note->done = $request->input('done') ? 1 : 0;     
        $note->save();     // Redirigir a la lista de notas     
        return redirect()->route('notes.index'); 
    } 
    ```
    
    🔝 Mejor utilizando el método `create`:
        
    ``` 
    public function store(Request $request) {     
        Note::create($request->all());     
        return redirect()->route('notes.index'); 
    } 
    ```

**Explicaciones Adicionales:**

* `@csrf` protege contra ataques CSRF (Cross-Site Request Forgery).
* `$request->all()` devuelve todos los datos enviados en el formulario.
* Laravel valida automáticamente que el token CSRF esté presente. Si no lo está, lanzará un error.

¿Cómo funciona CSRF?

* Laravel genera un token único para cada sesión de usuario.
* Este token se incluye en cada formulario generado por Laravel.
* Cuando se envía el formulario, Laravel verifica que el token enviado coincida con el de la sesión.
* Si no coinciden, Laravel lanza un error 419 (Page Expired).
* Esto previene que un atacante envíe formularios en nombre del usuario sin su consentimiento.

---

### 3.5. Editar una Nota

**1) Ruta para edición**

???+examplelaravel "Ruta para Editar Nota"
    
    ```
    Route::get('/notes/edit/{note}', [NoteController::class, 'edit'])->name('notes.edit');
    ```

**2) Controlador:**

Tenemos varias formas de recibir el parámetro `note`. En esta primer recibimos el ID y buscamos la nota, para poder pasarla a la vista:

???+examplelaravel "método edit, para mostrar el formulario de edición"
    
    ``` 
    public function edit($id) {     
        $note = Note::findOrFail($id);     
        return view('notes.edit', compact('note')); 
    } 
    ```

    🔝 En esta segunda forma, recibimos el modelo `Note`. De esta manera es Laravel el que se encarga de buscar la nota. Cuando trabajamos con modelos, esta es la forma recomendada (con inyección de modelo):

    ``` 
    public function edit(Note $note) {     
        return view('notes.edit', compact('note')); 
    } 
    ```

**3) Vista de Editar `resources/views/notes/edit.blade.php`:**

En este caso la ruta la hemos definido con el método `PUT`. Este método es el que se utiliza para actualizar los datos de un recurso existente. Pero ¿cómo hacerlo si las opciones de `form` solo permiten `GET` y `POST`?. Laravel nos ofrece una solución sencilla: la directiva `@method('PUT')`. Esta directiva simula el método PUT en formularios HTML. Esta directiva debe estar dentro del formulario y antes de los inputs.

???+teolaravel "Ejemplo para Editar Nota"

    ```
    <form id="sample-form" action="somepage.php" method="POST">     
    @csrf     
    @method('PUT')
        <!-- Otros campos del formulario --> 
    </form> 
    ```

Con este formato el formulario se enviará como un PUT, aunque el método del formulario sea POST.

???+examplelaravel "Formulario de Editar Nota"
    
    ``` 
    @extends('layouts.app')

    @section('title', 'Editar Nota')

    @section('content')
        <h1>EDITAR NOTA {{ $note->id }}</h1>

        <form action="{{ route('notes.update', $note->id) }}" method="POST">
            @csrf
            @method('PUT')
            <table>
                <caption>Formulario de Edición de Nota</caption>
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><label for="title">Título:</label></td>
                        <td><input type="text" id="title" name="title" value="{{ $note->title }}" required></td>
                    </tr>
                    <tr>
                        <td><label for="description">Descripción:</label></td>
                        <td><textarea id="description" name="description" required>
                            {{ $note->description }}
                        </textarea></td>
                    </tr>
                    <tr>
                        <td><label for="date_at">Fecha:</label></td>
                        <td><input type="date" id="date" name="date_at" value="{{ $note->date_at) }}" required></td>
                    </tr>
                    <tr>
                        <td><label for="done">Completada:</label></td>
                        <td>
                            <input type="hidden" name="done" value="0"> 
                            <input type="checkbox" id="done" name="done" value="1" {{ $note->done ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 1rem;">
                <button type="submit">Actualizar</button>

                <a href="{{ route('notes.index') }}" style="margin-left: 10px; text-decoration: none; color: blue;">Cancelar</a>
            </div>
        </form>
    @endsection
    ```
    
    - `@method('PUT')` simula el método HTTP PUT en formularios HTML (que solo permiten GET y POST).

---

### 3.6. Actualizar la Nota

**1) Ruta para actualizar:**

???+examplelaravel "Ruta para Actualizar Nota"
    
    ``` 
    Route::put('/notes/update/{note}', [NoteController::class, 'update'])->name('notes.update'); 
    ```

**2) Controlador:**

En este caso también tenemos dos formas de recibir el parámetro `Note $note`. En esta primer caso recibimos la *nota* y `laravel` por inyección de modelo la busca por nosotros y en el segundo caso recibimos el *ID* y buscamos la nota nosotros.

???+examplelaravel "método `update`"
    
    - 🔝 Por inyección de modelo:
    ``` 
    public function update(Request $request, Note $note) {     
        $note->update($request->all());     
        return redirect()->route('notes.index'); 
    } 
    ```
    - Buscando por ID:    
    ``` 
    public function update(Request $request, $id) {     
        $note = Note::findOrFail($id);     
        $note->update($request->all());     
        return redirect()->route('notes.index'); 
    } 
    ```

---

### 3.7. Eliminar una Nota

**1) Ruta para eliminar:**

???+examplelaravel "Ruta para Eliminar Nota"
    
    ```
    Route::delete('/notes/destroy/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    ```

**2) Controlador:**

Como en los casos anteriores, tenemos dos formas de recibir el parámetro `Note $note`. En esta primer caso recibimos la *nota* y `laravel` por inyección de modelo la busca por nosotros y en el segundo caso recibimos el *ID* y buscamos la nota nosotros.

???+examplelaravel "método `destroy`"

    - 🔝 Por inyección de modelo:
    ``` 
    public function destroy(Note $note) {     
        $note->delete();     
        return redirect()->route('notes.index'); 
    } 
    ```

    - Buscando por ID:   
    ``` 
    public function destroy($id) {     
        $note = Note::findOrFail($id);     
        $note->delete();     
        return redirect()->route('notes.index'); 
    } 
    ```

### 3.8. Prueba completa del CRUD

Ahora vamos probar todas las funcionalidades del CRUD:

> **Aspecto del ejemplo**
> 
> Al no utilizar nada de CSS, el aspecto es muy básico. En un proyecto real, se debería aplicar estilos CSS para mejorar la apariencia y usabilidad.

1) **Listar Notas:** Accede a la ruta `/` para ver el listado de notas.
    
<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_vista_index1.png"
                alt="vista /notes/index.blade.php"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            vista /notes/index.blade.php
    </figcaption>
</figure>
</div>

2) **Crear Nota:** Haz clic en el botón "*Añadir Nota*", rellena el formulario y envíalo.

Al hacer click en "*Añadir Nota*" se accede a `/notes/create`:
    
<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_vista_create2.png"
                alt="vista /notes/create.blade.php"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            vista /notes/create.blade.php
    </figcaption>
</figure>
</div>

Una vez rellenado el formulario lo enviamos al servidor (ruta `/notes/store`) y volvemos al listado de notas.
    
<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_vista_create3.png"
                alt="vista /notes/index.blade.php"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            vista /notes/index.blade.php después de añadir una nueva nota.
    </figcaption>
</figure>
</div>

   Podemos ver la nota creada y como aparecen los enlaces para editar y eliminar.

3) **Editar Nota:** Haz clic en "Editar" junto a una nota, modifica los datos y envía el formulario.

Al hacer click en "Editar" se accede a `/notes/edit/{id}`:
    
<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_vista_edit1.png"
                alt="vista /notes/edit.blade.php"
                class="figure-img-highlight" 
                style="max-width: 85%; height: auto;" />
    <figcaption class="figure-caption-small">
            vista /notes/edit.blade.php
    </figcaption>
</figure>
</div>

Una vez modificado el formulario lo enviamos al servidor (ruta `/notes/update/{id}`) que actualiza la nota y nos redirecciona al listado de notas.
    
<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_vista_edit2.png"
                alt="vista /notes/index.blade.php"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            vista /notes/index.blade.php después de modificar la nota 5
    </figcaption>
</figure>
</div>

1) **Mostrar Nota:** Haz clic en el título de una nota para ver sus detalles.

Al hacer click en el título de una nota se accede a `/notes/show/{id}`:
    
<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_vista_show2.png"
                alt="vista /notes/show.blade.php"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            vista /notes/show.blade.php de la nota 7
    </figcaption>
</figure>
</div>

Una vez vista pulsamos volver y nos redirecciona al listado de notas.

5) **Eliminar Nota:** Haz clic en "*Eliminar*" junto a una nota.
   
   Al hacer click en "*Eliminar*" se envía un formulario con método `DELETE` a la ruta `/notes/destroy/{id}` que elimina la nota y nos redirecciona al listado de notas.

!!!warning "Confirmación de Eliminación"
    
    En un proyecto real, es recomendable añadir una confirmación antes de eliminar una nota para evitar eliminaciones accidentales.

    Un ejemplo sería este:

    1) Cambia en el fichero `resources/views/notes/index.blade.php` el código para eliminar una fila:

    ```
    <form id="delete-form-{{ $item->id }}" 
          action="{{ route('notes.destroy', $item->id) }}" 
          method="POST" 
          style="display:inline">
    @csrf
    @method('DELETE')
        <button type="button" 
                onclick="confirmDelete({{ $item->id }})"
                style="border: none; background: none; padding: 0; cursor: pointer;">
            <img src="{{ asset('assets/img/delete.svg') }}" alt="Eliminar" title="Eliminar">
        </button>
    </form>
    ```
    2) Añade al final del fichero `resources/views/notes/index.blade.php` el `@push('scripts')`:

    ```
    @push('scripts')
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: '¿Estás seguro que deseas eliminar esta nota con ID ' + id + '?',
                    text: '¡Este cambio no se puede deshacer!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#769248ff',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();  // Enviar el formulario
                    }
                });
            }
        </script>
    @endpush
    ```

    3) Y en la plantilla `resources/views/layouts/app.blade.php` añade la línea:

    ```
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    ```

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_vista_destroy1.png"
                alt="vista /notes/destroy.blade.php"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            vista /notes/destory.blade.php de la eliminación de la nota 6
    </figcaption>
</figure>
</div>

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_vista_destroy2.png"
                alt="vista /notes/index.blade.php"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            vista /notes/index.blade.php después de la eliminación de la nota 6
    </figcaption>
</figure>
</div>

### 3.9. Tipado en los métodos del controlador

???+teolaravel "Ejemplo de tipado correcto:"

    ``` 
    public function update(Request $request, Note $note): RedirectResponse {     
        $note->update($request->all());     
        return redirect()->route('notes.index'); 
    } 
    ```

* Tipar los parámetros mejora la legibilidad y control de errores.
* Tipar el tipo de retorno ayuda a Laravel a validar internamente las respuestas.

**Tipos comunes de retorno**
       
* `View` para devolver vistas. `use Illuminate\View\View`
* `RedirectResponse` para redirecciones. `use Illuminate\Http\RedirectResponse`
* `JsonResponse` para APIs. `use Illuminate\Http\JsonResponse`


---
???questionlaravel "Práctica a Entregar"
    
    ### Objetivo de la actividad
    
    <p style="float: left; margin-left: 1rem;">
        <img src="../../img/laraveltask.svg"
             alt="Actividad en el aula virtual"
             width="150">
    </p>
    
    **Desarrollo de un CRUD de Productos**
    
    El objetivo de esta práctica es aprender a **aplicar formularios, rutas, controladores y vistas** en Laravel para desarrollar un **CRUD completo para el recurso `Product`**.
    
    Con esta actividad, aprenderás a trabajar con el patrón **MVC (Modelo-Vista-Controlador)**, a gestionar datos con formularios y a usar las herramientas de Eloquent para crear, leer, actualizar y eliminar productos en la base de datos. Además, practicarás la implementación de vistas con **Blade** y el uso de rutas dinámicas.
    
    Al finalizar, deberás ser capaz de:
    
    * Crear y ejecutar migraciones para definir la estructura de una base de datos.
    * Implementar un controlador con todos los métodos CRUD.
    * Crear vistas Blade para presentar datos y manejar formularios.
    * Gestionar datos de entrada y salida mediante formularios, con las directivas `@csrf` y `@method`.
    
    ---
    
    ### Instrucciones
    
    Sigue los pasos a continuación. Verifica que todo funciona correctamente antes de pasar al siguiente paso.
    
    Si alguna parte del proceso no está clara, consulta la documentación anterior o busca ejemplos dentro del proyecto que has trabajado anteriormente.
    
    ---
    
    ### 1. Preparar el entorno
    
    1) **Accede a tu proyecto Laravel.**

    Si no tienes un proyecto en marcha, puedes usar el que creaste en la prueba anterior o crear uno nuevo con `composer create-project laravel/laravel nombre_del_proyecto` (por ejemplo nombre del proyecto `testear`).

    2) **Inicia el servidor.**
    
    Asegúrate de que tu servidor esté funcionando, inicia `docker compose up -d` (o si usas Laragon, arranca los servicios correspondientes a Ngingx y Mysql).

    3) **Crea un nuevo modelo y migración para el recurso `Product`.**
    
    Utiliza el comando de Artisan para crear un modelo `Product` junto con su migración. Esto generará un archivo para crear la tabla de productos en la base de datos.
    
    ---
    
    ### 2. Crear la tabla y el modelo
    
    4) **Edita la migración de productos.**
    
    Abre el archivo de migración generado y define los siguientes campos para la tabla `products`. Estos campos incluyen el nombre del producto, una descripción, precio, stock y las fechas de creación/actualización automáticas.
    
    La estructura de la tabla deberá ser:
    
    | Campo | Tipo de dato | Restricciones / Descripción |
    | --- | --- | --- |
    | `id` | `bigIncrements` | Clave primaria, autoincremental |
    | `name` | `string(255)` | Nombre del producto, no nulo |
    | `description` | `text` | Descripción del producto |
    | `price` | `decimal(8,2)` | Precio del producto, no nulo |
    | `stock` | `integer` | Cantidad en stock, no nulo, por defecto 0 |
    | `created_at` | `timestamp` | Fecha de creación (automático) |
    | `updated_at` | `timestamp` | Fecha de actualización (automático) |
    
    5) **Ejecuta la migración**.
    
    Una vez hayas definido los campos, ejecuta la migración para crear la tabla en la base de datos.
            
    ```
    php artisan migrate
    ```
    
    Verifica que la tabla se haya creado correctamente en la base de datos.
    
    > **Datos de prueba**: 
    > 
    > En los recursos de esta actividad hay un fichero [`products.sql`](../sources/products.sql){:target="blank"} con 15 productos ficticios para importar en la tabla `products`.

    6) **Configura el modelo `Product`.**
    
    En el archivo del modelo `Product`, debes definir las propiedades que permiten la asignación masiva de los campos. Esto se hace con la propiedad `$fillable`:
    
    ```
    protected $fillable = ['name', 'description', 'price', 'stock'];
    ```
    
    ---
    
    ### 3. Crear el controlador *resource*
    
    7) **Genera el controlador de recursos.**
    
    Utiliza Artisan para crear un controlador de recursos. Esto generará automáticamente los métodos necesarios para manejar las operaciones CRUD. Los métodos a implementar serán: `index`, `create`, `store`, `show`, `edit`, `update` y `destroy`.
        
    ```
    php artisan make:controller ProductController --resource
    ```
    
    8) **Configura el controlador.**
    
    En el archivo generado `ProductController.php`, implementa la lógica necesaria para gestionar los productos. Recuerda que Laravel ya genera los métodos básicos (`index`, `create`, `store`, etc.), pero tendrás que completar la lógica específica de cada uno.
    
    - **`index`**: muestra todos los productos.
    - **`create`**: muestra el formulario para crear un nuevo producto.
    - **`store`**: guarda el nuevo producto en la base de datos.
    - **`show`**: muestra los detalles de un producto específico.
    - **`edit`**: muestra el formulario para editar un producto.
    - **`update`**: actualiza un producto existente en la base de datos.
    - **`destroy`**: elimina un producto de la base de datos.
    
    ---
    
    ### 4. Definir las rutas
    
    1) **Declara las rutas necesarias para el CRUD.**
    
    En `routes/web.php`, añade la ruta de tipo *resource* para el controlador `ProductController`. Laravel generará automáticamente todas las rutas necesarias.
    
    Agrega esta línea:
    
    ```
    //...
    use App\Http\Controllers\ProductController;

    //...
    Route::resource('products', ProductController::class);
    ```
    
    Esto creará las rutas necesarias para manejar las operaciones CRUD para el recurso `Product`.
    
    2)   **Verifica que las rutas se han registrado correctamente.**
    
    Usa el comando `php artisan route:list --path=products` para asegurarte de que las rutas están definidas correctamente.
    
    ---
    
    ### 5. Crear las vistas
    
    3)   **Crea la carpeta para las vistas.**
    
    En `resources/views`, crea una nueva carpeta llamada `products`. En esta carpeta crearás las vistas para las operaciones CRUD.

    **Vistas necesarias**:

    Crea las siguientes vistas dentro de la carpeta `product/`:

    4)   **`index.blade.php`**: Muestra todos los productos en una tabla. Esta vista debe listar todos los productos y permitir enlaces para crear, editar y eliminar productos.
   
    5)   **`create.blade.php`**: Formulario para crear un nuevo producto.
   
    6)   **`edit.blade.php`**: Formulario para editar un producto existente.
   
    7)   **`show.blade.php`**: Muestra los detalles de un producto.
    
    ---
    
    ### 6. Probar el CRUD completo
    
    8)   Accede a `/product` para ver el listado de productos.
   
    9)   Crea un nuevo producto y verifica que aparece en el listado.
   
    10) Edita un producto y confirma que se actualiza correctamente.
   
    11) Muestra los detalles de un producto desde el enlace.
   
    12) Elimina un producto y asegúrate de que desaparezca del listado.
    
    ---
    
    ### 7. CSS (opcional)
    
    Puedes crear un archivo CSS para la vista `index.blade.php` y otro para los formularios `create.blade.php`, `edit.blade.php` y `show.blade.php`. 

    > Recuerda:
    > 
    > Para mostrar archivos que tengamos en la carpeta `public`, crea (es conveniente) una carpeta dentro de nombre `assets/css` y aloja tu css allí mismo.
    > 
    > Además, en Laravel, podrás acceder a estos con la cláusula `asset`:
    > ```
    > <link rel="stylesheet" href="{{ asset('assets/css/style1.css') }}">
    > ```
    
    **Asegúrate de enlazar estos archivos CSS en las vistas correspondientes.**
    