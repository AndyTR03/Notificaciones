<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: IniciarSesion.php");
    exit();
}

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje = $_POST['mensaje'];
    $tipo_alerta = $_POST['tipo_alerta'];

    // Obtener el próximo valor de ID para la alerta
    $result = $conn->query("SELECT COALESCE(MAX(id), 0) + 1 AS new_id FROM alertas");
    $row = $result->fetch_assoc();
    $alerta_id = $row['new_id'];

    // Insertar la nueva alerta
    $stmt = $conn->prepare("INSERT INTO alertas (id, mensaje) VALUES (?, ?)");
    $stmt->bind_param("is", $alerta_id, $mensaje);
    
    if (!$stmt->execute()) {
        echo "Error al insertar la alerta: " . $stmt->error; // Manejo de errores
        exit();
    }

    // Asociar alerta con departamentos o usuarios
    if ($tipo_alerta === 'departamento' && !empty($_POST['departamentos'])) {
        $departamentos = $_POST['departamentos']; // Asegúrate de que esto se reciba correctamente

        // Asegurarte de que sea un array
        if (!is_array($departamentos)) {
            $departamentos = explode(',', $departamentos); // Convertir a array si es necesario
        }

        $stmt = $conn->prepare("INSERT INTO alertas_departamento (id, alerta_id, departamento_id) VALUES (?, ?, ?)");

        foreach ($departamentos as $departamento_id) {
            // Obtener el próximo valor de ID para la relación alertas_departamento
            $result = $conn->query("SELECT COALESCE(MAX(id), 0) + 1 AS new_rel_id FROM alertas_departamento");
            $row = $result->fetch_assoc();
            $rel_id = $row['new_rel_id'];

            $stmt->bind_param("iii", $rel_id, $alerta_id, $departamento_id);
            if (!$stmt->execute()) {
                echo "Error en la inserción de departamento: " . $stmt->error; // Manejo de errores
            }
        }
    } elseif ($tipo_alerta === 'persona' && !empty($_POST['usuarios'])) {
        $usuarios = $_POST['usuarios']; // Asegúrate de que esto se reciba correctamente

        // Asegurarte de que sea un array
        if (!is_array($usuarios)) {
            $usuarios = explode(',', $usuarios); // Convertir a array si es necesario
        }

        $stmt = $conn->prepare("INSERT INTO alertas_usuario (id, alerta_id, usuario_id) VALUES (?, ?, ?)");

        foreach ($usuarios as $usuario_id) {
            // Obtener el próximo valor de ID para la relación alertas_usuario
            $result = $conn->query("SELECT COALESCE(MAX(id), 0) + 1 AS new_rel_id FROM alertas_usuario");
            $row = $result->fetch_assoc();
            $rel_id = $row['new_rel_id'];

            $stmt->bind_param("iii", $rel_id, $alerta_id, $usuario_id);
            if (!$stmt->execute()) {
                echo "Error en la inserción de usuario: " . $stmt->error; // Manejo de errores
            }
        }
    }
// Configurar y enviar el mensaje de WhatsApp
$url = "https://graph.facebook.com/v20.0/433815236473844/messages";
$token = "EAAPocbe4RbYBO6fW46GdRrZBTfx4ujUx4ChTzCCsmQoNuKcYz3sAsiPqbta7TRZBQ1YNogx8SVc0KSNDQhuHaUVIB3dJv0YvY19hABCoc1eZB73aoOBC0AXrpkB9rzfebCszsh9YhWunso6Elvr6uZBopsoIIoZBG6ZBbrAR8loTIUhF7kZA3GeZBaSo8tUE0ZCjEWWA27mYZCVMtc0txa3uUZD";

$data = [
    "messaging_product" => "whatsapp",
    "to" => "51907988785",
    "type" => "template",
    "template" => [
        "name" => "andy", // Nombre de la plantilla aprobada
        "language" => [
            "code" => "es"
        ],
        "components" => [
            [
                "type" => "body",
                "parameters" => [
                    [
                        "type" => "text",
                        "text" => $mensaje // Este es el valor que reemplazará {{1}}
                    ]
                ]
            ]
        ]
    ]
];

$headers = [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    // Si hay un error en la conexión, mostrar un mensaje de alerta y redirigir
    echo '<script>
            alert("Error al enviar el mensaje: ' . curl_error($ch) . '");
            window.location.href = "crear_alerta.php";
          </script>';
} else {
    // Verificar la respuesta de la API de WhatsApp
    $response_data = json_decode($response, true);
    if (isset($response_data['error'])) {
        // Mostrar un mensaje de alerta si hay un error en la respuesta y redirigir
        echo '<script>
                alert("Error: ' . $response_data['error']['message'] . '");
                window.location.href = "crear_alerta.php";
              </script>';
    } else {
        // Redirigir a notificaciones.php si el mensaje se envió correctamente
        header("Location: notificaciones.php");
        exit();
    }
}

curl_close($ch);
}

