<!-- sidebar.php -->
<!-- Botón para abrir/cerrar la barra lateral -->
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="Styles.css"> <!-- Enlace a tus estilos personalizados -->
</head>

<div class="toggle-btn" onclick="toggleSidebar()">&#9776;</div>

<!-- Barra lateral -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3>Menú</h3>
        <button onclick="toggleSidebar()" style="color: white; background: none; border: none; font-size: 24px;">&times;</button>
    </div>
    <ul>
        <li><a href="index.php"><i class="fas fa-arrow-right"></i> Inicio</a></li>
        <li><a href="Login.php"><i class="fas fa-arrow-right"></i> Login</a></li>
        <li><a href="Usuarios.php"><i class="fas fa-arrow-right"></i> Usuario</a></li>
        <li><a href="departamento.php"><i class="fas fa-arrow-right"></i> Departamento</a></li>
        <li><a href="#"><i class="fas fa-arrow-right"></i> Notificación</a></li>
    </ul>

    <!-- Información del usuario -->
    <div class="user-info">
        <img src="https://www.ivan-garcia.com/blog/wp-content/uploads/2019/01/foto-perfil-dni-2019.jpg" alt="Imagen de perfil" class="user-avatar">
        <div>
            <!-- Mensaje de bienvenida -->
            <div class="welcome-message">
                <h6><?php echo $_SESSION['username']; ?></h6>
                <h6>
                    <?php
                    // Mostrar departamentos
                    if (!empty($_SESSION['departamentos'])) {
                        echo implode(', ', $_SESSION['departamentos']); // Mostrar departamentos separados por comas
                    } else {
                        echo 'Sin departamentos asignados';
                    }
                    ?>
                </h6>
            </div>
        </div>
        <!-- Icono de configuración con opción de salida -->
        <i class="fas fa-sign-out-alt exit-icon" onclick="openLogoutModal()"></i>
    </div>
</div>

<!-- Modal de confirmación de cierre de sesión -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeLogoutModal()">&times;</span>
        <h2>Confirmar cierre de sesión</h2>
        <p>¿Estás seguro de que deseas salir?</p>
        <div class="button-container">
            <button class="confirm-btn" onclick="confirmLogout()">Sí, salir</button>
            <button class="cancel-btn" onclick="closeLogoutModal()">Cancelar</button>
        </div>
    </div>
</div>

<script src="index.js"></script>

<!-- Scripts para manejar la ventana modal -->
<script>
    
document.getElementById('logoutModal').style.display = 'none';

function openLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex'; // Cambiar a 'flex' para centrar el modal
    document.body.style.overflow = 'hidden'; // Evitar el desplazamiento del fondo
    document.body.classList.add('modal-open'); // Añadir clase para desactivar el fondo
}

function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none'; // Ocultar el modal
    document.body.style.overflow = 'auto'; // Habilitar el desplazamiento del fondo
    document.body.classList.remove('modal-open'); // Quitar clase para reactivar el fondo
}

function confirmLogout() {
    window.location.href = 'logout.php'; // Redirige para cerrar sesión
}

</script>
