## Proyecto de Gestión de Librería con Laravel

Este proyecto consiste en desarrollar una aplicación web con *Laravel* que permita gestionar **usuarios**, **libros** y **préstamos**. La aplicación permitirá que los usuarios se registren, inicien sesión, visualicen los libros disponibles, realicen préstamos y consulten su historial de préstamos. Se implementarán relaciones entre las entidades para gestionar la información de manera eficiente.

### Objetivos

1. Implementar un sistema de autenticación de usuarios con Laravel.
2. Crear y administrar libros con información relevante como título, autor, género y disponibilidad.
3. Permitir que los usuarios realicen préstamos de libros.
4. Implementar una relación muchos a muchos entre usuarios y libros mediante una tabla intermedia para registrar los préstamos.
5. Gestionar los datos de los préstamos realizados por los usuarios.

### Entrega

El proyecto debe estar alojado en un repositorio de GitHub o GitLab, con instrucciones claras para su instalación y ejecución en un archivo `README.md`.

### Aspectos Técnicos

- **Framework:** Laravel (versión 9 o superior).
- **Base de datos:** MySQL o PostgreSQL.
- **Autenticación:** Laravel Breeze o Laravel Jetstream.
- **Interfaz:** Blade Templates o Vue.js para la parte frontend.
- **Control de versiones:** GitHub/GitLab.

## 1. Crear Proyecto

```bash
laravel new libreria
```

## 2. Modelo de Base de Datos

### Entidades y Atributos

- **Usuarios** (*users*)
  - `id` (PK): Identificador único del usuario.
  - `name`: Nombre del usuario.
  - `email`: Correo electrónico del usuario.
  - `password`: Contraseña del usuario.
  - `role`: Rol del usuario (admin o lector).
- **Libros** (*books*)
  - `id` (PK): Identificador único del libro.
  - `title`: Título del libro.
  - `author`: Autor del libro.
  - `genre`: Género del libro.
  - `available_copies`: Número de copias disponibles.
- **Préstamos** (*loans*)
  - `id` (PK): Identificador único del préstamo.
  - `user_id` (FK): Identificador del usuario que realiza el préstamo.
  - `book_id` (FK): Identificador del libro prestado.
  - `borrowed_at`: Fecha en que se realizó el préstamo.
  - `returned_at`: Fecha de devolución del libro (nullable).

### Relaciones

- **users → loans**: Un usuario puede realizar muchos préstamos.
- **books → loans**: Un libro puede estar en muchos préstamos.
- Relación **N a M** (muchos a muchos), resuelta con la tabla intermedia **loans**.

## 3. Migraciones

```php
// Migración de la tabla users
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['admin', 'lector'])->default('lector');
    $table->timestamps();
});

// Migración de la tabla books
Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('author');
    $table->string('genre');
    $table->integer('available_copies');
    $table->timestamps();
});

// Migración de la tabla loans
Schema::create('loans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users');
    $table->foreignId('book_id')->constrained('books');
    $table->date('borrowed_at');
    $table->date('returned_at')->nullable();
    $table->timestamps();
});
```

## 4. Modelos

```php
class User extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'password', 'role'];
    public function loans() {
        return $this->hasMany(Loan::class);
    }
}

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'author', 'genre', 'available_copies'];
    public function loans() {
        return $this->hasMany(Loan::class);
    }
}

class Loan extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'book_id', 'borrowed_at', 'returned_at'];
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function book() {
        return $this->belongsTo(Book::class);
    }
}
```

## Factories

### UserFactory

```php
public function definition(): array
{
    return [
        'name' => $this->faker->name(),
        'email' => $this->faker->unique()->safeEmail(),
        'email_verified_at' => now(),
        'password' => Hash::make('password123'), // Contraseña encriptada
        'remember_token' => Str::random(10),
        'created_at' => now(),
        'updated_at' => now(),
    ];
}
```

### BookFactory

```php
public function definition(): array
{
    return [
        'title' => $this->faker->sentence(4),
        'author' => $this->faker->name(2),
        'genre' => $this->faker->sentence(2),
        'available_copies' => $this->faker->numberBetween(1,10), // Contraseña encriptada
        'created_at' => now(),
        'updated_at' => now(),
    ];
}
```

### LoanFactory

```php
public function definition(): array
{
    $borrowed_at = $this->faker->dateTimeBetween('-2 years', 'now');
    $returned_at = $this->faker->boolean(70) ? $this->faker->dateTimeBetween($borrowed_at, 'now') : null;

    return [
        'user_id' => User::inRandomOrder()->first()->id,
        'book_id' => Book::inRandomOrder()->first()->id,
        'borrowed_at' => $borrowed_at,
        'returned_at' => $returned_at,
        'created_at' => now(),
        'updated_at' => now(),
    ];
}
```



## Seeders

### UserSeeder

```php
public function run(): void
{
    User::factory()->count(10)->create();
}
```

### BookSeeder

```php
public function run(): void
{
    Book::factory()->count(100)->create();
}
```

### LoanSeeder

```php
public function run(): void
{
    Loan::factory()->count(150)->create();
}
```

### DatabaseSeeder

```php
public function run(): void
{
    // User::factory(10)->create();

    $this->call([
        UserSeeder::class,
        BookSeeder::class,
        LoanSeeder::class,
       ]);
}
```





## 6. Controladores

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,lector',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'in:admin,lector',
        ]);

        if ($request->has('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}
```

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        return response()->json(Book::all());
    }

    public function show(Book $book)
    {
        return response()->json($book);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'available_copies' => 'required|integer|min:0',
        ]);

        $book = Book::create($validated);

        return response()->json($book, 201);
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'author' => 'string|max:255',
            'genre' => 'string|max:255',
            'available_copies' => 'integer|min:0',
        ]);

        $book->update($validated);

        return response()->json($book);
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['message' => 'Libro eliminado correctamente']);
    }
}
```

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Book;
use App\Models\User;

class LoanController extends Controller
{
    public function index()
    {
        return response()->json(Loan::with(['user', 'book'])->get());
    }

    public function show(Loan $loan)
    {
        return response()->json($loan->load(['user', 'book']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->available_copies <= 0) {
            return response()->json(['error' => 'No hay copias disponibles'], 400);
        }

        // Reducir en 1 la cantidad de copias disponibles
        $book->decrement('available_copies');

        $loan = Loan::create([
            'user_id' => $validated['user_id'],
            'book_id' => $validated['book_id'],
            'borrowed_at' => now(),
            'returned_at' => null,
        ]);

        return response()->json($loan, 201);
    }

    public function returnBook($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->returned_at) {
            return response()->json(['error' => 'El libro ya fue devuelto'], 400);
        }

        $loan->update(['returned_at' => now()]);

        // Aumentar en 1 la cantidad de copias disponibles del libro
        $loan->book->increment('available_copies');

        return response()->json(['message' => 'Libro devuelto correctamente', 'loan' => $loan]);
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return response()->json(['message' => 'Préstamo eliminado correctamente']);
    }
}
```



## 7. Rutas

```php
Route::resource('users', UserController::class);
Route::resource('books', BookController::class);
Route::resource('loans', LoanController::class);
```
