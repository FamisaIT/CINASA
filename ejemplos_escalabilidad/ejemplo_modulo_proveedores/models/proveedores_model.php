<?php
/**
 * Modelo de Proveedores
 * Basado en el modelo de clientes pero adaptado para proveedores
 */

class ProveedoresModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Listar proveedores con filtros, orden y paginación
     */
    public function listarProveedores($filtros = [], $orden = 'razon_social', $direccion = 'ASC', $limite = 20, $offset = 0) {
        $where = [];
        $params = [];
        
        if (!empty($filtros['buscar'])) {
            $where[] = "(razon_social LIKE :buscar OR rfc LIKE :buscar OR contacto_principal LIKE :buscar)";
            $params[':buscar'] = '%' . $filtros['buscar'] . '%';
        }
        
        if (!empty($filtros['estatus'])) {
            $where[] = "estatus = :estatus";
            $params[':estatus'] = $filtros['estatus'];
        }
        
        if (!empty($filtros['comprador'])) {
            $where[] = "comprador_asignado = :comprador";
            $params[':comprador'] = $filtros['comprador'];
        }
        
        if (!empty($filtros['pais'])) {
            $where[] = "pais = :pais";
            $params[':pais'] = $filtros['pais'];
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $columnasPermitidas = ['razon_social', 'rfc', 'estatus', 'fecha_alta', 'comprador_asignado', 'limite_credito'];
        if (!in_array($orden, $columnasPermitidas)) {
            $orden = 'razon_social';
        }
        
        $direccion = strtoupper($direccion) === 'DESC' ? 'DESC' : 'ASC';
        
        $sql = "SELECT * FROM proveedores {$whereClause} ORDER BY {$orden} {$direccion} LIMIT :limite OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Contar proveedores (para paginación)
     */
    public function contarProveedores($filtros = []) {
        $where = [];
        $params = [];
        
        if (!empty($filtros['buscar'])) {
            $where[] = "(razon_social LIKE :buscar OR rfc LIKE :buscar OR contacto_principal LIKE :buscar)";
            $params[':buscar'] = '%' . $filtros['buscar'] . '%';
        }
        
        if (!empty($filtros['estatus'])) {
            $where[] = "estatus = :estatus";
            $params[':estatus'] = $filtros['estatus'];
        }
        
        if (!empty($filtros['comprador'])) {
            $where[] = "comprador_asignado = :comprador";
            $params[':comprador'] = $filtros['comprador'];
        }
        
        if (!empty($filtros['pais'])) {
            $where[] = "pais = :pais";
            $params[':pais'] = $filtros['pais'];
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $sql = "SELECT COUNT(*) as total FROM proveedores {$whereClause}";
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $resultado = $stmt->fetch();
        return $resultado['total'];
    }
    
    /**
     * Obtener un proveedor por ID
     */
    public function obtenerProveedorPorId($id) {
        $sql = "SELECT * FROM proveedores WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Obtener un proveedor por RFC (para validar duplicados)
     */
    public function obtenerProveedorPorRFC($rfc, $excluirId = null) {
        $sql = "SELECT * FROM proveedores WHERE rfc = :rfc";
        
        if ($excluirId !== null) {
            $sql .= " AND id != :id";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':rfc', $rfc);
        
        if ($excluirId !== null) {
            $stmt->bindValue(':id', $excluirId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Crear nuevo proveedor
     */
    public function crearProveedor($datos) {
        $sql = "INSERT INTO proveedores (
            razon_social, rfc, regimen_fiscal, direccion, pais, contacto_principal,
            telefono, correo, dias_credito, limite_credito, condiciones_pago,
            banco, cuenta_bancaria, estatus, comprador_asignado
        ) VALUES (
            :razon_social, :rfc, :regimen_fiscal, :direccion, :pais, :contacto_principal,
            :telefono, :correo, :dias_credito, :limite_credito, :condiciones_pago,
            :banco, :cuenta_bancaria, :estatus, :comprador_asignado
        )";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($datos);
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Actualizar proveedor existente
     */
    public function actualizarProveedor($id, $datos) {
        $sql = "UPDATE proveedores SET
            razon_social = :razon_social,
            rfc = :rfc,
            regimen_fiscal = :regimen_fiscal,
            direccion = :direccion,
            pais = :pais,
            contacto_principal = :contacto_principal,
            telefono = :telefono,
            correo = :correo,
            dias_credito = :dias_credito,
            limite_credito = :limite_credito,
            condiciones_pago = :condiciones_pago,
            banco = :banco,
            cuenta_bancaria = :cuenta_bancaria,
            estatus = :estatus,
            comprador_asignado = :comprador_asignado
        WHERE id = :id";
        
        $datos['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($datos);
    }
    
    /**
     * Eliminar proveedor (bloqueo lógico)
     */
    public function eliminarProveedor($id) {
        $sql = "UPDATE proveedores SET estatus = 'bloqueado' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Obtener lista de compradores únicos (para filtros)
     */
    public function obtenerCompradores() {
        $sql = "SELECT DISTINCT comprador_asignado FROM proveedores WHERE comprador_asignado IS NOT NULL AND comprador_asignado != '' ORDER BY comprador_asignado";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Obtener lista de países únicos (para filtros)
     */
    public function obtenerPaises() {
        $sql = "SELECT DISTINCT pais FROM proveedores WHERE pais IS NOT NULL AND pais != '' ORDER BY pais";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
