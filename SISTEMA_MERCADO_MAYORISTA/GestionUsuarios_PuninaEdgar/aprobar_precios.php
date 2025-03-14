<?php
require 'conexion.php';

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Obtener la información del cambio de precio
    $sql = "SELECT producto_id, precio_nuevo FROM cambios_precios WHERE id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row) {
        $producto_id = $row["producto_id"];
        $precio_nuevo = $row["precio_nuevo"];

        // Actualizar el precio en la tabla de productos
        $conn->query("UPDATE productos SET precio = $precio_nuevo WHERE id = $producto_id");

        // Marcar como aprobado
        $conn->query("UPDATE cambios_precios SET aprobado = 'aprobado' WHERE id = $id");
    }
}

header("Location: panel_admin.php");
?>
