<?php
$servername = "localhost"; // o 127.0.0.1
$username = "root"; // usuario por defecto de XAMPP
$password = ""; // contraseña por defecto de XAMPP (usualmente está vacío)
$dbname = "notificaciones"; // nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} 
// No cerrar la conexión aquí
?>
