<?php
session_start();
include 'conexion.php'; // Incluir el archivo de conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar el usuario
    $sql = "SELECT * FROM login WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Iniciar sesión si las credenciales son correctas
        $_SESSION['username'] = $username;
        header("Location: index.php"); // Redirigir a index.php
        exit();
    } else {
        // Si las credenciales son incorrectas, redirigir al formulario con un mensaje de error
        header("Location: login.php?error=1");
        exit();
    }
}

// Cerrar conexión
$conn->close();
?>
