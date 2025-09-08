<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no autorizado.');
}

include "config/database.inc.php";

$conexion = null;

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;", $usuario, $password); // Crear una nueva conexión
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recoger datos del formulario
    $nombre = trim($_POST["nombre"]);
    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    // Validar que no haya campos vacíos
    if (empty($nombre) || empty($usuario) || empty($password) || empty($email)) {
        die('Error: No se permiten campos vacíos.');
    }

    // Cifrar la contraseña
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Preparar e insertar datos
    $sql = "INSERT INTO usuario (nombre, usuario, password, email) 
                        VALUES (:nombre, :usuario, :password, :email)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':password', $passwordHash);
    $stmt->bindParam(':email', $email);

    $stmt->execute();

    echo "El usuario: <strong>$usuario</strong> ha sido introducido y con contraseña: <strong>$password</strong>.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conexion = null;


?>