<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $precio_min = $_POST["precio_min"];
    $precio_max = $_POST["precio_max"];

    $sql = "UPDATE configuracion_precios SET precio_min = $precio_min, precio_max = $precio_max WHERE id = 1";
    $conn->query($sql);
}

header("Location: panel_admin.php");
?>
