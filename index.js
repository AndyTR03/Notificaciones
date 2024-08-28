function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var dashboard = document.querySelector('.dashboard');
    var toggleBtn = document.querySelector('.toggle-btn');

    sidebar.classList.toggle('active');

    if (sidebar.classList.contains('active')) {
        dashboard.style.marginLeft = "220px"; // Ajusta el margen cuando la barra lateral está visible
        toggleBtn.classList.add('hidden'); // Oculta el botón de las tres rayas
    } else {
        dashboard.style.marginLeft = "0";
        toggleBtn.classList.remove('hidden'); // Muestra el botón de las tres rayas
    }
}
