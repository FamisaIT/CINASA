# 🚚 Ejemplo: Módulo de Proveedores

Este ejemplo muestra cómo agregar un módulo completo de Proveedores al sistema.

## 🎯 Objetivo

Crear un catálogo de proveedores con las mismas capacidades que el de clientes:
- CRUD completo
- Filtros y búsqueda
- Exportación CSV/PDF
- Interfaz similar

## 📦 Archivos incluidos

```
ejemplo_modulo_proveedores/
├── README.md (este archivo)
├── database/
│   └── proveedores.sql
├── models/
│   └── proveedores_model.php
├── controllers/
│   ├── proveedores_crear.php
│   ├── proveedores_listar.php
│   ├── proveedores_editar.php
│   ├── proveedores_eliminar.php
│   └── export_proveedores_csv.php
├── views/
│   └── modal_proveedor.php
├── assets/
│   └── proveedores.js
└── proveedores.php (página principal)
```

## 🚀 Instalación

### Paso 1: Base de datos
```bash
mysql -u root -p clientes_db < database/proveedores.sql
```

### Paso 2: Copiar archivos
```bash
# Desde la raíz del proyecto
cp ejemplos_escalabilidad/ejemplo_modulo_proveedores/models/*.php app/models/
cp ejemplos_escalabilidad/ejemplo_modulo_proveedores/controllers/*.php app/controllers/
cp ejemplos_escalabilidad/ejemplo_modulo_proveedores/views/*.php app/views/
cp ejemplos_escalabilidad/ejemplo_modulo_proveedores/assets/*.js app/assets/
cp ejemplos_escalabilidad/ejemplo_modulo_proveedores/proveedores.php .
```

### Paso 3: Actualizar menú
Agregar en `app/views/header.php` (línea 26, después del item "Listado"):

```php
<li class="nav-item">
    <a class="nav-link" href="/proveedores.php">
        <i class="fas fa-truck"></i> Proveedores
    </a>
</li>
```

### Paso 4: Probar
Abrir en el navegador:
```
http://localhost/tu_proyecto/proveedores.php
```

## ✅ Funcionalidades

- [x] Listar proveedores con paginación
- [x] Crear nuevo proveedor
- [x] Editar proveedor existente
- [x] Bloquear proveedor
- [x] Búsqueda y filtros
- [x] Exportar a CSV
- [x] Validaciones frontend y backend

## 🔄 Diferencias con Clientes

| Aspecto | Clientes | Proveedores |
|---------|----------|-------------|
| Campos | 24 campos | 12 campos (simplificado) |
| Días de crédito | ✓ | ✓ |
| Límite de crédito | ✓ | ✓ |
| Vendedor asignado | ✓ | Comprador asignado |
| Uso CFDI | ✓ | - |

## 📝 Notas

- Los proveedores usan la misma estructura que clientes
- Puedes agregar más campos según necesites
- El código está basado en los patrones de clientes
- Es fácil de mantener porque sigue las convenciones establecidas

## 🎓 Aprendizajes

Este ejemplo demuestra:
1. ✅ Cómo reutilizar patrones existentes
2. ✅ Cómo mantener consistencia en el código
3. ✅ Cómo agregar módulos sin afectar los existentes
4. ✅ Cómo seguir la arquitectura establecida

## 🚧 Próximas mejoras posibles

- [ ] Agregar relación proveedor-productos
- [ ] Historial de compras
- [ ] Calificación de proveedores
- [ ] Documentos adjuntos (contratos, etc.)
