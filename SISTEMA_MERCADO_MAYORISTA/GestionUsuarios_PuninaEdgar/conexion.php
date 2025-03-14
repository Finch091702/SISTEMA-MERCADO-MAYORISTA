<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$bd = "gestion_usuarios";

$conn = new mysqli($servidor, $usuario, $clave, $bd);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
