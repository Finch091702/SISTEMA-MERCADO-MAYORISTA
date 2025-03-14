<?php
// Iniciar sesión para acceder al carrito
session_start();

// Incluir archivo de conexión
require_once 'config/conexion.php';

// Verificar si hay productos en el carrito
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    $_SESSION['mensaje'] = [
        'texto' => "No hay productos en el carrito para procesar",
        'tipo' => 'error'
    ];
    header("Location: ver_carrito.php");
    exit();
}

// Calcular el total del carrito
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
$_SESSION['total'] = $total;

// Función para generar referencia única para pagos
function generarReferencia() {
    return 'REF-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
}

$referencia_pago = generarReferencia();
$_SESSION['referencia_pago'] = $referencia_pago;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="style/styles.css">
</head>
<body>
    <header>
        <h1>Finalizar Compra</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="ver_carrito.php">Volver al Carrito</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Tu carrito de compras</h2>
        
        <!-- Mostrar el contenido del carrito -->
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
                    foreach ($_SESSION['carrito'] as $id => $item) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($item['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['cantidad']) . "</td>";
                        echo "<td>$" . number_format($item['precio'], 2) . "</td>";
                        echo "<td>$" . number_format($item['precio'] * $item['cantidad'], 2) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay productos en el carrito</td></tr>";
                }
                ?>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
        
        <h2>Método de Pago</h2>
        <form action="finalizar_pago.php" method="post">
            <div class="form-group">
                <label>
                    <input type="radio" name="metodoPago" value="transferencia" onclick="mostrarMetodoPago('transferencia')"> 
                    Transferencia bancaria
                </label>
                
                <div id="detalles-transferencia" class="payment-details">
                    <h3>Datos para la transferencia</h3>
                    <p><strong>Banco:</strong> Pichincha</p>
                    <p><strong>Titular:</strong> Edgar Patricio Yumbo Rea</p>
                    <p><strong>Número de cuenta:</strong> 2209914493</p>
                    <p>Una vez realizada la transferencia, envíe el comprobante a: edgar.yumbo@gmailcom</p>
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="radio" name="metodoPago" value="efectivo" onclick="mostrarMetodoPago('efectivo')"> 
                    Pago en efectivo
                </label>
                
                <div id="detalles-efectivo" class="payment-details">
                    <h3>Instrucciones para pago en efectivo</h3>
                    <p><strong>Por favor acuda a pagar en:</strong> C2X5+54P, Unnamed Road, Guanujo</p>
                    <p><strong>Horario de atención:</strong> Lunes a Viernes de 9:00 a 18:00</p>
                    <p>Su pedido estará disponible una vez confirmado el pago.</p>
                </div>
            </div>
            
            <h3>Datos de contacto</h3>
            <div class="form-group">
                <label for="nombre">Nombre completo:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required>
            </div>
            
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Finalizar compra</button>
        </form>
    </div>

    <footer>
        <p>&copy; Creado por Patricio y Rimax</p>
    </footer>
    
    <script>
    function mostrarMetodoPago(metodo) {
        // Ocultar todos los detalles primero
        document.getElementById('detalles-transferencia').style.display = 'none';
        document.getElementById('detalles-efectivo').style.display = 'none';
        
        // Mostrar solo el método seleccionado
        if (metodo === 'transferencia') {
            document.getElementById('detalles-transferencia').style.display = 'block';
        } else if (metodo === 'efectivo') {
            document.getElementById('detalles-efectivo').style.display = 'block';
        }
    }
    </script>
</body>
</html>