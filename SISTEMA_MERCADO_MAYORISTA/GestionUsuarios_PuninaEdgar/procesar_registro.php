<?php
require 'conexion.php'; // Conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = trim($_POST["id"]);
    $nombres = trim($_POST["nombres"]);
    $apellidos = trim($_POST["apellidos"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $tipo_usuario = trim($_POST["tipo_usuario"]);

    // Validaciones en PHP (además de las de JavaScript)
    if (!preg_match("/^\d{10}$/", $cedula)) {
        die("Error: La cédula debe contener exactamente 10 números.");
    }
    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/", $nombres)) {
        die("Error: Los nombres solo pueden contener letras y espacios.");
    }
    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/", $apellidos)) {
        die("Error: Los apellidos solo pueden contener letras y espacios.");
    }

    // Validación del correo electrónico (debe tener letras y números antes de @gmail.com, no solo números)
    if (!preg_match("/^[A-Za-z][A-Za-z0-9]*@gmail\.com$/", $email)) {
        die("Error: El correo debe tener letras y números antes de '@gmail.com' y no puede ser solo números.");
    }

    // Verificar si la cédula o el correo ya existen en la base de datos
    $sql_check = "SELECT * FROM usuarios_externos WHERE cedula = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $cedula, $email);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        die("Error: La cédula o el correo electrónico ya están registrados.");
    }

    // Validación de la contraseña (solo letras y números, al menos 8 caracteres)
    if (!preg_match("/^[A-Za-z0-9]{8,}$/", $password)) {
        die("Error: La contraseña debe tener al menos 8 caracteres, solo letras y números.");
    }

    // Cifrar la contraseña antes de guardarla en la BD
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta SQL para el registro
    $sql = "INSERT INTO usuarios_externos (cedula, nombres, apellidos, email, password, tipo_usuario) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Usar sentencias preparadas para evitar inyección SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $cedula, $nombres, $apellidos, $email, $password_hash, $tipo_usuario);

    if ($stmt->execute()) {
        echo "Registro exitoso. <a href='login_externo.html'>Iniciar sesión</a>";
    } else {
        echo "Error al registrar el usuario: " . $stmt->error;
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conn->close();
}
?>

