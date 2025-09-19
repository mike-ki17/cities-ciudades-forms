# Campos Condicionales en Formularios

Esta funcionalidad permite mostrar u ocultar campos del formulario basándose en las respuestas de otros campos.

## Estructura de Campos Condicionales

Para hacer un campo condicional, agregue la propiedad `visible` al campo en el JSON del formulario:

```json
{
  "key": "nombre_discapacitado",
  "label": "Nombres completos de la persona que cuida:",
  "type": "text",
  "required": true,
  "placeholder": "Nombres completos",
  "visible": {
    "model": "es_tutor",
    "value": "Sí",
    "condition": "equal"
  }
}
```

## Propiedades de la Visibilidad Condicional

### `model` (requerido)
El nombre del campo que controla la visibilidad del campo actual.

### `value` (requerido)
El valor que debe tener el campo de referencia para mostrar el campo actual.

### `condition` (requerido)
El tipo de comparación a realizar. Valores disponibles:

- `equal`: El valor debe ser igual
- `not_equal`: El valor debe ser diferente
- `contains`: El valor debe contener el texto especificado
- `not_contains`: El valor no debe contener el texto especificado
- `greater_than`: El valor debe ser mayor (para números)
- `less_than`: El valor debe ser menor (para números)
- `greater_equal`: El valor debe ser mayor o igual (para números)
- `less_equal`: El valor debe ser menor o igual (para números)

## Ejemplo Completo

```json
{
  "fields": [
    {
      "key": "es_tutor",
      "label": "¿Es usted un cuidador de una persona con discapacidad?",
      "type": "select",
      "required": true,
      "options": [
        {"value": "Sí", "label": "Sí"},
        {"value": "No", "label": "No"}
      ]
    },
    {
      "key": "nombre_discapacitado",
      "label": "Nombres completos de la persona que cuida:",
      "type": "text",
      "required": true,
      "placeholder": "Nombres completos",
      "visible": {
        "model": "es_tutor",
        "value": "Sí",
        "condition": "equal"
      }
    },
    {
      "key": "edad_discapacitado",
      "label": "Edad de la persona que cuida:",
      "type": "number",
      "required": true,
      "validations": {
        "min_value": 0,
        "max_value": 120
      },
      "visible": {
        "model": "es_tutor",
        "value": "Sí",
        "condition": "equal"
      }
    }
  ]
}
```

## Comportamiento

### Frontend
- Los campos condicionales se ocultan inicialmente si no se cumple la condición
- Se muestran/ocultan dinámicamente cuando cambia el valor del campo de referencia
- Los campos ocultos se limpian automáticamente
- Los campos requeridos en campos ocultos no se validan

### Backend
- La validación considera si un campo es visible antes de aplicarle las reglas de validación
- Los campos ocultos se limpian automáticamente en el envío
- Se mantiene la integridad de los datos

## Casos de Uso Comunes

1. **Formularios de Cuestionarios**: Mostrar preguntas adicionales basadas en respuestas anteriores
2. **Formularios de Registro**: Mostrar campos específicos según el tipo de usuario
3. **Formularios de Encuestas**: Mostrar secciones completas basadas en respuestas
4. **Formularios de Solicitudes**: Mostrar campos adicionales según el tipo de solicitud

## Notas Importantes

- Los campos condicionales pueden depender de otros campos condicionales
- La lógica se evalúa en tiempo real en el frontend
- El backend valida la lógica condicional antes de procesar el formulario
- Los campos ocultos no se envían al servidor
