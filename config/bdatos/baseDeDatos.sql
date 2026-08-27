-- ==============================================================================
-- FASE 1: SCRIPT DE BASE DE DATOS E/R (SISTEMA DE GESTIÓN DE LIBROS)
-- Motor: MySQL / MariaDB (InnoDB)
-- ==============================================================================

CREATE DATABASE IF NOT EXISTS `tienda_libros` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `tienda_libros`;

-- Deshabilitar claves foráneas temporalmente para la reconstrucción limpia de tablas
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `detalle_ventas`;
DROP TABLE IF EXISTS `ventas`;
DROP TABLE IF EXISTS `detalle_compras`;
DROP TABLE IF EXISTS `compras`;
DROP TABLE IF EXISTS `libros`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `proveedores`;
DROP TABLE IF EXISTS `clientes`;
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `roles`;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------------------------
-- 1. TABLA: ROLES
-- ------------------------------------------------------------------------------
CREATE TABLE `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(50) NOT NULL UNIQUE,
  `descripcion` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 2. TABLA: USUARIOS (Personal Interno: Admin y Vendedores)
-- ------------------------------------------------------------------------------
CREATE TABLE `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `rol_id` INT NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `estado` TINYINT(1) DEFAULT 1 COMMENT '1: Activo, 0: Inactivo',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 3. TABLA: CLIENTES
-- ------------------------------------------------------------------------------
CREATE TABLE `clientes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `documento` VARCHAR(20) NOT NULL UNIQUE,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NULL,
  `telefono` VARCHAR(20) NULL,
  `direccion` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 4. TABLA: PROVEEDORES
-- ------------------------------------------------------------------------------
CREATE TABLE `proveedores` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nit_rut` VARCHAR(20) NOT NULL UNIQUE,
  `razon_social` VARCHAR(150) NOT NULL,
  `contacto` VARCHAR(100) NULL,
  `telefono` VARCHAR(20) NULL,
  `email` VARCHAR(150) NULL,
  `direccion` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 5. TABLA: CATEGORIAS
-- ------------------------------------------------------------------------------
CREATE TABLE `categorias` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL UNIQUE,
  `descripcion` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 6. TABLA: LIBROS
-- ------------------------------------------------------------------------------
CREATE TABLE `libros` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `categoria_id` INT NOT NULL,
  `isbn` VARCHAR(20) NOT NULL UNIQUE,
  `titulo` VARCHAR(200) NOT NULL,
  `autor` VARCHAR(150) NOT NULL,
  `precio_compra` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `precio_venta` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `stock` INT NOT NULL DEFAULT 0,
  `caratula` VARCHAR(255) DEFAULT 'default_cover.jpg',
  `destacado` TINYINT(1) DEFAULT 0 COMMENT '1: Mas vendido / Recomendado',
  `estado` TINYINT(1) DEFAULT 1 COMMENT '1: Disponible, 0: Descontinuado',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_libros_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX `idx_libros_isbn` (`isbn`),
  INDEX `idx_libros_titulo` (`titulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 7. TABLA: COMPRAS (Entradas de Inventario)
-- ------------------------------------------------------------------------------
CREATE TABLE `compras` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `proveedor_id` INT NOT NULL,
  `usuario_id` INT NOT NULL COMMENT 'Usuario Admin que registra la compra',
  `numero_factura` VARCHAR(50) NOT NULL,
  `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `fecha_compra` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_compras_proveedores` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_compras_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 8. TABLA: DETALLE_COMPRAS
-- ------------------------------------------------------------------------------
CREATE TABLE `detalle_compras` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `compra_id` INT NOT NULL,
  `libro_id` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `precio_unitario` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  CONSTRAINT `fk_det_compras_compras` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_det_compras_libros` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 9. TABLA: VENTAS (Salidas de Inventario)
-- ------------------------------------------------------------------------------
CREATE TABLE `ventas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `cliente_id` INT NOT NULL,
  `usuario_id` INT NOT NULL COMMENT 'Usuario Vendedor que efectua la venta',
  `numero_factura` VARCHAR(50) NOT NULL UNIQUE,
  `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `fecha_venta` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_ventas_clientes` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_ventas_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX `idx_ventas_fecha` (`fecha_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 10. TABLA: DETALLE_VENTAS
-- ------------------------------------------------------------------------------
CREATE TABLE `detalle_ventas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `venta_id` INT NOT NULL,
  `libro_id` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `precio_unitario` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  CONSTRAINT `fk_det_ventas_ventas` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_det_ventas_libros` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- INSERCIÓN DE DATOS DE PRUEBA (SEEDERS)
-- ==============================================================================

-- Roles
INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Control total del sistema, compras a proveedores y gestion de usuarios'),
(2, 'Vendedor', 'Gestion de ventas a clientes y consulta de inventario');

-- Usuarios (Password por defecto para todos: Admin123* -> Hashed con BCRYPT)
INSERT INTO `usuarios` (`id`, `rol_id`, `nombre`, `email`, `password`, `estado`) VALUES
(1, 1, 'Admin Principal', 'admin@tienda.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1eL62tY8b.k.Oa2g.0mWR2FjR/.9fSm', 1),
(2, 2, 'Vendedor Uno', 'vendedor1@tienda.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1eL62tY8b.k.Oa2g.0mWR2FjR/.9fSm', 1);

-- Categorías
INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Ingeniería y Desarrollo', 'Libros sobre arquitectura de software, bases de datos y programación'),
(2, 'Ciencia Ficción', 'Novelas futuristas y tecnología imaginaria'),
(3, 'Negocios y Liderazgo', 'Administración, emprendimiento y gestión de proyectos');

-- Proveedores
INSERT INTO `proveedores` (`id`, `nit_rut`, `razon_social`, `contacto`, `telefono`, `email`, `direccion`) VALUES
(1, '900123456-1', 'Editorial Alfaomega S.A.', 'Carlos Mendoza', '6015551234', 'ventas@alfaomega.com', 'Calle 45 # 12-34'),
(2, '900987654-2', 'Distribuidora Pearson', 'Ana Maria Gomez', '6015555678', 'contacto@pearson.com', 'Carrera 7 # 89-12');

-- Clientes
INSERT INTO `clientes` (`id`, `documento`, `nombre`, `email`, `telefono`, `direccion`) VALUES
(1, '1018234567', 'Juan Pablo Pérez', 'juan.perez@email.com', '3001234567', 'Calle 100 # 15-20'),
(2, '52987654', 'Maria Fernanda Lopez', 'm.lopez@email.com', '3159876543', 'Av. Suba # 114-50');

-- Libros
INSERT INTO `libros` (`id`, `categoria_id`, `isbn`, `titulo`, `autor`, `precio_compra`, `precio_venta`, `stock`, `caratula`, `destacado`) VALUES
(1, 1, '978-0132350884', 'Clean Code', 'Robert C. Martin', 120000.00, 160000.00, 15, 'clean_code.jpg', 1),
(2, 1, '978-0201633610', 'Design Patterns', 'Erich Gamma et al.', 140000.00, 190000.00, 8, 'design_patterns.jpg', 1),
(3, 2, '978-0451524935', '1984', 'George Orwell', 35000.00, 55000.00, 25, '1984.jpg', 1),
(4, 3, '978-0307474773', 'El Método Lean Startup', 'Eric Ries', 45000.00, 70000.00, 12, 'lean_startup.jpg', 0);

-- Compra Inicial de Prueba (Stock Inicial registrado)
INSERT INTO `compras` (`id`, `proveedor_id`, `usuario_id`, `numero_factura`, `total`, `fecha_compra`) VALUES
(1, 1, 1, 'FAC-PROV-001', 4000000.00, NOW());

INSERT INTO `detalle_compras` (`compra_id`, `libro_id`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 15, 120000.00, 1800000.00),
(1, 2, 8, 140000.00, 1120000.00),
(1, 3, 25, 35000.00, 875000.00);

-- Venta Inicial de Prueba
INSERT INTO `ventas` (`id`, `cliente_id`, `usuario_id`, `numero_factura`, `total`, `fecha_venta`) VALUES
(1, 1, 2, 'FAC-VEN-0001', 215000.00, NOW());

INSERT INTO `detalle_ventas` (`venta_id`, `libro_id`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 1, 160000.00, 160000.00),
(1, 3, 1, 55000.00, 55000.00);