<?php
require 'config/conexion.php'; // Incluir archivo de conexión

// Obtener los parámetros de búsqueda
$query = isset($_GET['query']) ? $_GET['query'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Preparar la consulta SQL
$sql = "SELECT * FROM productos WHERE nombre LIKE :query";

// Si se seleccionó una categoría, agregarla a la consulta
if (!empty($categoria)) {
    $sql .= " AND categoria = :categoria";
}

$stmt = $pdo->prepare($sql);

// Agregar comodines para la búsqueda
$search_term = "%" . $query . "%";
$stmt->bindParam(':query', $search_term, PDO::PARAM_STR);

// Si se especificó una categoría, agregar el parámetro
if (!empty($categoria)) {
    $stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
}

// Ejecutar la consulta
$stmt->execute();

// Mostrar los resultados en un grid de tarjetas de producto
if ($stmt->rowCount() > 0) {
    echo "<div class='producto-grid'>";
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Verificar si la imagen es una URL o un archivo local
        if (filter_var($row["imagen"], FILTER_VALIDATE_URL)) {
            // Si es una URL directa
            $imagen_url = $row["imagen"];
        } else {
            // Si es un archivo local
            $imagen_url = file_exists("images/products/" . $row["imagen"]) 
                ? "images/products/" . $row["imagen"] 
                : "images/products/default-product.jpg";
        }
        
        // Usar las imágenes proporcionadas para productos específicos
        $nombre_producto = strtolower($row["nombre"]);
        if (strpos($nombre_producto, 'lechuga') !== false) {
            $imagen_url = "https://www.lasemilleria.com/img/med/lechuga-greatlakes-400.jpg";
        } elseif (strpos($nombre_producto, 'uva') !== false) {
            $imagen_url = "https://moyca.eu/wp-content/uploads/2022/10/ralli-uva-moyca.jpg";
        } elseif (strpos($nombre_producto, 'manzana') !== false) {
            $imagen_url = "https://cdn.pixabay.com/photo/2018/01/29/22/56/apple-3117507_1280.jpg";
        } elseif (strpos($nombre_producto, 'pera') !== false) {
            $imagen_url = "https://www.frutality.es/wp-content/uploads/frutality-fruta_pera_verde.png";
        }
        
        echo "<div class='producto-card'>";
        echo "<img src='" . htmlspecialchars($imagen_url) . "' alt='" . htmlspecialchars($row["nombre"]) . "' class='producto-img'>";
        echo "<div class='producto-info'>";
        echo "<h3>" . htmlspecialchars($row["nombre"]) . "</h3>";
        echo "<div class='producto-categoria'>" . htmlspecialchars($row["categoria"]) . "</div>";
        echo "<div class='producto-precio'>$" . number_format($row["precio"], 2) . "</div>";
        
        echo "<form method='POST' action='agregar_al_carrito.php' class='add-to-cart-form'>";
        echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
        echo "<input type='hidden' name='nombre' value='" . htmlspecialchars($row["nombre"]) . "'>";
        echo "<input type='hidden' name='precio' value='" . $row["precio"] . "'>";
        echo "<input type='hidden' name='imagen' value='" . htmlspecialchars($imagen_url) . "'>";
        echo "<input type='hidden' name='local' value='" . htmlspecialchars($row["local"]) . "'>";
        
        echo "<div class='form-group'>";
        echo "<label>Ubicación del Local:</label>";
        echo "<input type='text' value='" . htmlspecialchars($row["local"]) . "' class='local-input' readonly>";
        echo "</div>";
        
        echo "<div class='form-group'>";
        echo "<label for='cantidad_" . $row["id"] . "'>Cantidad:</label>";
        echo "<input type='number' name='cantidad' id='cantidad_" . $row["id"] . "' min='1' value='1' class='quantity-input'>";
        echo "</div>";
        
        echo "<button type='submit' class='btn btn-primary'>Agregar al carrito</button>";
        echo "</form>";
        
        echo "</div>"; // producto-info
        echo "</div>"; // producto-card
    }
    
    echo "</div>"; // producto-grid
} else {
    echo "<div class='alert alert-error'>No se encontraron resultados.</div>";
}
?>