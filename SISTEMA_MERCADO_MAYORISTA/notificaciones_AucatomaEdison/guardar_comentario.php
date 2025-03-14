<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "Opinion");

// Verificar si hay errores de conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer la codificación UTF-8 para la conexión
$conexion->set_charset("utf8");

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y sanitizar el comentario
    $comentario = $conexion->real_escape_string($_POST["comentario"]);

    // Preparar la consulta SQL
    $stmt = $conexion->prepare("INSERT INTO reseñas (comentario) VALUES (?)");
    
    if ($stmt) {
        // Vínculo de parámetros (s = string)
        $stmt->bind_param("s", $comentario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            header("Location: index.html"); // Redirigir a la página principal
            exit();
        } else {
            echo "Error al guardar el comentario: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }
}

// Cerrar la conexión
$conexion->close();
?>
