<?php
// Incluir en una carpeta admin/ - Este archivo sería admin/ver_direcciones.php
session_start();
require_once '../config/conexion.php';

// Aquí podrías implementar autenticación para administradores

// Obtener todas las direcciones con información de orden
try {
    $sql = "SELECT d.*, o.total, o.estado, o.fecha_creacion 
            FROM direcciones d 
            JOIN ordenes o ON d.orden_id = o.orden_id 
            ORDER BY o.fecha_creacion DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $direcciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al obtener las direcciones: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Direcciones</title>
    <link rel="stylesheet" href="../style/styles.css">
    <style>
        .direccion-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .direccion-card h3 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .direccion-info {
            margin-bottom: 15px;
        }
        .orden-info {
            background-color: #e9f7ef;
            padding: 10px;
            border-radius: 5px;
        }
        .estado-pendiente {
            color: #f39c12;
            font-weight: bold;
        }
        .estado-completado {
            color: #27ae60;
            font-weight: bold;
        }
        .estado-cancelado {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Administración de Direcciones</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Ir a la Tienda</a></li>
                <li><a href="index.php">Panel Admin</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Listado de Direcciones de Envío</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($direcciones) && count($direcciones) > 0): ?>
            <?php foreach ($direcciones as $dir): ?>
                <div class="direccion-card">
                    <h3>Orden: <?php echo htmlspecialchars($dir['orden_id']); ?></h3>
                    
                    <div class="orden-info">
                        <p><strong>Total:</strong> $<?php echo number_format($dir['total'], 2); ?></p>
                        <p><strong>Estado:</strong> 
                            <span class="estado-<?php echo strtolower($dir['estado']); ?>">
                                <?php echo ucfirst(htmlspecialchars($dir['estado'])); ?>
                            </span>
                        </p>
                        <p><strong>Fecha:</strong> <?php echo date("d/m/Y H:i", strtotime($dir['fecha_creacion'])); ?></p>
                        
                        <!-- Opciones para administrar la orden -->
                        <div class="admin-actions">
                            <a href="ver_detalles_orden.php?orden_id=<?php echo urlencode($dir['orden_id']); ?>" class="btn btn-primary">Ver Detalles</a>
                            <a href="actualizar_estado.php?orden_id=<?php echo urlencode($dir['orden_id']); ?>&estado=completado" class="btn btn-success">Marcar como Completado</a>
                            <a href="actualizar_estado.php?orden_id=<?php echo urlencode($dir['orden_id']); ?>&estado=cancelado" class="btn btn-danger">Cancelar Orden</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">No hay direcciones registradas.</div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; Creado por Patricio y Rimax</p>
    </footer>
</body>
</html>