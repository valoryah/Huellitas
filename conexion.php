<?php
$servidor = "localhost";
$usuario = "root"; // Este es el usuario por defecto en XAMPP
$password = ""; // En XAMPP la contraseña suele estar vacía
$base_datos = "huellitas_db";

// Crear la conexión
$conn = new mysqli($servidor, $usuario, $password, $base_datos);

// Revisar si hay errores
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>