<?php
require 'config/conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0;
    $categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';
    $local = isset($_POST['local']) ? trim($_POST['local']) : '';
    $imagen = 'default-product.jpg'; // Valor predeterminado

    // Manejar la imagen desde URL
    if (!empty($_POST['imagen_url'])) {
        $imagen_url = filter_var($_POST['imagen_url'], FILTER_VALIDATE_URL);
        
        if ($imagen_url) {
            // Generar un nombre único para la imagen
            $ext = pathinfo(parse_url($imagen_url, PHP_URL_PATH), PATHINFO_EXTENSION);
            $ext = $ext ?: 'jpg'; // default a jpg si no se encuentra extensión
            $newname = 'producto_url_' . time() . '.' . $ext;
            $target = 'images/products/' . $newname;
            
            // Crear directorio si no existe
            if (!file_exists('images/products/')) {
                mkdir('images/products/', 0777, true);
            }
            
            // Intentar descargar la imagen
            try {
                $image_content = @file_get_contents($imagen_url);
                if ($image_content !== false) {
                    file_put_contents($target, $image_content);
                    $imagen = $newname;
                }
            } catch (Exception $e) {
                // Si falla la descarga, usa imagen predeterminada
                error_log("Error al descargar imagen: " . $e->getMessage());
            }
        }
    }

    if (!empty($nombre) && $precio > 0 && !empty($categoria) && !empty($local)) {
        try {
            $sql = "INSERT INTO productos (nombre, precio, categoria, imagen, local) 
                    VALUES (:nombre, :precio, :categoria, :imagen, :local)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->bindParam(':local', $local);
            
            if ($stmt->execute()) {
                $mensaje = "Producto '{$nombre}' agregado exitosamente en el local '{$local}'.";
                $tipo = "success";
            } else {
                $mensaje = "Error al agregar el producto.";
                $tipo = "error";
            }
        } catch (PDOException $e) {
            $mensaje = "Error en la base de datos: " . $e->getMessage();
            $tipo = "error";
        }
    } else {
        $mensaje = "Todos los campos son obligatorios y el precio debe ser mayor que cero.";
        $tipo = "error";
    }
    
    // Guardar mensaje en sesión
    $_SESSION['mensaje'] = [
        'texto' => $mensaje,
        'tipo' => $tipo
    ];
    
    // Redirigir de vuelta a la página de registro
    header("Location: servicios/registro_productos.php");
    exit();
}
?>