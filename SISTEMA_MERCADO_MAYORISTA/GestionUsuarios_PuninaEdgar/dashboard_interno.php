<?php
session_start();
if (!isset($_SESSION["usuario_interno"])) {
    header("Location: login_interno.html");
    exit();
}

require 'conexion.php';

// Obtener todos los usuarios externos
$sql = "SELECT * FROM usuarios_externos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="estilo_admin.css">
    <style>
        body {
            
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        ul {
            list-style: none;
            padding: 0;
            text-align: center;
        }
        ul li {
            display: inline;
            margin: 10px;
        }
        ul li a {
            text-decoration: none;
            background: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
        ul li a:hover {
            background: #0056b3;
        }
        .logout {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .logout:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <h2>Panel de Administración - Usuarios Externos</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Cédula</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Email</th>
            <th>Tipo de Usuario</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["cedula"]; ?></td>
            <td><?php echo $row["nombres"]; ?></td>
            <td><?php echo $row["apellidos"]; ?></td>
            <td><?php echo $row["email"]; ?></td>
            <td><?php echo $row["tipo_usuario"]; ?></td>
            <td>
                <a href="eliminar_usuario.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Seguro que quieres eliminar este usuario?');" style="color: red;">Eliminar</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h2>Control de Precios</h2>
    <ul>
        <li><a href="#">Ver productos y precios</a></li>
        <li><a href="#">Modificar precios</a></li>
    </ul>
    
    <a href="logout.php" class="logout">Cerrar Sesión</a>
</body>
</html>
