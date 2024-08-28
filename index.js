function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var dashboard = document.querySelector('.dashboard');
    var toggleBtn = document.querySelector('.toggle-btn');

    sidebar.classList.toggle('active');

    // Ajustar el margen del dashboard dependiendo del estado del sidebar
    if (sidebar.classList.contains('active')) {
        dashboard.style.marginLeft = sidebar.offsetWidth + 'px'; // Usa el ancho real del sidebar
        toggleBtn.classList.add('hidden'); // Oculta el botón de las tres rayas
    } else {
        dashboard.style.marginLeft = "0"; // Restablece el margen cuando la barra lateral está oculta
        toggleBtn.classList.remove('hidden'); // Muestra el botón de las tres rayas
    }
}

// Agregar un evento de escucha para manejar el cambio de tamaño de la ventana
window.addEventListener('resize', function() {
    var sidebar = document.getElementById('sidebar');
    var dashboard = document.querySelector('.dashboard');
    var toggleBtn = document.querySelector('.toggle-btn');

    // Ajustar el margen del dashboard si la barra lateral está activa
    if (sidebar.classList.contains('active')) {
        dashboard.style.marginLeft = sidebar.offsetWidth + 'px'; // Usa el ancho real del sidebar
    } else {
        dashboard.style.marginLeft = "0"; // Restablece el margen
    }
});
