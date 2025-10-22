<?php
/**
 * Clase Validator
 * Contiene validaciones reutilizables para todo el sistema
 * 
 * Uso:
 *   $errores = Validator::validarRFC($rfc);
 *   if (!empty($errores)) { ... }
 */

class Validator {
    
    /**
     * Validar RFC mexicano
     */
    public static function validarRFC($rfc, $obligatorio = true) {
        $errores = [];
        
        if (empty($rfc)) {
            if ($obligatorio) {
                $errores[] = 'El RFC es obligatorio';
            }
            return $errores;
        }
        
        $rfc = strtoupper(trim($rfc));
        
        // Formato: 3-4 letras + 6 dígitos + 3 caracteres alfanuméricos
        if (!preg_match('/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/', $rfc)) {
            $errores[] = 'El RFC no tiene un formato válido';
        }
        
        return $errores;
    }
    
    /**
     * Validar email
     */
    public static function validarEmail($email, $obligatorio = false) {
        $errores = [];
        
        if (empty($email)) {
            if ($obligatorio) {
                $errores[] = 'El correo electrónico es obligatorio';
            }
            return $errores;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo electrónico no es válido';
        }
        
        return $errores;
    }
    
    /**
     * Validar teléfono
     */
    public static function validarTelefono($telefono, $obligatorio = false) {
        $errores = [];
        
        if (empty($telefono)) {
            if ($obligatorio) {
                $errores[] = 'El teléfono es obligatorio';
            }
            return $errores;
        }
        
        // Solo números, espacios, guiones y paréntesis
        if (!preg_match('/^[\d\s\-\(\)]+$/', $telefono)) {
            $errores[] = 'El teléfono solo puede contener números, espacios, guiones y paréntesis';
        }
        
        // Mínimo 10 dígitos
        $soloNumeros = preg_replace('/\D/', '', $telefono);
        if (strlen($soloNumeros) < 10) {
            $errores[] = 'El teléfono debe tener al menos 10 dígitos';
        }
        
        return $errores;
    }
    
    /**
     * Validar días de crédito
     */
    public static function validarDiasCredito($dias) {
        $errores = [];
        $valoresPermitidos = [0, 15, 30, 45, 60, 90];
        
        if (!in_array((int)$dias, $valoresPermitidos)) {
            $errores[] = 'Los días de crédito deben ser: 0, 15, 30, 45, 60 o 90';
        }
        
        return $errores;
    }
    
    /**
     * Validar límite de crédito
     */
    public static function validarLimiteCredito($limite) {
        $errores = [];
        
        if (!is_numeric($limite)) {
            $errores[] = 'El límite de crédito debe ser un número';
            return $errores;
        }
        
        if ($limite < 0) {
            $errores[] = 'El límite de crédito no puede ser negativo';
        }
        
        return $errores;
    }
    
    /**
     * Validar estatus
     */
    public static function validarEstatus($estatus, $valoresPermitidos = ['activo', 'suspendido', 'bloqueado']) {
        $errores = [];
        
        if (!in_array($estatus, $valoresPermitidos)) {
            $errores[] = 'El estatus debe ser: ' . implode(', ', $valoresPermitidos);
        }
        
        return $errores;
    }
    
    /**
     * Validar longitud de texto
     */
    public static function validarLongitud($texto, $min, $max, $campo = 'El campo') {
        $errores = [];
        $longitud = strlen($texto);
        
        if ($longitud < $min) {
            $errores[] = "{$campo} debe tener al menos {$min} caracteres";
        }
        
        if ($longitud > $max) {
            $errores[] = "{$campo} no puede tener más de {$max} caracteres";
        }
        
        return $errores;
    }
    
    /**
     * Validar campo obligatorio
     */
    public static function validarObligatorio($valor, $campo = 'Este campo') {
        $errores = [];
        
        if (empty($valor) && $valor !== '0' && $valor !== 0) {
            $errores[] = "{$campo} es obligatorio";
        }
        
        return $errores;
    }
    
    /**
     * Validar número positivo
     */
    public static function validarPositivo($numero, $campo = 'El valor') {
        $errores = [];
        
        if (!is_numeric($numero)) {
            $errores[] = "{$campo} debe ser un número";
            return $errores;
        }
        
        if ($numero < 0) {
            $errores[] = "{$campo} debe ser positivo";
        }
        
        return $errores;
    }
    
    /**
     * Validar fecha
     */
    public static function validarFecha($fecha, $formato = 'Y-m-d') {
        $errores = [];
        
        if (empty($fecha)) {
            return $errores; // Opcional por defecto
        }
        
        $d = DateTime::createFromFormat($formato, $fecha);
        if (!$d || $d->format($formato) !== $fecha) {
            $errores[] = "La fecha debe tener el formato {$formato}";
        }
        
        return $errores;
    }
    
    /**
     * Validar URL
     */
    public static function validarURL($url, $obligatorio = false) {
        $errores = [];
        
        if (empty($url)) {
            if ($obligatorio) {
                $errores[] = 'La URL es obligatoria';
            }
            return $errores;
        }
        
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $errores[] = 'La URL no es válida';
        }
        
        return $errores;
    }
    
    /**
     * Validar código postal mexicano
     */
    public static function validarCP($cp, $obligatorio = false) {
        $errores = [];
        
        if (empty($cp)) {
            if ($obligatorio) {
                $errores[] = 'El código postal es obligatorio';
            }
            return $errores;
        }
        
        if (!preg_match('/^\d{5}$/', $cp)) {
            $errores[] = 'El código postal debe tener 5 dígitos';
        }
        
        return $errores;
    }
    
    /**
     * Validar cliente completo (ejemplo de validación compuesta)
     */
    public static function validarCliente($datos) {
        $errores = [];
        
        // Validar razón social
        $errores = array_merge($errores, self::validarObligatorio($datos['razon_social'] ?? '', 'La razón social'));
        if (!empty($datos['razon_social'])) {
            $errores = array_merge($errores, self::validarLongitud($datos['razon_social'], 3, 250, 'La razón social'));
        }
        
        // Validar RFC
        $errores = array_merge($errores, self::validarRFC($datos['rfc'] ?? '', true));
        
        // Validar email
        if (!empty($datos['correo'])) {
            $errores = array_merge($errores, self::validarEmail($datos['correo']));
        }
        
        // Validar teléfono
        if (!empty($datos['telefono'])) {
            $errores = array_merge($errores, self::validarTelefono($datos['telefono']));
        }
        
        // Validar días de crédito
        $errores = array_merge($errores, self::validarDiasCredito($datos['dias_credito'] ?? 0));
        
        // Validar límite de crédito
        $errores = array_merge($errores, self::validarLimiteCredito($datos['limite_credito'] ?? 0));
        
        // Validar estatus
        $errores = array_merge($errores, self::validarEstatus($datos['estatus'] ?? 'activo'));
        
        return $errores;
    }
    
    /**
     * Validar proveedor completo
     */
    public static function validarProveedor($datos) {
        // Similar a validarCliente pero con campos específicos de proveedor
        return self::validarCliente($datos); // Por ahora usan la misma estructura
    }
}
