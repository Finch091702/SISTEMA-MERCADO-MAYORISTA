<?php
// Configurar conexión a MySQL
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "Opinion";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Obtener los comentarios de la base de datos
$sql = "SELECT * FROM reseñas";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    // Mostrar los comentarios
    while($row = $resultado->fetch_assoc()) {
        echo "<div class='comentario'>";
        echo "<p>" . htmlspecialchars($row["comentario"]) . "</p>";
        echo "</div>";
    }
} else {
    echo "No hay comentarios aún.";
}

// Cerrar la conexión
$conexion->close();
?>
