# Ejemplo: Campos con Visibilidad Condicional (Ciudad → Localidad → Barrio)

## Estructura de Campos Requerida

Para crear un sistema de campos condicionales que funcione correctamente, necesitas crear **3 campos separados** en este orden:

### 1. Campo Ciudad (Base)
```json
{
  "key": "ciudad",
  "label": "Ciudad",
  "type": "select",
  "required": true,
  "order": 1,
  "description": "Seleccione su ciudad",
  "options": [
    {"value": "bogota", "label": "Bogotá D.C.", "description": null},
    {"value": "medellin", "label": "Medellín", "description": null},
    {"value": "cali", "label": "Cali", "description": null},
    {"value": "barranquilla", "label": "Barranquilla", "description": null}
  ]
}
```

### 2. Campo Localidad (Condicional a Ciudad)
```json
{
  "key": "localidad_bogota",
  "label": "Localidad",
  "type": "select",
  "required": true,
  "order": 2,
  "description": "Seleccione su localidad en Bogotá",
  "visible": {
    "model": "ciudad",
    "value": "bogota",
    "condition": "equal"
  },
  "options": [
    {"value": "usaquen", "label": "Usaquén", "description": null},
    {"value": "chapinero", "label": "Chapinero", "description": null},
    {"value": "santa_fe", "label": "Santa Fe", "description": null},
    {"value": "san_cristobal", "label": "San Cristóbal", "description": null},
    {"value": "usme", "label": "Usme", "description": null},
    {"value": "tunjuelito", "label": "Tunjuelito", "description": null},
    {"value": "bosa", "label": "Bosa", "description": null},
    {"value": "kennedy", "label": "Kennedy", "description": null},
    {"value": "fontibon", "label": "Fontibón", "description": null},
    {"value": "engativa", "label": "Engativá", "description": null},
    {"value": "suba", "label": "Suba", "description": null},
    {"value": "barrios_unidos", "label": "Barrios Unidos", "description": null},
    {"value": "teusaquillo", "label": "Teusaquillo", "description": null},
    {"value": "martires", "label": "Los Mártires", "description": null},
    {"value": "antonio_narino", "label": "Antonio Nariño", "description": null},
    {"value": "puente_aranda", "label": "Puente Aranda", "description": null},
    {"value": "candelaria", "label": "La Candelaria", "description": null},
    {"value": "rafael_uribe", "label": "Rafael Uribe Uribe", "description": null},
    {"value": "ciudad_bolivar", "label": "Ciudad Bolívar", "description": null},
    {"value": "sumapaz", "label": "Sumapaz", "description": null}
  ]
}
```

### 3. Campo Barrios (Condicional a Localidad)
```json
{
  "key": "barrios_bogota_barrios_unidos",
  "label": "Barrios",
  "type": "select",
  "required": true,
  "order": 3,
  "description": "Seleccione su barrio en Barrios Unidos",
  "visible": {
    "model": "localidad_bogota",
    "value": "barrios_unidos",
    "condition": "equal"
  }
}
```

## Pasos para Crear los Campos

### Paso 1: Crear Campo Ciudad
1. Ve a "Crear Campo JSON"
2. Usa el ejemplo de "Campo Ciudad" o escribe el JSON manualmente
3. **NO subas archivo CSV** para este campo
4. Guarda el campo

### Paso 2: Crear Campo Localidad
1. Ve a "Crear Campo JSON"
2. Usa el ejemplo de "Localidad Bogotá" o escribe el JSON manualmente
3. **NO subas archivo CSV** para este campo
4. Guarda el campo

### Paso 3: Crear Campo Barrios
1. Ve a "Crear Campo JSON"
2. Escribe solo la estructura básica (sin opciones):
   ```json
   {
     "key": "barrios_bogota_barrios_unidos",
     "label": "Barrios",
     "type": "select",
     "required": true,
     "order": 3,
     "description": "Seleccione su barrio en Barrios Unidos",
     "visible": {
       "model": "localidad_bogota",
       "value": "barrios_unidos",
       "condition": "equal"
     }
   }
   ```
3. **SÍ sube el archivo CSV** `barrios_bogota_barrios_unidos.csv`
4. Guarda el campo

## Archivos CSV Necesarios

### Para Barrios Unidos:
- Archivo: `barrios_bogota_barrios_unidos.csv` ✅ (ya creado)

### Para otras localidades (crear según necesidad):
- `barrios_bogota_suba.csv`
- `barrios_bogota_usaquen.csv`
- `barrios_bogota_chapinero.csv`
- etc.

## Lógica de Visibilidad

1. **Usuario selecciona ciudad** → Se muestra campo localidad
2. **Usuario selecciona localidad** → Se muestra campo barrios correspondiente
3. **Cada campo depende del anterior** usando la propiedad `visible`

## Verificación

Después de crear los 3 campos:
1. Ve a crear un formulario
2. Agrega los 3 campos en orden
3. Prueba la visibilidad condicional:
   - Selecciona "Bogotá" → Debe aparecer "Localidad"
   - Selecciona "Barrios Unidos" → Debe aparecer "Barrios"
   - Los barrios deben cargar desde el CSV

## Notas Importantes

- ✅ **Sin duplicación**: CSV no se agrega al JSON
- ✅ **Visibilidad condicional**: Funciona correctamente
- ✅ **Orden correcto**: Ciudad (1) → Localidad (2) → Barrios (3)
- ✅ **Modelos correctos**: `ciudad` → `localidad_bogota` → `barrios_bogota_barrios_unidos`


