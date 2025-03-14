<?php
session_start();
if (!isset($_SESSION["usuario_interno"])) {
    header("Location: login_interno.html");
    exit();
}

require 'conexion.php';

// Obtener cambios de precios pendientes
$sql = "SELECT cp.id, p.nombre, cp.precio_nuevo, cp.aprobado 
        FROM cambios_precios cp
        JOIN productos p ON cp.producto_id = p.id
        WHERE cp.aprobado = 'pendiente'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="estilo_admin.css">
</head>
<body>
    <h2>Panel de Administración - Cambios de Precios</h2>
    <table border="1">
        <tr>
            <th>Producto</th>
            <th>Nuevo Precio</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["nombre"]; ?></td>
            <td><?php echo $row["precio_nuevo"]; ?></td>
            <td>
                <a href="aprobar_precio.php?id=<?php echo $row['id']; ?>">Aprobar</a> | 
                <a href="rechazar_precio.php?id=<?php echo $row['id']; ?>">Rechazar</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h2>Definir Rango de Precios</h2>
    <form action="guardar_rangos.php" method="POST">
        <label>Precio Mínimo:</label>
        <input type="number" step="0.01" name="precio_min" required>
        <label>Precio Máximo:</label>
        <input type="number" step="0.01" name="precio_max" required>
        <button type="submit">Guardar</button>
    </form>

    <br>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
