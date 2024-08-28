<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirigir a login si no está autenticado
    exit();
}
include 'conexion.php'; // Incluir el archivo de conexión

// Consulta para obtener los usuarios
$sql = "SELECT nombre, apellido, email, telefono FROM usuario";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Contactos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Styles.css">
</head>
<body>
    <?php include 'barralateral.php'; ?> <!-- Incluir la barra lateral -->
    <div class="dashboard">
        <div class="section">
            <br>
            <h2>Lista de Usuarios</h2> <!-- Usar un encabezado adecuado -->
            <table class="table table-striped"> <!-- Agregar clases de Bootstrap -->
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Mostrar cada fila de resultados
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>"; // Escapar salida para seguridad
                            echo "<td>" . htmlspecialchars($row["apellido"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["telefono"]) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No se encontraron usuarios</td></tr>";
                    }
                    $conn->close(); // Cerrar la conexión al final
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
