<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: IniciarSesion.php"); // Redirigir a login si no está autenticado
    exit();
}

// Incluir el archivo de conexión
include 'conexion.php';

// Realizar la consulta para obtener todas las alertas y los departamentos alertados
$query_alertas_departamentos = "
    SELECT 
        a.id AS alerta_id,
        a.mensaje,
        GROUP_CONCAT(DISTINCT d.nombre ORDER BY d.nombre ASC SEPARATOR ', ') AS departamentos_alertados
    FROM 
        alertas a
    JOIN 
        alertas_departamento ad ON a.id = ad.alerta_id
    JOIN 
        departamento d ON ad.departamento_id = d.id
    GROUP BY 
        a.id
    ORDER BY 
        a.id ASC
";

$result_alertas_departamentos = $conn->query($query_alertas_departamentos);

// Verifica si la consulta se ejecutó correctamente
if (!$result_alertas_departamentos) {
    die("Error en la consulta de alertas y departamentos: " . $conn->error);
}

// Realizar la consulta para obtener todas las alertas y los usuarios alertados
$query_alertas_usuarios = "
    SELECT 
        a.id AS alerta_id,
        a.mensaje,
        GROUP_CONCAT(DISTINCT u.nombre ORDER BY u.nombre ASC SEPARATOR ', ') AS usuarios_alertados
    FROM 
        alertas a
    JOIN 
        alertas_usuario au ON a.id = au.alerta_id
    JOIN 
        usuario u ON au.usuario_id = u.id
    GROUP BY 
        a.id
    ORDER BY 
        a.id ASC
";

$result_alertas_usuarios = $conn->query($query_alertas_usuarios);

// Verifica si la consulta se ejecutó correctamente
if (!$result_alertas_usuarios) {
    die("Error en la consulta de alertas y usuarios: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Styles.css">
</head>
<body>
    <?php include 'barralateral.php'; ?> <!-- Incluir la barra lateral -->

    <!-- Contenido principal -->
    <div class="dashboard">
        <div class="section">
            <br>
            <h2>Notificaciones</h2>
            <button class="btn btn-success" onclick="window.location.href='crear_alerta.php'">Crear Nueva Alerta</button>
            
            <!-- Mostrar todas las alertas y departamentos alertados en una tabla -->
            <h3>Alertas y Departamentos</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Alerta ID</th>
                        <th scope="col">Mensaje</th>
                        <th scope="col">Departamentos Alertados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($alerta = $result_alertas_departamentos->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alerta['alerta_id']); ?></td>
                            <td><?php echo htmlspecialchars($alerta['mensaje']); ?></td>
                            <td><?php echo htmlspecialchars($alerta['departamentos_alertados']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <!-- Mostrar todas las alertas y usuarios alertados en una tabla -->
            <h3>Alertas y Usuarios</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Alerta ID</th>
                        <th scope="col">Mensaje</th>
                        <th scope="col">Usuarios Alertados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($alerta_usuario = $result_alertas_usuarios->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alerta_usuario['alerta_id']); ?></td>
                            <td><?php echo htmlspecialchars($alerta_usuario['mensaje']); ?></td>
                            <td><?php echo htmlspecialchars($alerta_usuario['usuarios_alertados']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="index.js"></script>
</body>
</html>
