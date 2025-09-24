# Campo Dinámico Ciudad + Localidad

## 🎯 Descripción

Este campo permite crear **un solo campo** que contiene tanto la selección de ciudad como la selección de localidad, donde al elegir una ciudad se cargan automáticamente las localidades correspondientes desde la API.

## ✨ Características

- **Un solo campo** para ciudad y localidad
- **Carga dinámica** de localidades basada en la ciudad seleccionada
- **API integrada** que proporciona las localidades
- **Interfaz intuitiva** con dos selectores relacionados
- **Datos limpios** - solo se guarda la ciudad y localidad seleccionada

## 🏗️ Estructura del Campo

```json
{
    "key": "ubicacion",
    "label": "Ubicación",
    "type": "dynamic_select",
    "required": true,
    "order": 3,
    "description": "Seleccione su ciudad y localidad",
    "dynamic_options": {
        "api_endpoint": "/api/localities/{city}",
        "parent_field": "ciudad",
        "child_field": "localidad"
    },
    "options": [
        {"value": "bogota", "label": "Bogotá D.C.", "description": null},
        {"value": "medellin", "label": "Medellín", "description": null},
        {"value": "cali", "label": "Cali", "description": null},
        {"value": "barranquilla", "label": "Barranquilla", "description": null},
        {"value": "cartagena", "label": "Cartagena", "description": null},
        {"value": "bucaramanga", "label": "Bucaramanga", "description": null}
    ]
}
```

## 🔧 Propiedades del Campo

### Propiedades Básicas
- **key**: `ubicacion` - Identificador único del campo
- **label**: `Ubicación` - Etiqueta mostrada al usuario
- **type**: `dynamic_select` - Tipo especial para campos dinámicos
- **required**: `true` - Campo obligatorio
- **description**: Descripción del campo

### Propiedades Dinámicas
- **dynamic_options**: Configuración para el comportamiento dinámico
  - **api_endpoint**: `/api/localities/{city}` - Endpoint de la API
  - **parent_field**: `ciudad` - Campo padre (ciudad)
  - **child_field**: `localidad` - Campo hijo (localidad)

### Opciones del Campo Padre
- **options**: Array con las ciudades disponibles
- Cada ciudad tiene `value`, `label` y `description`

## 🚀 Cómo Crear el Campo

### Método 1: Usando el Botón Rápido
1. Ve a `http://localhost:8000/admin/fields-json/create`
2. Haz clic en el botón **"🚀 Ciudad + Localidad Dinámico"**
3. El JSON se cargará automáticamente
4. Haz clic en "Crear Campo"

### Método 2: Usando el Seeder
```bash
php artisan db:seed --class=CampoDinamicoSeeder
```

### Método 3: Manual
1. Copia el JSON de la estructura del campo
2. Pégalo en el editor JSON
3. Guarda el campo

## 🔄 Funcionamiento

### 1. **Selección de Ciudad**
- El usuario ve un selector con las ciudades disponibles
- Al seleccionar una ciudad, se activa el segundo selector

### 2. **Carga Dinámica de Localidades**
- Se hace una petición a `/api/localities/{city}`
- La API devuelve las localidades para esa ciudad
- Se cargan automáticamente en el segundo selector

### 3. **Selección de Localidad**
- El usuario selecciona su localidad
- Ambos valores se guardan en el formulario

## 📊 Datos Guardados

### Estructura de Datos
```json
{
    "ciudad": "bogota",
    "localidad": "usaquen"
}
```

### Ventajas
- **Datos limpios**: Solo se guardan los valores seleccionados
- **Sin campos vacíos**: No se almacenan campos innecesarios
- **Estructura simple**: Fácil de procesar y analizar
- **Relación clara**: Ciudad y localidad están relacionadas

## 🌐 API Endpoint

### Endpoint
```
GET /api/localities/{city}
```

### Ejemplo de Respuesta
```json
{
    "success": true,
    "city": "bogota",
    "localities": [
        {"value": "usaquen", "label": "Usaquén", "description": null},
        {"value": "chapinero", "label": "Chapinero", "description": null},
        {"value": "santa_fe", "label": "Santa Fe", "description": null}
    ]
}
```

### Ciudades Soportadas
- `bogota` - 20 localidades
- `medellin` - 16 comunas
- `cali` - 22 comunas
- `barranquilla` - 5 localidades
- `cartagena` - 3 localidades
- `bucaramanga` - 17 comunas

## 🎨 Interfaz de Usuario

### En el Formulario
1. **Primer Selector**: "Seleccione su ciudad"
2. **Segundo Selector**: "Seleccione su localidad" (se activa después de seleccionar ciudad)
3. **Validación**: Ambos campos son obligatorios

### Comportamiento
- Al cargar la página: Solo se ve el selector de ciudad
- Al seleccionar ciudad: Aparece el selector de localidad
- Al cambiar ciudad: Se actualiza la lista de localidades
- Al enviar: Se validan ambos campos

## 🔧 Configuración Técnica

### Base de Datos
- **Tabla**: `fields_json`
- **Columna**: `dynamic_options` (JSON)
- **Tipo**: `dynamic_select`

### Validación
- Campo padre (ciudad) es obligatorio
- Campo hijo (localidad) es obligatorio
- Validación de API endpoint
- Validación de estructura JSON

### JavaScript
- Carga dinámica de opciones
- Validación en tiempo real
- Manejo de errores de API
- Actualización de interfaz

## 🧪 Pruebas

### Prueba Básica
1. Crear el campo dinámico
2. Agregarlo a un formulario
3. Probar la selección de ciudad
4. Verificar la carga de localidades
5. Enviar el formulario
6. Verificar los datos guardados

### Prueba de API
```bash
curl -X GET "http://localhost:8000/api/localities/bogota"
```

### Prueba de Validación
- Intentar enviar sin seleccionar ciudad
- Intentar enviar sin seleccionar localidad
- Verificar mensajes de error

## 🎉 Beneficios

### Para el Usuario
- **Interfaz intuitiva**: Dos selectores relacionados
- **Datos relevantes**: Solo ve localidades de su ciudad
- **Experiencia fluida**: Carga automática de opciones

### Para el Desarrollador
- **Un solo campo**: Fácil de gestionar
- **API reutilizable**: Endpoint estándar
- **Datos limpios**: Estructura simple

### Para el Sistema
- **Eficiencia**: No se almacenan datos innecesarios
- **Escalabilidad**: Fácil agregar nuevas ciudades
- **Mantenimiento**: Un solo campo para gestionar

## 🔮 Futuras Mejoras

- **Búsqueda**: Campo de búsqueda en localidades
- **Caché**: Cachear respuestas de API
- **Validación**: Validación de localidad por ciudad
- **Estadísticas**: Análisis de selecciones por ciudad
- **Exportación**: Exportar datos por ubicación

---

**¡El campo dinámico está listo para usar!** 🚀

Ahora puedes crear un solo campo que maneje ciudad y localidad de manera inteligente y eficiente.
