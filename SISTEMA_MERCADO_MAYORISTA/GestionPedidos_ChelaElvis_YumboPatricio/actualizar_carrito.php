<?php
// Iniciar la sesión
session_start();

// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && 
    isset($_POST['cantidad']) && 
    isset($_POST['local'])) {
    
    // Verificar que exista el carrito
    if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
        // Recorrer las cantidades y locales recibidos
        foreach ($_SESSION['carrito'] as $id => $item) {
            // Validar cantidad
            $cantidad = isset($_POST['cantidad'][$id]) ? (int)$_POST['cantidad'][$id] : $item['cantidad'];
            $cantidad = $cantidad <= 0 ? 1 : $cantidad;
            
            // Validar local
            $local = isset($_POST['local'][$id]) ? trim($_POST['local'][$id]) : $item['local'];
            
            // Verificar que el local no esté vacío
            if (empty($local)) {
                $_SESSION['mensaje'] = [
                    'texto' => "El local no puede estar vacío para el producto " . $item['nombre'],
                    'tipo' => 'error'
                ];
                header("Location: ver_carrito.php");
                exit();
            }
            
            // Actualizar la cantidad y el local
            $_SESSION['carrito'][$id]['cantidad'] = $cantidad;
            $_SESSION['carrito'][$id]['local'] = $local;
        }
        
        // Mensaje de éxito
        $_SESSION['mensaje'] = [
            'texto' => "Carrito actualizado correctamente",
            'tipo' => 'success'
        ];
    } else {
        // Mensaje de error
        $_SESSION['mensaje'] = [
            'texto' => "No hay productos en el carrito",
            'tipo' => 'error'
        ];
    }
} else {
    // Mensaje de error
    $_SESSION['mensaje'] = [
        'texto' => "No se recibieron datos para actualizar",
        'tipo' => 'error'
    ];
}

// Redirigir de vuelta al carrito
header("Location: ver_carrito.php");
exit();
?>