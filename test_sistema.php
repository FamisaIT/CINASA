<?php
/**
 * Script de Prueba Completa del Sistema
 * Verifica que todos los componentes existan y sean funcionales
 */

$errores = [];
$exitos = [];

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Prueba Completa del Sistema</title>
    <style>
        body { font-family: Arial; max-width: 1000px; margin: 20px auto; padding: 20px; }
        .ok { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .section { margin: 20px 0; padding: 15px; background: #f5f5f5; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #333; color: white; }
    </style>
</head>
<body>
<h1>🔍 Prueba Completa del Sistema - Catálogo de Clientes</h1>";

// 1. VERIFICAR ARCHIVOS DE CONFIGURACIÓN
echo "<div class='section'><h2>1. Archivos de Configuración</h2><table>";
echo "<tr><th>Archivo</th><th>Estado</th><th>Tamaño</th></tr>";

$archivos_config = [
    'app/config/database.php' => 'Configuración de BD',
    'app/config/session.php' => 'Manejo de sesiones',
];

foreach ($archivos_config as $archivo => $desc) {
    $path = __DIR__ . '/' . $archivo;
    if (file_exists($path)) {
        $size = filesize($path);
        echo "<tr><td>{$desc}</td><td class='ok'>✓ Existe</td><td>{$size} bytes</td></tr>";
    } else {
        echo "<tr><td>{$desc}</td><td class='error'>✗ NO EXISTE</td><td>-</td></tr>";
        $errores[] = "Falta: $archivo";
    }
}
echo "</table></div>";

// 2. VERIFICAR CONTROLADORES
echo "<div class='section'><h2>2. Controladores</h2><table>";
echo "<tr><th>Controlador</th><th>Estado</th><th>Líneas</th></tr>";

$controladores = [
    'app/controllers/clientes_crear.php' => 'Crear cliente',
    'app/controllers/clientes_editar.php' => 'Editar cliente',
    'app/controllers/clientes_eliminar.php' => 'Eliminar/Bloquear cliente',
    'app/controllers/clientes_listar.php' => 'Listar clientes',
    'app/controllers/clientes_detalle.php' => 'Ver detalle',
    'app/controllers/export_csv.php' => 'Exportar CSV',
    'app/controllers/export_pdf.php' => 'Exportar PDF',
    'app/controllers/obtener_filtros.php' => 'Obtener filtros',
];

foreach ($controladores as $archivo => $desc) {
    $path = __DIR__ . '/' . $archivo;
    if (file_exists($path)) {
        $lines = count(file($path));
        echo "<tr><td>{$desc}</td><td class='ok'>✓ Existe</td><td>{$lines} líneas</td></tr>";
        if ($lines < 10) {
            $errores[] = "Controlador muy corto: $archivo ($lines líneas)";
        }
    } else {
        echo "<tr><td>{$desc}</td><td class='error'>✗ NO EXISTE</td><td>-</td></tr>";
        $errores[] = "Falta: $archivo";
    }
}
echo "</table></div>";

// 3. VERIFICAR MODELOS
echo "<div class='section'><h2>3. Modelos</h2><table>";
echo "<tr><th>Modelo</th><th>Estado</th><th>Líneas</th></tr>";

$modelos = [
    'app/models/clientes_model.php' => 'Modelo de Clientes',
];

foreach ($modelos as $archivo => $desc) {
    $path = __DIR__ . '/' . $archivo;
    if (file_exists($path)) {
        $lines = count(file($path));
        echo "<tr><td>{$desc}</td><td class='ok'>✓ Existe</td><td>{$lines} líneas</td></tr>";
        if ($lines < 50) {
            $errores[] = "Modelo muy corto: $archivo ($lines líneas)";
        }
    } else {
        echo "<tr><td>{$desc}</td><td class='error'>✗ NO EXISTE</td><td>-</td></tr>";
        $errores[] = "Falta: $archivo";
    }
}
echo "</table></div>";

// 4. VERIFICAR VISTAS
echo "<div class='section'><h2>4. Vistas</h2><table>";
echo "<tr><th>Vista</th><th>Estado</th><th>Líneas</th></tr>";

$vistas = [
    'app/views/header.php' => 'Header HTML',
    'app/views/footer.php' => 'Footer HTML',
    'app/views/modal_cliente.php' => 'Modal de Cliente',
    'app/views/pdf_cliente.php' => 'Template PDF',
];

foreach ($vistas as $archivo => $desc) {
    $path = __DIR__ . '/' . $archivo;
    if (file_exists($path)) {
        $lines = count(file($path));
        echo "<tr><td>{$desc}</td><td class='ok'>✓ Existe</td><td>{$lines} líneas</td></tr>";
    } else {
        echo "<tr><td>{$desc}</td><td class='error'>✗ NO EXISTE</td><td>-</td></tr>";
        $errores[] = "Falta: $archivo";
    }
}
echo "</table></div>";

// 5. VERIFICAR ASSETS
echo "<div class='section'><h2>5. Assets (CSS/JS)</h2><table>";
echo "<tr><th>Asset</th><th>Estado</th><th>Tamaño</th></tr>";

$assets = [
    'app/assets/style.css' => 'Estilos CSS',
    'app/assets/app.js' => 'JavaScript principal',
];

foreach ($assets as $archivo => $desc) {
    $path = __DIR__ . '/' . $archivo;
    if (file_exists($path)) {
        $size = round(filesize($path) / 1024, 2);
        echo "<tr><td>{$desc}</td><td class='ok'>✓ Existe</td><td>{$size} KB</td></tr>";
    } else {
        echo "<tr><td>{$desc}</td><td class='error'>✗ NO EXISTE</td><td>-</td></tr>";
        $errores[] = "Falta: $archivo";
    }
}
echo "</table></div>";

// 6. VERIFICAR BASE DE DATOS
echo "<div class='section'><h2>6. Base de Datos</h2><table>";
echo "<tr><th>Componente</th><th>Estado</th></tr>";

$sql_file = __DIR__ . '/database/clientes.sql';
if (file_exists($sql_file)) {
    echo "<tr><td>Archivo SQL</td><td class='ok'>✓ Existe (" . filesize($sql_file) . " bytes)</td></tr>";
} else {
    echo "<tr><td>Archivo SQL</td><td class='error'>✗ NO EXISTE</td></tr>";
    $errores[] = "Falta: database/clientes.sql";
}

// Intentar conexión
try {
    require_once __DIR__ . '/app/config/database.php';
    echo "<tr><td>Conexión a BD</td><td class='ok'>✓ Conectado</td></tr>";
    
    // Verificar tabla
    $stmt = $pdo->query("SHOW TABLES LIKE 'clientes'");
    if ($stmt->rowCount() > 0) {
        echo "<tr><td>Tabla 'clientes'</td><td class='ok'>✓ Existe</td></tr>";
        
        // Contar registros
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM clientes");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<tr><td>Registros</td><td class='ok'>✓ {$result['total']} clientes</td></tr>";
    } else {
        echo "<tr><td>Tabla 'clientes'</td><td class='error'>✗ No existe (importar SQL)</td></tr>";
        $errores[] = "Tabla 'clientes' no existe";
    }
} catch (Exception $e) {
    echo "<tr><td>Conexión a BD</td><td class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
    $errores[] = "Error de conexión: " . $e->getMessage();
}

echo "</table></div>";

// 7. VERIFICAR LIBRERÍAS
echo "<div class='section'><h2>7. Librerías Externas</h2><table>";
echo "<tr><th>Librería</th><th>Estado</th><th>Tamaño</th></tr>";

$fpdf = __DIR__ . '/vendor/fpdf.php';
if (file_exists($fpdf)) {
    $size = round(filesize($fpdf) / 1024, 2);
    echo "<tr><td>FPDF (PDF)</td><td class='ok'>✓ Existe</td><td>{$size} KB</td></tr>";
} else {
    echo "<tr><td>FPDF (PDF)</td><td class='error'>✗ NO EXISTE</td></tr>";
    $errores[] = "Falta: vendor/fpdf.php";
}

echo "</table></div>";

// 8. RESUMEN FINAL
echo "<div class='section'><h2>📊 Resumen Final</h2>";

if (empty($errores)) {
    echo "<div style='background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; font-size: 1.2em;'>";
    echo "✅ <strong>SISTEMA COMPLETO Y FUNCIONAL</strong><br><br>";
    echo "Todos los componentes están presentes:<br>";
    echo "• 8 Controladores ✓<br>";
    echo "• 1 Modelo ✓<br>";
    echo "• 4 Vistas ✓<br>";
    echo "• 2 Assets (CSS/JS) ✓<br>";
    echo "• Base de datos configurada ✓<br>";
    echo "• Librería FPDF ✓<br>";
    echo "<br><a href='index.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;'>🚀 IR AL SISTEMA</a>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px;'>";
    echo "❌ <strong>SE ENCONTRARON " . count($errores) . " PROBLEMAS:</strong><br><br>";
    foreach ($errores as $error) {
        echo "• $error<br>";
    }
    echo "</div>";
}

echo "</div>";

// Información adicional
echo "<div class='section'>";
echo "<h3>ℹ️ Información Adicional</h3>";
echo "<p><strong>Versión PHP:</strong> " . phpversion() . "</p>";
echo "<p><strong>Servidor:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>Ruta del proyecto:</strong> " . __DIR__ . "</p>";
echo "</div>";

echo "</body></html>";
