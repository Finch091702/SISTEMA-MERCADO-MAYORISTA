<?php
include("conexion.php");

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Verifica que la conexión existe
    if ($conn) {
        // Usa el nombre correcto de la tabla
        $sql = $conn->prepare("DELETE FROM usuarios_externos WHERE id = ?");
        $sql->bind_param("i", $id);

        if ($sql->execute()) {
            echo "Usuario eliminado correctamente.";
        } else {
            echo "Error al eliminar usuario: " . $conn->error;
        }

        $sql->close();
    } else {
        echo "Error de conexión con la base de datos.";
    }
} else {
    echo "ID de usuario no especificado.";
}

$conn->close();
?>
