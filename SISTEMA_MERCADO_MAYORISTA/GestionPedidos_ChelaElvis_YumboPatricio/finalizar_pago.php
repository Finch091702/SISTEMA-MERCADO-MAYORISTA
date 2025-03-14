<?php
// Asumimos que ya tienes una sesión iniciada para el carrito
session_start();
require_once 'config/conexion.php';

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y procesar el pago
    if (isset($_POST['metodoPago'])) {
        $metodo_pago = $_POST['metodoPago'];
        $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
        $telefono = isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '';
        $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
        
        // Datos de dirección
        $direccion = isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : '';
        
        // Generar un número de orden único
        $numero_orden = 'ORD-' . time() . '-' . rand(1000, 9999);
        
        try {
            // Iniciar transacción
            $pdo->beginTransaction();
            
            // 1. Guardar los datos del pedido en la tabla de órdenes (debes crear esta tabla)
            $sql_orden = "INSERT INTO ordenes (orden_id, cliente_nombre, cliente_telefono, cliente_email, metodo_pago, total) 
                          VALUES (:orden_id, :nombre, :telefono, :email, :metodo_pago, :total)";
            $stmt_orden = $pdo->prepare($sql_orden);
            $stmt_orden->bindParam(':orden_id', $numero_orden);
            $stmt_orden->bindParam(':nombre', $nombre);
            $stmt_orden->bindParam(':telefono', $telefono);
            $stmt_orden->bindParam(':email', $email);
            $stmt_orden->bindParam(':metodo_pago', $metodo_pago);
            $stmt_orden->bindParam(':total', $_SESSION['total']);
            $stmt_orden->execute();
            
            // 2. Guardar la dirección de envío
            $sql_direccion = "INSERT INTO direcciones (orden_id, nombre_cliente, direccion, ciudad, provincia, codigo_postal, instrucciones) 
                              VALUES (:orden_id, :nombre, :direccion, :ciudad, :provincia, :codigo_postal, :instrucciones)";
            $stmt_direccion = $pdo->prepare($sql_direccion);
            $stmt_direccion->bindParam(':orden_id', $numero_orden);
            $stmt_direccion->bindParam(':nombre', $nombre);
            $stmt_direccion->bindParam(':direccion', $direccion);
            
            // 3. Guardar los productos del carrito (debes crear esta tabla)
            if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
                $sql_detalle = "INSERT INTO detalles_orden (orden_id, producto_id, producto_nombre, cantidad, precio_unitario) 
                                VALUES (:orden_id, :producto_id, :producto_nombre, :cantidad, :precio)";
                $stmt_detalle = $pdo->prepare($sql_detalle);
                
                foreach ($_SESSION['carrito'] as $id => $item) {
                    $stmt_detalle->bindParam(':orden_id', $numero_orden);
                    $stmt_detalle->bindParam(':producto_id', $id);
                    $stmt_detalle->bindParam(':producto_nombre', $item['nombre']);
                    $stmt_detalle->bindParam(':cantidad', $item['cantidad']);
                    $stmt_detalle->bindParam(':precio', $item['precio']);
                    $stmt_detalle->execute();
                }
            }
            
            // Confirmar la transacción
            $pdo->commit();
            
            // Guardar el número de orden en la sesión
            $_SESSION['orden'] = $numero_orden;
            
            // Redirigir a la página de confirmación según el método de pago
            if ($metodo_pago == 'transferencia') {
                header("Location: confirmacion_transferencia.php");
                exit;
            } else if ($metodo_pago == 'efectivo') {
                header("Location: confirmacion_efectivo.php");
                exit;
            }
            
        } catch (PDOException $e) {
            // Si hay error, revertir la transacción
            $pdo->rollBack();
            $_SESSION['mensaje'] = [
                'texto' => "Error al procesar la orden: " . $e->getMessage(),
                'tipo' => 'error'
            ];
            header("Location: procesar_compra.php");
            exit;
        }
    }
}

// Si el código llega aquí, significa que hubo algún problema
$_SESSION['mensaje'] = [
    'texto' => "Hubo un problema al procesar la compra. Por favor intente nuevamente.",
    'tipo' => 'error'
];
header("Location: procesar_compra.php");
exit;
?>