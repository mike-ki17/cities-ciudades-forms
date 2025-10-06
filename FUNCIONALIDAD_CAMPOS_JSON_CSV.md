# Funcionalidad de Campos JSON con Carga de CSV

## Resumen de Cambios

Se ha implementado la funcionalidad para crear campos JSON con la estructura solicitada y la capacidad de cargar opciones desde archivos CSV, especialmente útil para localidades y barrios de ciudades.

## Problema Resuelto

**Error anterior:** "The field json field must be an array"
**Solución:** Se corrigió la validación en `StoreFieldJsonRequest` para permitir tanto strings JSON como arrays.

## Nuevas Funcionalidades

### 1. Estructura de Campo JSON Mejorada

Ahora se puede guardar campos con la estructura exacta solicitada:

```json
{
  "key": "barrios_bogota_suba",
  "label": "Barrios",
  "type": "select",
  "required": true,
  "order": 24,
  "description": "Seleccione su barrio en Suba",
  "visible": {
    "model": "localidad_bogota",
    "value": "suba",
    "condition": "equal"
  },
  "options": [
    { "value": "suba_centro", "label": "Suba Centro", "description": null },
    { "value": "cedritos_suba", "label": "Cedritos", "description": null },
    { "value": "niza_suba", "label": "Niza", "description": null },
    { "value": "santamaria_suba", "label": "Santa María", "description": null },
    { "value": "pinar_suba", "label": "El Pinar", "description": null },
    { "value": "calatrava", "label": "Calatrava", "description": null },
    { "value": "ibague_suba", "label": "Ibagué", "description": null }
  ]
}
```

### 2. Carga de Archivos CSV

#### Formato CSV Requerido:
```csv
value,label,description
suba_centro,Suba Centro,Centro de Suba
cedritos_suba,Cedritos,Barrio Cedritos
niza_suba,Niza,Barrio Niza
```

#### Características:
- **Columnas obligatorias:** `value`, `label`
- **Columna opcional:** `description`
- **Tamaño máximo:** 2MB
- **Formatos soportados:** .csv, .txt
- **Procesamiento automático:** Convierte CSV a formato JSON de opciones

### 3. Interfaz de Usuario Mejorada

#### Nuevas características en la vista de creación:
- Campo de carga de archivos CSV
- Ejemplo específico "Barrios Suba" disponible
- Validación en tiempo real del CSV
- Mensajes de éxito/error informativos
- Procesamiento automático del CSV al JSON

## Archivos Modificados

### 1. `app/Http/Requests/Field/StoreFieldJsonRequest.php`
- Corregida validación para permitir strings JSON
- Agregada validación para archivos CSV
- Implementado procesamiento automático de CSV
- Agregados mensajes de error personalizados

### 2. `app/Http/Controllers/Admin/FieldJsonController.php`
- Agregado soporte para campo `order`
- Implementado método `processCsvUpload()`
- Mejorado manejo de errores en carga de CSV

### 3. `resources/views/admin/fields-json/create.blade.php`
- Agregado campo de carga de archivos CSV
- Implementada funcionalidad JavaScript para procesamiento de CSV
- Agregado ejemplo específico "Barrios Suba"
- Mejorada interfaz con mensajes informativos

## Cómo Usar

### Opción 1: Crear Campo Manualmente
1. Ir a "Crear Campo JSON"
2. Escribir la estructura JSON completa
3. Hacer clic en "Crear Campo"

### Opción 2: Usar Ejemplo Predefinido
1. Ir a "Crear Campo JSON"
2. Hacer clic en "🎯 Barrios Suba (Ejemplo)"
3. Modificar según necesidades
4. Hacer clic en "Crear Campo"

### Opción 3: Cargar desde CSV
1. Ir a "Crear Campo JSON"
2. Crear estructura básica del campo (sin opciones)
3. Seleccionar archivo CSV con opciones
4. El sistema procesará automáticamente el CSV
5. Hacer clic en "Crear Campo"

## Archivo de Ejemplo

Se incluye `ejemplo_barrios_suba.csv` con datos de ejemplo para probar la funcionalidad.

## Beneficios

1. **Flexibilidad:** Permite crear campos complejos con visibilidad condicional
2. **Eficiencia:** Carga masiva de opciones desde CSV
3. **Usabilidad:** Interfaz intuitiva con ejemplos predefinidos
4. **Escalabilidad:** Ideal para grandes listas de localidades/barrios
5. **Mantenibilidad:** Estructura JSON clara y bien documentada

## Casos de Uso

- **Localidades de ciudades:** Cargar todas las localidades de Bogotá, Medellín, etc.
- **Barrios por localidad:** Cargar barrios específicos de cada localidad
- **Opciones dinámicas:** Crear campos que aparecen según selecciones previas
- **Formularios complejos:** Implementar lógica condicional avanzada


