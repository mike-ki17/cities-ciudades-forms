# Validaciones Generales para Formularios

Este documento describe todas las validaciones disponibles para los campos de formulario en el sistema.

## Tipos de Validaciones Disponibles

### 1. Validaciones de Texto

#### Longitud de Caracteres
```json
{
  "key": "nombre",
  "label": "Nombre Completo",
  "type": "text",
  "required": true,
  "validations": {
    "min_length": 2,
    "max_length": 100
  }
}
```

#### Conteo de Palabras
```json
{
  "key": "descripcion",
  "label": "Descripción",
  "type": "textarea",
  "validations": {
    "min_words": 5,
    "max_words": 200
  }
}
```

#### Caracteres Permitidos/Prohibidos
```json
{
  "key": "codigo",
  "label": "Código",
  "type": "text",
  "validations": {
    "allowed_chars": "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
    "forbidden_chars": "!@#$%^&*()"
  }
}
```

#### Patrones de Expresión Regular
```json
{
  "key": "codigo_postal",
  "label": "Código Postal",
  "type": "text",
  "validations": {
    "pattern": "^[0-9]{5}$"
  }
}
```

### 2. Validaciones de Email

```json
{
  "key": "email",
  "label": "Correo Electrónico",
  "type": "email",
  "required": true,
  "validations": {
    "format": "email",
    "unique": true
  }
}
```

### 3. Validaciones Numéricas

#### Valores Mínimos y Máximos
```json
{
  "key": "edad",
  "label": "Edad",
  "type": "number",
  "validations": {
    "min_value": 18,
    "max_value": 100,
    "step": 1
  }
}
```

#### Decimales
```json
{
  "key": "precio",
  "label": "Precio",
  "type": "number",
  "validations": {
    "decimal_places": 2,
    "min_value": 0.01,
    "max_value": 9999.99
  }
}
```

### 4. Validaciones de Fecha

#### Rango de Fechas
```json
{
  "key": "fecha_nacimiento",
  "label": "Fecha de Nacimiento",
  "type": "date",
  "validations": {
    "min_date": "1900-01-01",
    "max_date": "2024-12-31"
  }
}
```

#### Rango de Fechas Específico
```json
{
  "key": "fecha_evento",
  "label": "Fecha del Evento",
  "type": "date",
  "validations": {
    "date_range": {
      "start": "2024-01-01",
      "end": "2024-12-31"
    }
  }
}
```

#### Validación por Edad
```json
{
  "key": "fecha_nacimiento",
  "label": "Fecha de Nacimiento",
  "type": "date",
  "validations": {
    "min_age": 18,
    "max_age": 65
  }
}
```

### 5. Validaciones de Selección

#### Selecciones Múltiples
```json
{
  "key": "intereses",
  "label": "Intereses",
  "type": "select",
  "validations": {
    "min_selections": 1,
    "max_selections": 3
  },
  "options": [
    {"value": "deportes", "label": "Deportes"},
    {"value": "musica", "label": "Música"},
    {"value": "arte", "label": "Arte"}
  ]
}
```

#### Checkbox Obligatorio
```json
{
  "key": "acepta_terminos",
  "label": "Acepto los términos y condiciones",
  "type": "checkbox",
  "required": true
}
```

### 6. Validaciones de Formato Específico

#### DNI
```json
{
  "key": "dni",
  "label": "DNI",
  "type": "text",
  "validations": {
    "format": "dni",
    "pattern": "^[0-9]{8}[A-Z]$"
  }
}
```

#### Teléfono
```json
{
  "key": "telefono",
  "label": "Teléfono",
  "type": "text",
  "validations": {
    "format": "phone",
    "pattern": "^[+]?[0-9]{9,15}$"
  }
}
```

#### URL
```json
{
  "key": "sitio_web",
  "label": "Sitio Web",
  "type": "text",
  "validations": {
    "format": "url"
  }
}
```

#### Código Postal
```json
{
  "key": "codigo_postal",
  "label": "Código Postal",
  "type": "text",
  "validations": {
    "format": "postal_code",
    "pattern": "^[0-9]{5}$"
  }
}
```

#### Moneda
```json
{
  "key": "precio",
  "label": "Precio",
  "type": "number",
  "validations": {
    "format": "currency",
    "decimal_places": 2,
    "min_value": 0
  }
}
```

#### Porcentaje
```json
{
  "key": "descuento",
  "label": "Descuento (%)",
  "type": "number",
  "validations": {
    "format": "percentage",
    "min_value": 0,
    "max_value": 100
  }
}
```

### 7. Validaciones de Archivos

```json
{
  "key": "documento",
  "label": "Documento",
  "type": "file",
  "validations": {
    "file_types": ["pdf", "doc", "docx"],
    "max_file_size": 5242880
  }
}
```

### 8. Validaciones Condicionales

#### Campo Requerido Condicionalmente
```json
{
  "key": "telefono_emergencia",
  "label": "Teléfono de Emergencia",
  "type": "text",
  "validations": {
    "required_if": {
      "field": "tiene_emergencia",
      "value": "si"
    }
  }
}
```

### 9. Validaciones de Visibilidad Condicional

```json
{
  "key": "detalles_adicionales",
  "label": "Detalles Adicionales",
  "type": "textarea",
  "visible": {
    "model": "tipo_registro",
    "condition": "equal",
    "value": "premium"
  }
}
```

## Ejemplo Completo de Formulario

```json
{
  "fields": [
    {
      "key": "nombre",
      "label": "Nombre Completo",
      "type": "text",
      "required": true,
      "placeholder": "Ingrese su nombre completo",
      "validations": {
        "min_length": 2,
        "max_length": 100,
        "allowed_chars": "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz "
      }
    },
    {
      "key": "email",
      "label": "Correo Electrónico",
      "type": "email",
      "required": true,
      "placeholder": "ejemplo@correo.com",
      "validations": {
        "format": "email",
        "unique": true
      }
    },
    {
      "key": "dni",
      "label": "DNI",
      "type": "text",
      "required": true,
      "placeholder": "12345678A",
      "validations": {
        "format": "dni",
        "pattern": "^[0-9]{8}[A-Z]$"
      }
    },
    {
      "key": "telefono",
      "label": "Teléfono",
      "type": "text",
      "required": false,
      "placeholder": "+34 123 456 789",
      "validations": {
        "format": "phone",
        "pattern": "^[+]?[0-9]{9,15}$"
      }
    },
    {
      "key": "fecha_nacimiento",
      "label": "Fecha de Nacimiento",
      "type": "date",
      "required": true,
      "validations": {
        "min_age": 18,
        "max_age": 65
      }
    },
    {
      "key": "genero",
      "label": "Género",
      "type": "select",
      "required": false,
      "options": [
        {"value": "masculino", "label": "Masculino"},
        {"value": "femenino", "label": "Femenino"},
        {"value": "otro", "label": "Otro"}
      ]
    },
    {
      "key": "intereses",
      "label": "Intereses",
      "type": "select",
      "required": false,
      "validations": {
        "min_selections": 1,
        "max_selections": 3
      },
      "options": [
        {"value": "deportes", "label": "Deportes"},
        {"value": "musica", "label": "Música"},
        {"value": "arte", "label": "Arte"},
        {"value": "tecnologia", "label": "Tecnología"}
      ]
    },
    {
      "key": "comentarios",
      "label": "Comentarios Adicionales",
      "type": "textarea",
      "required": false,
      "placeholder": "Escribe aquí cualquier comentario adicional...",
      "validations": {
        "max_words": 200
      }
    },
    {
      "key": "acepta_terminos",
      "label": "Acepto los términos y condiciones",
      "type": "checkbox",
      "required": true
    }
  ]
}
```

## Tipos de Campos Soportados

- `text`: Campo de texto simple
- `email`: Campo de correo electrónico
- `number`: Campo numérico
- `textarea`: Área de texto multilínea
- `select`: Lista desplegable
- `checkbox`: Casilla de verificación
- `date`: Campo de fecha
- `file`: Campo de archivo (próximamente)

## Formatos Predefinidos

- `dni`: Documento Nacional de Identidad
- `phone`: Número de teléfono
- `email`: Correo electrónico
- `url`: URL web
- `postal_code`: Código postal
- `currency`: Moneda
- `percentage`: Porcentaje

## Condiciones de Visibilidad

- `equal`: Igual a
- `not_equal`: Diferente de
- `contains`: Contiene
- `not_contains`: No contiene
- `greater_than`: Mayor que
- `less_than`: Menor que
- `greater_equal`: Mayor o igual que
- `less_equal`: Menor o igual que
