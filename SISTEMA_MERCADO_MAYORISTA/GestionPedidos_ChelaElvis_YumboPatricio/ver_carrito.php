<?php
// Iniciar la sesión
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .cart-table th {
            background-color: #2c7be5;
            color: white;
            text-align: left;
            padding: 15px;
        }
        
        .cart-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        .cart-table tr:last-child td {
            border-bottom: none;
        }
        
        .cart-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .cart-table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        
        .quantity-update {
            display: flex;
            align-items: center;
        }
        
        .quantity-input, .local-input {
            width: 80px;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            text-align: center;
        }
        
        .local-input {
            width: 120px;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .btn-primary {
            background-color: #2c7be5;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #1a68d1;
        }
        
        .btn-success {
            background-color: #39b54a;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #2d9d3c;
        }
        
        .btn-danger {
            background-color: #e63757;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #d21e3c;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .actions {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        
        .cart-summary {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 20px;
            text-align: right;
        }
        
        .cart-total {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c7be5;
        }
        
        .empty-cart {
            text-align: center;
            padding: 50px 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .empty-cart h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .empty-cart p {
            color: #6c757d;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .cart-table {
                display: block;
                overflow-x: auto;
            }
            
            .actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .actions a, .actions button {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Carrito de Compras</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <?php
        // Mostrar mensajes de éxito o error
        if (isset($_SESSION['mensaje'])) {
            $tipo_clase = ($_SESSION['mensaje']['tipo'] == 'success') ? 'alert-success' : 'alert-error';
            echo '<div class="alert ' . $tipo_clase . '">' . $_SESSION['mensaje']['texto'] . '</div>';
            
            // Limpiar el mensaje para que no se muestre nuevamente
            unset($_SESSION['mensaje']);
        }
        ?>

        <h2>Tu Carrito</h2>
        
        <?php if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])): ?>
            <form method="post" action="actualizar_carrito.php" id="cartForm">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Imagen</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Local</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($_SESSION['carrito'] as $id => $item): 
                            $subtotal = $item['precio'] * $item['cantidad'];
                            $total += $subtotal;
                            
                            // Determinar la URL de la imagen
                            if (filter_var($item["imagen"], FILTER_VALIDATE_URL)) {
                                $imagen_url = $item["imagen"];
                            } else {
                                $imagen_url = file_exists("images/products/" . $item["imagen"]) 
                                    ? "images/products/" . $item["imagen"] 
                                    : "images/products/default-product.jpg";
                                
                                // Asignar imágenes específicas para productos conocidos
                                $nombre_producto = strtolower($item['nombre']);
                                if (strpos($nombre_producto, 'lechuga') !== false) {
                                    $imagen_url = "https://www.lasemilleria.com/img/med/lechuga-greatlakes-400.jpg";
                                } elseif (strpos($nombre_producto, 'uva') !== false) {
                                    $imagen_url = "https://moyca.eu/wp-content/uploads/2022/10/ralli-uva-moyca.jpg";
                                } elseif (strpos($nombre_producto, 'manzana') !== false) {
                                    $imagen_url = "https://cdn.pixabay.com/photo/2018/01/29/22/56/apple-3117507_1280.jpg";
                                } elseif (strpos($nombre_producto, 'pera') !== false) {
                                    $imagen_url = "https://www.frutality.es/wp-content/uploads/frutality-fruta_pera_verde.png";
                                }
                            }
                        ?>
                            <tr>
                                <td data-label="Producto"><?php echo htmlspecialchars($item['nombre']); ?></td>
                                <td data-label="Imagen">
                                    <img src="<?php echo htmlspecialchars($imagen_url); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                                </td>
                                <td data-label="Precio">$<?php echo number_format($item['precio'], 2); ?></td>
                                <td data-label="Cantidad">
                                    <div class="quantity-update">
                                        <input type="number" name="cantidad[<?php echo $id; ?>]" value="<?php echo $item['cantidad']; ?>" min="1" class="quantity-input">
                                    </div>
                                </td>
                                <td data-label="Local">
                                    <input type="text" name="local[<?php echo $id; ?>]" value="<?php echo htmlspecialchars($item['local']); ?>" class="local-input">
                                </td>
                                <td data-label="Subtotal">$<?php echo number_format($subtotal, 2); ?></td>
                                <td data-label="Acciones">
                                    <a href="javascript:void(0);" onclick="confirmarEliminar(<?php echo $id; ?>, '<?php echo addslashes($item['nombre']); ?>');" class="btn btn-danger">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="actions">
                    <a href="index.php" class="btn btn-primary">Seguir Comprando</a>
                    <button type="submit" class="btn btn-success">Actualizar Carrito</button>
                </div>
                
                <div class="cart-summary">
                    <div class="cart-total">Total: $<?php echo number_format($total, 2); ?></div>
                    
                    <?php
                    // Guardar el total en la sesión para usarlo en la página de pago
                    $_SESSION['total'] = $total;
                    ?>
                    
                    <a href="procesar_compra.php" class="btn btn-primary">Proceder al Pago</a>
                </div>
            </form>
            
        <?php else: ?>
            <div class="empty-cart">
                <h3>Tu carrito está vacío</h3>
                <p>No tienes productos en tu carrito de compras.</p>
                <a href="index.php" class="btn btn-primary">Ir a Comprar</a>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; Creado por Patricio y Rimax</p>
    </footer>
    
    <script>
    function confirmarEliminar(id, nombre) {
        if (confirm('¿Estás seguro que deseas eliminar "' + nombre + '" del carrito?')) {
            window.location.href = 'eliminar_producto.php?id=' + id;
        }
    }
    
    // Actualizar subtotales cuando cambia la cantidad
    document.querySelectorAll('.quantity-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const row = this.closest('tr');
            const precio = parseFloat(row.querySelector('[data-label="Precio"]').innerText.replace('$', ''));
            const cantidad = parseInt(this.value);
            const subtotal = precio * cantidad;
            row.querySelector('[data-label="Subtotal"]').innerText = '$' + subtotal.toFixed(2);
        });
    });
    </script>
</body>
</html>