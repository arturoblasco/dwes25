# Creación de APIs REST en Laravel

## Rutas API

<p style="float: left; margin-left: 1rem;">
  <img src="../../img/laravelactividad.png"
       alt="Actividad en el aula virtual"
       width="100">
</p>

En **Laravel 12**, a diferencia de versiones anteriores, el archivo `routes/api.php` **no viene incluido por defecto**. Laravel ahora permite habilitarlo opcionalmente para mantener la aplicación más ligera si no vas a construir una API.



### Activar el sistema de rutas API

Para trabajar con rutas API, primero debemos ejecutar el siguiente comando Artisan:

???+examplelaravel "Instalar soporte de rutas API"
    ``` 
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
    <img src="../../img/pru/laravel_apirest005.png"
                alt="Instalación soporte para rutas API"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            Instalación soporte para rutas API
    </figcaption>
</figure>
</div>

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest006.png"
                alt="Pregunta de creación de tabla `api-tokens`"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            Pregunta de creación de tabla `api-tokens`
    </figcaption>
</figure>
</div>

### ¿Dónde se registra esta configuración?

Laravel configura los archivos de rutas en `bootstrap/app.php`. Una vez activadas, podrás ver una línea como esta:

**Registro de rutas API con prefijo**
``` 
->withRouting(     
    api: __DIR__.'/../routes/api.php',     
    apiPrefix: 'api',     // ... 
) 
```

!!!tip "Parámetro `apiPrefix`"

    Laravel no añade este prefijo automáticamente, pero su valor por defecto es `api`. Por tanto añadir esta línea o no es equivalente. Pero yo recomiendo ponerla explícitamente para tener claro que las rutas de `api.php` van con el prefijo `/api`.


???info "Personalizar prefijo de rutas API"
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

    * `/api/...` -> `routes/api.php`
    * `/...` -> `routes/web.php`

---

## Introducción

Una **API** (Application Programming Interface) permite a aplicaciones diferentes comunicarse entre sí, intercambiando datos en formatos como **JSON**. Las APIs REST usan los verbos HTTP (GET, POST, PUT, DELETE) para definir operaciones sobre recursos.

Laravel ofrece todas las herramientas necesarias para construir APIs modernas, organizadas y seguras. En este tema construiremos paso a paso una API para el recurso `Note`, que ya conocemos de los temas anteriores.

---

## Definir Rutas de API

### Ficheros de rutas y su organización

Laravel separa las rutas para aplicación web y API:

| Archivo | Propósito |
| --- | --- |
| `routes/web.php` | Rutas para vistas HTML (interfaz web) |
| `routes/api.php` | Rutas para responder en JSON (API REST) |

Estas rutas están cargadas desde el archivo `app/Providers/RouteServiceProvider.php`, que se encarga de:

* Aplicar el prefijo `/api` automáticamente a las rutas definidas en `api.php`.
* Asignar middleware `api`, que aplica limitaciones como throttling, formato JSON, etc.

!!!info "Ejemplo del prefijo automático (`routes/api.php`)"
    ``` 
    Route::get('/notes', function () {     
        return ['mensaje' => 'Esta es la API de notas']; 
    }); 
    ```

    Accediendo a `http://localhost:8000/api/notes`, obtendrás: `{ "mensaje": "Esta es la API de notas" }`

Hay que fijarse que **no es necesario añadir `/api` en la ruta**, Laravel lo añade automáticamente. Pero sí hay que ponerlo en las peticiones.

---

## Crear Controlador para la API

Antes de generar el controlador asegurarnos que tenemos create la table de notas `notes` en nuestra base de datos. En caso de no tenerla, podemos crearla con el siguiente comando:

???+examplelaravel "Crear migración para tabla notes"

    ```
    php artisan make:migration create_notes_table
    ```

Esto generará un archivo de migración en `database/migrations` que podemos editar para definir la estructura de la tabla `notes`.

Asegúrate de que la migración tenga el siguiente contenido:

???+examplelaravel "Migración para la tabla notes"

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
                $table->date('date');             
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

???+examplelaravel "Migración"
    ```
    php artisan migrate
    ```

Esto creará la tabla `notes` en tu base de datos.

Podemos crear el modelo `Note` con el siguiente comando:

???+examplelaravel "Crear Modelo"
    ```
    php artisan make:model Note
    ```

Esto generará el modelo `Note` en `app/Models/Note.php`.

### Generar un controlador API

Usamos el flag `--api` para generar un controlador que sólo incluye los métodos necesarios para una API CRUD:

???+examplelaravel "Crear controlador API NoteController"

    ```
    php artisan make:controller Api/NoteController --api
    ```

Esto creará el archivo en `app/Http/Controllers/Api/NoteController.php` con los métodos: `index`, `store`, `show`, `update`, `destroy`.

???+examplelaravel "Controlador NoteController"

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

### Crear las rutas API para Notes

En `routes/api.php`:

???+examplelaravel "Ruta resource para Notes"

    ``` 
    use App\Http\Controllers\Api\NoteController;  


    Route::apiResource('notes', NoteController::class); 
    ```

    🔝 Por seguridadd podemos utilizar only() para definir las rutas que queremos habilitar:

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

???+examplelaravel "Comprobar las rutas"

    ```
    php artisan route:list --path=api/notes
    ```

Resultado de `route:list`:

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest007.png"
                alt="Listado de rutas api"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            Listado de rutas api
    </figcaption>
</figure>
</div>
---

## Implementar el CRUD API para Notes

### Modelo Note

Asegúrate de que el modelo `Note` está correctamente definido con `$fillable`:

???+examplelaravel "Modelo Note con fillable"

    ``` 
    class Note extends Model {     
        protected $fillable = ['title', 'description', 'date', 'done']; 
    } 
    ```

---

## Códigos de Estado HTTP en APIs

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

## Implementar métodos del controlador

Antes de implementar los métodos del controlador, asegúrate de importar las clases necesarias:

!!!examplelaravel "Importar clases en NoteController"

    ``` 
    use App\Models\Note; 
    use Illuminate\Http\JsonResponse; 
    use Illuminate\Http\Request;
    ```

### Método `index()` – Listar notas

!!!examplelaravel "Listar todas las notas"

    ``` 
    public function index(): JsonResponse {     
        return response()->json([         
                                'success' => true,         
                                'data' => Note::all()     
                            ], 200); 
    } 
    ```

### Método `show()` – Mostrar una nota

!!!examplelaravel "Mostrar una nota por ID"

    ``` 
    public function show(Note $note): JsonResponse {     
        return response()->json([         
                            'success' => true,         
                            'data' => $note     
                            ], 200); 
    }
    ```

### Método `store()` – Crear una nota

!!!examplelaravel "Guardar nueva nota"

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

### Método `update()` – Modificar nota

!!!examplelaravel "Actualizar una nota"

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

### Método `destroy()` – Eliminar nota

!!!examplelaravel "Eliminar una nota"

    ``` public function destroy(Note $note): JsonResponse {     
    $note->delete();    
    return response()->json([         
                        'success' => true,         
                        'message' => 'Nota eliminada correctamente.'     
                        ], 200); 
    } 
    ```

---

### Testing de la API

Para probar la API vamos a usar una extensión de *Code* *llamada `REST Client`* que permite hacer peticiones HTTP directamente desde el editor. También puedes usar herramientas como `Postman` o `Insomnia`.

???+examplelaravel "Testear la API"
    Para ello vamos a crear un archivo **`notes.rest`** en la raíz del proyecto, por ejemplo.

Vamos a escribir nuestra primera petición para listar todas las notas:

**Peticiones REST Client**

**Mostrar listado de notas**
``` 
### 
GET http://testear.test/api/note
```
Para lanzar la petición, sitúate en la línea `GET ...` y pulsa el botón `Send Request` que aparece encima.

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest001.png"
                alt="Petición GET y Respuesta GET"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            Petición GET y Respuesta GET
    </figcaption>
</figure>
</div>



**Crear nueva nota**
Vamos a crear una nueva nota con el método `POST`:
``` 
### 
POST http://testear.test/api/note

HTTP/1.1 content-type: application/json  {     
    "title": "Nueva Nota",     
    "description": "Descripción de la nueva nota",     
    "date_at": "2023-10-01",     
    "done": false 
}
```

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest002.png"
                alt="Petición POST y Respuesta POST"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            Petición POST y Respuesta POST
    </figcaption>
</figure>
</div>


En este código hay que observar varias cosas:

1. La URL de la petición es `http://testear.test/api/note`, que es la ruta que hemos definido en nuestro archivo de rutas.
2. El método HTTP utilizado es `POST`, lo que indica que estamos creando un nuevo recurso.
3. Hemos especificado la cabecera `content-type: application/json` para indicar que el cuerpo de la petición es un JSON.
4. En el cuerpo de la petición, estamos enviando un objeto JSON con los datos de la nueva nota que queremos crear.
5. Hemos dejado una línea en blanco entre las cabeceras y el cuerpo de la petición, que es obligatorio en HTTP.

Ahora vamos a modificar la nota creada anteriormente con el método `PUT`. Pero antes necesitamos su ID, para ello repetimos la petición `GET` para listar todas las notas y ver el ID de la nota que acabamos de crear. En mi caso es la nota con ID 5.

**Modificar nota con ID 9**

``` 
### 
PUT http://testear.test/api/note/9

HTTP/1.1 content-type: application/json  {     
    "id": 9,     
    "title": "Nota Modificada",     
    "description": "Descripción de la nota modificada",     
    "date_at": "2023-10-01",     
    "done": true 
} 
```
La estructura es similar a la petición `POST`, pero en este caso el método es `PUT` y la URL incluye el ID de la nota que queremos modificar.

<div class="figure-center">
<figure>
    <img src="../../img/pru/laravel_apirest003.png"
                alt="Petición PUT y Respuesta PUT"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            Petición PUT y Respuesta PUT
    </figcaption>
</figure>
</div>




Finalmente vamos a eliminar la nota con ID 5 usando el método `DELETE`:

**Eliminar nota con ID 9**

``` 
### 
DELETE testear.test/api/note/9
```

La petición es muy sencilla, sólo necesitamos el método `DELETE` y la URL con el ID de la nota que queremos eliminar.

Con esto hemos probado todas las operaciones CRUD de nuestra API REST para el recurso `Note`. En el siguiente apartado vamos a ver cómo mejorar la salida de los datos usando `API Resources`, podemos tomar el control del formato de los datos que devolvemos.

## API Resources

Laravel permite transformar la salida de tus APIs con clases Resource que te dan control sobre el formato.

### Crear un API Resource

???+examplelaravel "Crear NoteResource"

    ```
    php artisan make:resource NoteResource
    ```

Crea el archivo en `App\Http\Resources\NoteResource.php`

### Personalizar la transformación

Vamos a modificar la salida de los datos en `NoteResource.php`. Por ejemplo, podemos cambiar los nombres de los campos y añadir un campo calculado `estado` que indique si la nota está completada o pendiente:

???+examplelaravel "Ejemplo de transformación en `NoteResource`"

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

### Usar el recurso en el controlador

Añadimos la importación al controlador `NoteController`:

???+examplelaravel "Importar NoteResource en `NoteController`"

    ``` 
    use App\Http\Resources\NoteResource; 
    ```

Modificamos el método `index()` para devolver una colección de `NoteResource`:

???+examplelaravel "Devolver colección con estado y mensaje"

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
                alt="Respuesta GET con `NoteResource`"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            Respuesta GET con `NoteResource`
    </figcaption>
</figure>
</div>

En los demás métodos no devolvemos una colección sino un solo elemento. Por ello por ejemplo para el método `show()` podemos hacer lo siguiente:

???+examplelaravel "Devolver una sola nota con `NoteResource`"

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
                alt="Respuesta GET /api/notes/{id} con `NoteResource`"
                class="figure-img-highlight" />
    <figcaption class="figure-caption-small">
            Respuesta GET /api/notes/{id} con `NoteResource`
    </figcaption>
</figure>
</div>

Ahora sería aplicable a todos los métodos que devuelven un solo elemento, como `store()`y `update()`. Al `destroy()` no puesto que no devuelve los datos de la nota eliminada.

---

## Validación de los datos

Al igual que en los formularios, es importante validar los datos que recibimos en la API. `Laravel` ofrece un sistema de validación muy potente. Empezaremos por validar los datos en el método `store()` y `update()`. Para ello vamos a crear la clase `NoteRequest`:

!!!tip "Eliminar NoteRequest"

    En la práctica anterior creamos la clase `NoteRequest` para validar los datos del formulario. Si la tienes creada, elimínala para evitar conflictos.

???+examplelaravel "Crear `NoteRequest`"

    ```
    php artisan make:request NoteRequest
    ```

Esto generará el archivo en `app/Http/Requests/NoteRequest.php`.

???+examplelaravel "Clase `NoteRequest`"

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
                    'done' => 'boolean'         
            ];     
        }  
    } 
    ```

Esta función al igual que en los formularios, define las reglas de validación. En este caso:

* `title`: requerido, cadena de texto, máximo 255 caracteres.
* `description`: requerido, cadena de texto.
* `date_at`: requerido, debe ser una fecha válida.
* `done`: booleano (opcional).

En caso de error en la validación, Laravel devolverá automáticamente un error 422 con los detalles del error.

Ejemplo de error de validación

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

Podemos personalizar la respuesta de error en el método `failedValidation()` en la clase `NoteRequest`:

Debemos añadir las importaciones necesarias al principio del archivo:

Importar clases en NoteRequest

``` 
use Illuminate\Contracts\Validation\Validator; 
use Illuminate\Http\Exceptions\HttpResponseException; 
use Illuminate\Validation\ValidationException; 
```

Personalizar la respuesta de error

``` 
protected function failedValidation(Validator $validator) {     
    throw new HttpResponseException(response()->json([         
        'success' => false,         
        'message' => 'Error de validación',         
        'errors' => $validator->errors()     
    ], 422, [], JSON_UNESCAPED_UNICODE)); 
} 
```

parámetros JSON

En los parámetros de `json()` podemos añadir el tercer parámetro `JSON_UNESCAPED_UNICODE` para evitar que los caracteres especiales se escapen. Esto es útil si estás trabajando con caracteres no ASCII. Sino los acentos y caracteres especiales se escaparán y no se verán correctamente en la respuesta.

Ahora para quelas validaciones funcionen debemos ajustar los métodos `store()` y `update()` del controlador para usar `NoteRequest` en lugar de `Request`:

???+examplelaravel "Usar NoteRequest en `NoteController`"

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

## Ejemplos de peticiones

Si todo ha ido bien aquí tenemos una API REST completa para el recurso `Note` que podemos probar con herramientas como `Postman` o `RestClient`:

Ejemplo uso de `api`con API **RestClient**:

=== "GET api/note"
    ```
    ### listar todas las notas
    GET http://testear.test/api/note
    ```

=== "POST api/note"
    ```
    ### Creamos la primera nota
    POST http://testear.test/api/note HTTP/1.1
    content-type: application/json

    {
        "title": "First Note",
        "description": "This is the first note",
        "date": "2023-10-01",
        "done": false
    }
    ```

=== "PUT api/note/{id}"
    ```
    ### Modificamos la primera nota
    PUT http://testear.test/api/note/1 HTTP/1.1
    content-type: application/json

    {
        "id": 1,
        "title": "First Note",
        "description": "This is the first note, modified",
        "date": "2023-10-01",
        "done": true
    }
    ```

=== "GET api/note/{id}"
    ```
    ### Mostrar la nota con id 1
    GET http://testear.test/api/note/1
    ```

=== "DELETE api/note/{id}"
    ```
    ### Eliminar la nota con id 1
    DELETE http://testear.test/api/note/1
    ```


---

Entendido, vamos a añadir un punto a tu tema para controlar el error cuando no se encuentra una nota en la base de datos, utilizando primero el método `findOrFail` de Laravel y analizando el resultado. Después implementaremos el bloque `try-catch` para una solución más robusta.

Aquí tienes cómo podrías estructurar este punto:

## Control de Errores en la API con `findOrFail`

Cuando construimos una API REST en Laravel, es importante asegurarnos de que las respuestas a las solicitudes, especialmente las solicitudes AJAX, siempre sean en formato JSON, incluso cuando se produce un error, como intentar acceder a un recurso que no existe.

### Usando `findOrFail` para manejar errores

Laravel proporciona el método `findOrFail` para buscar un modelo en la base de datos por su ID. Si el modelo no existe, Laravel automáticamente lanza una excepción `ModelNotFoundException`, que puedes manejar para devolver una respuesta adecuada sin que se genere un error en formato HTML.

#### Ejemplo básico con `findOrFail`

En el siguiente ejemplo, la función `show` intenta obtener una nota por su ID usando `findOrFail`. Si la nota no se encuentra, Laravel devolverá automáticamente un error **404 Not Found** con una respuesta en formato JSON.

**Ejemplo de uso de `findOrFail`**

``` 
public function show($id): JsonResponse 
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

---

### Solución definitiva: usando `try-catch`

Para manejar de forma más controlada los errores y asegurarnos de que siempre devolvemos una respuesta en formato JSON, podemos usar el bloque `try-catch`. Esto nos permitirá interceptar la excepción y devolver una respuesta personalizada con un mensaje claro en formato JSON.

**Código con `try-catch`**:

``` 
public function show($id): JsonResponse {     
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

## Conclusiones

* Las APIs REST son ideales para aplicaciones SPA, móviles o integraciones.
* Laravel permite definir rutas específicas para API con prefijos automáticos y middleware personalizado.
* Los controladores API están enfocados a respuestas JSON.
* `ApiResource` permite estructurar y controlar la salida de tus datos.


???questionlaravel "Práctica: Validaciones y Mensajes en el CRUD de Productos"

    ### Objetivo de la actividad

    <p style="float: left; margin-left: 1rem;">
    <img src="../../img/laravelactividad.png"
        alt="Actividad en el aula virtual"
        width="150">
    </p>
    
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

    3) Verifica que los contenedores de Docker estén funcionando:
    
       ```
       docker compose up -d
       ```
       O si utilizas Laragon, comprueba que esté en marcha los servidores Nginx y Mysql.
    
    ---
    
    ### 2. Crear la tabla y el modelo de productos (opcional si no tienes ya una tabla `products`)
    
    4) Crea una nueva migración y el modelo asociado. Si ya lo tienes continúa con el siguiente paso.
    
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
    
    5) Crea el controlador `ProductController` en el espacio de nombres `api`.
   
    6) Implementa en él los cinco métodos principales (`index`, `store`, `show`, `update`, `destroy`) para manejar el CRUD. Puedes basarte en el ejemplo del recurso `Note` del tema.
    
    ---
    
    ### 4. Definir las rutas
    
    7) Crea las rutas para manejar los productos, utiliza `apiResource`.
   
    8) Comprueba que se hayan creado las rutas con:
    
    ```
    php artisan route:list --path=api/products
    ```
    
    ---
    
    ### 5. Crear el *API Resource*
    
    9)  Genera la clase `ProductResource`. Esta clase te permitirá personalizar la estructura de los datos JSON que devuelve la API. Un ejemplo de estructura sería:
    
    ```json
    {
        "id": 1,
        "nombre": "Camiseta",
        "precio": "$19.99",
        "stock": 25,
        "descripcion": "Camiseta de algodón"
    }
    ```
    
    10) Usa esta clase en el controlador para las respuestas JSON.
    
    ---
    
    ### 6. Validar los datos con una clase *Form Request*
    
    11) Crea la clase `ProductRequest` para validar los datos de entrada al crear o actualizar un producto. Las reglas de validación podrían ser:
    
       * `name`: obligatorio, cadena de texto, mínimo 3 y máximo 255 caracteres
       * `description`: obligatorio, cadena de texto, mínimo 10 caracteres
       * `price`: obligatorio, numérico, mínimo 0, máximo 9999.99
       * `stock`: obligatorio, entero, mínimo 0, máximo 10000
    
    12) Modifica los métodos `store()` y `update()` del controlador para usar esta clase.
    
    ---
    
    ### 7. Probar la API
    
    13) Crea un archivo `products.rest` en la raíz del proyecto (o usa *Postman*).
   
    14) Escribe las peticiones para probar todos los endpoints:
    
       * **GET** `/api/products` → Listar todos los productos
       * **POST** `/api/products` → Crear un nuevo producto
       * **GET** `/api/products/{id}` → Mostrar un producto
       * **PUT** `/api/products/{id}` → Modificar un producto
       * **DELETE** `/api/products/{id}` → Eliminar un producto

    15) Comprueba que todas las operaciones devuelven los **códigos HTTP correctos** (`200`, `201`, `204`, etc.) y que la respuesta JSON tiene el formato definido en `ProductResource`.


