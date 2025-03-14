<?php
require 'conexion.php';

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    $conn->query("UPDATE cambios_precios SET aprobado = 'rechazado' WHERE id = $id") or die($conn->error);
}

header("Location: panel_admin.php");
exit();
?>

