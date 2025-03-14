<?php
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);

    // Consultar el usuario en la base de datos
    $sql = "SELECT * FROM usuarios_internos WHERE usuario = ? AND password = MD5(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION["usuario_interno"] = $usuario;
        header("Location: dashboard_interno.php"); // Redirigir al panel de administración
        exit();
    } else {
        echo "Credenciales incorrectas. <a href='login_interno.html'>Volver</a>";
    }

    $stmt->close();
    $conn->close();
}
?>
