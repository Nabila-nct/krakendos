<?php
/**
 * admin_productos.php - CRUD para la gestión de productos
 */

// Incluir archivo de conexión
require_once __DIR__ . '/conexion.php';


// Función para obtener todas las categorías
function obtenerCategorias() {
    $query = "SELECT * FROM categoria ORDER BY nombre";
    return consultarDB($query);
}

// Función para obtener todos los proveedores
function obtenerProveedores() {
    $query = "SELECT * FROM proveedor ORDER BY nombre";
    return consultarDB($query);
}

// Función para obtener todos los productos
function obtenerProductos() {
    $query = "SELECT p.*, c.nombre as nombre_categoria, pr.nombre as nombre_proveedor 
              FROM producto p 
              LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
              LEFT JOIN proveedor pr ON p.id_proveedor = pr.id_proveedor 
              ORDER BY p.nombre";
    return consultarDB($query);
}

// Función para obtener un producto por ID
function obtenerProductoPorId($id_producto) {
    $query = "SELECT * FROM producto WHERE id_producto = ?";
    $resultado = consultarDB($query, [$id_producto]);
    return $resultado ? $resultado[0] : false;
}

// Función para obtener imágenes de un producto
function obtenerImagenesProducto($id_producto) {
    $query = "SELECT * FROM producto_imagen WHERE id_producto = ? ORDER BY es_principal DESC";
    return consultarDB($query, [$id_producto]);
}

// Función para agregar un nuevo producto
function agregarProducto($nombre, $descripcion, $precio, $precio_mayoreo, $unidades_mayoreo, $existencia, $id_proveedor, $id_categoria) {
    $query = "INSERT INTO producto (nombre, descripcion, precio, precio_mayoreo, unidades_mayoreo, existencia, id_proveedor, id_categoria) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    ejecutarDB($query, [
        $nombre, 
        $descripcion, 
        $precio, 
        $precio_mayoreo, 
        $unidades_mayoreo, 
        $existencia, 
        $id_proveedor ?: null, 
        $id_categoria
    ]);
    
    return ultimoIdInsertado();
}

// Función para actualizar un producto existente
function actualizarProducto($id_producto, $nombre, $descripcion, $precio, $precio_mayoreo, $unidades_mayoreo, $existencia, $id_proveedor, $id_categoria) {
    $query = "UPDATE producto 
              SET nombre = ?, descripcion = ?, precio = ?, precio_mayoreo = ?, 
                  unidades_mayoreo = ?, existencia = ?, id_proveedor = ?, id_categoria = ? 
              WHERE id_producto = ?";
    
    return ejecutarDB($query, [
        $nombre, 
        $descripcion, 
        $precio, 
        $precio_mayoreo, 
        $unidades_mayoreo, 
        $existencia, 
        $id_proveedor ?: null, 
        $id_categoria, 
        $id_producto
    ]);
}

// Función para eliminar un producto
function eliminarProducto($id_producto) {
    // Primero eliminamos las imágenes asociadas
    $query1 = "DELETE FROM producto_imagen WHERE id_producto = ?";
    ejecutarDB($query1, [$id_producto]);
    
    // Luego eliminamos el producto
    $query2 = "DELETE FROM producto WHERE id_producto = ?";
    return ejecutarDB($query2, [$id_producto]);
}

// Función para agregar una imagen a un producto
function agregarImagenProducto($id_producto, $ruta_imagen, $es_principal = false) {
    // Si es imagen principal, primero quitamos el atributo principal de otras imágenes
    if ($es_principal) {
        $query = "UPDATE producto_imagen SET es_principal = false WHERE id_producto = ?";
        ejecutarDB($query, [$id_producto]);
    }
    
    $query = "INSERT INTO producto_imagen (id_producto, ruta_imagen, es_principal) VALUES (?, ?, ?)";
    ejecutarDB($query, [$id_producto, $ruta_imagen, $es_principal]);
    
    return ultimoIdInsertado();
}

// Función para eliminar una imagen de un producto
function eliminarImagenProducto($id_imagen) {
    $query = "DELETE FROM producto_imagen WHERE id_imagen = ?";
    return ejecutarDB($query, [$id_imagen]);
}

// Función para actualizar la ruta de la imagen en la tabla producto (compatibilidad con estructura actual)
function actualizarImagenProducto($id_producto, $ruta_imagen) {
    $query = "UPDATE producto SET imagen = ? WHERE id_producto = ?";
    return ejecutarDB($query, [$ruta_imagen, $id_producto]);
}

// Función para buscar productos
function buscarProductos($termino) {
    $termino = "%$termino%"; // Para búsqueda parcial
    $query = "SELECT p.*, c.nombre as nombre_categoria 
              FROM producto p 
              LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
              WHERE p.nombre ILIKE ? OR p.descripcion ILIKE ? 
              ORDER BY p.nombre";
    
    return consultarDB($query, [$termino, $termino]);
}

// Función para filtrar productos por categoría
function filtrarProductosPorCategoria($id_categoria) {
    $query = "SELECT p.*, c.nombre as nombre_categoria 
              FROM producto p 
              LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
              WHERE p.id_categoria = ? 
              ORDER BY p.nombre";
    
    return consultarDB($query, [$id_categoria]);
}

/**
 * Función para búsqueda avanzada de productos con múltiples filtros
 * 
 * @param array $filtros Array con los filtros de búsqueda
 * @return array Lista de productos que coinciden con los filtros
 */
function buscarProductosAvanzado($filtros) {
    // Inicializar la consulta base
    $query = "SELECT p.*, c.nombre as nombre_categoria, pr.nombre as nombre_proveedor 
              FROM producto p 
              LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
              LEFT JOIN proveedor pr ON p.id_proveedor = pr.id_proveedor 
              WHERE 1=1";
    
    $params = [];
    
    // Filtro por término de búsqueda
    if (!empty($filtros['search_term'])) {
        $query .= " AND (p.nombre ILIKE ? OR p.descripcion ILIKE ?)";
        $termino = '%' . $filtros['search_term'] . '%';
        $params[] = $termino;
        $params[] = $termino;
    }
    
    // Filtro por categoría
    if (!empty($filtros['id_categoria'])) {
        $query .= " AND p.id_categoria = ?";
        $params[] = $filtros['id_categoria'];
    }
    
    // Filtro por proveedor
    if (!empty($filtros['id_proveedor'])) {
        $query .= " AND p.id_proveedor = ?";
        $params[] = $filtros['id_proveedor'];
    }
    
    // Filtro por precio mínimo
    if (!empty($filtros['precio_min'])) {
        $query .= " AND p.precio >= ?";
        $params[] = $filtros['precio_min'];
    }
    
    // Filtro por precio máximo
    if (!empty($filtros['precio_max'])) {
        $query .= " AND p.precio <= ?";
        $params[] = $filtros['precio_max'];
    }
    
    // Filtro por existencia mínima
    if (!empty($filtros['existencia_min'])) {
        $query .= " AND p.existencia >= ?";
        $params[] = $filtros['existencia_min'];
    }
    
    // Filtro por existencia máxima
    if (!empty($filtros['existencia_max'])) {
        $query .= " AND p.existencia <= ?";
        $params[] = $filtros['existencia_max'];
    }
    
    // Filtro para bajo stock
    if (!empty($filtros['low_stock']) && $filtros['low_stock'] == '1') {
        $query .= " AND p.existencia < 10";
    }
    
    // Ordenar por nombre
    $query .= " ORDER BY p.nombre";
    
    return consultarDB($query, $params);
}

/**
 * Función auxiliar para validar los filtros de búsqueda
 * 
 * @param array $filtros Array con los filtros a validar
 * @return array Array con los filtros validados y limpiados
 */
function validarFiltrosBusqueda($filtros) {
    $filtrosValidados = [];
    
    // Validar término de búsqueda
    if (isset($filtros['search_term']) && !empty(trim($filtros['search_term']))) {
        $filtrosValidados['search_term'] = trim($filtros['search_term']);
    }
    
    // Validar ID de categoría
    if (isset($filtros['id_categoria']) && is_numeric($filtros['id_categoria']) && $filtros['id_categoria'] > 0) {
        $filtrosValidados['id_categoria'] = intval($filtros['id_categoria']);
    }
    
    // Validar ID de proveedor
    if (isset($filtros['id_proveedor']) && is_numeric($filtros['id_proveedor']) && $filtros['id_proveedor'] > 0) {
        $filtrosValidados['id_proveedor'] = intval($filtros['id_proveedor']);
    }
    
    // Validar precios
    if (isset($filtros['precio_min']) && is_numeric($filtros['precio_min']) && $filtros['precio_min'] >= 0) {
        $filtrosValidados['precio_min'] = floatval($filtros['precio_min']);
    }
    
    if (isset($filtros['precio_max']) && is_numeric($filtros['precio_max']) && $filtros['precio_max'] >= 0) {
        $filtrosValidados['precio_max'] = floatval($filtros['precio_max']);
    }
    
    // Validar existencias
    if (isset($filtros['existencia_min']) && is_numeric($filtros['existencia_min']) && $filtros['existencia_min'] >= 0) {
        $filtrosValidados['existencia_min'] = intval($filtros['existencia_min']);
    }
    
    if (isset($filtros['existencia_max']) && is_numeric($filtros['existencia_max']) && $filtros['existencia_max'] >= 0) {
        $filtrosValidados['existencia_max'] = intval($filtros['existencia_max']);
    }
    
    // Validar low stock
    if (isset($filtros['low_stock']) && $filtros['low_stock'] == '1') {
        $filtrosValidados['low_stock'] = '1';
    }
    
    return $filtrosValidados;
}