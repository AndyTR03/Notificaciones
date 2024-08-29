<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: IniciarSesion.php");
    exit();
}

include 'conexion.php';

// Obtener lista de departamentos
$departamentos = $conn->query("SELECT id, nombre FROM departamento");

// Obtener lista de usuarios
$usuarios = $conn->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM usuario");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Alerta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .selected {
            background-color: green; /* Color verde para indicar selección */
            color: white; /* Cambia el color del texto a blanco */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Crear Nueva Alerta</h2>
        <form action="guardar_alerta.php" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="mensaje">Mensaje:</label>
                <input type="text" class="form-control" id="mensaje" name="mensaje" required>
            </div>
            <div class="form-group">
                <label for="tipo_alerta">Tipo de Alerta:</label>
                <select class="form-control" id="tipo_alerta" name="tipo_alerta" required onchange="toggleLista(this.value)">
                    <option value="departamento">Departamentos</option>
                    <option value="persona">Personas</option>
                </select>
            </div>
            <div class="form-group" id="lista_departamentos">
                <label for="departamentos">Seleccione Departamentos:</label>
                <div class="list-group" id="departamentos">
                    <?php while($row = $departamentos->fetch_assoc()): ?>
                        <div class="list-group-item" data-id="<?php echo $row['id']; ?>" onclick="toggleDepartmentSelection(this)">
                            <?php echo $row['nombre']; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="form-group" id="lista_usuarios" style="display: none;">
                <label for="usuarios">Seleccione Usuarios:</label>
                <div class="list-group" id="usuarios">
                    <?php while($row = $usuarios->fetch_assoc()): ?>
                        <div class="list-group-item" data-id="<?php echo $row['id']; ?>" onclick="toggleUserSelection(this)">
                            <?php echo $row['nombre_completo']; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <input type="hidden" name="departamentos" id="departamentos_input">
            <input type="hidden" name="usuarios" id="usuarios_input">
            <button type="submit" class="btn btn-primary">Guardar Alerta</button>
            <button type="button" class="btn btn-primary" onclick="location.href='Notificaciones.php'">Cancelar</button>
        </form>
    </div>

    <script>
        function toggleLista(value) {
            if (value === 'departamento') {
                document.getElementById('lista_departamentos').style.display = 'block';
                document.getElementById('lista_usuarios').style.display = 'none';
                clearSelection('usuarios');
            } else {
                document.getElementById('lista_departamentos').style.display = 'none';
                document.getElementById('lista_usuarios').style.display = 'block';
                clearSelection('departamentos');
            }
        }

        function toggleUserSelection(element) {
            element.classList.toggle('selected'); // Cambia la clase para seleccionar/deseleccionar
            updateUserSelection(); // Actualiza la selección de usuarios
        }

        function toggleDepartmentSelection(element) {
            element.classList.toggle('selected'); // Cambia la clase para seleccionar/deseleccionar
            updateDepartmentSelection(); // Actualiza la selección de departamentos
        }

        function clearSelection(listId) {
            const listItems = document.querySelectorAll(`#${listId} .list-group-item`);
            listItems.forEach(item => {
                item.classList.remove('selected');
            });
        }

        function updateUserSelection() {
            const selectedUsers = [];
            const selectedItems = document.querySelectorAll('#usuarios .selected');
            selectedItems.forEach(item => {
                selectedUsers.push(item.getAttribute('data-id'));
            });
            document.getElementById('usuarios_input').value = selectedUsers.join(','); // Asigna los IDs seleccionados al input oculto
        }

        function updateDepartmentSelection() {
            const selectedDepartments = [];
            const selectedItems = document.querySelectorAll('#departamentos .selected');
            selectedItems.forEach(item => {
                selectedDepartments.push(item.getAttribute('data-id'));
            });
            document.getElementById('departamentos_input').value = selectedDepartments.join(','); // Asigna los IDs seleccionados al input oculto
        }

        function validateForm() {
            const departamentosSelected = document.querySelectorAll('#departamentos .selected').length;
            const usuariosSelected = document.querySelectorAll('#usuarios .selected').length;

            // Asegurarse de que se seleccione uno de los dos
            if ((departamentosSelected === 0 && usuariosSelected === 0) || 
                (departamentosSelected > 0 && usuariosSelected > 0)) {
                alert('Seleccione solo departamentos o solo usuarios, no ambos.');
                return false; // Evitar el envío del formulario
            }
            return true; // Permitir el envío del formulario
        }
    </script>
</body>
</html>
