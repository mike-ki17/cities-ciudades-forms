# 🎯 Formulario de Prueba - Listo para Usar

## ✅ Estado del Formulario

- **ID**: 25
- **Nombre**: Formulario de Prueba - Campos Condicionales y Validaciones
- **Slug**: `formulario-de-prueba-campos-condicionales-y-validaciones`
- **Estado**: Activo
- **Total de Campos**: 21

## 🌐 Acceso al Formulario

### URL Principal:
```
http://localhost/form/formulario-de-prueba-campos-condicionales-y-validaciones
```

### URL Relativa:
```
/form/formulario-de-prueba-campos-condicionales-y-validaciones
```

## 🔧 Problema Resuelto

**Problema identificado**: La vista del formulario estaba intentando usar `getRelationalFields()` que busca campos en la estructura relacional, pero nuestro formulario de prueba se creó con la estructura JSON tradicional.

**Solución implementada**: Modifiqué la vista `resources/views/public/forms/show.blade.php` para que:
1. Primero intente obtener campos relacionales
2. Si no hay campos relacionales, use los campos JSON del `schema_json`
3. Maneje ambos tipos de formularios correctamente

## 📋 Campos del Formulario

### Campos Básicos (Siempre visibles):
1. **Tipo de Registro** [select] - Básico, Premium, Empresarial
2. **Nombre Completo** [text] - 2-50 caracteres, solo letras
3. **Correo Electrónico** [email] - Formato válido, único
4. **Teléfono** [text] - 9-15 dígitos, formato internacional
5. **Edad** [number] - 18-100 años
6. **Fecha de Nacimiento** [date] - 18-65 años
7. **¿Tienes una empresa?** [select] - Sí/No

### Campos Condicionales (10 campos):

#### Aparecen si "¿Tienes empresa?" = "Sí":
- **Nombre de la Empresa** [text] - 3-100 caracteres
- **CIF de la Empresa** [text] - Formato: 1 letra + 8 dígitos
- **Descripción de la Empresa** [textarea] - 10-200 palabras, 50-1000 caracteres

#### Aparecen si "Tipo de Registro" = "Premium":
- **Teléfono de Emergencia** [text] - 9-15 dígitos
- **Dirección Completa** [textarea] - 5-50 palabras, 20-200 caracteres
- **Código Postal** [text] - Exactamente 5 dígitos
- **Presupuesto Disponible** [number] - €100-€100,000, 2 decimales

#### Aparecen si "Tipo de Registro" = "Empresarial":
- **Dirección Completa** [textarea] - 5-50 palabras, 20-200 caracteres
- **Código Postal** [text] - Exactamente 5 dígitos
- **Número de Empleados** [number] - 1-10,000 empleados
- **Sector Empresarial** [select] - 8 opciones disponibles
- **Sitio Web de la Empresa** [text] - URL válida, 10-100 caracteres
- **Presupuesto Disponible** [number] - €100-€100,000, 2 decimales

### Campos Adicionales:
- **Áreas de Interés** [select múltiple] - 1-5 selecciones de 8 opciones
- **Comentarios Adicionales** [textarea] - 5-100 palabras, 25-500 caracteres
- **Acepto los términos y condiciones** [checkbox] - Requerido
- **Acepto recibir información comercial** [checkbox] - Opcional

## 🧪 Casos de Prueba Recomendados

### 1. Campos Condicionales:
- Cambia el "Tipo de Registro" y observa cómo aparecen/desaparecen campos
- Selecciona "¿Tienes empresa?" = "Sí" y verifica que aparezcan los campos de empresa

### 2. Validaciones de Longitud:
- **Nombre**: Prueba con menos de 2 caracteres o más de 50
- **Teléfono**: Prueba con menos de 9 dígitos o más de 15
- **CIF**: Prueba con formato incorrecto (ej: 123456789 en lugar de A12345678)
- **Código Postal**: Prueba con menos o más de 5 dígitos

### 3. Validaciones de Formato:
- **Email**: Prueba con formato inválido (ej: "test@" o "test.com")
- **Sitio Web**: Prueba con URL inválida (ej: "no-es-url")
- **Presupuesto**: Prueba con más de 2 decimales

### 4. Validaciones de Rango:
- **Edad**: Prueba con valores menores a 18 o mayores a 100
- **Presupuesto**: Prueba con valores menores a €100 o mayores a €100,000
- **Número de Empleados**: Prueba con valores menores a 1 o mayores a 10,000

### 5. Validaciones de Palabras:
- **Descripción de Empresa**: Prueba con menos de 10 palabras o más de 200
- **Comentarios**: Prueba con menos de 5 palabras o más de 100

### 6. Validaciones de Selección:
- **Áreas de Interés**: Prueba sin seleccionar nada o seleccionando más de 5

## 🎉 ¡Listo para Probar!

El formulario está completamente funcional y listo para ser probado. Incluye:

- ✅ **21 campos** con diferentes tipos y validaciones
- ✅ **10 campos condicionales** que aparecen según las selecciones
- ✅ **16 campos con validaciones** de longitud, formato, rango, etc.
- ✅ **JavaScript funcional** para campos condicionales y contadores de caracteres
- ✅ **Validaciones del lado del servidor** para todos los campos
- ✅ **Mensajes de error claros** y específicos

**¡Accede al formulario y disfruta probando todas las funcionalidades!** 🚀
