<?php
session_start();
require_once 'config/conexion.php';

// Verificar que existe una orden
if (!isset($_SESSION['orden'])) {
    header("Location: index.php");
    exit;
}

$numero_orden = $_SESSION['orden'];

// Obtener datos de dirección
$datos_direccion = [];
try {
    $sql = "SELECT * FROM direcciones WHERE orden_id = :orden_id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':orden_id', $numero_orden);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $datos_direccion = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    // Si hay error, simplemente continuamos sin mostrar la dirección
    error_log("Error al obtener dirección: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pago en Efectivo</title>
    <link rel="stylesheet" href="style/styles.css">
</head>
<body>
    <div class="container" id="printable-area">
        <h1>¡Gracias por tu compra!</h1>
        
        <div class="confirmation-box">
            <h2>Detalles de tu orden</h2>
            <p><strong>Número de orden:</strong> <?php echo htmlspecialchars($numero_orden); ?></p>
            <p><strong>Método de pago:</strong> Pago en efectivo</p>
            <p><strong>Monto a pagar:</strong> $<?php echo number_format(isset($_SESSION['total']) ? $_SESSION['total'] : 0, 2); ?></p>
        </div>
        
        <?php if (!empty($datos_direccion)): ?>
        <div class="address-box">
            <h3>Dirección </h3>
            <p><strong>Destinatario:</strong> <?php echo htmlspecialchars($datos_direccion['nombre_cliente']); ?></p>
            <?php if (!empty($datos_direccion['instrucciones'])): ?>
            <p><strong>Instrucciones de entrega:</strong> <?php echo htmlspecialchars($datos_direccion['instrucciones']); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <h3>Instrucciones para realizar tu pago</h3>
        <p>Para completar tu compra, por favor acude a pagar en:</p>
        
        <div class="payment-location">
            <p><strong>Dirección:</strong> C2X5+54P, Unnamed Road, Guanujo</p>
            <p><strong>Horario de atención:</strong> Lunes a Viernes de 9:00 a 18:00</p>
        </div>
        
        <p><strong>Importante:</strong> Presenta este comprobante al momento de realizar tu pago o menciona tu número de orden.</p>
        
        <p>Tu pedido estará disponible una vez confirmado el pago y será enviado a la dirección proporcionada.</p>
        
        <div class="no-print">
            <p>
                <a href="index.php" class="btn btn-primary">Volver a la tienda</a>
                <button onclick="window.print();" class="btn btn-success">Imprimir comprobante</button>
            </p>
        </div>
    </div>
    
    <footer>
        <p>&copy; Creado por Patricio y Rimax</p>
    </footer>
</body>
</html>