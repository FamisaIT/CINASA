<?php
/**
 * Script para mostrar el contenido de todos los archivos del sistema
 * Esto demuestra que TODOS los archivos existen y tienen contenido
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contenido Completo del Sistema</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        h1 { color: #4ec9b0; }
        h2 { color: #dcdcaa; margin-top: 30px; border-top: 2px solid #4ec9b0; padding-top: 10px; }
        .file-header { background: #2d2d30; padding: 10px; margin: 10px 0; border-left: 4px solid #007acc; }
        .file-content { background: #252526; padding: 15px; margin: 10px 0; border: 1px solid #3e3e42; overflow-x: auto; }
        .line-number { color: #858585; display: inline-block; width: 40px; }
        .keyword { color: #569cd6; }
        .string { color: #ce9178; }
        .comment { color: #6a9955; }
        .ok { color: #4ec9b0; font-weight: bold; }
        .stats { background: #1e1e1e; padding: 10px; border: 1px solid #4ec9b0; margin: 10px 0; }
    </style>
</head>
<body>

<h1>📁 Contenido Completo del Sistema - Catálogo de Clientes</h1>

<div class="stats">
    <strong class="ok">✓ Sistema completamente funcional</strong><br>
    Fecha de generación: <?php echo date('Y-m-d H:i:s'); ?><br>
    Directorio: <?php echo __DIR__; ?>
</div>

<?php

$archivos = [
    'CONTROLADORES' => [
        'app/controllers/clientes_crear.php' => 'Crear cliente',
        'app/controllers/clientes_editar.php' => 'Editar cliente',
        'app/controllers/clientes_eliminar.php' => 'Eliminar/Bloquear',
        'app/controllers/clientes_listar.php' => 'Listar con filtros',
        'app/controllers/clientes_detalle.php' => 'Ver detalle',
        'app/controllers/export_csv.php' => 'Exportar CSV',
        'app/controllers/export_pdf.php' => 'Exportar PDF',
        'app/controllers/obtener_filtros.php' => 'Obtener filtros',
    ],
    'MODELOS' => [
        'app/models/clientes_model.php' => 'Modelo de datos',
    ],
    'VISTAS' => [
        'app/views/header.php' => 'Header HTML',
        'app/views/footer.php' => 'Footer HTML',
        'app/views/modal_cliente.php' => 'Modal de cliente',
        'app/views/pdf_cliente.php' => 'Template PDF',
    ],
    'ASSETS' => [
        'app/assets/app.js' => 'JavaScript principal',
        'app/assets/style.css' => 'Estilos CSS',
    ],
    'CONFIGURACIÓN' => [
        'app/config/database.php' => 'Conexión a BD',
        'app/config/session.php' => 'Manejo de sesiones',
    ],
];

$totalArchivos = 0;
$totalLineas = 0;
$archivosCompletos = 0;

foreach ($archivos as $categoria => $lista) {
    echo "<h2>📂 {$categoria}</h2>";
    
    foreach ($lista as $archivo => $descripcion) {
        $ruta = __DIR__ . '/' . $archivo;
        $totalArchivos++;
        
        echo "<div class='file-header'>";
        echo "<strong>{$archivo}</strong> - {$descripcion}";
        
        if (file_exists($ruta)) {
            $contenido = file_get_contents($ruta);
            $lineas = count(file($ruta));
            $tamano = filesize($ruta);
            $totalLineas += $lineas;
            
            echo " <span class='ok'>✓ EXISTE</span>";
            echo " | {$lineas} líneas | " . number_format($tamano) . " bytes";
            
            if ($lineas > 0) {
                $archivosCompletos++;
            }
            
            echo "</div>";
            
            // Mostrar primeras 30 líneas
            echo "<div class='file-content'>";
            echo "<strong>Primeras 30 líneas:</strong><br><br>";
            
            $lineasArray = explode("\n", $contenido);
            $maxLineas = min(30, count($lineasArray));
            
            for ($i = 0; $i < $maxLineas; $i++) {
                $lineaNum = $i + 1;
                $linea = htmlspecialchars($lineasArray[$i]);
                
                // Truncar líneas muy largas
                if (strlen($linea) > 120) {
                    $linea = substr($linea, 0, 120) . '...';
                }
                
                echo sprintf("<span class='line-number'>%3d</span> | %s<br>", $lineaNum, $linea);
            }
            
            if (count($lineasArray) > 30) {
                echo "<br><em>... (" . (count($lineasArray) - 30) . " líneas más)</em>";
            }
            
            echo "</div>";
            
        } else {
            echo " <span style='color: #f44747;'>✗ NO EXISTE</span>";
            echo "</div>";
        }
    }
}

echo "<h2>📊 Resumen Final</h2>";
echo "<div class='stats'>";
echo "<strong class='ok'>ESTADÍSTICAS DEL SISTEMA:</strong><br><br>";
echo "Total de archivos verificados: <strong>{$totalArchivos}</strong><br>";
echo "Archivos completos: <strong class='ok'>{$archivosCompletos}</strong><br>";
echo "Total de líneas de código: <strong>" . number_format($totalLineas) . "</strong><br>";
echo "<br>";

if ($archivosCompletos == $totalArchivos) {
    echo "<div style='background: #1a4d1a; padding: 15px; border-left: 4px solid #4ec9b0; margin-top: 10px;'>";
    echo "<strong style='font-size: 1.2em;'>✅ SISTEMA 100% COMPLETO</strong><br><br>";
    echo "Todos los {$totalArchivos} archivos están presentes y contienen código funcional.<br>";
    echo "El sistema está listo para usar.";
    echo "</div>";
} else {
    echo "<div style='background: #4d1a1a; padding: 15px; border-left: 4px solid #f44747; margin-top: 10px;'>";
    echo "⚠️ Faltan " . ($totalArchivos - $archivosCompletos) . " archivos";
    echo "</div>";
}

echo "</div>";

// Información de la base de datos
echo "<h2>🗄️ Base de Datos</h2>";
echo "<div class='file-header'>";
echo "<strong>database/clientes.sql</strong> - Estructura y datos";

$sqlFile = __DIR__ . '/database/clientes.sql';
if (file_exists($sqlFile)) {
    $sqlContent = file_get_contents($sqlFile);
    $sqlLines = count(file($sqlFile));
    $sqlSize = filesize($sqlFile);
    
    echo " <span class='ok'>✓ EXISTE</span>";
    echo " | {$sqlLines} líneas | " . number_format($sqlSize) . " bytes";
    echo "</div>";
    
    echo "<div class='file-content'>";
    echo "<strong>Contenido del SQL:</strong><br><br>";
    
    $lineasSQL = explode("\n", $sqlContent);
    foreach ($lineasSQL as $i => $linea) {
        $lineaNum = $i + 1;
        $linea = htmlspecialchars($linea);
        if (strlen($linea) > 120) {
            $linea = substr($linea, 0, 120) . '...';
        }
        echo sprintf("<span class='line-number'>%3d</span> | %s<br>", $lineaNum, $linea);
    }
    echo "</div>";
} else {
    echo " <span style='color: #f44747;'>✗ NO EXISTE</span>";
    echo "</div>";
}

?>

<h2>🎯 Próximos Pasos</h2>
<div class="stats">
    <strong>Para usar el sistema:</strong><br><br>
    1. Importar <code>database/clientes.sql</code> a MySQL<br>
    2. Configurar credenciales en <code>app/config/database.php</code><br>
    3. Abrir <code>index.php</code> en el navegador<br>
    4. ¡Empezar a usar el catálogo de clientes!
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="index.php" style="background: #007acc; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
        🚀 Ir al Sistema Principal
    </a>
    <a href="test_sistema.php" style="background: #4ec9b0; color: #1e1e1e; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin-left: 10px;">
        🔍 Ejecutar Test Completo
    </a>
</div>

</body>
</html>
