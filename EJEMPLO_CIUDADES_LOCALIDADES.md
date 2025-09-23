# Ejemplo: Formulario de Ciudades y Localidades Condicionales

Este documento explica cómo crear un formulario con campos condicionales donde al seleccionar una ciudad se muestran las localidades correspondientes.

## Estructura del JSON

El archivo `ejemplo_formulario_ciudades_localidades.json` contiene un formulario completo que demuestra:

1. **Campo principal**: Select de ciudades
2. **Campos condicionales**: Selects de localidades que aparecen según la ciudad seleccionada
3. **Validaciones**: Campos requeridos y validaciones de formato
4. **Campos adicionales**: Información complementaria del usuario

## Cómo Funciona

### 1. Campo de Ciudad (Campo Principal)
```json
{
  "key": "ciudad",
  "label": "Ciudad",
  "type": "select",
  "required": true,
  "options": [
    {"value": "bogota", "label": "Bogotá D.C."},
    {"value": "medellin", "label": "Medellín"},
    {"value": "cali", "label": "Cali"},
    {"value": "barranquilla", "label": "Barranquilla"},
    {"value": "cartagena", "label": "Cartagena"},
    {"value": "bucaramanga", "label": "Bucaramanga"}
  ]
}
```

### 2. Campos Condicionales de Localidades

Cada ciudad tiene su propio campo de localidades que se muestra solo cuando esa ciudad está seleccionada:

#### Para Bogotá:
```json
{
  "key": "localidad_bogota",
  "label": "Localidad",
  "type": "select",
  "required": true,
  "options": [
    {"value": "usaquen", "label": "Usaquén"},
    {"value": "chapinero", "label": "Chapinero"},
    {"value": "santa_fe", "label": "Santa Fe"},
    // ... más localidades
  ],
  "visible": {
    "model": "ciudad",
    "value": "bogota",
    "condition": "equal"
  }
}
```

#### Para Medellín:
```json
{
  "key": "localidad_medellin",
  "label": "Comuna",
  "type": "select",
  "required": true,
  "options": [
    {"value": "popular", "label": "Popular"},
    {"value": "santa_cruz", "label": "Santa Cruz"},
    // ... más comunas
  ],
  "visible": {
    "model": "ciudad",
    "value": "medellin",
    "condition": "equal"
  }
}
```

## Propiedades de Visibilidad Condicional

### `visible` (objeto)
- **`model`**: Nombre del campo que controla la visibilidad (en este caso "ciudad")
- **`value`**: Valor que debe tener el campo de referencia para mostrar este campo
- **`condition`**: Tipo de comparación ("equal" para igualdad exacta)

## Comportamiento del Formulario

### Frontend
1. **Inicialmente**: Solo se muestra el campo de ciudad
2. **Al seleccionar ciudad**: Aparece el campo de localidades correspondiente
3. **Al cambiar ciudad**: Se oculta la localidad anterior y aparece la nueva
4. **Validación**: Solo se validan los campos visibles

### Backend
1. **Validación**: Solo valida campos que están visibles según la lógica condicional
2. **Almacenamiento**: Se guardan todos los valores, incluyendo los campos condicionales
3. **Integridad**: Se mantiene la consistencia de los datos

## Ciudades y Localidades Incluidas

### Bogotá D.C.
- 20 localidades (Usaquén, Chapinero, Santa Fe, etc.)

### Medellín
- 16 comunas (Popular, Santa Cruz, Manrique, etc.)

### Cali
- 22 comunas (Comuna 1 a Comuna 22)

### Barranquilla
- 5 localidades (Riomar, Norte Centro Histórico, etc.)

### Cartagena
- 3 localidades (Historia y Caribe Norte, etc.)

### Bucaramanga
- 17 comunas (Norte, Nororiente, Santander, etc.)

## Cómo Usar Este Ejemplo

### 1. Importar el JSON
```bash
# Copiar el contenido del archivo JSON a tu seeder o controlador
php artisan make:seeder CiudadesLocalidadesSeeder
```

### 2. Crear el Formulario
```php
// En tu seeder
$form = Form::create([
    'event_id' => $event->id,
    'name' => 'Formulario de Ubicación',
    'slug' => 'formulario-ubicacion-' . time(),
    'description' => 'Formulario con ciudades y localidades condicionales',
    'schema_json' => $jsonContent, // Contenido del archivo JSON
    'is_active' => true,
    'version' => 1
]);
```

### 3. Personalizar
- **Agregar más ciudades**: Añadir opciones al select de ciudad
- **Agregar más localidades**: Crear nuevos campos condicionales
- **Modificar validaciones**: Ajustar las reglas según necesidades
- **Cambiar etiquetas**: Personalizar los textos mostrados

## Casos de Uso Similares

Este patrón se puede aplicar a:

1. **Países y Estados/Provincias**
2. **Categorías y Subcategorías**
3. **Tipos de Vehículo y Modelos**
4. **Departamentos y Empleados**
5. **Productos y Variantes**

## Ventajas de este Enfoque

1. **UX Mejorada**: El usuario solo ve opciones relevantes
2. **Validación Inteligente**: Solo valida campos necesarios
3. **Datos Consistentes**: Evita combinaciones inválidas
4. **Escalabilidad**: Fácil agregar nuevas opciones
5. **Mantenibilidad**: Estructura clara y organizada

## Notas Técnicas

- Los campos condicionales se ocultan con `display: none` inicialmente
- JavaScript evalúa las condiciones en tiempo real
- El backend valida la lógica condicional antes de procesar
- Los campos ocultos se limpian automáticamente
- Se mantiene la integridad referencial de los datos
