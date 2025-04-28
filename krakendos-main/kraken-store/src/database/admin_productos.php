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