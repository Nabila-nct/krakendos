<?php
/**
 * login.php - Formulario de inicio de sesión para el panel de administración
 */

// Iniciar sesión
session_start();

// Incluir archivo de conexión
require_once __DIR__ . '/conexion.php';


// Verificar si ya hay sesión iniciada
if (isset($_SESSION['id_usuario'])) {
    // Redirigir al panel de administración
    header('Location: panel_admin.php');
    exit;
}

// Variable para mensajes de error
$error = '';

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($username) || empty($password)) {
        $error = 'Por favor complete todos los campos';
    } else {
        // Consultar usuario en la base de datos
        $query = "SELECT * FROM usuario WHERE username = ?";
        $resultado = consultarDB($query, [$username]);
        
        if ($resultado && count($resultado) > 0) {
            $usuario = $resultado[0];
            
            // En un entorno de producción, la contraseña debe estar hasheada (password_hash/password_verify)
            // Aquí lo dejamos en texto plano para simplificar
            if ($password === $usuario['contrasena']) {
                // Iniciar sesión
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['id_empleado'] = $usuario['id_empleado'];
                $_SESSION['username'] = $usuario['username'];
                
                // Redirigir al panel de administración
                header('Location: panel_admin.php');
                exit;
            } else {
                $error = 'Contraseña incorrecta';
            }
        } else {
            $error = 'Usuario no encontrado';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Kraken Store Admin</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        .form-signin {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            margin: auto;
        }
        .form-signin .form-floating:focus-within {
            z-index: 2;
        }
        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        .admin-icon {
            font-size: 5rem;
            color: #343a40;
        }
    </style>
</head>
<body class="text-center">
    <main class="form-signin">
        <form method="post" action="login.php">
            <div class="text-center mb-4">
                <div class="admin-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h1 class="h3 mb-3 fw-normal">Kraken Store</h1>
                <p>Panel de Administración</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="nombre de usuario" required autofocus>
                <label for="username">Usuario</label>
            </div>
            
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                <label for="password">Contraseña</label>
            </div>
            
            <button class="w-100 btn btn-lg btn-primary" type="submit">Iniciar Sesión</button>
            
            <p class="mt-3">
                <a href="main.html">Volver a la tienda</a>
            </p>
            
            <p class="mt-5 mb-3 text-muted">&copy; 2025 Kraken Store</p>
        </form>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>