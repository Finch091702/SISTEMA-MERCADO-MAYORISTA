<?php
require 'config/conexion.php'; // Incluir archivo de conexión
// Iniciar la sesión
session_start();

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && 
    isset($_POST['id']) && 
    isset($_POST['nombre']) && 
    isset($_POST['precio']) && 
    isset($_POST['cantidad']) && 
    isset($_POST['local'])) {
    
    // Obtener los datos del formulario
    $producto_id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = (float)$_POST['precio'];
    $cantidad = (int)$_POST['cantidad'];
    $local = $_POST['local'];
    $imagen = isset($_POST['imagen']) ? $_POST['imagen'] : 'default-product.jpg';
    
    // Validar que la cantidad sea un número positivo
    if ($cantidad <= 0) {
        $cantidad = 1;
    }
    
    // Validar que el local no esté vacío
    if (empty($local)) {
        $_SESSION['mensaje'] = [
            'texto' => "El local es obligatorio para agregar el producto",
            'tipo' => 'error'
        ];
        header("Location: index.php");
        exit();
    }
    
    // Inicializar el carrito si aún no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    
    // Verificar si el producto ya está en el carrito
    if (isset($_SESSION['carrito'][$producto_id])) {
        // Sumar la cantidad
        $_SESSION['carrito'][$producto_id]['cantidad'] += $cantidad;
    } else {
        // Agregar el producto al carrito
        $_SESSION['carrito'][$producto_id] = [
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => $cantidad,
            'imagen' => $imagen,
            'local' => $local
        ];
    }
    
    // Mensaje de éxito
    $_SESSION['mensaje'] = [
        'texto' => "Producto '{$nombre}' agregado al carrito del local '{$local}'",
        'tipo' => 'success'
    ];
    
} else {
    // Mensaje de error
    $_SESSION['mensaje'] = [
        'texto' => "Datos del formulario incompletos",
        'tipo' => 'error'
    ];
}

// Redirigir a la página del carrito
header("Location: ver_carrito.php");
exit();
?>