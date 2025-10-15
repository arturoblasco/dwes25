---
title: Ejercicios
---
# Ejercicios


### Ejercicio 301 Investigación
Investiga la diferencia entre un paradigma orientado a objetos basado en clases (*PHP*) respecto a uno basado en prototipos (*JavaScript*).<br /><br />

<hr>


### Ejercicio 302

`302Empleado.php`: crea una clase `Empleado` con su *nombre*, *apellidos* y *sueldo*. Encapsula las propiedades mediante *getters*/*setters* y añade métodos para:


-  `getNombreCompleto(): string`  : obtener su nombre completo

- `debePagarImpuestos(): bool` : que devuelva un booleano indicando si debe o no pagar impuestos (se pagan cuando el sueldo es superior a 3333€).


### Ejercicio 303
`303EmpleadoTelefonos.php`: copia la clase del ejercicio anterior y modifícala. Añade una propiedad privada que almacene un *array de números de teléfonos*. Añade los siguientes métodos:

- `public function anyadirTelefono(int $telefono) : void` : añade un teléfono al array.
- `public function listarTelefonos(): string` : muestra los teléfonos separados por comas.
- `public function vaciarTelefonos(): void` : elimina todos los teléfonos.

### Ejercicio 304
`304EmpleadoConstructor.php`: copia la clase del ejercicio anterior y modifícala. Elimina los *setters* de `nombre` y `apellidos`, de manera que dichos datos se asignan mediante el constructor (utiliza la sintaxis de PHP7). Si el constructor recibe un tercer parámetro, será el sueldo del `Empleado`. Si no, se le asignará *1000€* como sueldo inicial.

`304EmpleadoConstructor8.php`: modifica la clase y utiliza la sintaxis de PHP 8 de promoción de las propiedades del constructor.

### Ejercicio 305
`305EmpleadoConstante.php`: copia la clase del ejercicio anterior y modifícala. Añade una constante `SUELDO_TOPE` con el valor del sueldo que debe pagar impuestos, y modifica el código para utilizar la constante.

### Ejercicio 306
`306EmpleadoSueldo.php`: copia la clase del ejercicio anterior y modifícala. Cambia la constante por una variable estática `sueldoTope`, de manera que mediante *getter*/*setter* puedas modificar su valor.

### Ejercicio 307
`307EmpleadoStatic.php`: copia la clase del ejercicio anterior y modifícala.

Completa la clase con el siguiente método que muestre los datos de un empleado dentro de un párrafo y todos los teléfonos mediante una lista ordenada en HTML (para ello, deberás crear un *getter* para los teléfonos): `public static function toHtml(Empleado $emp): string`

### Ejercicio 308
<div style="text-align: center;"><figure><img src="../../img/ut03/ejer08.png" alt="ejer08" style="zoom:95%;" /><figcaption style="font-size: 13px; color: #bd8f04;">La clase Empleado hereda de la clase Persona (ejercicio308).</figcaption></figure></div>

`308Persona.php`: copia la clase del ejercicio anterior en `308Empleado.php` y modifícala.

Crea una clase `Persona` que sea padre de `Empleado`, de manera que `Persona` contenga el nombre y los apellidos, y en `Empleado` quede el salario y los teléfonos.

### Ejercicio 309
`309PersonaH.php`: copia las clases del ejercicio anterior y modifícalas. Crea en `Persona` el método estático `toHtml(Persona $p)`, y modifica en `Empleado` el mismo método `toHtml(Persona $p)`, pero cambia la firma para que reciba una `Persona` como parámetro.

Para acceder a las propiedades del empleado con la persona que recibimos como parámetro, comprobaremos su tipo:

```php
<?php
class Empleado extends Persona {
   // resto del código

   public static function toHtml(Persona $p): string {
      if ($p instanceof Empleado) {
          // Aqui ya podemos acceder a las propiedades y métodos de Empleado
      }
   }
}
```

### Ejercicio 310
`310PersonaE.php`: copia las clases del ejercicio anterior y modifícalas.

Añade en `Persona` un atributo `edad`.

A la hora de saber si un empleado debe pagar impuestos, lo hará siempre y cuando tenga más de *21 años* y dependa del valor de su sueldo. Modifica todo el código necesario para mostrar y/o editar la edad cuando sea necesario.

### Ejercicio 311
`311PersonaS.php`: copia las clases del ejercicio anterior y modifícalas.
Añade nuevos métodos que hagan una representación de todas las propiedades de las clases `Persona` y `Empleado`, de forma similar a los realizados en HTML, pero sin que sean estáticos, de manera que obtenga los datos mediante `$this`:

- `function public __toString(): string`

> **Magic method**
> 
> El método `__toString()` es un método mágico que se invoca automáticamente cuando queremos obtener la representación en cadena de un objeto.
    

### Ejercicio 312
`312PersonaA.php`: copia las clases del ejercicio anterior y modifícalas.

Transforma `Persona` a una clase abstracta donde su método estático `toHtml(Persona $p)` tenga que ser redefinido en todos sus hijos. 

### Ejercicio 313
`313Trabajador.php`: copia las clases del ejercicio anterior y modifícalas.

- Cambia la estructura de clases conforme al gráfico respetando todos los métodos que ya están hechos.

- `Trabajador` es una clase abstracta que ahora almacena los `telefonos` y donde `calcularSueldo` es un método abstracto de manera que:
- El sueldo de un `Empleado` se calcula a partir de las horas trabajadas y lo que cobra por hora.
  
- Para los `Gerente`*s*, su sueldo se incrementa porcentualmente en base a su edad: `salario + salario*edad/100`

<div style="text-align: center;"><figure><img src="../../img/ut03/ejer13.png" alt="ejer13" style="zoom:100%;" /><figcaption style="font-size: 13px; color: #bd8f04;">Estructura de herencia de clases del ejercicio 313.</figcaption></figure></div>

### Ejercicio 314

`314Empresa.php`: utilizando las clases de los ejercicios anteriores:

- Crea una clase `Empresa` que además del nombre y la dirección, contenga una propiedad con un array de `Trabajador`es, ya sean `Empleado`s o `Gerente`s.

- Añade *getters/setters* para el nombre y dirección.

- Añade métodos para añadir y listar los trabajadores.

   - `public function anyadirTrabajador(Trabajador $t)`
   - `public function listarTrabajadoresHtml() : string` -> utiliza `Trabajador::toHtml(Persona $p)`

- Añade un método para obtener el coste total en nóminas.

   - `public function getCosteNominas(): float` -> recorre los trabajadores e invoca al método `calcularSueldo()`.

### Ejercicio 315
`315EmpresaI.php`: copia las clases del ejercicio anterior y modifícalas.

 a) Crea un interfaz `JSerializable`, de manera que ofrezca los métodos:

- `toJSON(): string` → utiliza la función [json_encode(mixed)](https://www.php.net/manual/es/function.json-encode.php). Ten en cuenta que como tenemos las propiedades de los objetos privados, debes recorrer las propiedades y colocarlas en un mapa. Por ejemplo:

```php
<?php
    public function toJSON(): string {
        foreach ($this as $clave => $valor) {
            $mapa->$clave = $valor;
        }
        return json_encode($mapa);
    }
?>
```

- `toSerialize(): string` → utiliza la función [serialize(mixed)](https://www.php.net/manual/es/function.serialize.php)

 b) Modifica todas las clases que no son abstractas para que implementen el interfaz creado.


<hr>

## Proyecto Videoclub

> **Proyecto no real**
> 
> El siguiente proyecto está pensado desde un punto de vista formativo. Algunas de las decisiones que se toman no se deben usar (como hacer `echo` dentro de las clases) o probar el código comparando el resultado en el navegador.

En los siguientes ejercicios vamos a simular un pequeño proyecto de un Videoclub (basado en la propuesta que hace el tutorial de desarrolloweb.com), el cual vamos a realizar mediante un desarrollo incremental y siguiendo la práctica de programación en parejas (*pair programming*).

> **Git del proyecto**
> 
> Antes de nada, crea un repositorio privado en GitHub y sube el proyecto actual de `Videoclub`. Una vez creado, invita a tu compañero al repositorio como colaborador.
> 
> 1. Inicializa en local tu repositorio de *g*it, mediante `git init`.
> 2. Añade y sube los cambios a tu repositorio, mediante `git add .` y luego `git commit -m 'Inicializando proyecto'`.
> 3.Conecta tu repositorio con GitHub y sube los cambios (mira la instrucciones de GitHub: comandos `git remote` y `git push`).
> 
> Tu compañero deberá descargar el proyecto con sus credenciales.



Cada clase debe ir en un archivo php separado. Para facilitar su implementación, se muestra la estructura UML del modelo y un fragmento de código para probar las clases:

### Ejercicio 321
<div style="text-align: center;"><figure><img src="../../img/ut03/pro01.png" alt="pro01" style="zoom:100%;" /><figcaption style="font-size: 13px; color: #bd8f04;">Estructura de la clase Soporte del ejercicio 321.</figcaption></figure></div>

Crea una clase para almacenar soportes (`Soporte.php`). Esta clase será la clase madre de los diferentes soportes con los que trabaje nuestro videoclub (*cintas de vídeo*, *videojuegos*, etc...):

- Crea el constructor que inicialice sus propiedades. Fíjate que la clase no tiene métodos *setters*.
- Definir una constante mediante un propiedad privada y estática denominada `IVA` con un valor del *21%*.
- Crear un archivo (`inicio.php`) para usar las clases y copia el siguiente fragmento:

???examplephp "Ejemplo"
	=== "inicio.php"
        ```php
        <?php
            include_once ("Soporte.php");
	
            $soporte1 = new Soporte("Tenet", 22, 3); 
            echo "<strong>" . $soporte1->titulo . "</strong>"; 
            echo "<br>Precio: " . $soporte1->getPrecio() . " €"; 
            echo "<br>Precio con IVA: " . $soporte1->getPrecioConIVA() . " €";
    		echo "<br/>";
            echo $soporte1->muestraResumen();
        ```
    === "Resultado"
    	<div style="text-align: left;"><img src="../../img/ut03/e321.png" alt="e321" style="zoom:90%;" /></div>

### Ejercicio 322
<div style="text-align: center;"><figure><img src="../../img/ut03/pro02.png" alt="pro02" style="zoom:100%;" /><figcaption style="font-size: 13px; color: #bd8f04;">La clase CintaVideo hereda de la clase Soporte (ejercicio322).</figcaption></figure></div>

Crea la clase `CintaVideo` la cual hereda de `Soporte`. Añade el atributo `duracion` y sobreescribe tanto el constructor como el método `muestraResumen` (desde `CintaVideo` deberás llamar al método `muestraResumen` del padre).

Añade a `inicio.php` el código para probar la clase:
???examplephp "Ejemplo"
	=== "inicio.php"
        ```php
        <?php
            include_once "CintaVideo.php";

            $miCinta = new CintaVideo("Los cazafantasmas", 23, 3.5, 107); 
            echo "<strong>" . $miCinta->titulo . "</strong>"; 
            echo "<br>Precio: " . $miCinta->getPrecio() . " €"; 
            echo "<br>Precio con IVA: " . $miCinta->getPrecioConIva() . " €";
            echo "<br/>";
    
            echo $miCinta->muestraResumen();
        ```
    === "Resultado"
    	<div style="text-align: left;"><img src="../../img/ut03/e322.png" alt="e322" style="zoom:90%;" /></div>



### Ejercicio 323
<div style="text-align: center;"><figure><img src="../../img/ut03/pro03.png" alt="pro03" style="zoom:100%;" /><figcaption style="font-size: 13px; color: #bd8f04;">las clases CintaVideo y Dvd heredan de la clase Soporte (ejercicio323).</figcaption></figure></div>

Crea la clase `Dvd` la cual hereda de `Soporte`. Añade los atributos `idiomas` (que se un array como "es", "en", ...) y `formatoPantalla`. A continuación sobreescribe tanto el constructor como el método `muestraResumen`.

Añade a `inicio.php` el código para probar la clase:
???examplephp "Ejemplo"
	=== "inicio.php"
        ```php
        <?php
            include_once "Dvd.php";

            $miDvd = new Dvd("Origen", 24, 15, ["es","en","fr"], "16:9"); 
            echo "<strong>" . $miDvd->titulo . "</strong>"; 
            echo "<br>Precio: " . $miDvd->getPrecio() . " €"; 
            echo "<br>Precio con IVA: " . $miDvd->getPrecioConIva() . " €";
    		echo "<br/>";
    		
            echo $miDvd->muestraResumen();
        ```
    === "Resultado"
    	<div style="text-align: left;"><img src="../../img/ut03/e323.png" alt="e323" style="zoom:90%;" /></div>



### Ejercicio 324
Crea la clase `Juego` la cual hereda de `Soporte`. Añade los atributos `consola`, `minNumJugadores` y `maxNumJugadores`. 

A continuación añade el método `muestraJugadoresPosibles`, el cual debe mostrar *Para un jugador*, *Para X jugadores* o *De X a Y jugadores* dependiendo de los valores de las atributos creados. 

Finalmente, sobreescribe tanto el constructor como el método `muestraResumen`.

Añade a `inicio.php` el código para probar la clase:
???examplephp "Ejemplo"
	=== "inicio.php"
        ```php
        <?php
            include_once "Juego.php";

            $miJuego = new Juego("The Last of Us Part II", 26, 49.99, "PS4", 1, 1); 
            echo "<strong>" . $miJuego->titulo . "</strong>"; 
            echo "<br>Precio: " . $miJuego->getPrecio() . " €"; 
            echo "<br>Precio con IVA: " . $miJuego->getPrecioConIva() . " €";
    		echo "<br/>";
    		
            echo $miJuego->muestraResumen();
        ```
    === "Resultado"
    	<div style="text-align: left;"><img src="../../img/ut03/e324.png" alt="e324" style="zoom:90%;" /></div>

Llegados a este punto, nuestro modelo es similar al siguiente diagrama:

<div style="text-align: center;"><figure><img src="../../img/ut03/pro05.png" alt="pro05" style="zoom:85%;" /><figcaption style="font-size: 13px; color: #bd8f04;">Estructura de herencia para el ejercicio 324.</figcaption></figure></div>

### Ejercicio 325
<div style="text-align: center;"><figure><img src="../../img/ut03/pro06.png" alt="pro06" style="zoom:95%;" /><figcaption style="font-size: 13px; color: #bd8f04;">Estructura de la clase Cliente para el ejercicio 325.</figcaption></figure></div>

Crear la clase `Cliente`. El constructor recibirá el `nombre`, `numero` y `maxAlquilerConcurrente`, este último pudiendo ser opcional y tomando como valor por defecto *3*. 

Tras ello, añade *getter/setter* únicamente a `numero`, y un *getter* a `numSoportesAlquilados` (este campo va a almacenar un contador del total de alquileres que ha realizado). 

El array de soportes alquilados `$soportesAlquilados` contedrá clases que hereden de `Soporte`. 

Finalmente, añade el método `muestraResumen` que muestre el *nombre* y la *cantidad de alquileres* (tamaño del array `soportesAlquilados`).

### Ejercicio 326
Dentro de `Cliente`, añade las siguiente operaciones:

- `tieneAlquilado(Soporte $s): bool` : recorre el array de soportes y comprueba si está el soporte alquilado.
- `alquilar(Soporte $s): bool` : debe comprobar si el soporte está alquilado y si no ha superado el cupo de alquileres. Al alquilar, incrementará el `numSoportesAlquilados` y almacenará el soporte en el array. Para cada caso debe mostrar un mensaje informando de lo ocurrido.

### Ejercicio 327
Seguimos con `Cliente` para añadir las operaciones:

- `devolver(int $numSoporte): bool` : debe comprobar que el soporte estaba alquilado y actualizar la cantidad de soportes alquilados. Para cada caso debe mostrar un mensaje informando de lo ocurrido.
- `listarAlquileres(): void` : informa de cuantos alquileres tiene el cliente y los muestra.

Crea el archivo `inicio2.php` con el siguiente código fuente para probar la clase:
???examplephp "Ejemplo"
	=== "inicio2.php"
        ```php
        <?php
            include_once "CintaVideo.php";
            include_once "Dvd.php";
            include_once "Juego.php";
            include_once "Cliente.php";

            //instanciamos un par de objetos cliente
            $cliente1 = new Cliente("Bruce Wayne", 23);
            $cliente2 = new Cliente("Clark Kent", 33);
    
    		echo "<br/>";
            //mostramos el número de cada cliente creado 
            echo "<br>El identificador del cliente 1 es: " . $cliente1->getNumero();
            echo "<br>El identificador del cliente 2 es: " . $cliente2->getNumero();
    
            //instancio algunos soportes 
            $soporte1 = new CintaVideo("Los cazafantasmas", 23, 3.5, 107);
            $soporte2 = new Juego("The Last of Us Part II", 26, 49.99, "PS4", 1, 1);  
            $soporte3 = new Dvd("Origen", 24, 15, ["es","en","fr"], "16:9");
            $soporte4 = new Dvd("El Imperio Contraataca", 4, 3, ["es","en"],"16:9");
    
            //alquilo algunos soportes
            $cliente1->alquilar($soporte1);
            $cliente1->alquilar($soporte2);
            $cliente1->alquilar($soporte3);
    
            //voy a intentar alquilar de nuevo un soporte que ya tiene alquilado
            $cliente1->alquilar($soporte1);
            //el cliente tiene 3 soportes en alquiler como máximo
            //este soporte no lo va a poder alquilar
            $cliente1->alquilar($soporte4);
            //este soporte no lo tiene alquilado
            $cliente1->devolver(4);
            //devuelvo un soporte que sí que tiene alquilado
            $cliente1->devolver(2);
            //alquilo otro soporte
            $cliente1->alquilar($soporte4);
            //listo los elementos alquilados
            $cliente1->listarAlquileres();
            //este cliente no tiene alquileres
            $cliente2->devolver(2);
        ```
    === "Resultado"
    	<div style="text-align: left;"><img src="../../img/ut03/e327.png" alt="e327" style="zoom:100%;" /></div>

### Ejercicio 328
Llegado a este punto, vamos a relacionar los clientes y los soportes mediante la clase `Videoclub`. Así pues crea la clase que representa la imagen posterior, teniendo en cuenta que:

- `productos` es un array de `Soporte`.
- `socios` es una array de `Cliente`.
- Los métodos públicos de incluir algún soporte, crearán la clase y llamarán al método privado de `incluirProducto`, el cual es el encargado de introducirlo dentro del array.

El modelo completo quedará de la siguiente manera:

<div style="text-align: center;"><figure><img src="../../img/ut03/pro07.png" alt="pro07" style="zoom:70%;" /><figcaption style="font-size: 13px; color: #bd8f04;">Estructura de herencia de las clases implicadas (ejercicio328).</figcaption></figure></div>

Y para probar el proyecto, dentro inicio3.php colocaremos:
???examplephp "Ejemplo"
	=== "inicio3.php"
        ```php
        <?php
           include_once "Videoclub.php"; // No incluimos nada más
	
           $vc = new Videoclub("Severo 8A"); 
    
           //voy a incluir unos cuantos soportes de prueba 
           $vc->incluirJuego("God of War", 19.99, "PS4", 1, 1); 
           $vc->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1);
           $vc->incluirDvd("Torrente", 4.5, ["es"],"16:9"); 
           $vc->incluirDvd("Origen", 4.5, ["es","en","fr"], "16:9"); 
           $vc->incluirDvd("El Imperio Contraataca", 3, ["es,en"],"16:9"); 
           $vc->incluirCintaVideo("Los cazafantasmas", 3.5, 107); 
           $vc->incluirCintaVideo("El nombre de la Rosa", 1.5, 140); 
    
           //listo los productos 
           $vc->listarProductos(); 
    
           //voy a crear algunos socios 
           $vc->incluirSocio("Amancio Ortega"); 
           $vc->incluirSocio("Pablo Picasso", 2); 
    
           $vc->alquilarSocioProducto(1,2); 
           $vc->alquilarSocioProducto(1,3); 
           //alquilo otra vez el soporte 2 al socio 1. 
           // no debe dejarme porque ya lo tiene alquilado 
           $vc->alquilarSocioProducto(1,2); 
           //alquilo el soporte 6 al socio 1. 
           //no se puede porque el socio 1 tiene 2 alquileres como máximo 
           $vc->alquilarSocioProducto(1,6); 
    
           //listo los socios 
           $vc->listarSocios();
        ```
    === "Resultado"
    	<div style="text-align: left;"><img src="../../img/ut03/e328.png" alt="e328" style="zoom:100%;" /></div>


### Ejercicio 329
Transforma `Soporte` a una clase abstracta y comprueba que todo sigue funcionando. ¿Qué conseguimos al hacerla abstracta?

### Ejercicio 330
Crea un interfaz `Resumible` de manera que las clases que lo implementen deben ofrecer el método `muestraResumen()`.

Modifica la clase `Soporte` y haz que implemente el interfaz. ¿Hace falta que también lo implementen los hijos?

<hr>

## Proyecto Videoclub 2.0

> **Git del proyecto**
> 
> Antes de comenzar con la segunda parte del videoclub, crea una etiqueta mediante `git tag` con el nombre `v0.330` y sube los cambios a GitHub.

### Ejercicio 331
Modifica las operaciones de alquilar, tanto en `Cliente` (métodos `alquilar`, `devolver`) como en `Videoclub`  (métodos `incluirProducto`, `incluirCintaVideo`, `incluirDvd`, `incluirJuego`, `incluirSocio`, `listarProductos`, `listarSocios`, `alquilarSocioProducto`) , para dar soporte al encadenamiento de métodos. 

Posteriormente, modifica el código de prueba para utilizar esta técnica.
```php
<?php
include_once "Videoclub.php"; // No incluimos nada más

$vc = new Videoclub("Severo 8A");

// Incluir soportes de prueba y crear algunos socios con encadenamiento de métodos
$vc->incluirJuego("God of War", 19.99, "PS4", 1, 1)
   ->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1)
   ->incluirDvd("Torrente", 4.5, ["es"], "16:9")
   ->incluirDvd("Origen", 4.5, ["es", "en", "fr"], "16:9")
   ->incluirDvd("El Imperio Contraataca", 3, ["es", "en"], "16:9")
   ->incluirCintaVideo("Los cazafantasmas", 3.5, 107)
   ->incluirCintaVideo("El nombre de la Rosa", 1.5, 140)
   ->listarProductos() // Listar productos después de añadirlos
   ->incluirSocio("Amancio Ortega") 
   ->incluirSocio("Pablo Picasso", 2)
   ->alquilarSocioProducto(1, 2)
   ->alquilarSocioProducto(1, 3)
   ->alquilarSocioProducto(1, 2) // Intento de alquilar un soporte ya alquilado
   ->alquilarSocioProducto(2, 6) // Intento de alquilar más de lo permitido
   ->listarSocios(); // Listar socios después de realizar las operaciones de alquiler
?>
```


### Ejercicio 332
Haciendo uso de *namespaces*:

- Coloca todas las clases/interfaces en `Dwes\ProyectoVideoclub`.
- Cada clase debe hacer `include_once` de los recursos que emplea.
- Coloca el/los archivos de prueba en el raíz (sin espacio de nombres).
- Desde el archivo de pruebas, utiliza `use` para poder realizar accesos sin cualificar:

```php
<?php
use Dwes\ProyectoVideoclub\Videoclub;

include_once "Videoclub.php";

$vc = new Videoclub("Severo 8A");

// Incluir soportes de prueba y crear algunos socios con encadenamiento de métodos
$vc->incluirJuego("God of War", 19.99, "PS4", 1, 1)
   ->incluirJuego("The Last of Us Part II", 49.99, "PS4", 1, 1)
   ->incluirDvd("Torrente", 4.5, ["es"], "16:9")
   ->incluirDvd("Origen", 4.5, ["es", "en", "fr"], "16:9")
   ->incluirDvd("El Imperio Contraataca", 3, ["es", "en"], "16:9")
   ->incluirCintaVideo("Los cazafantasmas", 3.5, 107)
   ->incluirCintaVideo("El nombre de la Rosa", 1.5, 140)
   ->listarProductos() // Listar productos después de añadirlos
   ->incluirSocio("Amancio Ortega") 
   ->incluirSocio("Pablo Picasso", 2)
   ->alquilarSocioProducto(1, 2)
   ->alquilarSocioProducto(1, 3)
   ->alquilarSocioProducto(1, 2) // Intento de alquilar un soporte ya alquilado
   ->alquilarSocioProducto(2, 6) // Intento de alquilar más de lo permitido
   ->listarSocios(); // Listar socios después de realizar las operaciones de alquiler
?>
```



> **Git del proyecto**
> 
> Etiqueta los cambios como `v0.331`.

### Ejercicio 333
Reorganiza las carpeta tal como hemos visto en los apuntes: `app`, `test` y`vendor`.

- Crea un fichero `autoload.php` para registrar la ruta donde encontrar las clases
- Modifica todo el código necesario, incluyendo `autoload.php` donde sea necesario y borrando los *includes* previos.

### Ejercicio 334
A continuación vamos a crear un conjunto de excepciones de aplicación. Estas excepciones son simples, no necesitan sobreescribir ningún método. Así pues, crea la excepción de aplicación `VideoclubException` en el *namespace* `Dwes\ProyectoVideoclub\Util`. Posteriormente crea los siguientes hijos (deben heredar de `VideoclubException`), cada uno en su propio archivo:

- `SoporteYaAlquiladoException`.
- `CupoSuperadoException`.
- `SoporteNoEncontradoException`.
- `ClienteNoEncontradoException`.

### Ejercicio 335
En `Cliente`, modifica los métodos `alquilar` y `devolver`, para que hagan uso de las nuevas excepciones (lanzándolas cuando sea necesario) y funcionen como métodos encadenados. Destacar que estos métodos, no se capturar estás excepciones, sólo se lanzan. En `Videoclub`, modifica `alquilarSocioPelicula `para capturar todas las excepciones que ahora lanza `Cliente` e informar al usuario en consecuencia.

### Ejercicio 336
Vamos a modificar el proyecto para que el videoclub sepa qué productos están o no alquilados:

- En `Soporte`, crea una propiedad pública cuyo nombre sea `alquilado` que inicialmente estará a `false`. Cuando se alquile, se pondrá a `true`. Al devolver, la volveremos a poner a `false`.
- En `Videoclub`, crea dos nuevas propiedades y sus getters:<br />

	_ `numProductosAlquilados`<br />
	_ `numTotalAlquileres`

### Ejercicio 337
Crea un nuevo método en `Videoclub` llamado `alquilarSocioProductos(int numSocio, array numerosProductos)`, el cual debe recibir un array con los productos a alquilar.

Antes de alquilarlos, debe comprobar que todos los soportes estén disponibles, de manera que si uno no lo está, no se le alquile ninguno.

### Ejercicio 338
Crea dos nuevos métodos en `Videoclub`, y mediante la definición, deduce qué deben realizar:

- `devolverSocioProducto(int numSocio, int numeroProducto)`
- `devolverSocioProductos(int numSocio, array numerosProductos)`

Deben soportar el encadenamiento de métodos. Recuerda actualizar la propiedad `alquilado` de los diferentes soportes.



> **Git del proyecto**
> 
> Cuando hayas realizado todos los ejercicios, crea una etiqueta mediante `git tag` con el nombre `v0.338` y sube los cambios a GitHub.
