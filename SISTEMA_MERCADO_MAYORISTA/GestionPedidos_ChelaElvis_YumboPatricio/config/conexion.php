<?php
$host = 'localhost';
$dbname = 'proyecto1'; // Asegúrate que este nombre coincida con tu base de datos
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // No imprimir mensajes de confirmación
} catch (PDOException $e) {
    // Log del error en archivo para depuración
    error_log("Error de conexión: " . $e->getMessage(), 0);
    // Mensaje de error genérico para producción
    die("Error en la conexión a la base de datos. Contacte al administrador.");
}
?>