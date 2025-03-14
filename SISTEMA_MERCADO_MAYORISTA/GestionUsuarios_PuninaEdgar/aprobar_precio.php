<?php
require 'conexion.php';

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]); // Asegurar que el ID es un número

    // Obtener la información del cambio de precio
    $sql = "SELECT producto_id, precio_nuevo FROM cambios_precios WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($row = $result->fetch_assoc()) {
        $producto_id = $row["producto_id"];
        $precio_nuevo = $row["precio_nuevo"];

        // Actualizar el precio del producto
        $conn->query("UPDATE productos SET precio = $precio_nuevo WHERE id = $producto_id") or die($conn->error);

        // Marcar como aprobado
        $conn->query("UPDATE cambios_precios SET aprobado = 'aprobado' WHERE id = $id") or die($conn->error);
    }
}

header("Location: panel_admin.php");
exit();
?>

