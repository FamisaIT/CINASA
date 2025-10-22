<?php
/**
 * Clase Utils
 * Utilidades generales del sistema
 * 
 * Uso:
 *   $limpio = Utils::sanitize($_POST['texto']);
 *   $slug = Utils::slug('Mi Texto');
 */

class Utils {
    
    /**
     * Sanitizar texto (prevenir XSS)
     */
    public static function sanitize($texto) {
        return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Sanitizar array recursivamente
     */
    public static function sanitizeArray($array) {
        $resultado = [];
        
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $resultado[$key] = self::sanitizeArray($value);
            } else {
                $resultado[$key] = self::sanitize($value);
            }
        }
        
        return $resultado;
    }
    
    /**
     * Formatear RFC (mayúsculas y sin espacios)
     */
    public static function formatRFC($rfc) {
        return strtoupper(trim(str_replace(' ', '', $rfc)));
    }
    
    /**
     * Generar slug de URL
     */
    public static function slug($texto) {
        $texto = self::removeAccents($texto);
        $texto = strtolower($texto);
        $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
        $texto = trim($texto, '-');
        return $texto;
    }
    
    /**
     * Remover acentos
     */
    public static function removeAccents($texto) {
        $acentos = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N'
        ];
        
        return strtr($texto, $acentos);
    }
    
    /**
     * Formatear dinero
     */
    public static function formatMoney($cantidad, $moneda = 'MXN') {
        $simbolos = [
            'MXN' => '$',
            'USD' => 'USD $',
            'EUR' => '€'
        ];
        
        $simbolo = $simbolos[$moneda] ?? $moneda . ' ';
        return $simbolo . number_format($cantidad, 2, '.', ',');
    }
    
    /**
     * Formatear fecha
     */
    public static function formatDate($fecha, $formato = 'd/m/Y') {
        if (empty($fecha)) return 'N/A';
        
        $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
        return date($formato, $timestamp);
    }
    
    /**
     * Formatear fecha y hora
     */
    public static function formatDateTime($fecha, $formato = 'd/m/Y H:i') {
        return self::formatDate($fecha, $formato);
    }
    
    /**
     * Obtener tiempo relativo (hace X días)
     */
    public static function timeAgo($fecha) {
        $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
        $diferencia = time() - $timestamp;
        
        if ($diferencia < 60) {
            return 'Hace menos de un minuto';
        } elseif ($diferencia < 3600) {
            $minutos = floor($diferencia / 60);
            return "Hace {$minutos} minuto" . ($minutos > 1 ? 's' : '');
        } elseif ($diferencia < 86400) {
            $horas = floor($diferencia / 3600);
            return "Hace {$horas} hora" . ($horas > 1 ? 's' : '');
        } elseif ($diferencia < 604800) {
            $dias = floor($diferencia / 86400);
            return "Hace {$dias} día" . ($dias > 1 ? 's' : '');
        } else {
            return self::formatDate($fecha);
        }
    }
    
    /**
     * Truncar texto
     */
    public static function truncate($texto, $longitud = 100, $sufijo = '...') {
        if (mb_strlen($texto) <= $longitud) {
            return $texto;
        }
        
        return mb_substr($texto, 0, $longitud) . $sufijo;
    }
    
    /**
     * Generar código único
     */
    public static function generarCodigo($prefijo = '', $longitud = 10) {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = '';
        
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        
        return $prefijo . $codigo;
    }
    
    /**
     * Generar folio con fecha
     */
    public static function generarFolio($prefijo = 'FOL') {
        return $prefijo . '-' . date('Ymd') . '-' . rand(1000, 9999);
    }
    
    /**
     * Encriptar (simple)
     */
    public static function encrypt($texto, $clave = null) {
        $clave = $clave ?? 'clave_por_defecto_cambiar';
        return base64_encode(openssl_encrypt($texto, 'AES-128-ECB', $clave));
    }
    
    /**
     * Desencriptar (simple)
     */
    public static function decrypt($textoEncriptado, $clave = null) {
        $clave = $clave ?? 'clave_por_defecto_cambiar';
        return openssl_decrypt(base64_decode($textoEncriptado), 'AES-128-ECB', $clave);
    }
    
    /**
     * Validar CURP
     */
    public static function validarCURP($curp) {
        $curp = strtoupper(trim($curp));
        return preg_match('/^[A-Z]{4}\d{6}[HM][A-Z]{5}[0-9A-Z]\d$/', $curp);
    }
    
    /**
     * Formatear teléfono
     */
    public static function formatTelefono($telefono) {
        $numeros = preg_replace('/\D/', '', $telefono);
        
        if (strlen($numeros) === 10) {
            return '(' . substr($numeros, 0, 3) . ') ' . 
                   substr($numeros, 3, 3) . '-' . 
                   substr($numeros, 6, 4);
        }
        
        return $telefono;
    }
    
    /**
     * Convertir array a CSV
     */
    public static function arrayToCSV($array, $delimitador = ',') {
        if (empty($array)) return '';
        
        $output = fopen('php://temp', 'w');
        
        // Headers
        fputcsv($output, array_keys($array[0]), $delimitador);
        
        // Datos
        foreach ($array as $fila) {
            fputcsv($output, $fila, $delimitador);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
    
    /**
     * Descargar archivo CSV
     */
    public static function downloadCSV($array, $nombreArchivo = 'export.csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        if (!empty($array)) {
            fputcsv($output, array_keys($array[0]));
            
            // Datos
            foreach ($array as $fila) {
                fputcsv($output, $fila);
            }
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Obtener IP del cliente
     */
    public static function getClientIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        }
    }
    
    /**
     * Log simple a archivo
     */
    public static function log($mensaje, $tipo = 'INFO', $archivo = 'app.log') {
        $logDir = __DIR__ . '/../../logs/';
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $ip = self::getClientIP();
        $linea = "[{$timestamp}] [{$tipo}] [IP: {$ip}] {$mensaje}\n";
        
        file_put_contents($logDir . $archivo, $linea, FILE_APPEND);
    }
    
    /**
     * Redireccionar
     */
    public static function redirect($url, $codigo = 302) {
        http_response_code($codigo);
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Verificar si es AJAX
     */
    public static function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Obtener valores POST seguros
     */
    public static function post($key, $default = null) {
        return isset($_POST[$key]) ? self::sanitize($_POST[$key]) : $default;
    }
    
    /**
     * Obtener valores GET seguros
     */
    public static function get($key, $default = null) {
        return isset($_GET[$key]) ? self::sanitize($_GET[$key]) : $default;
    }
}
