<?php
// Incluir archivo de conexión
require_once '../../../src/database/conexion.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';
    
    // Validar campos
    if (empty($correo) || empty($comentario)) {
        $mensaje_error = 'Por favor complete todos los campos';
        header('Location: contacto.php?error=' . urlencode($mensaje_error));
        exit;
    }
    
    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje_error = 'Por favor ingrese un correo electrónico válido';
        header('Location: contacto.php?error=' . urlencode($mensaje_error));
        exit;
    }
    
    // Crear tabla de mensajes si no existe
    $queryTabla = "CREATE TABLE IF NOT EXISTS mensaje_contacto (
        id_mensaje SERIAL PRIMARY KEY,
        correo VARCHAR(100) NOT NULL,
        comentario TEXT NOT NULL,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    ejecutarDB($queryTabla);
    
    // Guardar en la base de datos
    $query = "INSERT INTO mensaje_contacto (correo, comentario) VALUES (?, ?)";
    $resultado = ejecutarDB($query, [$correo, $comentario]);
    
    if ($resultado !== false) {
        // Redireccionar con mensaje de éxito
        header('Location: contacto.php?exito=1');
        exit;
    } else {
        $mensaje_error = 'Ocurrió un error al enviar su mensaje. Por favor intente nuevamente.';
        header('Location: contacto.php?error=' . urlencode($mensaje_error));
        exit;
    }
} else {
    // Si se accede directamente sin enviar el formulario
    header('Location: contacto.php');
    exit;
}