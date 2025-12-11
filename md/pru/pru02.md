# Controladores y Rutas

## Objetivo de la actividad

<p style="float: left; margin-left: 1rem;">
  <img src="../../img/laravel.svg"
       alt="Actividad en el aula virtual"
       width="150">
</p>

Implementarás **rutas** y un **controlador** que devuelvan listados ordenados y filtrados, y mostrarás los datos en una **vista Blade** mediante una tabla HTML con columnas homogéneas.

Al finalizar, serás capaz de:

* Definir rutas que invocan métodos de un controlador.
* Devolver listados ordenados por diferentes criterios.
* Aplicar filtros sobre consultas de Eloquent.
* Presentar los datos en una vista con una tabla HTML clara y consistente.

---

## Requisitos previos

1. Crea un proyecto laravel de nombre **testear**.
2. **Base de datos**: acceso a la base `testear`.
3. **Tabla `employees`** creada mediante migraciones con la siguiente estructura:

| Campo | Tipo | Restricciones |
| --- | --- | --- |
| `id` | `integer` | Clave primaria, no autoincremental, no nulo |
| `emp_firstname` | `string(100)` | No nulo |
| `emp_lastname` | `string(100)` | No nulo |
| `emp_birth_date` | `date` | No nulo |
| `emp_hire_date` | `date` | No nulo |
| `emp_salary` | `decimal` | Puede ser nulo |
| `created_at` | `timestamp` | Automático |
| `updated_at` | `timestamp` | Automático |

1. **Modelo `Employee`** configurado y operativo (tabla, clave primaria, tipos y asignación masiva coherentes con la estructura anterior).
2. **Datos de prueba**:
   Carga de datos de prueba: importa el anexo SQL.

> **info:** "Importar datos de prueba"
> 
> Lo más rápido es utilizar alguna extensión para vsCode como "SQLTools" o "**MySQL**" para conectarte a la base de datos y ejecutar el script SQL que se proporciona al final de esta actividad.
> 
> ![Extensiones para VSCode](../img/mysqlextension.png)



> **Datos de prueba**: 
> 
> En los recursos de esta actividad hay un fichero [`employees.sql`](../sources/employees.sql){:target="blank"} con 100 empleados ficticios para importar en la tabla `employees`.

---

## Instrucciones paso a paso

### 1. Crear el controlador `EmployeeController`

Crea un controlador para centralizar la lógica de consulta de empleados. Dentro de él implementarás, como mínimo, los métodos que devuelvan colecciones de empleados según los criterios indicados más abajo. Cada método debe:

* Obtener los empleados aplicando el **criterio de ordenación o filtrado** correspondiente.
* Enviar el resultado a una **vista Blade** común (ver punto 3) mediante una variable llamada exactamente `employees`.

Métodos a implementar:

- **Listado por ID ascendente**
   Nombre sugerido del método: `byId`.
   Comportamiento: devuelve todos los empleados ordenados por `emp_id` de menor a mayor.
- **Listado por apellidos y nombre**
   Nombre sugerido del método: `byLastName`.
   Comportamiento: devuelve todos los empleados ordenados por `emp_lastname` (ascendente) y, en caso de empate, por `emp_firstname` (ascendente).
- **Subconjunto por letra inicial de apellido**
   Nombre sugerido del método: `lastNameStartsWith`.
   Comportamiento: devuelve solo los empleados cuyo `emp_lastname` **empiece por la letra “A”**, ordenados por `emp_lastname` y `emp_firstname` (ascendente).
   
   > **Nota**: en este tema el valor “A” es fijo. Las rutas con parámetros dinámicos se verán más adelante.

- **Subconjunto por año de nacimiento**
   Nombre sugerido del método: `bornIn`.
   Comportamiento: devuelve solo los empleados **nacidos en el año 1980**, ordenados por `emp_lastname` y `emp_firstname` (ascendente).

   > **Nota**: el año “1980” es fijo en esta práctica (los parámetros dinámicos se verán más adelante).

Todos los métodos devolverán la misma vista (ver punto 3) y **no** deben repetir lógica de presentación en el controlador.

---

### 2. Definir las rutas

Declara rutas **GET** que apunten a los métodos anteriores. Usa exactamente estas URL para homogeneizar correcciones:

| Ruta | Método del controlador | Descripción |
| --- | --- | --- |
| `/employees/by-id` | `byId` | Listado ordenado por `emp_id` ascendente. |
| `/employees/by-lastname` | `byLastName` | Listado ordenado por apellidos y nombre. |
| `/employees/filter-letter` | `lastNameStartsWith` | Subconjunto: apellidos que empiezan por “A”. |
| `/employees/filter-year` | `bornIn` | Subconjunto: nacidos en el año 1990. |

> Requisitos de las rutas:
> 
> * Todas deben retornar **la misma vista** con la variable `employees`.
> * Usa **nombres de ruta** coherentes para cada una (por ejemplo, `employees.byId`, `employees.byLastName`, `employees.starts`, `employees.born`).

---

### 3. Crear la vista `employees/index.blade.php`

Crea una vista única para los cuatro casos. Esta vista debe:

1. Mostrar un **título** claro del listado.
2. Si no hay registros en `employees`, mostrar un **mensaje**: “No hay empleados que cumplan el criterio.”
3. En caso contrario, presentar una **tabla HTML** con las siguientes columnas y en este orden exacto:

| Columna mostrada | Procede del campo |
| --- | --- |
| **ID** | `emp_id` |
| **Apellidos** | `emp_lastname` |
| **Nombre** | `emp_firstname` |
| **Edad** | calculada a partir de `emp_birth_date` |
| **Fecha de contratación** | `emp_hire_date` formateada en `YYYY-MM-DD` |

1. Mostrar, sobre la tabla o como “caption”, el **total de registros** del listado.

> Notas de presentación:
> 
> * La **Edad** debe calcularse a partir de la fecha de nacimiento. Puedes calcularla en el controlador o en la vista, pero debe mostrarse como número entero de años.
> * La **Fecha de contratación** debe mostrarse en formato `YYYY-MM-DD`.
> * Usa una **tabla legible**: cabecera con títulos, filas con celdas alineadas, y un estilo simple pero claro. No es necesario usar CSS avanzado.

---

## Comprobaciones

Verifica manualmente que:

1. **/employees/by-id**
   Muestra todos los registros ordenados por ID ascendente y las 5 columnas requeridas.
2. **/employees/by-lastname**
   Muestra todos los registros ordenados por apellidos y, en empates, por nombre. Se ven las 5 columnas.
3. **/employees/filter-letter**
   Muestra solo apellidos que empiezan por **A**. Ordenación por apellidos y nombre.
4. **/employees/filter-year**
   Muestra únicamente los empleados nacidos en **1990**. Ordenación por apellidos y nombre.
5. En todos los casos:
   
    - Se muestra el **total** de registros listados.
    - Si no hay coincidencias, aparece el **mensaje** de “No hay empleados que cumplan el criterio.”

---

???questionlaravel "Práctica a entregar"
    
    <p style="float: left; margin-left: 1rem;">
        <img src="../../img/laraveltask.svg"
            alt="Actividad en el aula virtual"
            width="150">
    </p>
    
    1. **Capturas de pantalla** de cada ruta funcionando:
              
         - `/employees/by-id`
         - `/employees/by-lastname`
         - `/employees/filter-letter`
         - `/employees/filter-year`
   
    2. **Listado de rutas** definido (solo nombres y URIs, sin código fuente).
    3. **Descripción breve** de cómo calculas la **Edad** y cómo **formateas** la **Fecha de contratación** (dos o tres líneas).
    4. **Evidencia de datos cargados**: captura del total de filas o vista parcial de la tabla `employees` en tu gestor de BD.

