<?php
/**
 * productos.php - Funciones para obtener y mostrar productos en el sitio web
 */

// Incluir archivo de conexión
require_once __DIR__ . '/conexion.php';


/**
 * Obtiene los productos de una categoría específica
 * 
 * @param int $id_categoria ID de la categoría
 * @return array Lista de productos
 */
function obtenerProductosPorCategoria($id_categoria) {
    $query = "SELECT p.*, c.nombre as nombre_categoria 
              FROM producto p 
              LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
              WHERE p.id_categoria = ? AND p.existencia > 0
              ORDER BY p.nombre";
    
    return consultarDB($query, [$id_categoria]);
}

/**
 * Obtiene un producto por su ID
 * 
 * @param int $id_producto ID del producto
 * @return array|bool Datos del producto o false si no existe
 */
function obtenerProducto($id_producto) {
    $query = "SELECT p.*, c.nombre as nombre_categoria 
              FROM producto p 
              LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
              WHERE p.id_producto = ?";
    
    $resultado = consultarDB($query, [$id_producto]);
    return $resultado ? $resultado[0] : false;
}

/**
 * Obtiene las imágenes de un producto
 * 
 * @param int $id_producto ID del producto
 * @return array Lista de imágenes
 */
function obtenerImagenesProducto($id_producto) {
    $query = "SELECT * FROM producto_imagen WHERE id_producto = ? ORDER BY es_principal DESC";
    return consultarDB($query, [$id_producto]);
}

/**
 * Obtiene la imagen principal de un producto
 * 
 * @param int $id_producto ID del producto
 * @return string|bool Ruta de la imagen o false si no tiene imágenes
 */
function obtenerImagenPrincipal($id_producto) {
    // Intentar obtener desde la tabla producto_imagen
    $query = "SELECT ruta_imagen FROM producto_imagen WHERE id_producto = ? AND es_principal = true LIMIT 1";
    $resultado = consultarDB($query, [$id_producto]);
    
    if ($resultado && count($resultado) > 0) {
        return $resultado[0]['ruta_imagen'];
    }
    
    // Si no hay imagen principal, obtener la primera imagen
    $query = "SELECT ruta_imagen FROM producto_imagen WHERE id_producto = ? LIMIT 1";
    $resultado = consultarDB($query, [$id_producto]);
    
    if ($resultado && count($resultado) > 0) {
        return $resultado[0]['ruta_imagen'];
    }
    
    // Si no hay imágenes en producto_imagen, verificar el campo imagen en la tabla producto
    $query = "SELECT imagen FROM producto WHERE id_producto = ? AND imagen IS NOT NULL";
    $resultado = consultarDB($query, [$id_producto]);
    
    if ($resultado && count($resultado) > 0 && !empty($resultado[0]['imagen'])) {
        return $resultado[0]['imagen'];
    }
    
    // Si no hay imagen, devolver una imagen por defecto
    return 'img/no-image.jpg';
}

/**
 * Obtiene las categorías con productos
 * 
 * @return array Lista de categorías
 */
function obtenerCategoriasConProductos() {
    $query = "SELECT c.*, COUNT(p.id_producto) as total_productos
              FROM categoria c
              LEFT JOIN producto p ON c.id_categoria = p.id_categoria AND p.existencia > 0
              GROUP BY c.id_categoria
              HAVING COUNT(p.id_producto) > 0
              ORDER BY c.nombre";
    
    return consultarDB($query);
}

/**
 * Busca productos por término
 * 
 * @param string $termino Término de búsqueda
 * @return array Lista de productos
 */
function buscarProductos($termino) {
    $termino = "%$termino%"; // Para búsqueda parcial
    $query = "SELECT p.*, c.nombre as nombre_categoria 
              FROM producto p 
              LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
              WHERE (p.nombre ILIKE ? OR p.descripcion ILIKE ?) AND p.existencia > 0 
              ORDER BY p.nombre";
    
    return consultarDB($query, [$termino, $termino]);
}

/**
 * Obtiene productos destacados (puede ser los más vendidos o una selección manual)
 * 
 * @param int $limite Número de productos a devolver
 * @return array Lista de productos
 */
function obtenerProductosDestacados($limite = 8) {
    // En un escenario real, podrías querer destacar los más vendidos o los mejor valorados
    // Para este ejemplo, simplemente devolvemos algunos productos aleatorios
    $query = "SELECT p.*, c.nombre as nombre_categoria 
              FROM producto p 
              LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
              WHERE p.existencia > 0
              ORDER BY RANDOM() 
              LIMIT ?";
    
    return consultarDB($query, [$limite]);
}

/**
 * Genera el HTML para mostrar un producto en una vista de lista/grid
 * 
 * @param array $producto Datos del producto
 * @return string HTML para mostrar el producto
 */
function mostrarProductoCard($producto) {
    $id = $producto['id_producto'];
    $nombre = htmlspecialchars($producto['nombre']);
    $precio = number_format($producto['precio'], 2);
    $descripcion = isset($producto['descripcion']) ? htmlspecialchars($producto['descripcion']) : '';
    
    // Obtener imagen del producto
    $imagen = obtenerImagenPrincipal($id);
    
    // Construir HTML
    $html = <<<HTML
    <div class="col-md-6 text-center">
        <img src="{$imagen}" class="img-fluid" alt="{$nombre}" style="max-height: 300px;">
    </div>
    <div class="col-md-6">
        <h2>{$nombre}</h2>
        <p>{$descripcion}</p>
        <h3>\${$precio}</h3>
    </div>
HTML;
    
    return $html;
}

/**
 * Inicializa el carrito de compras si no existe
 */
function inicializarCarrito() {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
}

/**
 * Agrega un producto al carrito
 * 
 * @param int $id_producto ID del producto
 * @param int $cantidad Cantidad del producto
 * @return bool True si se agregó correctamente, false si no
 */
function agregarAlCarrito($id_producto, $cantidad = 1) {
    inicializarCarrito();
    
    // Verificar si el producto existe y tiene stock suficiente
    $producto = obtenerProducto($id_producto);
    
    if (!$producto || $producto['existencia'] < $cantidad) {
        return false;
    }
    
    // Agregar al carrito
    if (isset($_SESSION['carrito'][$id_producto])) {
        // Si ya existe, sumar la cantidad
        $_SESSION['carrito'][$id_producto]['cantidad'] += $cantidad;
    } else {
        // Si no existe, agregarlo
        $_SESSION['carrito'][$id_producto] = [
            'id_producto' => $id_producto,
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad,
            'imagen' => obtenerImagenPrincipal($id_producto)
        ];
    }
    
    return true;
}

/**
 * Actualiza la cantidad de un producto en el carrito
 * 
 * @param int $id_producto ID del producto
 * @param int $cantidad Nueva cantidad
 * @return bool True si se actualizó correctamente, false si no
 */
function actualizarCantidadCarrito($id_producto, $cantidad) {
    inicializarCarrito();
    
    if (!isset($_SESSION['carrito'][$id_producto])) {
        return false;
    }
    
    // Verificar stock
    $producto = obtenerProducto($id_producto);
    
    if (!$producto || $producto['existencia'] < $cantidad) {
        return false;
    }
    
    // Actualizar cantidad
    $_SESSION['carrito'][$id_producto]['cantidad'] = $cantidad;
    
    return true;
}

/**
 * Elimina un producto del carrito
 * 
 * @param int $id_producto ID del producto
 * @return bool True si se eliminó correctamente, false si no
 */
function eliminarDelCarrito($id_producto) {
    inicializarCarrito();
    
    if (!isset($_SESSION['carrito'][$id_producto])) {
        return false;
    }
    
    unset($_SESSION['carrito'][$id_producto]);
    
    return true;
}

/**
 * Obtiene el contenido del carrito
 * 
 * @return array Contenido del carrito
 */
function obtenerCarrito() {
    inicializarCarrito();
    return $_SESSION['carrito'];
}

/**
 * Calcula el total del carrito
 * 
 * @return float Total del carrito
 */
function calcularTotalCarrito() {
    inicializarCarrito();
    
    $total = 0;
    
    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
    
    return $total;
}

/**
 * Vacía el carrito
 */
function vaciarCarrito() {
    $_SESSION['carrito'] = [];
}