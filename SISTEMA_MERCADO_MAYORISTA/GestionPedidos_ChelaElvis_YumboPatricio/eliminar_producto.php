<?php
// Iniciar la sesión
session_start();

// Verificar si se recibió el ID del producto a eliminar
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];
    
    // Verificar si existe el carrito y el producto en él
    if (isset($_SESSION['carrito']) && isset($_SESSION['carrito'][$producto_id])) {
        // Guardar el nombre del producto para el mensaje
        $nombre_producto = $_SESSION['carrito'][$producto_id]['nombre'];
        
        // Eliminar el producto del carrito
        unset($_SESSION['carrito'][$producto_id]);
        
        // Si el carrito queda vacío, eliminar la variable de sesión
        if (empty($_SESSION['carrito'])) {
            unset($_SESSION['carrito']);
        }
        
        // Mensaje de éxito
        $_SESSION['mensaje'] = [
            'texto' => "Producto '{$nombre_producto}' eliminado del carrito",
            'tipo' => 'success'
        ];
    } else {
        // Mensaje de error
        $_SESSION['mensaje'] = [
            'texto' => "El producto no existe en el carrito",
            'tipo' => 'error'
        ];
    }
} else {
    // Mensaje de error
    $_SESSION['mensaje'] = [
        'texto' => "No se especificó el producto a eliminar",
        'tipo' => 'error'
        ];
}

// Redirigir de vuelta al carrito
header("Location: ver_carrito.php");
exit();
?>