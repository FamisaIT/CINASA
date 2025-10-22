<?php
/**
 * Clase JsonResponse
 * Estandariza las respuestas JSON del sistema
 * 
 * Uso:
 *   JsonResponse::success('Cliente creado', ['id' => 123]);
 *   JsonResponse::error('No encontrado', [], 404);
 */

class JsonResponse {
    
    /**
     * Respuesta exitosa
     */
    public static function success($message = 'Operación exitosa', $data = [], $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ], JSON_UNESCAPED_UNICODE);
        
        exit;
    }
    
    /**
     * Respuesta de error
     */
    public static function error($message = 'Error en la operación', $errors = [], $statusCode = 400) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        
        exit;
    }
    
    /**
     * Respuesta de error de validación (400)
     */
    public static function validationError($errors = [], $message = 'Errores de validación') {
        self::error($message, $errors, 400);
    }
    
    /**
     * Respuesta de no encontrado (404)
     */
    public static function notFound($message = 'Recurso no encontrado') {
        self::error($message, [], 404);
    }
    
    /**
     * Respuesta de no autorizado (401)
     */
    public static function unauthorized($message = 'No autorizado') {
        self::error($message, [], 401);
    }
    
    /**
     * Respuesta de prohibido (403)
     */
    public static function forbidden($message = 'Acceso prohibido') {
        self::error($message, [], 403);
    }
    
    /**
     * Respuesta de método no permitido (405)
     */
    public static function methodNotAllowed($message = 'Método no permitido') {
        self::error($message, [], 405);
    }
    
    /**
     * Respuesta de error del servidor (500)
     */
    public static function serverError($message = 'Error interno del servidor') {
        self::error($message, [], 500);
    }
    
    /**
     * Respuesta con paginación
     */
    public static function paginated($data = [], $pagination = [], $message = 'Datos obtenidos') {
        http_response_code(200);
        header('Content-Type: application/json; charset=utf-8');
        
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'pagination' => [
                'total' => $pagination['total'] ?? 0,
                'pagina_actual' => $pagination['pagina_actual'] ?? 1,
                'total_paginas' => $pagination['total_paginas'] ?? 1,
                'por_pagina' => $pagination['por_pagina'] ?? 20
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ], JSON_UNESCAPED_UNICODE);
        
        exit;
    }
    
    /**
     * Respuesta con recurso creado (201)
     */
    public static function created($message = 'Recurso creado', $data = []) {
        self::success($message, $data, 201);
    }
    
    /**
     * Respuesta sin contenido (204)
     */
    public static function noContent() {
        http_response_code(204);
        exit;
    }
    
    /**
     * Verificar que el método HTTP sea el correcto
     */
    public static function requireMethod($method) {
        if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
            self::methodNotAllowed("Se requiere método {$method}");
        }
    }
    
    /**
     * Verificar que los parámetros requeridos existan
     */
    public static function requireParams($params, $source = 'POST') {
        $missing = [];
        $data = $source === 'POST' ? $_POST : $_GET;
        
        foreach ($params as $param) {
            if (!isset($data[$param]) || $data[$param] === '') {
                $missing[] = $param;
            }
        }
        
        if (!empty($missing)) {
            self::validationError(
                ['Parámetros faltantes: ' . implode(', ', $missing)],
                'Parámetros requeridos no proporcionados'
            );
        }
    }
}
