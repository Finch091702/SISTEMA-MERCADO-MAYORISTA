<?php
// Asumimos que ya tienes una sesión iniciada para el carrito
session_start();

// Variables para mostrar el carrito (ejemplo)
$total_carrito = isset($_SESSION['total']) ? $_SESSION['total'] : 0;

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y procesar el pago
    if (isset($_POST['metodoPago'])) {
        $metodo_pago = $_POST['metodoPago'];
        $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
        $telefono = isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '';
        $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
        
        // Generar un número de orden único
        $numero_orden = 'ORD-' . time() . '-' . rand(1000, 9999);
        
        // Aquí puedes guardar la información en tu base de datos
        // Por ejemplo: guardar_orden($numero_orden, $metodo_pago, $nombre, $telefono, $email, $total_carrito);
        
        // Redirigir a una página de confirmación según el método de pago
        if ($metodo_pago == 'transferencia') {
            $_SESSION['orden'] = $numero_orden;
            header("Location: confirmacion_transferencia.php");
            exit;
        } else if ($metodo_pago == 'efectivo') {
            $_SESSION['orden'] = $numero_orden;
            header("Location: confirmacion_efectivo.php");
            exit;
        }
    }
}

// Función ejemplo para generar referencia única (puedes reemplazarla por tu propio sistema)
function generarReferencia() {
    return 'REF-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
}

$referencia_pago = generarReferencia();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .payment-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 3px solid #4285f4;
            margin-top: 10px;
            display: none;
        }
        .btn {
            background-color: #4285f4;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #3367d6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tu carrito de compras</h1>
        
        <!-- Mostrar el contenido del carrito -->
        <table>
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
                // Asumimos que tienes tus productos en la sesión o base de datos
                // Este es un ejemplo, ajústalo según tu estructura
                if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
                    foreach ($_SESSION['carrito'] as $item) {
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
                    <td><strong>$<?php echo number_format($total_carrito, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
        
        <h2>Método de Pago</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                    <p>Una vez realizada la transferencia, envíe el comprobante a: [email@ejemplo.com]</p>
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="radio" name="metodoPago" value="efectivo" onclick="mostrarMetodoPago('efectivo')"> 
                    Pago en efectivo
                </label>
                
                <div id="detalles-efectivo" class="payment-details">
                    <h3>Instrucciones para pago en efectivo</h3>
                    <p><strong>Por favor acuda a pagar en:</strong> Mercado Mayorista Guanujo</p>
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
            
            <button type="submit" class="btn">Finalizar compra</button>
        </form>
    </div>
    
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