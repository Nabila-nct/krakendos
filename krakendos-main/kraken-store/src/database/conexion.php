<?php
/**
 * Conexión a la base de datos PostgreSQL para Kraken Store
 */

// Parámetros de conexión
$host = 'localhost';
$port = '5432';
$dbname = 'kraken_store';
$user = 'kraken_user';     
$password = 'kraken_2025'; 
// Cadena de conexión
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";

try {
    // Crear conexión PDO
    $pdo = new PDO($dsn);
    
    // Configurar el modo de error para lanzar excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Opcional: configurar el modo de búsqueda para devolver arrays asociativos
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // echo "Conexión establecida correctamente";
} catch (PDOException $e) {
    // En producción, mostrar un mensaje genérico y registrar el error
    die("Error de conexión: " . $e->getMessage());
}

/**
 * Función para ejecutar consultas SELECT
 * 
 * @param string $query La consulta SQL
 * @param array $params Parámetros para la consulta preparada
 * @return array Resultados de la consulta
 */
function consultarDB($query, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error en consulta: " . $e->getMessage());
        return false;
    }
}

/**
 * Función para ejecutar consultas INSERT, UPDATE, DELETE
 * 
 * @param string $query La consulta SQL
 * @param array $params Parámetros para la consulta preparada
 * @return int|bool Número de filas afectadas o false en caso de error
 */
function ejecutarDB($query, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("Error en consulta: " . $e->getMessage());
        return false;
    }
}

/**
 * Función para obtener el último ID insertado
 * 
 * @return string El último ID insertado
 */
function ultimoIdInsertado() {
    global $pdo;
    return $pdo->lastInsertId();
}