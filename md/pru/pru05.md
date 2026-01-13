# 5. Creación de APIs REST en Laravel

## 1. Rutas API

<p style="float: left; margin: 0 1rem 1rem 0;">
  <img src="../../img/laravel.svg"
       alt="Actividad en el aula virtual"
       width="120">
</p>

*“Usamos un backend con API para que la aplicación no dependa de cómo se ve, <br/>sino de cómo funciona, y poder usar los datos desde cualquier sitio.”*

<br/>

### 1.1. ¿Qué es una API que devuelve JSON?

Es un backend (por ejemplo en Laravel, Java Spring, Node…) que:

- Recibe peticiones (GET, POST, PUT, DELETE).
- Procesa datos (BD, lógica de negocio).
- Devuelve respuestas en JSON, no HTML.

    Ejemplo:
    ```json
    {
    "id": 5,
    "nombre": "Juan",
    "nota": 8.5
    }
    ```

### 1.2. ¿Para qué sirve realmente?

1) **Separar frontend y backend (arquitectura moderna).**

El backend:

- Gestiona datos
- Aplica reglas de negocio
- Controla seguridad

El frontend:

- Se encarga solo de mostrar
- Puede ser Vue, React, móvil, etc.
- Cambias el frontend sin tocar el backend.

2) **Reutilizar el mismo backend.**

Una API JSON puede ser usada por:

- Web (Vue / React)
- App móvil
- Aplicación de escritorio
- Otro sistema externo

Una sola lógica, muchos clientes

3) **Comunicación estándar y ligera.**

JSON es:

- Ligero
- Fácil de leer
- Compatible con cualquier lenguaje

```js
fetch('/api/alumnos')
    .then(r => r.json())
    .then(data => console.log(data));
```

Ideal para aplicaciones web modernas

4) **Facilita el trabajo en equipo.**

- Backend devs → API
- Frontend devs → consumo de API

Cada parte puede avanzar en paralelo.

5) **Mejor seguridad.**

El backend:

- No expone la base de datos
- Controla permisos y roles
- Usa tokens (JWT, Sanctum, OAuth…)

El frontend nunca accede directamente a los datos.

6) **Escalabilidad y futuro.**

Hoy:

- Web con Vue

Mañana:

- App móvil
- Integración con otra empresa
- Microservicios

Si usas API JSON, ya estás preparado

#### 1.3. Comparación rápida

|Sin API (todo junto)  |	Con API JSON|
|----|----|
|HTML + PHP mezclado	|  Frontend + Backend separados|
|Poco reutilizable	|Muy reutilizable
|Difícil de escalar|	Escalable
|Acoplado|Modular

???teolaravel "Ejemplo de integración Laravel+Vue"
    **1) Esquema visual (arquitectura)**
    ```pgsql
    [ Navegador / Vue ]  ── HTTP (fetch/axios) ──>  [ API Laravel ]
        |                                             |
        |   pinta pantallas                           |  valida, aplica reglas
        |   botones, formularios                      |  consulta BD, permisos
        v                                             v
    UI / Componentes                           [ Base de datos ]
                   <─── JSON (datos) ────────
    ```
    Idea clave: Vue no habla con la BD. Vue habla con la API. La API habla con la BD.

    **2) Ejemplo mínimo Laravel + Vue**
   
    **Backend (Laravel) – ruta API que devuelve JSON**

    `routes/api.php`
    ```php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Api\StudentController;

    Route::get('/students', [StudentController::class, 'index']);
    ```
    `app/Http/Controllers/Api/StudentController.php`
    ```php
    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Student;

    class StudentController extends Controller
    {
        public function index()
        {
            // Devuelve datos en JSON (no HTML)
            return response()->json(
                Student::select('id','nombre','apellidos')->orderBy('apellidos')->get()
            );
        }
    }
    ```
    Si llamas a: GET `/api/students`

    Te devuelve:
    ```json
    [
        {"id":1,"nombre":"Ana","apellidos":"Gómez"},
        {"id":2,"nombre":"Luis","apellidos":"Martínez"}
    ]
    ```
    **Frontend (Vue) – consumir la API y pintar la lista**

    `StudentsList.vue`
    ```vue
    <script setup>
    import { ref, onMounted } from "vue";

    const students = ref([]);
    const loading = ref(true);
    const error = ref("");

    onMounted(async () => {
    try {
        const res = await fetch("/api/students"); // mismo dominio
        if (!res.ok) throw new Error("Error cargando alumnos");
        students.value = await res.json();
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
    });
    </script>

    <template>
    <div>
        <h2>Alumnado</h2>
        <p v-if="loading">Cargando...</p>
        <p v-else-if="error">{{ error }}</p>

        <ul v-else>
        <li v-for="s in students" :key="s.id">
            {{ s.apellidos }}, {{ s.nombre }}
        </li>
        </ul>
    </div>
    </template>
    ```
    Qué ganas aquí:

    - Laravel puede cambiar la BD, reglas, permisos… y Vue ni se entera mientras el JSON se mantenga.
    - Puedes hacer móvil u otro frontend usando la misma API.

    **3) Respuesta *tipo examen***

    Se usa un backend con una API que devuelve JSON para separar el cliente (frontend) de la lógica de negocio y el acceso a datos. 
    
    El backend se encarga de validar, aplicar permisos, consultar la base de datos y devolver respuestas estándar en JSON. 
    
    El frontend (Vue/React) consume esos datos mediante peticiones HTTP y se limita a representar la interfaz. Esto permite reutilizar el mismo backend para distintos clientes (web, móvil), facilita el trabajo en equipo, mejora la seguridad porque la base de datos no se expone, y hace la aplicación más escalable y mantenible.

En **Laravel 12**, a diferencia de versiones anteriores, el archivo `routes/api.php` **no viene incluido por defecto**. Laravel ahora permite habilitarlo opcionalmente para mantener la aplicación más ligera si no vas a construir una API.


### 1.4. Activar el sistema de rutas API

Para trabajar con rutas API, primero debemos ejecutar el siguiente comando Artisan para instalar:

#### (paso 1) Instalar api en Laravel
```bash
php artisan install:api 
```

Este comando:

* Crea automáticamente el archivo `routes/api.php`.
* Registra el archivo en el sistema de rutas.
* Aplica el middleware `api` a las rutas definidas allí.
* Añade el prefijo `/api` a todas las rutas de ese archivo.

En el proceso nos puede pedir crear una nueva migración para la tabla `api_tokens`, podemos decir que sí aunque de momento no la vamos a utilizar ya que no hemos visto la autenticación.

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest001.png"
                alt="Instalación soporte para rutas API"
                class="figure-img-highlight" 
                style="max-width: 60%; height: auto;" />
    <figcaption class="figure-caption-small">
            Instalación soporte para rutas API
    </figcaption>
</figure>
</div>

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest002.png"
                alt="Pregunta de creación de tabla `api-tokens`"
                class="figure-img-highlight" 
                style="max-width: 75%; height: auto;" />
    <figcaption class="figure-caption-small">
            Pregunta de creación de tabla `api-tokens`
    </figcaption>
</figure>
</div>

### 1.2. ¿Dónde se registra esta configuración?

Laravel configura los archivos de rutas en **`bootstrap/app.php`**. Una vez activadas, podrás ver una línea como esta:

#### (paso 2) Añadir linea de prefijo de api
```  hl_lines="3"
->withRouting(     
    api: __DIR__.'/../routes/api.php',     
    apiPrefix: 'api',     // ... 
) 
```

Laravel no añade este prefijo automáticamente, pero su valor por defecto es `api`. Por tanto añadir la línea **`apiPrefix: 'api',` **o no es equivalente. 

Se recomienda ponerla explícitamente para tener claro que las rutas de `api.php` van con el prefijo `/api`.


???teolaravel "Personalizar prefijo de rutas API"
    Si quieres cambiar el prefijo, por ejemplo a `api/admin`, puedes modificar la clave `apiPrefix`:
    ``` 
    ->withRouting(     
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api/admin', 
    ) 
    ```
    A partir de este momento, cualquier ruta definida en `routes/api.php` responderá a URLs que empiecen por `/api/` (o el prefijo que hayas definido).

!!!tip "Importante"

    El archivo `web.php` sigue existiendo por defecto y está pensado para rutas que devuelven vistas HTML. Por tanto, recuerda usar `api.php` exclusivamente para tu API REST.
    El `middleware` de laravel ya busca las rutas en el archivo correspondiente:

    * **`/api/...`** -> `routes/api.php`
    * `/...` -> `routes/web.php`



## 2. Definir Rutas de API

### 2.1. Ficheros de rutas y su organización

Laravel separa las rutas para aplicación web y API:

| Archivo | Propósito |
| --- | --- |
| `routes/web.php` | Rutas para vistas HTML (interfaz web) |
| `routes/api.php` | Rutas para responder en JSON (API REST) |

Estas rutas están cargadas desde el archivo `app/Providers/RouteServiceProvider.php`, que se encarga de:

* Aplicar el prefijo `/api` automáticamente a las rutas definidas en `api.php`.
* Asignar middleware `api`, que aplica limitaciones como throttling, formato JSON, etc.

!!!teolaravel "Ejemplo del prefijo automático (`routes/api.php`)"
    ``` 
    Route::get('/notes', function () {     
        return ['mensaje' => 'Esta es la API de notas']; 
    }); 
    ```

    Accediendo a `http://testear.test/api/notes`, obtendrás: `{ "mensaje": "Esta es la API de notas" }`

    <div class="figure-center">
    <figure>
        <img src="../../img/pru/laravel_apirest003.png"
                    alt="Respuesta GET /api/notes"
                    class="figure-img-highlight" 
                    style="max-width: 90%; height: auto;" />
        <figcaption class="figure-caption-small">
                Respuesta GET /api/notes
        </figcaption>
    </figure>
    </div>

Hay que fijarse que **no es necesario añadir `/api` en la ruta**, Laravel lo añade automáticamente. Pero sí hay que ponerlo en las peticiones.

---

## 3. Crear Controlador para la API


!!!tip "Crear migración y Modelo (Opcional)"
    Este punto solo se realizará **si todavía no tenemos creada la tabla de notas** `notes` en nuestra base de datos. 

    En caso de no tener la tabla creada, podemos crearla con el siguiente comando:

    **Crear migración para tabla notes**

    ```
    php artisan make:migration create_notes_table
    ```

    Esto generará un archivo de migración en `database/migrations` que podemos editar para definir la estructura de la tabla `notes`.

    Asegúrate de que la migración tenga el siguiente contenido:

    **Migración para la tabla notes**

    ``` 
    use Illuminate\Database\Migrations\Migration; 
    use Illuminate\Database\Schema\Blueprint; 
    use Illuminate\Support\Facades\Schema; 

    class CreateNotesTable extends Migration {     
        public function up()     {         
            Schema::create('notes', function (Blueprint $table) {             
                $table->id();             
                $table->string('title');             
                $table->text('description');             
                $table->date('date_at');             
                $table->boolean('done')->default(false);             
                $table->timestamps();         
            });     
        }      
                
        public function down()     {         
            Schema::dropIfExists('notes');     
        } 
    } 
    ```

    Después de editar la migración, ejecuta el siguiente comando para crear la tabla:

    **Migración**
    ```
    php artisan migrate
    ```

    Esto creará la tabla `notes` en tu base de datos.

    Podemos crear el modelo `Note` con el siguiente comando:

    **Crear Modelo**
    ```
    php artisan make:model Note
    ```

    Esto generará el modelo `Note` en `app/Models/Note.php`.

    **Modelo Note con $fillable**
    Asegúrate de que el modelo `Note` está correctamente definido con `$fillable`:
    ``` 
    class Note extends Model {     
        protected $fillable = ['title', 'description', 'date_at', 'done']; 
    } 
    ```

### 3.1. Generar un controlador API

Usamos el *flag* **`--api`** para generar un controlador que sólo incluye los métodos necesarios para una API CRUD:

#### (paso 3) Crear controlador API NoteController

```bash
php artisan make:controller Api/NoteController --api
```

Esto creará el archivo en `app/Http/Controllers/Api/NoteController.php` con los métodos: `index`, `store`, `show`, `update`, `destroy`.

#### (paso 4) Controlador NoteController

```
namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller; 
use App\Models\Note; 
use Illuminate\Http\JsonResponse; 
use Illuminate\Http\Request; 

class NoteController extends Controller {     
    public function index(): JsonResponse     {         
        // Listar todas las notas     
        }      
        
    public function show(Note $note): JsonResponse     {         
        // Mostrar una nota por ID     
        }      
            
    public function store(Request $request): JsonResponse     {         
        // Crear una nueva nota     
    }      
    
    public function update(Request $request, Note $note): JsonResponse     {         
        // Actualizar una nota existente     
    }      
    
    public function destroy(Note $note): JsonResponse     {         
        // Eliminar una nota     
    } 
} 
```

El controlador `NoteController` extiende de `Controller` y usa el modelo `Note` para interactuar con la base de datos.

### 3.2. Crear las rutas API para Notes

En `routes/api.php`:

#### (paso 5) Ruta resource para Notes

``` 
use App\Http\Controllers\Api\NoteController;  


Route::apiResource('notes', NoteController::class); 
```

🔝 Por seguridadd podemos utilizar **`only()`** para definir las rutas que queremos habilitar:

```
Route::apiResource('notes', NoteController::class)->only([
    'index', 'show', 'store', 'update', 'destroy'
]);
```

Esto generará automáticamente las rutas necesarias para el controlador `NoteController` usando el método `apiResource`. Laravel se encarga de crear las rutas RESTful para los métodos del controlador.

Esto define rutas como:

* GET `/api/notes`
* GET `/api/notes/{id}`
* POST `/api/notes`
* PUT/PATCH `/api/notes/{id}`
* DELETE `/api/notes/{id}`

#### (paso 6) Comprobar las rutas

```
php artisan route:list --path=api/notes
```

Resultado de `route:list`:

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest004.png"
                alt="Listado de rutas api"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            Listado de rutas api
    </figcaption>
</figure>
</div>

---


## 4. Códigos de estado HTTP en APIs

En una API REST, es importante devolver **códigos de estado HTTP apropiados** para indicar si la operación fue exitosa o si ocurrió un error.

A continuación, una tabla con los códigos más comunes y su uso recomendado:

| Código | Nombre | Cuándo se usa |
| --- | --- | --- |
| `200 OK` | Éxito | La petición fue exitosa (por ejemplo, GET, PUT, DELETE) |
| `201 Created` | Recurso creado | Se ha creado un nuevo recurso correctamente (por ejemplo, POST) |
| `204 No Content` | Sin contenido | La petición fue exitosa pero no se devuelve ningún contenido (opcional tras DELETE) |
| `400 Bad Request` | Petición incorrecta | Cuando el cliente envía datos inválidos |
| `401 Unauthorized` | No autorizado | Cuando el usuario no está autenticado |
| `403 Forbidden` | Prohibido | El usuario está autenticado pero no tiene permisos |
| `404 Not Found` | No encontrado | El recurso solicitado no existe |
| `422 Unprocessable Entity` | Entidad no procesable | Validaciones fallidas en los datos enviados |
| `500 Internal Server Error` | Error del servidor | Error inesperado en el servidor |

!!!info "¿Por qué usamos 200, 201 y 204 en este tema?"

    * Usamos **200 OK** en respuestas normales donde devolvemos datos (GET, PUT, DELETE).
    * Usamos **201 Created** al crear un recurso con POST para indicar que se creó exitosamente.
    * Usaríamos **204 No Content** si quisiéramos responder a un DELETE sin mensaje (aunque aquí devolvemos mensaje con 200).

---

## 5. Implementar métodos del controlador

Antes de implementar los métodos del controlador, asegúrate de importar las clases necesarias:

#### (paso 7) Importar clases en NoteController

``` 
use App\Models\Note; 
use Illuminate\Http\JsonResponse; 
use Illuminate\Http\Request;

//...
```

#### (paso 8) Método `index()` – Listar notas

``` 
public function index(): JsonResponse {     
    return response()->json([         
                            'success' => true,         
                            'data' => Note::all()     
                        ], 200); 
} 
```

#### (paso 9) Método `show()` – Mostrar una nota

``` 
public function show(Note $note): JsonResponse {     
    return response()->json([         
                        'success' => true,         
                        'data' => $note     
                        ], 200); 
}
```

#### (paso 10) Método `store()` – Crear una nota

``` 
public function store(Request $request): JsonResponse {     
    $note = Note::create($request->all());     
    return response()->json([         
                        'success' => true,         
                        'message' => 'Nota creada correctamente.',         
                        'data' => $note     
                        ], 201);
} 
```

#### (paso 11) *Método `update()` – Modificar nota

``` 
public function update(Request $request, Note $note): JsonResponse {     
    $note->update($request->all());     
    return response()->json([         
                        'success' => true,         
                        'message' => 'Nota actualizada correctamente.',         
                        'data' => $note     
                        ], 200); 
} 
```

#### (paso 12) Método `destroy()` – Eliminar nota
  
``` 
public function destroy(Note $note): JsonResponse {     
    $note->delete();    
    return response()->json([         
                        'success' => true,         
                        'message' => 'Nota eliminada correctamente.'     
                        ], 200); 
} 
```


### 5.6. Testing de la API

Para probar la API vamos a usar una extensión de *Visual Studio Code* llamada **`REST Client`** que permite hacer peticiones HTTP directamente desde el editor. 

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest005.png"
                alt="Extensión RestClient para Visual Studio Code"
                class="figure-img-highlight" 
                style="max-width: 55%; height: auto;" />
    <figcaption class="figure-caption-small">
            Extensión RestClient para Visual Studio Code
    </figcaption>
</figure>
</div>

También puedes usar herramientas como `Postman` o `Insomnia`.

#### (paso 13) Crear fichero `.rest` para pruebas

Para ello vamos a crear un archivo, por ejemplo, **`notes.rest`** en la raíz del proyecto.


### 5.7. Ejemplos de peticiones con REST Client

Para lanzar la petición, sitúate en la línea `GET ...` y pulsa el botón **`Send Request`** que aparece arriba de este.

=== "Mostrar listado de notas"
    ``` 
    ### 
    GET http://testear.test/api/notes
    ```
=== "Resultado"
    <div class="figure-center">
    <figure>
        <img src="../../img/pru/laravel_apirest_index.png"
                    alt="Petición GET y Respuesta GET"
                    class="figure-img-highlight" 
                    style="max-width: 95%; height: auto;" />
        <figcaption class="figure-caption-small">
                Petición GET y Respuesta GET
        </figcaption>
    </figure>
    </div>

---

=== "Mostrar nota con id 5"
    ```
    ### 
    GET http://testear.test/api/notes/5
    ```
=== "Resultado"
    <div class="figure-center">
    <figure>
        <img src="../../img/pru/laravel_apirest_show.png"
                    alt="Petición GET y Respuesta GET"
                    class="figure-img-highlight" 
                    style="max-width: 85%; height: auto;" />
        <figcaption class="figure-caption-small">
                Petición GET y Respuesta GET
        </figcaption>
    </figure>
    </div>

---

=== "Crear nueva nota"
    Plantilla:
    ```json
    ###
    METHOD http://url
    Content-Type: application/json

    {
    "key": "value"
    }
    ```
    Vamos a crear una nueva nota con el método `POST`:

    ``` json
    ###
    POST http://testear.test/api/notes
    Content-Type: application/json

    {
    "title": "Nueva Nota",
    "description": "Descripción de la nueva nota",
    "date_at": "2023-10-01",
    "done": false
    }
    ```

    En este código hay que observar varias cosas:

    1. **`###`** en la primera linea y pegado a la izquierda.
    2. La URL de la petición es **`http://testear.test/api/notes`**, que es la ruta que hemos definido en nuestro archivo de rutas.
    3. El método HTTP utilizado es **`POST`**, lo que indica que estamos creando un nuevo recurso.
    4. Hemos especificado la cabecera **`content-type: application/json`** para indicar que el cuerpo de la petición es un JSON.
    5. En el cuerpo de la petición, estamos enviando un objeto JSON con los datos de la nueva nota que queremos crear.
    6. Hemos dejado una línea en blanco entre las cabeceras y el cuerpo de la petición, que es obligatorio en HTTP.
=== "Resultado"
    <div class="figure-center">
    <figure>
        <img src="../../img/pru/laravel_apirest_store.png"
                    alt="Petición POST y Respuesta POST"
                    class="figure-img-highlight" />
        <figcaption class="figure-caption-small">
                Petición POST y Respuesta POST
        </figcaption>
    </figure>
    </div>

---

=== "Modificar nota con id 9"
    Ahora vamos a modificar la nota creada anteriormente con el método `PUT`. Pero antes necesitamos su ID, para ello repetimos la petición `GET` para listar todas las notas y ver el ID de la nota que acabamos de crear. En mi caso es la nota con ID 5.

    ```json
    ### 
    PUT http://testear.test/api/notes/9
    Content-Type: application/json

    {
    "title": "Nota Modificada con UPDATE",
    "description": "Descripción de la nota modificada",
    "date_at": "2023-10-01",
    "done": true
    }
    ```
    La estructura es similar a la petición `POST`, pero en este caso:
    
    - el método es **`PUT`** y 
    - la URL incluye el **ID** de la nota que queremos modificar.
=== "Resultado"
    <div class="figure-center">
    <figure>
        <img src="../../img/pru/laravel_apirest_update.png"
                    alt="Petición PUT y Respuesta PUT"
                    class="figure-img-highlight" />
        <figcaption class="figure-caption-small">
                Petición PUT y Respuesta PUT
        </figcaption>
    </figure>
    </div>

---

=== "Eliminar nota con id 9"
    ``` 
    ### 
    DELETE testear.test/api/notes/9
    ```
    La petición es muy sencilla, sólo necesitamos el método **`DELETE`** y la URL con el **ID** de la nota que queremos eliminar.


## 6. API Resources

En el siguiente apartado vamos a ver cómo mejorar la salida de los datos usando `API Resources`, podemos tomar el control del formato de los datos que devolvemos.

Laravel permite transformar la salida de tus APIs con clases `Resource` que te dan control sobre el formato.

### 6.1. Crear un API Resource

#### (paso 14) Crear NoteResource

```
php artisan make:resource NoteResource
```

Crea el archivo en `App\Http\Resources\NoteResource.php`

### 6.2. Personalizar la transformación

Vamos a modificar la salida de los datos en `NoteResource.php`. Por ejemplo, podemos cambiar los nombres de los campos y añadir un campo calculado `estado` que indique si la nota está completada o pendiente:

#### (paso 15) Ejemplo de transformación en `NoteResource`

``` 
public function toArray($request) {     
    return [         
        'id' => $this->id,         
        'titulo' => $this->title,         
        'descripcion' => $this->description,         
        'fecha' => $this->date_at,         
        'estado' => $this->done ? 'Completada' : 'Pendiente'     
        ]; 
} 
```

### 6.3. Usar el recurso en el controlador

Añadimos la importación al controlador `NoteController`:

#### (paso 16) Importar NoteResource en `NoteController`

``` 
use App\Http\Resources\NoteResource; 

//...
```



#### (paso 17) Devolver colección con estado y mensaje

Modificamos el método `index()` para devolver una colección de `NoteResource`:
``` 
return response()->json([     
                    'success' => true,     
                    'data' => NoteResource::collection(Note::all()) 
                    ], 200); 
```

De esta manera, la respuesta incluirá el estado y el mensaje de éxito. Vamos a comprobarlo con una petición `GET` a `/api/notes`:

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest009.png"
                alt="Respuesta de index con `NoteResource`"
                class="figure-img-highlight" 
                style="max-width: 85%; height: auto;" />
    <figcaption class="figure-caption-small">
            Respuesta de index con `NoteResource`
    </figcaption>
</figure>
</div>


En los demás métodos no devolvemos una colección sino un solo elemento. 

#### (paso 18) Devolver una sola nota con `NoteResource`

Por ejemplo para el método `show()` podemos hacer lo siguiente:
``` 
public function show(Note $note): JsonResponse {     
    return response()->json([         
                        'success' => true,         
                        'data' => new NoteResource($note)     
                        ], 200); 
} 
```

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest010.png"
                alt="Respuesta de show con `NoteResource`"
                class="figure-img-highlight" 
                style="max-width: 85%; height: auto;" />
    <figcaption class="figure-caption-small">
            Respuesta de show con `NoteResource`
    </figcaption>
</figure>
</div>

Ahora sería aplicable a todos los métodos que devuelven un solo elemento, como `store()`y `update()`. Al método `destroy()` no se pondrá, puesto que no devuelve los datos de la nota eliminada.

---

## 7. Validación de los datos

Al igual que en los formularios, es importante validar los datos que recibimos en la API. `Laravel` ofrece un sistema de validación muy potente. Empezaremos por validar los datos en el método `store()` y `update()`. Para ello vamos a crear la clase `NoteRequest`:

!!!tip "Eliminar NoteRequest"

    En la práctica anterior creamos la clase `NoteRequest` para validar los datos del formulario. Si ya la tienes creada, elimínala para evitar conflictos.

#### (paso 19) Crear `NoteRequest`

```
php artisan make:request NoteRequest
```

Esto generará el archivo en `app/Http/Requests/NoteRequest.php`.

#### (paso 20) Clase `NoteRequest`

``` 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest; 
use Illuminate\Contracts\Validation\Validator; 
use Illuminate\Http\Exceptions\HttpResponseException; 
use Illuminate\Validation\ValidationException;  

class NoteRequest extends FormRequest {     
    public function authorize(): bool     {         
        return true;     
    }      
    
    public function rules(): array     {         
        return [             
                'title' => 'required|string|max:255',             
                'description' => 'required|string',             
                'date_at' => 'required|date',             
                'done' => 'sometimes|boolean'         
        ];     
    }  
} 
```

Esta función al igual que en los formularios, define las reglas de validación. En este caso:

* `title`: requerido, cadena de texto, máximo 255 caracteres.
* `description`: requerido, cadena de texto.
* `date_at`: requerido, debe ser una fecha válida.
* `done`: algunas veces, booleano.

En caso de error en la validación, Laravel devolverá automáticamente un **error 422 Unprocessable Entity** (salidaciones fallidas en los datos enviados) con los detalles del error.

???+teolaravel "Ejemplo de error de validación"
    ``` 
    {    
        "message": "The given data was invalid.",     
        "errors": {         
            "title": [             
                        "The title field is required."         
            ]     
        } 
    } 
    ```

#### (paso 21) Lanzar los mensajes en castellano

Si queremos que nuestros mensajes de error de validación sean devueltos en castellano, recuerda el punto `2.5. Traducir los mensajes de error` del apartado anterior.

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest_validacion1.png"
                alt="Respuesta de show con `NoteResource`"
                class="figure-img-highlight" 
                style="max-width: 90%; height: auto;" />
    <figcaption class="figure-caption-small">
            Error en validación del campo `description`, pero con caracteres especiales escapados.
    </figcaption>
</figure>
</div>

**Parámetros JSON**

En los parámetros de `json()` podemos añadir el tercer parámetro **`JSON_UNESCAPED_UNICODE`** para evitar que los caracteres especiales se escapen. Esto es útil si estás trabajando con caracteres no ASCII. Si no los acentos y caracteres especiales se escaparán y no se verán correctamente en la respuesta.

Deberemos añadir el método **`failedValidation`** en nuestro `NoteRequest` (acuérdate de importar las clases pertinentes):

``` hl_lines="11"
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

protected function failedValidation(Validator $validator)
{
    throw new HttpResponseException(
        response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors'  => $validator->errors(),
        ], 422, [], JSON_UNESCAPED_UNICODE)
    );
}
```
<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest_validacion2.png"
                alt="Respuesta de show con `NoteResource`"
                class="figure-img-highlight" 
                style="max-width: 90%; height: auto;" />
    <figcaption class="figure-caption-small">
            Error en validación del campo `description`, con caracteres especiales NO escapados.
    </figcaption>
</figure>
</div>

Ahora para que las validaciones funcionen debemos ajustar los métodos `store()` y `update()` del controlador para usar `NoteRequest` en lugar de `Request`:

#### (paso 22) Usar NoteRequest en `NoteController`

``` 
use App\Http\Requests\NoteRequest; 
```

``` 
public function store(NoteRequest $request): JsonResponse {     
    $note = Note::create($request->validated());     
    return response()->json([         
        'success' => true,         
        'message' => 'Nota creada correctamente.',         
        'data' => new NoteResource($note)     
    ], 201); 
}  
        
public function update(NoteRequest $request, Note $note): JsonResponse {     
    $note->update($request->validated());     
    return response()->json([         
        'success' => true,         
        'message' => 'Nota actualizada correctamente.',         
        'data' => new NoteResource($note)     
    ], 200); 
} 
```

Se puede observar en los dos métodos que hemos cambiado `$request->all()` por **`$request->validated()`**. Esto asegura que sólo los datos que pasaron la validación se usan para crear o actualizar la nota.

Por ejemplo, imaginemos que tenemos la siguiente validación, una clase que solo admite los campos `name` y `email`:

???+teolaravel "Ejemplo"
    ``` 
    class StoreUserRequest extends FormRequest {     
        public function rules()     {         
            return [             
                    'name'  => ['required', 'string'],            
                    'email' => ['required', 'email'],         
            ];     
        } 
    } 
    ```

    Ahora el cliente envía el siguiente JSON:

    ```json 
    {     
        "name": "John Doe",     
        "email": "john.doe@example.com",     
        "role": "admin" 
    } 
    ```

    El campo `role` no está definido en las reglas de validación, por lo que será ignorado cuando usemos `$request->validated()`. Esto ayuda a prevenir la asignación masiva de campos no deseados.

En resumen:

``` 
$request->validated(); 
// => ['name' => 'John Doe', 'email' => 'john.doe@example.com'] 


$request->all(); 
// => ['name' => 'John Doe', 'email' => 'john.doe@example.com', 'role' => 'admin'] 
```

Recordemos que si `role` no está en `$fillable` en el modelo, no se asignará de todas formas. Pero es una buena práctica usar `validated()` para asegurarnos de que sólo los datos permitidos se procesan.


## 8. Control de errores en la API con `findOrFail`

Ahora vamos a añadir un punto a tu tema para controlar el error cuando no se encuentra una nota en la base de datos, utilizando primero el método `findOrFail` de Laravel y analizando el resultado. Después implementaremos el bloque `try-catch` para una solución más robusta.

Cuando construimos una API REST en Laravel, es importante asegurarnos de que las respuestas a las solicitudes, especialmente las solicitudes AJAX, siempre sean en formato JSON, incluso cuando se produce un error, como intentar acceder a un recurso que no existe.

### 8.1. Usando `findOrFail` para manejar errores

Laravel proporciona el método **`findOrFail`** para buscar un modelo en la base de datos por su ID. Si el modelo no existe, Laravel automáticamente lanza una excepción **`ModelNotFoundException`**, que puedes manejar para devolver una respuesta adecuada sin que se genere un error en formato HTML.

#### 8.1.1. Ejemplo básico con `findOrFail`

En el siguiente ejemplo, la función `show` intenta obtener una nota por su ID usando `findOrFail`. Si la nota no se encuentra, Laravel devolverá automáticamente un error **404 Not Found** con una respuesta en formato JSON.

???+teolaravel "Ejemplo de uso de `findOrFail`"

    ``` 
    public function show(string $id): JsonResponse 
    {     
        // Usamos findOrFail para intentar encontrar la nota por ID     
        $note = Note::findOrFail($id);      
        
        // Si la nota existe, la devolvemos en formato JSON con código de estado 200     
        return response()->json([         
                                'success' => true,         
                                'data' => new NoteResource($note)     
        ], 200); 
    } 
    ```

**¿Qué sucede cuando la nota no existe?**

Cuando la solicitud se realiza con un ID que no existe en la base de datos, Laravel lanzará una excepción `ModelNotFoundException`. Si la solicitud es realizada mediante AJAX, Laravel automáticamente enviará una respuesta con el código de estado **404 Not Found** y un error en formato HTML, lo que no es adecuado si la solicitud espera un **JSON**.

**¿Cómo manejarlo con el manejador de excepciones de Laravel?**

Laravel ya está configurado para manejar automáticamente las excepciones de modelo no encontrado, pero esto genera una respuesta HTML. En lugar de manejar la excepción directamente, una mejor opción sería asegurarnos de que la respuesta siempre esté en formato JSON, para evitar que la aplicación devuelva una página de error en HTML.

### 8.2. Solución definitiva: usando `try-catch`

Para manejar de forma más controlada los errores y asegurarnos de que siempre devolvemos una respuesta en formato JSON, podemos usar el bloque **`try-catch`**. Esto nos permitirá interceptar la excepción y devolver una respuesta personalizada con un mensaje claro en formato JSON.

**Código con `try-catch`**:

``` 
public function show(string $id): JsonResponse {     
    try {         
        // Intentamos obtener la nota con findOrFail        
        $note = Note::findOrFail($id);          
        
        // Si la nota existe, devolvemos la respuesta en JSON         
        return response()->json([             
                                'success' => true,             
                                'data' => new NoteResource($note)         
        ], 200);   

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {         
        // Si no se encuentra la nota, devolvemos un error 404 en formato JSON         
        return response()->json([             
                                'success' => false,             
                                'message' => 'Note not found'         
        ], 404);     
    } 
} 
```

**¿Qué cambia con `try-catch`?**

1. **Manejo explícito del error**: Al utilizar `try-catch`, puedes controlar el flujo de la aplicación cuando ocurre un error, y devolver un mensaje de error personalizado en formato JSON.
2. **Mensaje claro en JSON**: Si la nota no se encuentra, el bloque `catch` captura la excepción y devuelve una respuesta JSON con un mensaje de error claro, en lugar de una página HTML de error.
3. **Código de estado HTTP adecuado**: Se asegura de que el código de estado HTTP sea **404 Not Found** cuando no se encuentra el recurso.

### Resumen de las soluciones

* **Usando `findOrFail`**: Laravel maneja automáticamente el error, pero devuelve una respuesta HTML que puede no ser ideal cuando se realiza una solicitud AJAX.
* **Usando `try-catch`**: Permite manejar el error de manera más controlada y devolver una respuesta JSON, asegurando que la aplicación no devuelva errores HTML en solicitudes AJAX.

Con esto, puedes manejar de manera efectiva los casos en los que un recurso no existe y asegurarte de que la respuesta de tu API esté siempre en el formato esperado.

Ahora falta extender este control de errores a los demás métodos del controlador (`update`, `destroy`) donde también se utiliza `findOrFail` para obtener la nota por ID.

## 9. Conclusiones

* Las APIs REST son ideales para aplicaciones SPA, móviles o integraciones.
* Laravel permite definir rutas específicas para API con prefijos automáticos y middleware personalizado.
* Los controladores API están enfocados a respuestas JSON.
* `ApiResource` permite estructurar y controlar la salida de tus datos.


???praclaravel "Práctica a entregar"

    ### Objetivo de la actividad

    <p style="float: left; margin: 0 1rem 1rem 0;">
        <img src="../../img/laraveltask.svg"
            alt="Actividad en el aula virtual"
            width="150">
    </p>
    
    **Validaciones y Mensajes en el CRUD de Productos**
    
    En esta práctica vas a aplicar todo lo aprendido sobre **Laravel y la creación de APIs REST** para desarrollar un servicio completo que gestione productos (*Products*).

    Al finalizar, habrás implementado todos los elementos esenciales de una API moderna:
    
    * Definición de rutas API (`api.php`).
    * Creación de un controlador con métodos CRUD (`index`, `show`, `store`, `update`, `destroy`).
    * Uso de modelos y migraciones.
    * Validación de datos mediante *Form Request*.
    * Personalización de la salida con *API Resources*.
    * Pruebas de los endpoints con *Postman* o *REST Client*.
    
    ---
    
    ### Instrucciones
    
    Sigue los pasos **en orden**, comprobando el funcionamiento de cada parte antes de pasar a la siguiente.
    Puedes basarte en el ejemplo del tema anterior sobre *Notes*, adaptándolo al nuevo recurso *Product*. O continuar con el proyecto de las prácticas anteriores que ya trata sobre productos.
    
    ---
    
    ### 1. Preparar el entorno
    
    1) Abre el proyecto de Laravel que has elegido.
   
    2) Asegúrate de tener las rutas API activadas. Si no lo hiciste antes, ejecuta:
    
       ```
       php artisan install:api
       ```

    3) Encender servicios:
        
    - opción 1) Con **Docker**: verifica que los contenedores de Docker estén funcionando:
    ```
    docker compose up -d
    ```
       
    - opción 2) Con **Laragon**: comprueba que esté en marcha los servidores Nginx y Mysql.
    
    ---
    
    ### 2. Crear la tabla y el modelo de productos (opcional)
    
    Si **no** tienes todavía creada la tabla `products`:
    
    1) Crea una nueva migración y el modelo asociado. Si ya lo tienes continúa con el siguiente paso.
    
    - Genera la migración para la tabla `products`:
    ```
    php artisan make:migration create_products_table
    ```
    
    - Edita la migración para que incluya los siguientes campos:
    ```
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->decimal('price', 8, 2);
    $table->integer('stock');
    $table->timestamps();
    ```
    
    - Ejecuta las migraciones:
    ```
    php artisan migrate
    ```
    
    - Crea el modelo `Product`:
    ```
    php artisan make:model Product
    ```
    
    - Define en el modelo los campos permitidos para asignación masiva:
    ```
    protected $fillable = ['name', 'description', 'price', 'stock'];
    ```
    
    ---
    
    ### 3. Crear el controlador API
    
    1) Crea el controlador `ProductController` en el espacio de nombres `api`.
   
    2) Implementa en él los cinco métodos principales (`index`, `store`, `show`, `update`, `destroy`) para manejar el CRUD. Puedes basarte en el ejemplo del recurso `Note` del tema.
    
    ---
    
    ### 4. Definir las rutas
    
    3) Crea las rutas para manejar los productos (utiliza `apiResource`).
   
    4) Comprueba que se hayan creado las rutas con:
    ```
    php artisan route:list --path=api/products
    ```
    
    ---
    
    ### 5. Crear el *API Resource*
    
    5)  Genera la clase `ProductResource`. Esta clase te permitirá personalizar la estructura de los datos JSON que devuelve la API.
    
    Un ejemplo de estructura sería:
    ```json
    {
        "id": 1,
        "nombre": "Camiseta",
        "precio": "$19.99",
        "stock": 25,
        "descripcion": "Camiseta de algodón"
    }
    ```
    
    6)  Usa esta clase en el controlador para las respuestas JSON.
    
    ---
    
    ### 6. Validar los datos con una clase *Form Request*
    
    7)  Crea la clase `ProductRequest` para validar los datos de entrada al crear o actualizar un producto. Las reglas de validación podrían ser:
    
       * `name`: obligatorio, cadena de texto, mínimo 3 y máximo 255 caracteres.
       * `description`: obligatorio, cadena de texto, mínimo 10 caracteres.
       * `price`: obligatorio, numérico, mínimo 0, máximo 9999.99.
       * `stock`: obligatorio, entero, mínimo 0, máximo 10000.
    
    8)  Modifica los métodos `store()` y `update()` del controlador para usar esta clase.
   
    9)  Introduce el método `failedValidation` en la clase `ProductRequest` y los cambios en `resources/lang/es/validation` para que los errores de validación se muestren en castellano.
    
    ---
    
    ### 7. Probar la API
    
    10) Crea un archivo `products.rest` en la raíz del proyecto (o usa *Postman*).
   
    11) Escribe las peticiones para probar todos los *endpoints*:
    
       * GET `/api/products` → Listar todos los productos
       * POST `/api/products` → Crear un nuevo producto
       * GET `/api/products/{id}` → Mostrar un producto
       * PUT `/api/products/{id}` → Modificar un producto
       * DELETE `/api/products/{id}` → Eliminar un producto

    12) Escribe una petición que devuelva un error de validación (por ejemplo: precio negativo o stock como un string).
    13) Comprueba que todas las operaciones devuelven los **códigos HTTP correctos** (`200`, `201`, `204`, etc.) y que la respuesta JSON tiene el formato definido en `ProductResource`.


