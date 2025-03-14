<?php
session_start();
if (!isset($_SESSION['usuario_interno'])) {
    header("Location: login_interno.html");
    exit();
}

include "conexion.php";

$sql = "SELECT * FROM usuarios WHERE tipo = 'externo'";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Externos</title>
    <link rel="stylesheet" href="estilo_admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Usuarios Externos Registrados</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?= $fila['id'] ?></td>
            <td><?= $fila['nombres'] ?></td>
            <td><?= $fila['apellidos'] ?></td>
            <td><?= $fila['email'] ?></td>
            <td>
                <button onclick="verDetalles(<?= $fila['id'] ?>)">Ver</button>
                <button onclick="eliminarUsuario(<?= $fila['id'] ?>)">Eliminar</button>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div id="detallesUsuario" style="display: none;">
        <h3>Detalles del Usuario</h3>
        <p><strong>ID:</strong> <span id="detalle_id"></span></p>
        <p><strong>Nombres:</strong> <span id="detalle_nombres"></span></p>
        <p><strong>Apellidos:</strong> <span id="detalle_apellidos"></span></p>
        <p><strong>Correo:</strong> <span id="detalle_email"></span></p>
        <p><strong>Contraseña:</strong> <span id="detalle_password"></span></p>
        <button onclick="cerrarDetalles()">Cerrar</button>
    </div>

    <script>
        function verDetalles(id) {
            $.post("ver_usuario.php", { id: id }, function(data) {
                let usuario = JSON.parse(data);
                $("#detalle_id").text(usuario.id);
                $("#detalle_nombres").text(usuario.nombres);
                $("#detalle_apellidos").text(usuario.apellidos);
                $("#detalle_email").text(usuario.email);
                $("#detalle_password").text(usuario.password);
                $("#detallesUsuario").show();
            });
        }

        function eliminarUsuario(id) {
    if (confirm("¿Seguro que deseas eliminar este usuario?")) {
        fetch("eliminar_usuario.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "id=" + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                location.reload();
            }
        })
        .catch(error => console.error("Error en la eliminación:", error));
    }
}


    </script>
</body>
</html>
