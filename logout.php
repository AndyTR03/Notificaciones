<?php
session_start();
session_unset(); // Limpiar todas las variables de sesión
session_destroy(); // Destruir la sesión
header("Location: IniciarSesion.php"); // Redirigir al usuario a la página de inicio de sesión
exit();
?>
