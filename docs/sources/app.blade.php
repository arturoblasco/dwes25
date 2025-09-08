<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Mi proyecto - @yield('titulo')</title>
    
  <!-- introducir la siguiente linea para poder utilizar TailwindCSS -->
  @vite('resources/css/app.css')
    
</head>
    
<body class="bg-gray-100">
  <header class="p-5 border-b bg-white shadow">
    <div class="container mx-auto flex justify-between items-center">
       <h1 class="text-3xl font-black">
           @yield('titulo')
       </h1>

       <nav class="flex gap-5 items-center">
           <a class="font-bold uppercase text-gray-600 text-sm" href="#">Login</a>
           <a class="font-bold uppercase text-gray-600 text-sm"  href="#">Crear cuenta</a>
       </nav>            
    </div>
  </header>
    
  <!-- BORRAR MÁS ADELANTE - este menú de navegación -->
  <nav class="flex gap-5 items-center">
       <a class="font-bold uppercase text-gray-600 text-sm"
          href={{ route('inicio') }} >inicio</a> |
       <a class="font-bold uppercase text-gray-600 text-sm"  
          href={{ route('noticias') }} >blogs</a> |
       <a class="font-bold uppercase text-gray-600 text-sm"  
          href={{ route('galeria') }} >fotos</a>
    </nav>    
    <hr>
    
    <!-- CONTENIDO PRINCIPAL -->
    <main class="container mx-auto mt-10">
       <h2 class="font-black text-center text-3xl mb-10">
           @yield('titulo')
       </h2>
       @yield('contenido')
    </main>    
    
    <!-- FOOTER -->
    <footer class="text-center p-5 text-gray-500 font-bold uppercase">
       MiPrimeraWeb - Todos los derechos reservados @php echo date('Y') @endphp
       <br>
       <!-- con helpers -->
       MiPrimeraWeb - Todos los derechos reservados {{ now()->year}}
    </footer>    
</body>
</html>
