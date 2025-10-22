# 🛠️ Ejemplo: Helpers Reutilizables

Este ejemplo muestra cómo crear clases auxiliares (helpers) que pueden ser usadas por todos los módulos del sistema.

## 🎯 Objetivo

Evitar duplicación de código creando funciones reutilizables para:
- Validaciones comunes
- Respuestas JSON estandarizadas
- Utilidades generales

## 📦 Archivos incluidos

```
ejemplo_helpers/
├── README.md
├── Validator.php      # Validaciones reutilizables
├── JsonResponse.php   # Respuestas JSON estandarizadas
├── Utils.php          # Utilidades generales
└── autoload.php       # Autoloader simple
```

## 🚀 Instalación

### Paso 1: Crear carpeta helpers
```bash
mkdir -p app/helpers
```

### Paso 2: Copiar archivos
```bash
cp ejemplos_escalabilidad/ejemplo_helpers/*.php app/helpers/
```

### Paso 3: Incluir autoload en tus controladores
```php
// Al inicio de cualquier controlador
require_once __DIR__ . '/../helpers/autoload.php';
```

## 📚 Componentes

### 1. Validator.php

Validaciones comunes para todos los módulos:

```php
// Uso en clientes_crear.php
$errores = Validator::validarRFC($_POST['rfc']);
$errores = array_merge($errores, Validator::validarEmail($_POST['correo']));
$errores = array_merge($errores, Validator::validarDiasCredito($_POST['dias_credito']));

if (!empty($errores)) {
    JsonResponse::error('Errores de validación', $errores, 400);
}
```

### 2. JsonResponse.php

Respuestas JSON consistentes:

```php
// Éxito
JsonResponse::success('Cliente creado', ['id' => $id]);

// Error
JsonResponse::error('Cliente no encontrado', [], 404);

// Validación fallida
JsonResponse::validationError($errores);
```

### 3. Utils.php

Utilidades generales:

```php
// Sanitizar texto
$texto = Utils::sanitize($_POST['nombre']);

// Formatear RFC
$rfc = Utils::formatRFC($_POST['rfc']);

// Generar slug
$slug = Utils::slug('Razón Social SA de CV'); // razon-social-sa-de-cv

// Formatear moneda
echo Utils::formatMoney(1000000); // $1,000,000.00
```

## 💡 Ventajas

### Antes (sin helpers):
```php
// clientes_crear.php (50 líneas de validación)
if (empty($rfc)) {
    $errores[] = 'RFC obligatorio';
} elseif (!preg_match('/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/', $rfc)) {
    $errores[] = 'RFC inválido';
}

// proveedores_crear.php (50 líneas DUPLICADAS)
if (empty($rfc)) {
    $errores[] = 'RFC obligatorio';
} elseif (!preg_match('/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/', $rfc)) {
    $errores[] = 'RFC inválido';
}
```

### Después (con helpers):
```php
// clientes_crear.php (1 línea)
$errores = Validator::validarRFC($rfc);

// proveedores_crear.php (1 línea)
$errores = Validator::validarRFC($rfc);
```

## 🎓 Cómo extender

### Agregar nueva validación

```php
// En Validator.php
public static function validarCurp($curp) {
    $errores = [];
    
    if (empty($curp)) {
        return $errores; // Opcional
    }
    
    if (!preg_match('/^[A-Z]{4}\d{6}[HM][A-Z]{5}[0-9A-Z]\d$/', $curp)) {
        $errores[] = 'CURP inválido';
    }
    
    return $errores;
}
```

### Agregar nueva utilidad

```php
// En Utils.php
public static function generarFolio($prefijo = 'FOL') {
    return $prefijo . '-' . date('Ymd') . '-' . rand(1000, 9999);
}
```

## 📊 Impacto en Escalabilidad

### Código reducido
- ❌ Antes: 500 líneas de validación duplicadas
- ✅ Después: 100 líneas en Validator + 50 líneas por módulo

### Mantenimiento
- ❌ Antes: Cambiar validación en 5 lugares
- ✅ Después: Cambiar en 1 solo lugar

### Consistencia
- ❌ Antes: Mensajes de error diferentes por módulo
- ✅ Después: Mensajes consistentes en todo el sistema

## 🚧 Próximos pasos

Una vez implementados estos helpers, puedes:

1. Refactorizar controladores existentes para usarlos
2. Agregar más validaciones según necesites
3. Crear helpers específicos por área (FacturacionHelper, ReportesHelper, etc.)
4. Documentar cada helper con ejemplos

## ✅ Beneficios para el Sistema

- [x] Menos código duplicado
- [x] Más fácil de mantener
- [x] Más fácil de probar
- [x] Respuestas consistentes
- [x] Validaciones centralizadas
- [x] Código más limpio y legible

---

**Recomendación:** Implementa estos helpers antes de agregar más módulos para maximizar su beneficio.
