<?php
include "conexion.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($fila = $resultado->fetch_assoc()) {
        echo json_encode($fila);
    }
}

$conn->close();
?>
