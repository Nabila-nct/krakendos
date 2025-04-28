<?php
/**
 * api_productos.php - API para obtener productos mediante AJAX
 */

// Incluir los archivos necesarios
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/productos.php';


// Permitir solicitudes AJAX desde el mismo dominio
header('Content-Type: application/json');

// Obtener acción solicitada
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

// Respuesta por defecto
$respuesta = [
    'exito' => false,
    'mensaje' => 'Acción no válida',
    'datos' => null
];

// Procesar según la acción solicitada
switch ($accion) {
    case 'obtener_categoria':
        // Obtener productos de una categoría
        $id_categoria = isset($_GET['id_categoria']) ? intval($_GET['id_categoria']) : 0;
        
        if ($id_categoria > 0) {
            $productos = obtenerProductosPorCategoria($id_categoria);
            
            if ($productos !== false) {
                // Para cada producto, añadir la URL de la imagen
                foreach ($productos as &$producto) {
                    $producto['imagen'] = obtenerImagenPrincipal($producto['id_producto']);
                }
                
                $respuesta = [
                    'exito' => true,
                    'mensaje' => 'Productos obtenidos con éxito',
                    'datos' => $productos
                ];
            } else {
                $respuesta['mensaje'] = 'Error al obtener productos';
            }
        } else {
            $respuesta['mensaje'] = 'ID de categoría no válido';
        }
        break;
        
    case 'obtener_producto':
        // Obtener detalles de un producto
        $id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;
        
        if ($id_producto > 0) {
            $producto = obtenerProducto($id_producto);
            
            if ($producto !== false) {
                // Añadir imágenes del producto
                $producto['imagenes'] = obtenerImagenesProducto($id_producto);
                
                // Si no hay imágenes en la tabla producto_imagen, usar el campo imagen
                if (empty($producto['imagenes']) && !empty($producto['imagen'])) {
                    $producto['imagenes'] = [
                        [
                            'id_imagen' => 0,
                            'ruta_imagen' => $producto['imagen'],
                            'es_principal' => true
                        ]
                    ];
                }
                
                $respuesta = [
                    'exito' => true,
                    'mensaje' => 'Producto obtenido con éxito',
                    'datos' => $producto
                ];
            } else {
                $respuesta['mensaje'] = 'Producto no encontrado';
            }
        } else {
            $respuesta['mensaje'] = 'ID de producto no válido';
        }
        break;
        
    case 'buscar':
        // Buscar productos
        $termino = isset($_GET['termino']) ? trim($_GET['termino']) : '';
        
        if (!empty($termino)) {
            $productos = buscarProductos($termino);
            
            if ($productos !== false) {
                // Para cada producto, añadir la URL de la imagen
                foreach ($productos as &$producto) {
                    $producto['imagen'] = obtenerImagenPrincipal($producto['id_producto']);
                }
                
                $respuesta = [
                    'exito' => true,
                    'mensaje' => 'Búsqueda realizada con éxito',
                    'datos' => $productos
                ];
            } else {
                $respuesta['mensaje'] = 'Error al realizar la búsqueda';
            }
        } else {
            $respuesta['mensaje'] = 'Término de búsqueda no válido';
        }
        break;
        
    case 'destacados':
        // Obtener productos destacados
        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 8;
        
        $productos = obtenerProductosDestacados($limite);
        
        if ($productos !== false) {
            // Para cada producto, añadir la URL de la imagen
            foreach ($productos as &$producto) {
                $producto['imagen'] = obtenerImagenPrincipal($producto['id_producto']);
            }
            
            $respuesta = [
                'exito' => true,
                'mensaje' => 'Productos destacados obtenidos con éxito',
                'datos' => $productos
            ];
        } else {
            $respuesta['mensaje'] = 'Error al obtener productos destacados';
        }
        break;
        
    case 'categorias':
        // Obtener categorías con productos
        $categorias = obtenerCategoriasConProductos();
        
        if ($categorias !== false) {
            $respuesta = [
                'exito' => true,
                'mensaje' => 'Categorías obtenidas con éxito',
                'datos' => $categorias
            ];
        } else {
            $respuesta['mensaje'] = 'Error al obtener categorías';
        }
        break;
        
    default:
        // Acción no reconocida
        $respuesta['mensaje'] = 'Acción no reconocida';
        break;
}

// Devolver respuesta JSON
echo json_encode($respuesta);