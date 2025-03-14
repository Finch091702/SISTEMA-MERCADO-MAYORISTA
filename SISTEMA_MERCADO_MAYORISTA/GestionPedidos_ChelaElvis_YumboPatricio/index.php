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


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        main {
            background-image: url('https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEiTdWFhYsV7ef6n_dD0zlqiwhV-dzDPkYbY5KQOTAIBcrA30AmU4VMDp61oqZCw3MyBQKXWJ3pJNwlD5fQZNWw7AX6bR6WleMl_I4m-fHuO31ojJbhnoQGQUb1BObEDlrpGYEkMw-fPg-PF/s1600/DSC_2980.JPG');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        #busqueda {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        #resultados {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .destacados {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
        }
        .producto-destacado {
            width: 22%;
            margin-bottom: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }
        .producto-destacado:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .producto-destacado img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .producto-destacado-info {
            padding: 10px;
        }
        .producto-destacado-info h3 {
            margin-top: 0;
            font-size: 18px;
        }
        .producto-destacado-precio {
            font-weight: bold;
            color: #2c7be5;
            font-size: 18px;
            margin: 5px 0;
        }
        .producto-destacado-categoria {
            display: inline-block;
            background-color: #e9ecef;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .producto-destacado-ubicacion {
            font-size: 13px;
            color: #6c757d;
            margin-top: 5px;
        }
        .producto-destacado button {
            width: 100%;
            padding: 8px;
            background-color: #2c7be5;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .producto-destacado button:hover {
            background-color: #1a68d1;
        }
        .carrito-icon {
            position: relative;
            display: inline-block;
        }
        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #ff3860;
            color: white;
            font-size: 12px;
            font-weight: bold;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function buscarProductos() {
            var query = $('#query').val();
            var categoria = $('#categoria').val();

            $.ajax({
                url: 'buscar_productos.php',
                type: 'GET',
                data: {
                    query: query,
                    categoria: categoria
                },
                success: function(data) {
                    $('#resultados').html(data);
                }
            });
        }

        function agregarDirecto(id, nombre, precio, imagen, local) {
            $.ajax({
                url: 'agregar_al_carrito.php',
                type: 'POST',
                data: {
                    id: id,
                    nombre: nombre,
                    precio: precio,
                    imagen: imagen,
                    local: local,
                    cantidad: 1
                },
                success: function(response) {
                    mostrarNotificacion("¡Producto agregado al carrito!");
                    actualizarContadorCarrito();
                }
            });
        }

        function actualizarContadorCarrito() {
            $.ajax({
                url: 'contador_carrito.php',
                type: 'GET',
                success: function(data) {
                    $('.cart-count').text(data);
                }
            });
        }

        function mostrarNotificacion(mensaje) {
            var notificacion = $('<div class="notificacion"></div>').text(mensaje);
            $('body').append(notificacion);
            
            setTimeout(function() {
                notificacion.addClass('mostrar');
            }, 100);
            
            setTimeout(function() {
                notificacion.removeClass('mostrar');
                setTimeout(function() {
                    notificacion.remove();
                }, 500);
            }, 3000);
        }
    </script>
</head>
<body>
    <header>
        <h1>Gestión de Pedidos</h1>
        <nav>
            <ul>
                <li><a href="servicios/registro_productos.php">Registrar mis productos</a></li>
                <li>
                    <a href="ver_carrito.php" class="carrito-icon">
                        Ver Carrito
                        <span class="cart-count">
                            <?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>
                        </span>
                    </a>
                </li>
                <li><button onclick="mostrarNotificacion('¡Bienvenido a nuestra tienda!')">Notificaciones</button></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="busqueda">
            <h2>Buscar Productos</h2>
            
            <form onsubmit="event.preventDefault(); buscarProductos();">
                <div class="form-group">
                    <label for="query">Nombre del Producto:</label>
                    <input type="text" id="query" name="query" onkeyup="buscarProductos()" placeholder="Buscar por nombre...">
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select id="categoria" name="categoria" onchange="buscarProductos()">
                        <option value="">Todas</option>
                        <option value="Lácteos">Lácteos</option>
                        <option value="Electrónica">Electrónica</option>
                        <option value="Ropa">Ropa</option>
                        <option value="Alimentos">Alimentos</option>
                        <option value="Hogar">Hogar</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>

            <h3>Resultados:</h3>
            <div id="resultados">
                    </button>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; Creado por Patricio y Rimax</p>
    </footer>

    <script>
        // Ejecutar la búsqueda inicial al cargar la página
        $(document).ready(function() {
            buscarProductos();
            actualizarContadorCarrito();
        });
    </script>
</body>
</html>