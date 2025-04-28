<?php
/**
 * integracion.php - Funciones para integrar productos a las páginas HTML existentes
 * 
 * Este script debe incluirse al principio de cada página HTML para convertirla en PHP
 * y poder mostrar dinámicamente los productos desde la base de datos.
 */

// Iniciar sesión
session_start();

// Definir ruta base para incluir archivos
$base_path = __DIR__;

// Incluir archivos necesarios
require_once $base_path . '/conexion.php';
require_once $base_path . '/productos.php';

// Obtener la ruta actual para determinar qué página está viendo el usuario
$ruta_actual = basename($_SERVER['REQUEST_URI']);
if (strpos($ruta_actual, '?') !== false) {
    $ruta_actual = substr($ruta_actual, 0, strpos($ruta_actual, '?'));
}

// Función para obtener el nombre de la categoría actual basado en la URL
function obtenerNombreCategoria($pagina) {
    switch ($pagina) {
        case 'audifonos.php':
            return 'Audífonos';
        case 'apple-watch.php':
            return 'Apple Watch';
        case 'proyectores.php':
            return 'Proyectores';
        case 'magsafe.php':
            return 'MagSafe';
        case 'cargadores.php':
            return 'Cargadores';
        case 'cargadores-qi2.php':
            return 'Cargadores MagSafe 3 en 1';
        case 'accesorios.php':
            return 'Accesorios y Cargadores';
        default:
            return null;
    }
}

// Obtener el ID de la categoría actual
function obtenerIdCategoria($nombre_categoria) {
    if (!$nombre_categoria) return null;
    
    $query = "SELECT id_categoria FROM categoria WHERE nombre ILIKE ?";
    $resultado = consultarDB($query, [$nombre_categoria]);
    
    return $resultado && count($resultado) > 0 ? $resultado[0]['id_categoria'] : null;
}

// Obtener productos de la categoría actual
function obtenerProductosPaginaActual($pagina) {
    $nombre_categoria = obtenerNombreCategoria($pagina);
    if (!$nombre_categoria) return [];
    
    $id_categoria = obtenerIdCategoria($nombre_categoria);
    if (!$id_categoria) return [];
    
    return obtenerProductosPorCategoria($id_categoria);
}

// Función para convertir HTML a PHP y reemplazar los productos estáticos con dinámicos
function mostrarProductos($productos) {
    if (empty($productos)) return '';
    
    $html = '';
    $contador = 0;
    
    foreach ($productos as $producto) {
        $id = $producto['id_producto'];
        $nombre = htmlspecialchars($producto['nombre']);
        $descripcion = isset($producto['descripcion']) ? htmlspecialchars($producto['descripcion']) : '';
        $precio = number_format($producto['precio'], 2);
        
        // Obtener imagen del producto
        $imagen = obtenerImagenPrincipal($id);
        
        // Determinar si es un producto par o impar para el orden de la imagen
        $es_par = ($contador % 2 == 1);
        $clase_col_imagen = $es_par ? 'col-md-6 order-md-2 text-center' : 'col-md-6 text-center';
        $clase_col_info = $es_par ? 'col-md-6 order-md-1' : 'col-md-6';
        
        // Construir HTML para el producto
        $html .= <<<HTML
        <div class="row align-items-center mb-5" id="producto-{$id}">
            <div class="{$clase_col_imagen}">
                <img src="{$imagen}" class="img-fluid" alt="{$nombre}" style="max-height: 300px;">
            </div>
            <div class="{$clase_col_info}">
                <h2>{$nombre}</h2>
                <p>{$descripcion}</p>
                <h3>\${$precio}</h3>
            </div>
        </div>
HTML;
        
        $contador++;
    }
    
    return $html;
}

// Obtener productos para la página actual
$productos_actuales = obtenerProductosPaginaActual($ruta_actual);

// Función para generar el menú de categorías dinámicamente
function generarMenuCategorias() {
    $categorias = obtenerCategoriasConProductos();
    if (empty($categorias)) return '';
    
    $html = '';
    
    foreach ($categorias as $categoria) {
        $id = $categoria['id_categoria'];
        $nombre = htmlspecialchars($categoria['nombre']);
        $url = strtolower(preg_replace('/\s+/', '-', $nombre)) . '.php';
        
        $html .= "<li><a class=\"dropdown-item\" href=\"/kraken-store/src/pages/productos/{$url}\">{$nombre}</a></li>\n";
    }
    
    return $html;
}
// He eliminado la función agregarScriptsCarrito ya que mencionaste que no implementarás e-commerce