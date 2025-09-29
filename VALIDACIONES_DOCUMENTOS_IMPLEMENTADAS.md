# Validaciones de Documentos Implementadas

## Resumen
Se han implementado validaciones específicas para los tipos de documentos de identificación colombianos, incluyendo limpieza automática de espacios y validaciones en tiempo real.

## Tipos de Documentos Soportados

### 1. Cédula de Ciudadanía (CC)
- **Formato**: Solo números
- **Longitud**: Entre 6 y 10 dígitos
- **Ejemplo válido**: 1032456789
- **Regex**: `/^[0-9]{6,10}$/`

### 2. Tarjeta de Identidad (TI)
- **Formato**: Solo números
- **Longitud**: Entre 6 y 11 dígitos
- **Ejemplo válido**: 1002345678
- **Regex**: `/^[0-9]{6,11}$/`

### 3. Cédula de Extranjería (CE)
- **Formato**: Solo números
- **Longitud**: Entre 6 y 15 dígitos
- **Ejemplo válido**: 987654321
- **Regex**: `/^[0-9]{6,15}$/`

### 4. NIT (Número de Identificación Tributaria)
- **Formato**: Números con o sin guion final
- **Longitud**: 9 a 15 dígitos
- **Dígito de verificación**: Opcional, separado por guión
- **Ejemplo válido**: 900123456-7
- **Regex**: `/^[0-9]{9,15}(-[0-9])?$/`

### 5. Pasaporte (PA)
- **Formato**: Alfanumérico (letras y números)
- **Longitud**: Entre 6 y 12 caracteres
- **Ejemplo válido**: AB1234567
- **Regex**: `/^[A-Z0-9]{6,12}$/`

### 6. Registro Civil (RC)
- **Formato**: Solo números
- **Longitud**: Entre 10 y 15 dígitos
- **Ejemplo válido**: 123456789012
- **Regex**: `/^[0-9]{10,15}$/`

### 7. PEP / PPT (Permiso Especial / Permiso por Protección Temporal)
- **Formato**: Alfanumérico (letras y números)
- **Longitud**: 6 a 15 caracteres
- **Ejemplo válido**: PPT1234567
- **Regex**: `/^[A-Z0-9]{6,15}$/`

### 8. Otros (NUIP, Carné Diplomático, etc.)
- **NUIP**: Numérico de hasta 15 dígitos
- **Carné Diplomático**: Alfanumérico, 6 a 12 caracteres
- **Formato general**: Alfanumérico
- **Longitud**: 6 a 15 caracteres
- **Ejemplo válido**: NUIP123456789
- **Regex**: `/^[A-Z0-9]{6,15}$/`

## Funcionalidades Implementadas

### 1. Validaciones del Servidor (Backend)
- **Archivo**: `app/Http/Requests/Form/SubmitFormSlugRequest.php`
- **Método**: `validateDocumentNumber()`
- **Funcionalidad**: Validación específica por tipo de documento
- **Mensajes personalizados**: Mensajes de error específicos para cada tipo

### 2. Limpieza Automática de Datos
- **Espacios**: Se eliminan automáticamente de todos los campos de texto
- **Documentos**: Se convierten a mayúsculas y se eliminan espacios
- **Método**: `prepareForValidation()` en el Request
- **Controlador**: Limpieza adicional en `FormSlugSubmitController`

### 3. Validaciones en Tiempo Real (Frontend)
- **Archivo**: `resources/views/public/forms/show.blade.php`
- **Funcionalidad**: Validación JavaScript en tiempo real
- **Mensajes de ayuda**: Dinámicos según el tipo de documento seleccionado
- **Limpieza automática**: Espacios eliminados mientras el usuario escribe

### 4. Interfaz de Usuario Mejorada
- **Opciones actualizadas**: Todos los tipos de documentos disponibles
- **Mensajes de ayuda**: Información contextual para cada tipo
- **Validación visual**: Indicadores de error en tiempo real
- **Experiencia de usuario**: Limpieza automática de espacios

## Archivos Modificados

1. **`app/Http/Requests/Form/SubmitFormSlugRequest.php`**
   - Validaciones específicas por tipo de documento
   - Método `validateDocumentNumber()`
   - Método `getDocumentValidationMessage()`
   - Método `prepareForValidation()`

2. **`app/Http/Controllers/Public/FormSlugSubmitController.php`**
   - Limpieza de datos en el procesamiento
   - Normalización de números de documento

3. **`resources/views/public/forms/show.blade.php`**
   - Opciones actualizadas del select de tipos de documento
   - JavaScript para validaciones en tiempo real
   - Mensajes de ayuda dinámicos
   - Limpieza automática de espacios

## Beneficios

1. **Consistencia de datos**: Los números de documento se almacenan sin espacios y en mayúsculas
2. **Validación robusta**: Cada tipo de documento tiene sus propias reglas de validación
3. **Experiencia de usuario**: Validaciones en tiempo real y mensajes de ayuda claros
4. **Mantenibilidad**: Código organizado y fácil de extender
5. **Seguridad**: Validación tanto en frontend como backend

## Uso

Los usuarios ahora pueden:
- Seleccionar el tipo de documento apropiado
- Ver ejemplos y formatos requeridos en tiempo real
- Recibir validación inmediata mientras escriben
- Tener sus datos limpiados automáticamente al enviar

El sistema valida tanto en el navegador (tiempo real) como en el servidor (seguridad) para garantizar la integridad de los datos.
