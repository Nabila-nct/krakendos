<?php
/**
 * panel_admin.php - Panel de administración para Kraken Store
 */

// Iniciar sesión
session_start();

// Incluir archivo de conexión y funciones
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/productos.php';


// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    // Redirigir a la página de login
    header('Location: login.php');
    exit;
}

// Obtener información del usuario y empleado
$id_usuario = $_SESSION['id_usuario'];
$query = "SELECT u.*, e.nombre as nombre_empleado, e.puesto 
          FROM usuario u 
          JOIN empleado e ON u.id_empleado = e.id_empleado 
          WHERE u.id_usuario = ?";
$usuario = consultarDB($query, [$id_usuario]);

if (!$usuario) {
    // Si no se encuentra el usuario, cerrar sesión
    session_destroy();
    header('Location: login.php');
    exit;
}

$usuario = $usuario[0];
$nombre_empleado = $usuario['nombre_empleado'];
$puesto = $usuario['puesto'];

// Procesar acciones
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';
$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'productos';

// Manejo de mensajes
$mensaje = '';
$tipo_mensaje = '';

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    $tipo_mensaje = $_SESSION['tipo_mensaje'];
    unset($_SESSION['mensaje']);
    unset($_SESSION['tipo_mensaje']);
}

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar formulario de producto
    if (isset($_POST['guardar_producto'])) {
        $id_producto = isset($_POST['id_producto']) ? $_POST['id_producto'] : null;
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $precio_mayoreo = $_POST['precio_mayoreo'] ?: null;
        $unidades_mayoreo = $_POST['unidades_mayoreo'] ?: null;
        $existencia = $_POST['existencia'];
        $id_proveedor = $_POST['id_proveedor'] ?: null;
        $id_categoria = $_POST['id_categoria'];
        
        // Manejar carga de imagen si hay archivo
        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen_temp = $_FILES['imagen']['tmp_name'];
            $nombre_imagen = time() . '_' . $_FILES['imagen']['name'];
            $directorio_destino = 'uploads/productos/';
            
            // Crear directorio si no existe
            if (!is_dir($directorio_destino)) {
                mkdir($directorio_destino, 0755, true);
            }
            
            $ruta_imagen = $directorio_destino . $nombre_imagen;
            
            if (move_uploaded_file($imagen_temp, $ruta_imagen)) {
                $imagen = $ruta_imagen;
            }
        }
        
        if ($id_producto) {
            // Actualizar producto existente
            actualizarProducto($id_producto, $nombre, $descripcion, $precio, $precio_mayoreo, $unidades_mayoreo, $existencia, $id_proveedor, $id_categoria);
            
            // Actualizar imagen si se proporcionó una nueva
            if ($imagen) {
                actualizarImagenProducto($id_producto, $imagen);
                agregarImagenProducto($id_producto, $imagen, true);
            }
            
            $_SESSION['mensaje'] = 'Producto actualizado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            // Agregar nuevo producto
            $id_producto = agregarProducto($nombre, $descripcion, $precio, $precio_mayoreo, $unidades_mayoreo, $existencia, $id_proveedor, $id_categoria);
            
            // Guardar imagen si se proporcionó
            if ($imagen) {
                actualizarImagenProducto($id_producto, $imagen);
                agregarImagenProducto($id_producto, $imagen, true);
            }
            
            $_SESSION['mensaje'] = 'Producto agregado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        }
        
        // Redirigir para evitar reenvío del formulario
        header('Location: panel_admin.php?seccion=productos');
        exit;
    }
    
    // Procesar formulario de categoría
    if (isset($_POST['guardar_categoria'])) {
        $nombre = $_POST['nombre'];
        $query = "INSERT INTO categoria (nombre) VALUES (?)";
        ejecutarDB($query, [$nombre]);
        
        $_SESSION['mensaje'] = 'Categoría agregada correctamente';
        $_SESSION['tipo_mensaje'] = 'success';
        
        header('Location: panel_admin.php?seccion=categorias');
        exit;
    }
    
    // Procesar formulario de proveedor
    if (isset($_POST['guardar_proveedor'])) {
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        
        $query = "INSERT INTO proveedor (nombre, direccion, telefono, correo) VALUES (?, ?, ?, ?)";
        ejecutarDB($query, [$nombre, $direccion, $telefono, $correo]);
        
        $_SESSION['mensaje'] = 'Proveedor agregado correctamente';
        $_SESSION['tipo_mensaje'] = 'success';
        
        header('Location: panel_admin.php?seccion=proveedores');
        exit;
    }
}

// Eliminar producto
if ($accion === 'eliminar' && $seccion === 'productos' && isset($_GET['id'])) {
    $id_producto = $_GET['id'];
    eliminarProducto($id_producto);
    
    $_SESSION['mensaje'] = 'Producto eliminado correctamente';
    $_SESSION['tipo_mensaje'] = 'success';
    
    header('Location: panel_admin.php?seccion=productos');
    exit;
}

// Eliminar categoría
if ($accion === 'eliminar' && $seccion === 'categorias' && isset($_GET['id'])) {
    $id_categoria = $_GET['id'];
    $query = "DELETE FROM categoria WHERE id_categoria = ?";
    ejecutarDB($query, [$id_categoria]);
    
    $_SESSION['mensaje'] = 'Categoría eliminada correctamente';
    $_SESSION['tipo_mensaje'] = 'success';
    
    header('Location: panel_admin.php?seccion=categorias');
    exit;
}

// Eliminar proveedor
if ($accion === 'eliminar' && $seccion === 'proveedores' && isset($_GET['id'])) {
    $id_proveedor = $_GET['id'];
    $query = "DELETE FROM proveedor WHERE id_proveedor = ?";
    ejecutarDB($query, [$id_proveedor]);
    
    $_SESSION['mensaje'] = 'Proveedor eliminado correctamente';
    $_SESSION['tipo_mensaje'] = 'success';
    
    header('Location: panel_admin.php?seccion=proveedores');
    exit;
}

// Obtener datos para formularios
$producto = null;
if ($accion === 'editar' && $seccion === 'productos' && isset($_GET['id'])) {
    $id_producto = $_GET['id'];
    $producto = obtenerProductoPorId($id_producto);
}

$categorias = obtenerCategorias();
$proveedores = obtenerProveedores();

// Si estamos en la sección de productos, obtener lista de productos
$productos = [];
if ($seccion === 'productos' && $accion === 'listar') {
    $productos = obtenerProductos();
}

// Si estamos en la sección de categorías, obtener lista de categorías
$categorias_lista = [];
if ($seccion === 'categorias' && $accion === 'listar') {
    $categorias_lista = $categorias;
}

// Si estamos en la sección de proveedores, obtener lista de proveedores
$proveedores_lista = [];
if ($seccion === 'proveedores' && $accion === 'listar') {
    $proveedores_lista = $proveedores;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Kraken Store</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="panel_admin.php">Kraken Store - Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <?php echo htmlspecialchars($nombre_empleado); ?> (<?php echo htmlspecialchars($puesto); ?>)
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/src/index.php">Ver tienda</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $seccion === 'productos' ? 'active' : ''; ?>" href="panel_admin.php?seccion=productos">
                                <i class="bi bi-box"></i> Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $seccion === 'categorias' ? 'active' : ''; ?>" href="panel_admin.php?seccion=categorias">
                                <i class="bi bi-tags"></i> Categorías
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $seccion === 'proveedores' ? 'active' : ''; ?>" href="panel_admin.php?seccion=proveedores">
                                <i class="bi bi-truck"></i> Proveedores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $seccion === 'ventas' ? 'active' : ''; ?>" href="panel_admin.php?seccion=ventas">
                                <i class="bi bi-cart"></i> Ventas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $seccion === 'usuarios' ? 'active' : ''; ?>" href="panel_admin.php?seccion=usuarios">
                                <i class="bi bi-people"></i> Usuarios
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="content">
                    <?php if ($mensaje): ?>
                        <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($mensaje); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

                    <!-- Contenido según la sección -->
                    <?php if ($seccion === 'productos'): ?>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>Gestión de Productos</h2>
                            <?php if ($accion === 'listar'): ?>
                                <a href="panel_admin.php?seccion=productos&accion=nuevo" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Nuevo Producto
                                </a>
                            <?php else: ?>
                                <a href="panel_admin.php?seccion=productos" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Volver a la lista
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if ($accion === 'listar'): ?>
                            <!-- Lista de productos -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Categoría</th>
                                                    <th>Precio</th>
                                                    <th>Existencia</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($productos as $producto): ?>
                                                    <tr>
                                                        <td><?php echo $producto['id_producto']; ?></td>
                                                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                                        <td><?php echo htmlspecialchars($producto['nombre_categoria']); ?></td>
                                                        <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                                        <td><?php echo $producto['existencia']; ?></td>
                                                        <td>
                                                            <a href="panel_admin.php?seccion=productos&accion=editar&id=<?php echo $producto['id_producto']; ?>" class="btn btn-sm btn-info">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <a href="panel_admin.php?seccion=productos&accion=eliminar&id=<?php echo $producto['id_producto']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <?php if (empty($productos)): ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center">No hay productos registrados</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($accion === 'nuevo' || $accion === 'editar'): ?>
                            <!-- Formulario de producto -->
                            <div class="card">
                                <div class="card-header">
                                    <?php echo $accion === 'nuevo' ? 'Nuevo Producto' : 'Editar Producto'; ?>
                                </div>
                                <div class="card-body">
                                    <form action="panel_admin.php?seccion=productos" method="post" enctype="multipart/form-data">
                                        <?php if ($accion === 'editar'): ?>
                                            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                        <?php endif; ?>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="nombre" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $accion === 'editar' ? htmlspecialchars($producto['nombre']) : ''; ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="id_categoria" class="form-label">Categoría</label>
                                                <select class="form-select" id="id_categoria" name="id_categoria" required>
                                                    <option value="">Seleccione una categoría</option>
                                                    <?php foreach ($categorias as $categoria): ?>
                                                        <option value="<?php echo $categoria['id_categoria']; ?>" <?php echo ($accion === 'editar' && $producto['id_categoria'] == $categoria['id_categoria']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="descripcion" class="form-label">Descripción</label>
                                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo $accion === 'editar' ? htmlspecialchars($producto['descripcion']) : ''; ?></textarea>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="precio" class="form-label">Precio</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" value="<?php echo $accion === 'editar' ? $producto['precio'] : ''; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="precio_mayoreo" class="form-label">Precio Mayoreo</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control" id="precio_mayoreo" name="precio_mayoreo" step="0.01" min="0" value="<?php echo $accion === 'editar' ? $producto['precio_mayoreo'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="unidades_mayoreo" class="form-label">Unidades Mayoreo</label>
                                                <input type="number" class="form-control" id="unidades_mayoreo" name="unidades_mayoreo" min="0" value="<?php echo $accion === 'editar' ? $producto['unidades_mayoreo'] : ''; ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="existencia" class="form-label">Existencia</label>
                                                <input type="number" class="form-control" id="existencia" name="existencia" min="0" value="<?php echo $accion === 'editar' ? $producto['existencia'] : '0'; ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="id_proveedor" class="form-label">Proveedor</label>
                                                <select class="form-select" id="id_proveedor" name="id_proveedor">
                                                    <option value="">Seleccione un proveedor</option>
                                                    <?php foreach ($proveedores as $proveedor): ?>
                                                        <option value="<?php echo $proveedor['id_proveedor']; ?>" <?php echo ($accion === 'editar' && $producto['id_proveedor'] == $proveedor['id_proveedor']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($proveedor['nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="imagen" class="form-label">Imagen</label>
                                                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                                <?php if ($accion === 'editar' && !empty($producto['imagen'])): ?>
                                                    <div class="mt-2">
                                                        <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="img-thumbnail" style="max-height: 100px;">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="text-end">
                                            <button type="submit" name="guardar_producto" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    <?php elseif ($seccion === 'categorias'): ?>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>Gestión de Categorías</h2>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Formulario de categoría -->
                                <div class="card">
                                    <div class="card-header">Nueva Categoría</div>
                                    <div class="card-body">
                                        <form action="panel_admin.php?seccion=categorias" method="post">
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                                            </div>
                                            <div class="text-end">
                                                <button type="submit" name="guardar_categoria" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <!-- Lista de categorías -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Nombre</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($categorias_lista as $categoria): ?>
                                                        <tr>
                                                            <td><?php echo $categoria['id_categoria']; ?></td>
                                                            <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                                                            <td>
                                                                <a href="panel_admin.php?seccion=categorias&accion=eliminar&id=<?php echo $categoria['id_categoria']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta categoría? Se eliminarán todos los productos asociados.')">
                                                                    <i class="bi bi-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <?php if (empty($categorias_lista)): ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center">No hay categorías registradas</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    <?php elseif ($seccion === 'proveedores'): ?>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>Gestión de Proveedores</h2>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Formulario de proveedor -->
                                <div class="card">
                                    <div class="card-header">Nuevo Proveedor</div>
                                    <div class="card-body">
                                        <form action="panel_admin.php?seccion=proveedores" method="post">
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="direccion" class="form-label">Dirección</label>
                                                <input type="text" class="form-control" id="direccion" name="direccion">
                                            </div>
                                            <div class="mb-3">
                                                <label for="telefono" class="form-label">Teléfono</label>
                                                <input type="text" class="form-control" id="telefono" name="telefono" maxlength="10">
                                            </div>
                                            <div class="mb-3">
                                                <label for="correo" class="form-label">Correo</label>
                                                <input type="email" class="form-control" id="correo" name="correo">
                                            </div>
                                            <div class="text-end">
                                                <button type="submit" name="guardar_proveedor" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <!-- Lista de proveedores -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Nombre</th>
                                                        <th>Teléfono</th>
                                                        <th>Correo</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($proveedores_lista as $proveedor): ?>
                                                        <tr>
                                                            <td><?php echo $proveedor['id_proveedor']; ?></td>
                                                            <td><?php echo htmlspecialchars($proveedor['nombre']); ?></td>
                                                            <td><?php echo htmlspecialchars($proveedor['telefono']); ?></td>
                                                            <td><?php echo htmlspecialchars($proveedor['correo']); ?></td>
                                                            <td>
                                                                <a href="panel_admin.php?seccion=proveedores&accion=eliminar&id=<?php echo $proveedor['id_proveedor']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este proveedor?')">
                                                                    <i class="bi bi-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <?php if (empty($proveedores_lista)): ?>
                                                        <tr>
                                                            <td colspan="5" class="text-center">No hay proveedores registrados</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    <?php elseif ($seccion === 'ventas'): ?>
                        <h2>Gestión de Ventas</h2>
                        <p>En desarrollo...</p>
                        
                    <?php elseif ($seccion === 'usuarios'): ?>
                        <h2>Gestión de Usuarios</h2>
                        <p>En desarrollo...</p>
                        
                    <?php endif; ?>