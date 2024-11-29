<?php
$host = "localhost"; // Cambia si usas otro host
$user = "root"; // Cambia al nombre de usuario de tu base de datos
$password = "123456"; // Ingresa tu contraseña
$dbname = "autismo"; // Nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
