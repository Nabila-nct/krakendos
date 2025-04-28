<?php
/**
 * logout.php - Cierre de sesión para el panel de administración
 */

// Iniciar sesión
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redirigir al formulario de login
header('Location: login.php');
exit;