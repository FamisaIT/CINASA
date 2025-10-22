-- Tabla de Proveedores
-- Similar a la tabla de clientes pero simplificada

CREATE TABLE IF NOT EXISTS proveedores (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  razon_social VARCHAR(250) NOT NULL,
  rfc VARCHAR(14) NOT NULL UNIQUE,
  regimen_fiscal VARCHAR(100),
  direccion TEXT,
  pais VARCHAR(100) DEFAULT 'México',
  contacto_principal VARCHAR(150),
  telefono VARCHAR(30),
  correo VARCHAR(150),
  dias_credito TINYINT UNSIGNED DEFAULT 0,
  limite_credito DECIMAL(15,2) DEFAULT 0.00,
  condiciones_pago VARCHAR(100),
  banco VARCHAR(150),
  cuenta_bancaria VARCHAR(50),
  estatus ENUM('activo','suspendido','bloqueado') DEFAULT 'activo',
  comprador_asignado VARCHAR(100),
  fecha_alta DATETIME DEFAULT CURRENT_TIMESTAMP,
  fecha_ultima_compra DATETIME DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_rfc (rfc),
  INDEX idx_estatus (estatus),
  INDEX idx_razon_social (razon_social(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo
INSERT INTO proveedores (razon_social, rfc, regimen_fiscal, direccion, pais, contacto_principal, telefono, correo, dias_credito, limite_credito, condiciones_pago, banco, cuenta_bancaria, estatus, comprador_asignado) VALUES
('DISTRIBUIDORA ALPHA SA DE CV', 'DAL950101ABC', '601 - General de Ley Personas Morales', 'Av. Insurgentes 500, Col. Nápoles, CDMX, CP 03810', 'México', 'Roberto Mendoza', '5556789012', 'ventas@alpha.com.mx', 30, 300000.00, 'Transferencia bancaria', 'BBVA Bancomer', '012180009876543210', 'activo', 'Laura Pérez'),
('IMPORTADORA OMEGA SC', 'IOM880215XYZ', '601 - General de Ley Personas Morales', 'Calle Madero 200, Centro, Monterrey, NL, CP 64000', 'México', 'Carlos Ramírez', '8181234567', 'contacto@omega.com', 45, 500000.00, 'Cheque', 'Santander', '014020009876543210', 'activo', 'Laura Pérez'),
('TECNOLOGÍA GAMMA SA', 'TGA020610DEF', '601 - General de Ley Personas Morales', 'Blvd. Díaz Ordaz 1000, Guadalajara, JAL, CP 44100', 'México', 'Ana Torres', '3339876543', 'ventas@gamma.tech', 15, 200000.00, 'Contado', 'Banorte', '072580009876543210', 'activo', 'Miguel Sánchez');
