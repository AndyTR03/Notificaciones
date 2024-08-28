<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: IniciarSesion.php"); // Redirigir a login si no está autenticado
    exit();
}

// Incluir el archivo de conexión
include 'conexion.php';

// Obtener el nombre de usuario de la sesión
$usuario = $_SESSION['username'];

// Consulta para obtener los departamentos del usuario
$sql = "SELECT d.nombre AS departamento
        FROM login l
        JOIN usuario_departamento ud ON l.usuario_id = ud.usuario_id
        JOIN departamento d ON ud.departamento_id = d.id
        WHERE l.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

// Almacenar los departamentos en la sesión
$_SESSION['departamentos'] = [];
while ($row = $result->fetch_assoc()) {
    $_SESSION['departamentos'][] = $row['departamento'];
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Styles.css">
</head>
<body>
    <?php include 'barralateral.php'; ?> <!-- Incluir la barra lateral -->

    <!-- Contenido principal -->
    <div class="dashboard">
        <div class="section">
        <br>
        <h2>Proyectos</h2>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="index.js"></script>
</body>
</html>
