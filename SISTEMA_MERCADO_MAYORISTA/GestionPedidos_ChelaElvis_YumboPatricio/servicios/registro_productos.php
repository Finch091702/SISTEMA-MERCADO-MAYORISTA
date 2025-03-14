<?php
session_start();
require '../config/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRAR Productos</title>
    <link rel="stylesheet" href="../style/styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: #333;
        }
        
        main {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        
        #ingresar-producto {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        
        .btn {
            padding: 12px 20px;
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
        
        .preview-container {
            margin-top: 15px;
            text-align: center;
        }
        
        #preview-imagen {
            max-width: 200px;
            max-height: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            display: none;
        }
        
        .image-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .image-suggestion {
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            transition: transform 0.2s;
        }
        
        .image-suggestion:hover {
            transform: scale(1.05);
            border-color: #2c7be5;
        }
        
        .image-suggestion img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        
        .suggestion-name {
            text-align: center;
            font-size: 14px;
            padding: 5px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <header>
        <h1>Registro de Productos</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Inicio</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <section id="ingresar-producto">
            <h2>Ingresar Producto</h2>
            
            <?php
            // Mostrar mensajes de éxito o error
            if (isset($_SESSION['mensaje'])) {
                $tipo_clase = ($_SESSION['mensaje']['tipo'] == 'success') ? 'alert-success' : 'alert-error';
                echo '<div class="alert ' . $tipo_clase . '">' . $_SESSION['mensaje']['texto'] . '</div>';
                
                // Limpiar el mensaje
                unset($_SESSION['mensaje']);
            }
            ?>
            
            <form action="../insert_product.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre del Producto:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="number" id="precio" name="precio" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select id="categoria" name="categoria" required>
                        <option value="Lácteos">Lácteos</option>
                        <option value="Electrónica">Electrónica</option>
                        <option value="Ropa">Ropa</option>
                        <option value="Alimentos">Alimentos</option>
                        <option value="Hogar">Hogar</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="local">Local/Ubicación:</label>
                    <input type="text" id="local" name="local" placeholder="Nombre o dirección del local" required>
                </div>
                
                    <div class="preview-container">
                        <img id="preview-imagen" src="" alt="Vista previa">
                    </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; Creado por Patricio y Rimax</p>
    </footer>

    <script>
    // Script para previsualizar la imagen
    document.getElementById('imagen_url').addEventListener('input', function() {
        previewImagen(this.value);
    });
    
    function previewImagen(url) {
        var preview = document.getElementById('preview-imagen');
        
        if (url) {
            preview.src = url;
            preview.style.display = 'block';
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
    
    function seleccionarImagen(url, nombreProducto) {
        document.getElementById('imagen_url').value = url;
        previewImagen(url);
        
        // Actualizar también el nombre del producto si está vacío
        var nombreInput = document.getElementById('nombre');
        if (!nombreInput.value) {
            nombreInput.value = nombreProducto;
        }
        
        // Actualizar categoría para frutas y verduras
        if (['Lechuga', 'Uva', 'Manzana', 'Pera'].includes(nombreProducto)) {
            document.getElementById('categoria').value = 'Alimentos';
        }
    }
    </script>
</body>
</html>