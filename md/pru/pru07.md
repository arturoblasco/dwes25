# <img src="../../img/laravel_auro.svg" width="40"> 7. Sistema de almacenamiento de archivos

En las sesiones anteriores aprendiste a manejar formularios, validar datos y autenticar usuarios. Pero ¿qué pasa cuando los usuarios suben **archivos** como imágenes de perfil, fotos de productos, o documentos?

El almacenamiento de archivos es una necesidad fundamental en aplicaciones web modernas. Laravel proporciona un sistema de almacenamiento robusto, seguro y fácil de usar a través de su facade `Storage`.

## 7.1. ¿Por qué necesitamos un sistema de almacenamiento?

Cuando los usuarios suben archivos a tu aplicación (imágenes, documentos, videos), estos archivos deben:

1. **Almacenarse en el servidor** de forma segura.
2. **Ser accesibles** públicamente (para imágenes que se muestran en la web).
3. **Gestionarse** (crear, leer, actualizar, eliminar).
4. **Organizarse** en directorios lógicos.

## 7.2. Discos de almacenamiento en Laravel

Laravel organiza el almacenamiento en "discos" (disks) que son ubicaciones configuradas donde se guardan archivos.

### 7.2.1. Discos predefinidos

Laravel viene con tres discos configurados por defecto, cada uno con un propósito específico según el nivel de acceso y ubicación requerida.

| Disco | Ubicación | Acceso Público | Uso Típico |
| --- | --- | --- | --- |
| **local** | `storage/app/` | ❌ No | Archivos privados, logs internos |
| **public** | `storage/app/public/` | ✅ Sí (con enlace simbólico) | Imágenes de productos, avatares |
| **s3** | Amazon S3 (nube) | ✅ Configurable | Archivos en producción, alta escalabilidad |

### 7.2.2. Configuración de discos

Los discos se configuran en **`config/filesystems.php`**:

```php
<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],
    ],
];
```

## 7.3. El disco público y el enlace simbólico

El disco `public` es especial porque necesita ser accesible desde la web, pero por defecto está en `storage/app/public/`, que **no es accesible públicamente**.

### 7.3.1. ¿Por qué existe este problema?

La carpeta **`storage/`** está fuera de la carpeta **`public/`** por seguridad. Los navegadores solo pueden acceder a archivos dentro de `public/`, por lo que archivos en **`storage/app/public/` no son accesibles directamente**.

```text
tu-proyecto/
├── public/              ←  Carpeta accesible por navegadores (www.tuapp.com/)
│   ├── index.php
│   ├── css/
│   └── js/
└── storage/             ←  Carpeta PRIVADA, NO accesible por navegadores
    └── app/
        └── public/      ←← ¿Cómo hacer que esto sea accesible?
            └── products/
                └── iphone.jpg
```

**Problema:**

* Los navegadores solo pueden acceder a archivos en `public/`
* Pero guardamos archivos en `storage/app/public/`
* Los navegadores **no pueden** acceder directamente a `storage/`

### 7.3.2. Solución: enlace simbólico

Un **enlace simbólico** (*symbolic link*) es como un "portal" que conecta dos ubicaciones:

```bash
# Crear el enlace simbólico
php artisan storage:link
```

**Resultado:**

```text
public/storage → storage/app/public/
   (enlace)        (carpeta real)
```

Ahora:

```text
www.tuapp.com/storage/products/iphone.jpg

              ↓

public/storage/products/iphone.jpg

              ↓ (enlace simbólico)

storage/app/public/products/iphone.jpg
```

> **Cuándo crear el enlace simbólico**
> 
> Siempre debes ejecutar **`php artisan storage:link`** después de:
> 
> * Clonar el proyecto por primera vez.
> * Configurar un nuevo entorno de desarrollo.
> * Desplegar en un nuevo servidor.
> 
> **Sin el enlace simbólico, las imágenes no se mostrarán en el navegador.**

### 7.3.3. Verificar el enlace

Puedes verificar si el enlace simbólico ya existe ejecutando el comando nuevamente. Si existe, Laravel te lo indicará para evitar conflictos.

```bash
# Verificar si el enlace existe
sail artisan storage:link

# Si ya existe, verás:
# The "public/storage" directory already exists.

# Para forzar la recreación:
sail artisan storage:link --force
```

## 7.4. Operaciones básicas con Storage

La facade **`Storage`** proporciona métodos simples para guardar, leer, actualizar y eliminar archivos. Estas operaciones son consistentes sin importar qué disco uses (`local`, `public`, `s3`).

### 7.4.1. Guardar archivos

Para guardar archivos desde un formulario, usa el método **`store()`** o **`storeAs()`** del archivo subido. Laravel automáticamente gestiona el nombre y ubicación del archivo.

**Desde un formulario:**

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        // Validar el archivo
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB máximo
        ]);

        // Guardar el archivo en storage/app/public/products/
        $path = $request->file('image')->store('products', 'public');
        // $path: contiene la ruta relativa (products/abc123def456.jpg>)
        
        // Guardar la ruta en la base de datos
        $product = Product::create([
            'name' => $request->name,
            'image' => $path,  // ← Guardamos la ruta
        ]);

        return redirect()->route('products.show', $product);
    }
}
```

- `$request->file('image')`: obtiene el archivo subido.
- `->store('products', 'public')`: lo guarda en el disco public (configurado en `config/filesystems.php`) dentro de la carpeta `products/`. Laravel genera un nombre único para el archivo.
- El resultado (`$path`) es una ruta relativa dentro del disco, por ejemplo: "products/abc123def456.jpg".

En el sistema de archivos real, esto va a: `storage/app/public/products/abc123def456.jpg`

Y a través del enlace simbólico `public/storage` (si has hecho php artisan storage:link) se podrá acceder como: `http://tuapp.test/storage/products/abc123def456.jpg`

**Con nombre personalizado:**

```php
<?php
// Guardar con nombre original
$path = $request->file('image')->storeAs('products',
                                         $request->file('image')->getClientOriginalName(),
                                         'public' );
```

```php
<?php
// Guardar con nombre personalizado
$fileName = 'product_' . time() . '.' . $request->file('image')->extension();
$path = $request->file('image')->storeAs('products', $fileName, 'public');
```

### 7.4.2. Leer/Obtener archivos

Para mostrar archivos guardados en `storage` necesitas obtener su URL pública. Laravel proporciona el método **`Storage::url()`** y el helper **`asset()`** para generar URLs accesibles.

**Obtener la URL pública:**

```php
<?php
// En el controlador
$product = Product::find(1);
$url = Storage::url($product->image);  // $url = "/storage/products/abc123def456.jpg"
```

**En Blade:**

```php
<?php
{{ -- Mostrar imagen desde Storage -- }}
<img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
```

```php
<?php
{{ -- ó usando el helper Storage::url() -- }}
<img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
```

**Verificar si un archivo existe:**

```php
<?php
if (Storage::disk('public')->exists($product->image)) {
    // El archivo existe
    $url = Storage::url($product->image);
} else {
    // Usar imagen placeholder
    $url = asset('images/placeholder.jpg');
}
```

### 7.4.3. Actualizar archivos

Cuando actualizas un registro que tiene un archivo, debes:

1. Eliminar el archivo antiguo (si existe).
2. Guardar el nuevo archivo.
3. Actualizar la ruta en la base de datos.

```php
<?php

public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Actualizar datos básicos
    $product->name = $request->name;

    // Si se subió una nueva imagen
    if ($request->hasFile('image')) {
        // 1. Eliminar imagen anterior (si existe)
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // 2. Guardar nueva imagen
        $path = $request->file('image')->store('products', 'public');

        // 3. Actualizar la ruta
        $product->image = $path;
    }

    $product->save();

    return redirect()->route('products.show', $product)
        ->with('success', 'Producto actualizado exitosamente');
}
```

### 7.4.4. Eliminar archivos

Cuando eliminas un registro que tiene archivos asociados, también debes eliminar los archivos del disco para evitar archivos "huérfanos" que ocupan espacio innecesariamente.



```php
<?php

public function destroy(Product $product)
{
    // Eliminar el archivo de Storage
    if ($product->image && Storage::disk('public')->exists($product->image)) {
        Storage::disk('public')->delete($product->image);
    }

    // Eliminar el registro de la base de datos
    $product->delete();

    return redirect()->route('products.index')
        ->with('success', 'Producto eliminado exitosamente');
}
```

## 7.5. Métodos útiles de Storage

Además de las operaciones básicas, `Storage` proporciona métodos útiles para verificar, gestionar directorios y copiar o mover archivos.

### 7.5.1. Verificación de archivos

Verifica la existencia, tamaño y última modificación de archivos antes de realizar operaciones sobre ellos.

```php
<?php

// Verificar si existe
Storage::disk('public')->exists('products/image.jpg');

// Verificar si NO existe
Storage::disk('public')->missing('products/image.jpg');

// Obtener tamaño en bytes
Storage::disk('public')->size('products/image.jpg');

// Obtener última modificación (timestamp)
Storage::disk('public')->lastModified('products/image.jpg');
```

### 7.5.2. Gestión de directorios

Crea, lista y elimina directorios para mantener tus archivos organizados. Estos métodos facilitan la gestión de la estructura de carpetas.

```php
<?php

// Listar archivos en un directorio
$files = Storage::disk('public')->files('products');
// ['products/img1.jpg', 'products/img2.jpg']

// Listar todos los archivos (recursivo)
$files = Storage::disk('public')->allFiles('products');

// Listar directorios
$directories = Storage::disk('public')->directories('products');

// Crear directorio
Storage::disk('public')->makeDirectory('products/featured');

// Eliminar directorio y todo su contenido
Storage::disk('public')->deleteDirectory('products/old');
```

### 7.5.3. Copiar y mover archivos

Copia o mueve archivos entre directorios sin necesidad de subirlos de nuevo. Útil para reorganizar contenido o crear respaldos.

```php
<?php

// Copiar archivo
Storage::disk('public')->copy(
    'products/old-image.jpg',
    'products/backup/old-image.jpg'
);

// Mover archivo
Storage::disk('public')->move(
    'products/temp-image.jpg',
    'products/featured/image.jpg'
);

// Renombrar archivo (es un alias de move)
Storage::disk('public')->move(
    'products/abc123.jpg',
    'products/producto-destacado.jpg'
);
```

## 7.6. Archivos privados (opcional)

A diferencia del disco `public` que es accesible para cualquiera, el disco `local` permite almacenar archivos que **requieren autenticación** para ser descargados.

### 7.6.1. ¿Cuándo usar archivos privados?

**Casos de uso comunes:**

* Facturas de clientes.
* Documentos personales.
* Contratos privados.
* Archivos de nóminas.
* Cualquier documento sensible que requiera control de acceso.

### 7.6.2. Guardar archivos privados

Los archivos privados se guardan en el disco `local` que no es accesible directamente vía web. Debes servir estos archivos a través de controladores que verifican permisos.

```php
<?php

// Guardar en el disco 'local' (privado)
$documentPath = $request->file('invoice')->store('invoices', 'local');

// Se guarda en: storage/app/invoices/
// NO es accesible desde el navegador
```

### 7.6.3. Servir archivos privados con control de acceso

Para servir archivos privados, necesitas un controlador que verifique los permisos:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceController extends Controller
{
    /**
     * Descargar factura privada.
     */
    public function download($id): StreamedResponse
    {
        // 1. Buscar la factura
        $invoice = Invoice::findOrFail($id);
        
        // 2. Verificar que el usuario tiene permiso
        if (auth()->id() !== $invoice->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para acceder a este archivo.');
        }
        
        // 3. Verificar que el archivo existe
        if (!Storage::disk('local')->exists($invoice->file_path)) {
            abort(404, 'Archivo no encontrado.');
        }
        
        // 4. Descargar el archivo
        return Storage::disk('local')->download(
            $invoice->file_path,
            $invoice->original_filename  // Nombre del archivo descargado
        );
    }
    
    /**
     * Ver archivo privado en el navegador (sin descargar).
     */
    public function view($id): StreamedResponse
    {
        $invoice = Invoice::findOrFail($id);
        
        // Verificar permisos
        if (auth()->id() !== $invoice->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para acceder a este archivo.');
        }
        
        // Mostrar en el navegador (para PDFs, imágenes)
        return Storage::disk('local')->response(
            $invoice->file_path,
            $invoice->original_filename
        );
    }
}
```

**Definir la ruta:**

```php
<?php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/invoices/{id}/download', [InvoiceController::class, 'download'])
        ->name('invoices.download');
    
    Route::get('/invoices/{id}/view', [InvoiceController::class, 'view'])
        ->name('invoices.view');
});
```

**En la vista:**

```php
<?php
{{ -- Enlace para descargar factura privada -- }}
<a href="{{ route('invoices.download', $invoice->id) }}" 
   class="btn btn-primary">
    Descargar Factura
</a>

{{ -- Enlace para ver en el navegador -- }}
<a href="{{ route('invoices.view', $invoice->id) }}" 
   target="_blank"
   class="btn btn-secondary">
    Ver Factura
</a>
```

**Diferencia: download() vs response()**

* **`download()`**: fuerza la descarga del archivo (header `Content-Disposition: attachment`).
* **`response()`**: muestra el archivo en el navegador (para PDFs, imágenes).

## 7.7. Buenas prácticas

Seguir buenas prácticas en el manejo de archivos es crucial para la seguridad, rendimiento y mantenibilidad de tu aplicación. Estas recomendaciones te ayudarán a evitar problemas comunes.

### 7.7.1. Siempre validar archivos

Valida tipo, tamaño y dimensiones de los archivos subidos para prevenir archivos maliciosos, excesivamente grandes o de formatos incorrectos.

```php
<?php

$request->validate([
    'image' => [
        'required',          // Campo obligatorio
        'image',             // Debe ser una imagen
        'mimes:jpeg,png,jpg,gif,webp',  // Tipos permitidos
        'max:2048',          // Tamaño máximo en KB (2MB)
        'dimensions:min_width=100,min_height=100',  // Dimensiones mínimas
    ],
]);
```

### 7.7.2. Advertencia de seguridad: extensiones de archivo

Confiar solo en la extensión del archivo es un grave error de seguridad. Los atacantes pueden manipular extensiones para intentar subir código malicioso.

!!!warning "Nunca confíes en la extensión del archivo"
    Un atacante puede renombrar un archivo malicioso `virus.php` a `virus.jpg` para intentar engañar al sistema.

**Medidas de seguridad obligatorias:**

1. **Siempre usa la regla `image`**: Laravel valida el contenido real del archivo (tipo MIME), no solo la extensión.

2. **Nunca ejecutes archivos subidos**: los archivos subidos por usuarios nunca deben ejecutarse directamente.

3. **Usa el disco correcto**:

    * Archivos públicos → disco `public` (imágenes visibles).
    * Archivos sensibles → disco `local` (con control de acceso).
  
4. **Valida el tipo MIME**: `mimes:jpeg,png,jpg,gif` verifica el contenido real.

```php
<?php

// ✅ BUENO: Validación completa

$request->validate([
    'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
]);
```

```php
<?php

// ❌ MALO: Solo verificar extensión

if (pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION) === 'jpg') {
    // Un atacante puede renombrar virus.php a virus.jpg
}
```

### 7.7.3. Organizar archivos en subdirectorios

Mantén tus archivos organizados en subdirectorios por tipo o fecha. Esto facilita el mantenimiento, backups y evita problemas de rendimiento con muchos archivos.


```php
<?php
// ❌ Malo: Todos los archivos en el mismo directorio

Storage::disk('public')->put('image.jpg', $file);
// storage/app/public/image.jpg
```

```php
<?php
// ✅ Bueno: Organizar por tipo

Storage::disk('public')->put('products/image.jpg', $file);
// storage/app/public/products/image.jpg
```

```php
<?php
// ✅ Mejor: Organizar por fecha

$date = now()->format('Y/m');
Storage::disk('public')->put("products/{$date}/image.jpg", $file);
// storage/app/public/products/2025/01/image.jpg
```

### 7.7.4. Eliminar archivos huérfanos

Siempre elimina el archivo cuando eliminas el registro:

```php
<?php
// ❌ Malo: Solo eliminar el registro

$product->delete();
// El archivo queda en storage ocupando espacio
```

```php
<?php
// ✅ Bueno: Eliminar archivo y registro

if ($product->image && Storage::disk('public')->exists($product->image)) {
    Storage::disk('public')->delete($product->image);
}
$product->delete();
```

### 7.7.5. Usar nombres de archivo únicos

Genera nombres únicos para evitar colisiones y sobrescritura accidental de archivos cuando múltiples usuarios suben archivos con el mismo nombre.

```php
<?php
// ❌ Malo: Nombre predecible

$fileName = 'product.jpg';
// Si varios usuarios suben 'product.jpg', se sobrescriben
```

```php
<?php
// ✅ Bueno: Nombre único con timestamp

$fileName = 'product_' . time() . '.' . $request->file('image')->extension();
```

```php
<?php
// ✅ Mejor: Nombre único con UUID

$fileName = Str::uuid() . '.' . $request->file('image')->extension();
```

```php
<?php
// ✅ Automático: Laravel genera nombres únicos con store()

$path = $request->file('image')->store('products', 'public');
// Laravel genera: "products/abc123def456ghi789.jpg"
```

### 7.7.6. Manejar imágenes faltantes en vistas

Siempre maneja el caso de imágenes faltantes o eliminadas mostrando una imagen *placeholder*. Esto evita enlaces rotos y mejora la experiencia de usuario.


```php
<?php
{{ -- ❌ Malo: Error si la imagen no existe -- }}

<img src="{{ asset('storage/' . $product->image) }}">
```

```php
<?php
{{ -- ✅ Bueno: Verificar primero -- }}

@if($product->image && Storage::disk('public')->exists($product->image))
    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
@else
    <img src="{{ asset('images/placeholder.jpg') }}" alt="Sin imagen">
@endif
```

```php
<?php
{{ -- ✅ Mejor: Usar un accessor en el modelo -- }}

<img src="{{ $product->image_url }}" alt="{{ $product->name }}">
```

**Accessor en el modelo:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    /**
     * Get the product's image URL.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->image && Storage::disk('public')->exists($this->image)) {
                    return asset('storage/' . $this->image);
                }
                return asset('images/placeholder.jpg');
            },
        );
    }
}
```

## 7.8. Flujo completo: subida de imagen en un producto

Este ejemplo completo integra todas las piezas: 

- formulario HTML, 
- controlador con validación y 
- almacenamiento y vista para mostrar la imagen. 

Es el patrón típico para gestionar *uploads*.

### 7.8.1. Formulario HTML

El formulario debe incluir **`enctype="multipart/form-data"`** y un input de tipo **`file`** para permitir la subida de archivos.

```php
<?php
{{ -- resources/views/admin/products/create.blade.php -- }}

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div>
        <label for="name">Nombre del Producto</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        @error('name')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="image">Imagen del Producto</label>
        <input type="file" id="image" name="image" accept="image/*" required>
        @error('image')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit">Crear Producto</button>
</form>
```

### 7.8.2. Controlador

El controlador valida el archivo, lo guarda en storage y almacena la ruta en la base de datos. También incluye métodos para actualizar y eliminar imágenes correctamente.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Guardar nuevo producto.
     */
    public function store(Request $request)
    {
        // 1. Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Guardar imagen en Storage
        $imagePath = $request->file('image')->store('products', 'public');

        // 3. Crear producto en la base de datos
        $product = Product::create([
            'name' => $validated['name'],
            'image' => $imagePath,
        ]);

        // 4. Redirigir con mensaje de éxito
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Actualizar producto existente.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validar datos (imagen opcional en actualización)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Actualizar nombre
        $product->name = $validated['name'];

        // 3. Si se subió nueva imagen
        if ($request->hasFile('image')) {
            // 3a. Eliminar imagen anterior
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // 3b. Guardar nueva imagen
            $product->image = $request->file('image')->store('products', 'public');
        }

        // 4. Guardar cambios
        $product->save();

        // 5. Redirigir con mensaje de éxito
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Eliminar producto.
     */
    public function destroy(Product $product)
    {
        // 1. Eliminar imagen de Storage
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // 2. Eliminar producto de la base de datos
        $product->delete();

        // 3. Redirigir con mensaje de éxito
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}
```

### 7.8.3. Mostrar imagen en la vista

En la vista, verifica siempre que la imagen existe antes de mostrarla. Si no existe, muestra una imagen placeholder para mantener una buena experiencia de usuario.


```php
<?php

{{ -- resources/views/products/show.blade.php -- }}
<div class="product-card">
    <div class="product-image">
        @if($product->image && Storage::disk('public')->exists($product->image))
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}"
                 class="w-full h-64 object-cover">
        @else
            <img src="{{ asset('images/placeholder.jpg') }}" 
                 alt="Sin imagen"
                 class="w-full h-64 object-cover">
        @endif
    </div>

    <div class="product-info">
        <h2>{{ $product->name }}</h2>
    </div>
</div>
```
