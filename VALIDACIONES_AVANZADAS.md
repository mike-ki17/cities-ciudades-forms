# Validaciones Avanzadas para Formularios Dinámicos

Este documento describe todas las validaciones avanzadas disponibles para los formularios dinámicos del sistema.

## Estructura de Validaciones

Las validaciones se definen en el campo `validations` de cada campo del formulario:

```json
{
  "key": "nombre_campo",
  "type": "text",
  "label": "Etiqueta del Campo",
  "required": true,
  "validations": {
    // Validaciones específicas aquí
  }
}
```

## Validaciones por Tipo de Campo

### Campos de Texto (`text`, `textarea`, `email`)

#### Longitud
```json
{
  "validations": {
    "min_length": 2,        // Longitud mínima
    "max_length": 100       // Longitud máxima
  }
}
```

#### Patrones Regex
```json
{
  "validations": {
    "pattern": "^[a-zA-ZáéíóúÁÉÍÓÚñÑ\\s]+$"  // Solo letras y espacios
  }
}
```

#### Validaciones Predefinidas
```json
{
  "validations": {
    "letters_only": true,     // Solo letras (incluye acentos)
    "numbers_only": true,     // Solo números
    "alphanumeric": true      // Solo letras y números
  }
}
```

### Campos Numéricos (`number`)

#### Valores Mínimos y Máximos
```json
{
  "validations": {
    "min_value": 18,         // Valor mínimo
    "max_value": 65,         // Valor máximo
    "integer_only": true,    // Solo enteros
    "positive": true,        // Solo números positivos
    "negative": true         // Solo números negativos
  }
}
```

### Campos de Fecha (`date`)

#### Rangos de Fechas
```json
{
  "validations": {
    "min_date": "1990-01-01",           // Fecha mínima
    "max_date": "2009-12-31",           // Fecha máxima
    "date_range": {                     // Rango específico
      "start": "1990-11-11",
      "end": "2009-11-11"
    }
  }
}
```

#### Restricciones de Edad
```json
{
  "validations": {
    "min_age": 15,          // Edad mínima (años)
    "max_age": 34           // Edad máxima (años)
  }
}
```

### Campos de Selección Múltiple (`checkbox`)

#### Número de Selecciones
```json
{
  "validations": {
    "min_selections": 2,    // Mínimo de opciones a seleccionar
    "max_selections": 5     // Máximo de opciones a seleccionar
  }
}
```

## Ejemplo Completo

```json
{
  "fields": [
    {
      "key": "nombre_completo",
      "type": "text",
      "label": "Nombre Completo",
      "required": true,
      "placeholder": "Ingrese su nombre completo",
      "validations": {
        "min_length": 2,
        "max_length": 100,
        "letters_only": true
      }
    },
    {
      "key": "fecha_nacimiento",
      "type": "date",
      "label": "Fecha de Nacimiento",
      "required": true,
      "validations": {
        "date_range": {
          "start": "1990-11-11",
          "end": "2009-11-11"
        },
        "min_age": 15,
        "max_age": 34
      }
    },
    {
      "key": "edad",
      "type": "number",
      "label": "Edad",
      "required": true,
      "validations": {
        "min_value": 18,
        "max_value": 65,
        "integer_only": true,
        "positive": true
      }
    },
    {
      "key": "telefono",
      "type": "text",
      "label": "Teléfono",
      "required": false,
      "validations": {
        "pattern": "^[0-9+\\-\\s\\(\\)]+$",
        "min_length": 7,
        "max_length": 15
      }
    },
    {
      "key": "intereses",
      "type": "checkbox",
      "label": "Intereses (seleccione al menos 2)",
      "required": true,
      "options": [
        "Deportes",
        "Música",
        "Arte",
        "Tecnología",
        "Cocina",
        "Viajes",
        "Lectura",
        "Cine"
      ],
      "validations": {
        "min_selections": 2,
        "max_selections": 5
      }
    }
  ]
}
```

## Validaciones Personalizadas

### Reglas Personalizadas
```json
{
  "validations": {
    "custom_rule": "unique:users,email"  // Regla de Laravel personalizada
  }
}
```

### Validaciones de Archivos (Futuro)
```json
{
  "validations": {
    "file_size": 2048,              // Tamaño máximo en KB
    "file_types": ["jpg", "png", "pdf"]  // Tipos de archivo permitidos
  }
}
```

## Mensajes de Error

El sistema genera automáticamente mensajes de error descriptivos para cada validación:

- **Longitud**: "El campo debe tener al menos X caracteres"
- **Rango de fechas**: "La fecha debe estar entre X y Y"
- **Edad**: "La edad debe estar entre X y Y años"
- **Selecciones**: "Debe seleccionar al menos X opciones"
- **Patrones**: "El formato del campo no es válido"

## Compatibilidad

- ✅ **Laravel 12+**: Totalmente compatible
- ✅ **MySQL 8+**: Todas las validaciones funcionan
- ✅ **Formularios Existentes**: Compatible con formularios sin validaciones
- ✅ **Validaciones Opcionales**: Todas las validaciones son opcionales

## Notas Importantes

1. **Fechas**: Usar formato `YYYY-MM-DD` para todas las fechas
2. **Regex**: Escapar caracteres especiales con doble barra invertida
3. **Edades**: Se calculan automáticamente basándose en la fecha actual
4. **Compatibilidad**: Los formularios existentes sin validaciones siguen funcionando
5. **Rendimiento**: Las validaciones se ejecutan tanto en frontend como backend
