-- Crear tabla Proveedor
CREATE TABLE proveedor (
    id_proveedor SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255),
    telefono VARCHAR(10),
    correo VARCHAR(100)
);

-- Crear tabla Categoría
CREATE TABLE categoria (
    id_categoria SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Crear tabla Empleado
CREATE TABLE empleado (
    id_empleado SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    puesto VARCHAR(100),
    telefono VARCHAR(10),
    correo VARCHAR(100)
);

-- Crear tabla Usuario (para acceso al sistema)
CREATE TABLE usuario (
    id_usuario SERIAL PRIMARY KEY,
    id_empleado INT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado)
);

-- Crear tabla Cliente
CREATE TABLE cliente (
    id_cliente SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(10),
    correo VARCHAR(100),
    direccion VARCHAR(255)
);

-- Crear tabla Producto
CREATE TABLE producto (
    id_producto SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    precio_mayoreo DECIMAL(10, 2),
    unidades_mayoreo INT,
    existencia INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255), -- Ruta a la imagen del producto
    id_proveedor INT,
    id_categoria INT NOT NULL,
    FOREIGN KEY (id_proveedor) REFERENCES proveedor(id_proveedor),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
);

-- Crear tabla Precio_Mayoreo (para diferentes niveles de mayoreo)
CREATE TABLE precio_mayoreo (
    id_mayoreo SERIAL PRIMARY KEY,
    id_producto INT NOT NULL,
    cantidad_minima INT NOT NULL,
    precio_mayoreo DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto) ON DELETE CASCADE
);

-- Crear tabla Venta
CREATE TABLE venta (
    id_venta SERIAL PRIMARY KEY,
    fecha DATE NOT NULL DEFAULT CURRENT_DATE,
    total DECIMAL(10, 2) NOT NULL,
    id_cliente INT,
    id_empleado INT NOT NULL, -- Empleado que realizó la venta
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado)
);

-- Crear tabla Detalle_Venta
CREATE TABLE detalle_venta (
    id_detalle SERIAL PRIMARY KEY,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL, -- Precio al momento de la venta
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES venta(id_venta) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);

-- Crear tabla para manejo de imágenes de productos (múltiples imágenes por producto)
CREATE TABLE producto_imagen (
    id_imagen SERIAL PRIMARY KEY,
    id_producto INT NOT NULL,
    ruta_imagen VARCHAR(255) NOT NULL,
    es_principal BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto) ON DELETE CASCADE
);

-- Crear índices para mejorar el rendimiento de consultas frecuentes
CREATE INDEX idx_producto_categoria ON producto(id_categoria);
CREATE INDEX idx_producto_nombre ON producto(nombre);
CREATE INDEX idx_detalle_venta_producto ON detalle_venta(id_producto);
CREATE INDEX idx_venta_fecha ON venta(fecha);

-- Funciones y Triggers

-- Función para actualizar el stock al realizar una venta
CREATE OR REPLACE FUNCTION actualizar_stock_venta() RETURNS TRIGGER AS $$
BEGIN
    UPDATE producto 
    SET existencia = existencia - NEW.cantidad
    WHERE id_producto = NEW.id_producto;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger para actualizar stock automáticamente
CREATE TRIGGER trigger_actualizar_stock
AFTER INSERT ON detalle_venta
FOR EACH ROW
EXECUTE FUNCTION actualizar_stock_venta();

-- Función para calcular el subtotal automáticamente
CREATE OR REPLACE FUNCTION calcular_subtotal() RETURNS TRIGGER AS $$
BEGIN
    NEW.subtotal := NEW.cantidad * NEW.precio_unitario;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger para calcular subtotal automáticamente
CREATE TRIGGER trigger_calcular_subtotal
BEFORE INSERT OR UPDATE ON detalle_venta
FOR EACH ROW
EXECUTE FUNCTION calcular_subtotal();

-- Función para actualizar el total de la venta
CREATE OR REPLACE FUNCTION actualizar_total_venta() RETURNS TRIGGER AS $$
BEGIN
    UPDATE venta
    SET total = (SELECT SUM(subtotal) FROM detalle_venta WHERE id_venta = NEW.id_venta)
    WHERE id_venta = NEW.id_venta;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger para actualizar el total de la venta
CREATE TRIGGER trigger_actualizar_total
AFTER INSERT OR UPDATE ON detalle_venta
FOR EACH ROW
EXECUTE FUNCTION actualizar_total_venta();

-- Insertar datos de ejemplo

-- Insertar categorías
INSERT INTO categoria (nombre) VALUES 
('Audífonos'),
('Apple Watch'),
('Proyectores'),
('MagSafe'),
('Cargadores'),
('Accesorios');

-- Insertar proveedores
INSERT INTO proveedor (nombre, direccion, telefono, correo) VALUES 
('Distribuidor Oficial', 'Av. Principal 123, Ciudad de México', '5512345678', 'contacto@distribuidoroficial.com'),
('Importaciones Tech', 'Calle Comercio 456, Veracruz', '2298765432', 'ventas@importacionestech.com');

-- Insertar empleados
INSERT INTO empleado (nombre, puesto, telefono, correo) VALUES 
('Administrador', 'Gerente', '2291234567', 'admin@krakenstore.com'),
('Vendedor', 'Ventas', '2299876543', 'ventas@krakenstore.com');

-- Insertar usuarios
INSERT INTO usuario (id_empleado, username, contrasena) VALUES 
(1, 'admin', 'admin123'); -- En producción usar contraseñas hasheadas