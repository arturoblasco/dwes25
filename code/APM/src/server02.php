
<?php
// EJEMPLO:
echo $_SERVER["PHP_SELF"]."<br>";        // /pru04/401server.php
echo $_SERVER["SERVER_SOFTWARE"]."<br>"; // Apache/2.4.62 (Debian) OpenSSL/1.1.1g PHP/7.4.9
echo $_SERVER["SERVER_NAME"]."<br>";     // localhost

echo $_SERVER["REQUEST_METHOD"]."<br>";  // GET
echo $_SERVER["REQUEST_URI"]."<br>";     // /pru04/server.php?nombre=Cambria&stock=14
echo $_SERVER["QUERY_STRING"]."<br>";    // nombre=Cambria&stock=14