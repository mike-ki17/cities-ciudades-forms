# Campos JSON Mejorados - Guía Completa

## 🎯 Resumen

Se ha mejorado significativamente el módulo de campos para permitir la creación y gestión de campos con estructura JSON completa, incluyendo visibilidad condicional y opciones complejas, exactamente como funciona en la creación de formularios completos.

## ✨ Nuevas Características

### 1. **Creación de Campos con JSON Completo**
- Soporte completo para estructura JSON con todas las propiedades
- Validación robusta de la estructura del campo
- Ejemplos predefinidos y botones de carga rápida

### 2. **Visibilidad Condicional**
- Campos que aparecen/desaparecen basados en otros campos
- Soporte para múltiples condiciones (equal, not_equal, contains, etc.)
- Configuración visual e intuitiva

### 3. **Opciones Complejas para Select**
- Soporte para opciones con value, label y description
- Carga masiva desde archivos CSV
- Gestión visual de opciones existentes

### 4. **Ejemplos Predefinidos**
- Botones de carga rápida para ejemplos comunes
- Ejemplos específicos para ciudades y localidades
- Código JSON clickeable para copiar

## 🏗️ Estructura de Campos Soportada

### Campos Básicos
```json
{
  "key": "nombre",
  "label": "Nombre Completo",
  "type": "text",
  "required": true,
  "placeholder": "Ingresa tu nombre completo"
}
```

### Campos Select con Opciones
```json
{
  "key": "ciudad",
  "label": "Ciudad",
  "type": "select",
  "required": true,
  "description": "Seleccione su ciudad",
  "options": [
    {"value": "bogota", "label": "Bogotá D.C.", "description": null},
    {"value": "medellin", "label": "Medellín", "description": null}
  ]
}
```

### Campos con Visibilidad Condicional
```json
{
  "key": "localidad_bogota",
  "label": "Localidad",
  "type": "select",
  "required": true,
  "description": "Seleccione su localidad en Bogotá",
  "visible": {
    "model": "ciudad",
    "value": "bogota",
    "condition": "equal"
  },
  "options": [
    {"value": "usaquen", "label": "Usaquén", "description": null},
    {"value": "chapinero", "label": "Chapinero", "description": null}
  ]
}
```

## 🚀 Cómo Usar

### 1. **Acceder al Módulo de Campos**
- URL: `http://localhost:8000/admin/fields-json`
- Navegar a: Admin → Campos JSON

### 2. **Crear un Nuevo Campo**
1. Hacer clic en "Crear Nuevo Campo"
2. Usar los ejemplos predefinidos o escribir JSON personalizado
3. Hacer clic en "Crear Campo"

### 3. **Usar Ejemplos Rápidos**
- **Campo Ciudad**: Carga el campo principal de ciudades
- **Localidad Bogotá**: Campo condicional para localidades de Bogotá
- **Localidad Medellín**: Campo condicional para comunas de Medellín
- **Localidad Cali**: Campo condicional para comunas de Cali

### 4. **Cargar Opciones desde CSV**
1. Crear un campo de tipo "select"
2. En la edición, usar la función "Cargar Opciones desde CSV"
3. Formato CSV: `value,label,description`

## 📋 Ejemplos de Campos Creados

### Campo Principal: Ciudad
- **Key**: `ciudad`
- **Tipo**: Select
- **Opciones**: 6 ciudades principales de Colombia
- **Visibilidad**: Siempre visible

### Campos Condicionales: Localidades
- **localidad_bogota**: 20 localidades de Bogotá
- **localidad_medellin**: 16 comunas de Medellín
- **localidad_cali**: 22 comunas de Cali
- **localidad_barranquilla**: 5 localidades de Barranquilla
- **localidad_cartagena**: 3 localidades de Cartagena
- **localidad_bucaramanga**: 17 comunas de Bucaramanga

## 🔧 Propiedades Soportadas

### Propiedades Requeridas
- `key`: Identificador único del campo
- `label`: Etiqueta mostrada al usuario
- `type`: Tipo de campo (text, email, number, select, etc.)
- `required`: Si el campo es obligatorio

### Propiedades Opcionales
- `placeholder`: Texto de ayuda en el campo
- `description`: Descripción del campo
- `validations`: Reglas de validación
- `visible`: Configuración de visibilidad condicional
- `default_value`: Valor por defecto
- `options`: Opciones para campos select
- `order`: Orden de aparición

### Tipos de Campo Soportados
- `text`: Campo de texto
- `email`: Campo de correo electrónico
- `number`: Campo numérico
- `textarea`: Área de texto
- `select`: Lista desplegable
- `checkbox`: Casillas de verificación
- `date`: Campo de fecha

### Condiciones de Visibilidad
- `equal`: Igual a un valor
- `not_equal`: Diferente a un valor
- `contains`: Contiene un valor
- `not_contains`: No contiene un valor
- `greater_than`: Mayor que
- `less_than`: Menor que
- `greater_equal`: Mayor o igual que
- `less_equal`: Menor o igual que

## 🎨 Interfaz de Usuario

### Página de Creación
- **Editor JSON**: Área principal para escribir la estructura
- **Ejemplos**: Código JSON clickeable para copiar
- **Botones Rápidos**: Carga automática de ejemplos comunes
- **Ayuda**: Información detallada sobre propiedades

### Página de Edición
- **Editor JSON**: Pre-cargado con la estructura actual
- **Carga CSV**: Para campos select con muchas opciones
- **Información del Campo**: Detalles del campo actual
- **Opciones Actuales**: Lista visual de opciones existentes

## 🔄 Flujo de Trabajo Recomendado

### Para Campos Simples
1. Usar los ejemplos predefinidos
2. Modificar según necesidades
3. Crear el campo

### Para Campos Complejos
1. Crear el campo base
2. Usar carga CSV para opciones
3. Configurar visibilidad condicional
4. Probar en un formulario

### Para Campos Condicionales
1. Crear el campo principal (ej: ciudad)
2. Crear campos dependientes (ej: localidades)
3. Configurar visibilidad condicional
4. Probar la funcionalidad

## 🧪 Pruebas y Validación

### Validaciones Automáticas
- Estructura JSON válida
- Propiedades requeridas presentes
- Tipos de campo válidos
- Condiciones de visibilidad válidas
- Opciones para campos select

### Pruebas Recomendadas
1. Crear un campo de ciudad
2. Crear campos de localidades condicionales
3. Crear un formulario que use estos campos
4. Probar la visibilidad condicional
5. Verificar el guardado de datos

## 📊 Estadísticas de Campos Creados

- **Total de campos**: 7
- **Campo principal**: 1 (ciudad)
- **Campos condicionales**: 6 (localidades)
- **Total de opciones**: 89
- **Ciudades soportadas**: 6
- **Localidades por ciudad**: 3-22

## 🌐 URLs Importantes

- **Lista de campos**: `/admin/fields-json`
- **Crear campo**: `/admin/fields-json/create`
- **Editar campo**: `/admin/fields-json/{id}/edit`
- **Ver campo**: `/admin/fields-json/{id}`

## 🎉 Beneficios

### Para Desarrolladores
- Creación rápida de campos complejos
- Reutilización de campos en múltiples formularios
- Validación robusta y consistente
- Estructura JSON familiar

### Para Usuarios
- Interfaz intuitiva y visual
- Ejemplos predefinidos
- Carga masiva de opciones
- Gestión centralizada de campos

### Para el Sistema
- Consistencia en la estructura de datos
- Validaciones centralizadas
- Fácil mantenimiento
- Escalabilidad mejorada

## 🔮 Próximas Mejoras

- Editor visual de campos (drag & drop)
- Plantillas de campos predefinidas
- Importación/exportación de campos
- Validaciones en tiempo real
- Preview de campos en tiempo real

---

**¡El módulo de campos JSON está listo para usar!** 🚀

Puedes crear campos complejos con visibilidad condicional y opciones masivas de manera fácil y eficiente.
