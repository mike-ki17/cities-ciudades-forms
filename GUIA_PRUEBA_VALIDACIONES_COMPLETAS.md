# 🧪 Guía de Prueba - Formulario Completo de Validaciones

## 📋 **Información del Formulario**

- **Nombre**: Formulario Completo - Todas las Validaciones
- **Slug**: `formulario-validaciones-completas-1758661987`
- **Total de campos**: 28
- **URL de acceso**: http://localhost:8000/form/formulario-validaciones-completas-1758661987
- **URL de administración**: http://localhost:8000/admin/forms/31

## 🎯 **Objetivo**

Este formulario está diseñado para probar **todas las validaciones** implementadas en el sistema. Incluye 28 campos diferentes con más de 50 validaciones distintas.

## 📊 **Campos y Validaciones Incluidas**

### 1. **Validaciones de Texto**

#### Nombre Completo
- **Tipo**: `text`
- **Validaciones**: `min_length: 2`, `max_length: 100`
- **Pruebas**:
  - ✅ Válido: "Juan Pérez"
  - ❌ Inválido: "A" (muy corto)
  - ❌ Inválido: Texto de más de 100 caracteres

#### Descripción Personal
- **Tipo**: `textarea`
- **Validaciones**: `min_words: 5`, `max_words: 200`
- **Pruebas**:
  - ✅ Válido: "Soy una persona interesada en la tecnología y el desarrollo de software."
  - ❌ Inválido: "Hola" (menos de 5 palabras)
  - ❌ Inválido: Texto de más de 200 palabras

#### Código de Usuario
- **Tipo**: `text`
- **Validaciones**: `allowed_chars: "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"`, `forbidden_chars: "!@#$%^&*()"`
- **Pruebas**:
  - ✅ Válido: "ABC123", "USER456"
  - ❌ Inválido: "abc123" (minúsculas no permitidas)
  - ❌ Inválido: "ABC@123" (símbolos prohibidos)

#### Teléfono
- **Tipo**: `text`
- **Validaciones**: `pattern: "^\\+?[1-9]\\d{1,14}$"`
- **Pruebas**:
  - ✅ Válido: "+573001234567", "3001234567"
  - ❌ Inválido: "123" (muy corto)
  - ❌ Inválido: "0123456789" (empieza con 0)

### 2. **Validaciones de Email**

#### Email Principal
- **Tipo**: `email`
- **Validaciones**: `format: "email"`
- **Pruebas**:
  - ✅ Válido: "usuario@ejemplo.com"
  - ❌ Inválido: "usuario@", "usuario.ejemplo.com"

#### Email Secundario
- **Tipo**: `email`
- **Validaciones**: `format: "email"`
- **Pruebas**: Similar al anterior (campo opcional)

### 3. **Validaciones Numéricas**

#### Edad
- **Tipo**: `number`
- **Validaciones**: `min: 18`, `max: 100`
- **Pruebas**:
  - ✅ Válido: 25, 50, 99
  - ❌ Inválido: 17 (menor de edad)
  - ❌ Inválido: 101 (muy mayor)

#### Salario Mensual
- **Tipo**: `number`
- **Validaciones**: `min: 0`, `max: 50000000`, `decimal_places: 0`
- **Pruebas**:
  - ✅ Válido: 2500000, 0
  - ❌ Inválido: -1000 (negativo)
  - ❌ Inválido: 50000001 (muy alto)
  - ❌ Inválido: 2500000.5 (decimales no permitidos)

#### Porcentaje de Descuento
- **Tipo**: `number`
- **Validaciones**: `min: 0`, `max: 100`, `decimal_places: 2`, `step: 0.1`
- **Pruebas**:
  - ✅ Válido: 15.5, 0, 100
  - ❌ Inválido: -1 (negativo)
  - ❌ Inválido: 101 (muy alto)
  - ❌ Inválido: 15.555 (más de 2 decimales)

### 4. **Validaciones de Fecha**

#### Fecha de Nacimiento
- **Tipo**: `date`
- **Validaciones**: `min_date: "1900-01-01"`, `max_date: "2010-12-31"`
- **Pruebas**:
  - ✅ Válido: "1990-05-15", "2000-12-31"
  - ❌ Inválido: "1899-12-31" (muy antigua)
  - ❌ Inválido: "2011-01-01" (muy reciente)

#### Fecha de Ingreso
- **Tipo**: `date`
- **Validaciones**: `min_date: "2020-01-01"`, `max_date: "2025-12-31"`
- **Pruebas**:
  - ✅ Válido: "2023-06-15", "2025-12-31"
  - ❌ Inválido: "2019-12-31" (muy antigua)
  - ❌ Inválido: "2026-01-01" (muy futura)

### 5. **Validaciones de Archivo**

#### Foto de Perfil
- **Tipo**: `file`
- **Validaciones**: `file_types: ["image/jpeg", "image/png", "image/gif"]`, `max_file_size: 5242880` (5MB)
- **Pruebas**:
  - ✅ Válido: Archivo JPG, PNG o GIF menor a 5MB
  - ❌ Inválido: Archivo PDF
  - ❌ Inválido: Imagen mayor a 5MB

#### Documento PDF
- **Tipo**: `file`
- **Validaciones**: `file_types: ["application/pdf"]`, `max_file_size: 10485760` (10MB)
- **Pruebas**:
  - ✅ Válido: Archivo PDF menor a 10MB
  - ❌ Inválido: Archivo JPG
  - ❌ Inválido: PDF mayor a 10MB

### 6. **Validaciones Condicionales**

#### ¿Tiene Empresa?
- **Tipo**: `radio`
- **Opciones**: "Sí", "No"
- **Pruebas**: Seleccionar "Sí" para mostrar campos condicionales

#### Nombre de la Empresa
- **Tipo**: `text`
- **Validaciones**: `min_length: 2`, `max_length: 100`
- **Visible**: Solo si "¿Tiene Empresa?" = "Sí"
- **Pruebas**:
  - ✅ Válido: "Mi Empresa S.A.S."
  - ❌ Inválido: "A" (muy corto)

#### NIT de la Empresa
- **Tipo**: `text`
- **Validaciones**: `pattern: "^[0-9]{8,9}-[0-9]$"`
- **Visible**: Solo si "¿Tiene Empresa?" = "Sí"
- **Pruebas**:
  - ✅ Válido: "12345678-9", "123456789-1"
  - ❌ Inválido: "1234567-9" (muy corto)
  - ❌ Inválido: "123456789" (sin guión)

### 7. **Validaciones de Selección**

#### País
- **Tipo**: `select`
- **Opciones**: Colombia, México, Argentina, Chile, Perú
- **Pruebas**: Seleccionar cualquier opción

#### Intereses
- **Tipo**: `checkbox`
- **Opciones**: Tecnología, Deportes, Música, Viajes, Lectura
- **Pruebas**: Seleccionar múltiples opciones

### 8. **Validaciones de URL**

#### Sitio Web
- **Tipo**: `url`
- **Validaciones**: `format: "url"`
- **Pruebas**:
  - ✅ Válido: "https://www.ejemplo.com"
  - ❌ Inválido: "www.ejemplo.com" (sin protocolo)

#### Perfil de LinkedIn
- **Tipo**: `url`
- **Validaciones**: `format: "url"`, `pattern: "^https://linkedin\\.com/in/"`
- **Pruebas**:
  - ✅ Válido: "https://linkedin.com/in/usuario"
  - ❌ Inválido: "https://facebook.com/usuario"

### 9. **Validaciones de Contraseña**

#### Contraseña
- **Tipo**: `password`
- **Validaciones**: `min_length: 8`, `max_length: 50`, `pattern: "^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&])[A-Za-z\\d@$!%*?&]"`
- **Pruebas**:
  - ✅ Válido: "MiPass123!"
  - ❌ Inválido: "password" (sin mayúscula, número y símbolo)
  - ❌ Inválido: "Pass1!" (muy corto)

#### Confirmar Contraseña
- **Tipo**: `password`
- **Validaciones**: `matches: "contrasena"`
- **Pruebas**:
  - ✅ Válido: Mismo valor que "Contraseña"
  - ❌ Inválido: Valor diferente

### 10. **Validaciones de Teléfono**

#### Teléfono de Emergencia
- **Tipo**: `tel`
- **Validaciones**: `pattern: "^\\+?[1-9]\\d{1,14}$"`
- **Pruebas**: Similar al teléfono principal

### 11. **Validaciones de Rango**

#### Nivel de Satisfacción
- **Tipo**: `range`
- **Validaciones**: `min: 1`, `max: 10`, `step: 1`
- **Pruebas**: Deslizar entre 1 y 10

### 12. **Validaciones de Color**

#### Color Favorito
- **Tipo**: `color`
- **Validaciones**: `format: "hex"`
- **Pruebas**: Seleccionar cualquier color

### 13. **Validaciones de Texto con Formato**

#### Código Postal
- **Tipo**: `text`
- **Validaciones**: `pattern: "^[0-9]{6}$"`, `min_length: 6`, `max_length: 6`
- **Pruebas**:
  - ✅ Válido: "110111"
  - ❌ Inválido: "11011" (muy corto)
  - ❌ Inválido: "1101111" (muy largo)
  - ❌ Inválido: "11011A" (contiene letras)

### 14. **Validaciones de Aceptación**

#### Acepto los Términos y Condiciones
- **Tipo**: `checkbox`
- **Validaciones**: `required: true`
- **Pruebas**:
  - ✅ Válido: Marcado
  - ❌ Inválido: Sin marcar

#### Acepto recibir información de marketing
- **Tipo**: `checkbox`
- **Pruebas**: Opcional

## 🧪 **Plan de Pruebas Recomendado**

### **Fase 1: Pruebas Básicas**
1. Llenar todos los campos con datos válidos
2. Enviar el formulario
3. Verificar que se guarda correctamente

### **Fase 2: Pruebas de Validación**
1. Probar cada campo con datos inválidos
2. Verificar que aparecen los mensajes de error
3. Corregir los datos y verificar que se aceptan

### **Fase 3: Pruebas Condicionales**
1. Seleccionar "No" en "¿Tiene Empresa?"
2. Verificar que los campos de empresa se ocultan
3. Seleccionar "Sí" y verificar que aparecen
4. Llenar los campos condicionales

### **Fase 4: Pruebas de Archivos**
1. Subir archivos válidos
2. Intentar subir archivos inválidos
3. Verificar los mensajes de error

## 📝 **Datos de Prueba Válidos**

```json
{
  "nombre_completo": "Juan Carlos Pérez García",
  "descripcion_personal": "Soy un desarrollador de software con experiencia en Laravel y PHP. Me gusta trabajar en proyectos innovadores y aprender nuevas tecnologías.",
  "codigo_usuario": "JCPG2025",
  "telefono": "+573001234567",
  "email_principal": "juan.perez@ejemplo.com",
  "email_secundario": "juan.backup@ejemplo.com",
  "edad": 28,
  "salario": 3500000,
  "porcentaje_descuento": 15.5,
  "fecha_nacimiento": "1995-06-15",
  "fecha_ingreso": "2023-01-15",
  "tiene_empresa": "si",
  "nombre_empresa": "Tech Solutions S.A.S.",
  "nit_empresa": "900123456-1",
  "pais": "colombia",
  "intereses": ["tecnologia", "lectura"],
  "sitio_web": "https://www.juanperez.com",
  "linkedin": "https://linkedin.com/in/juanperez",
  "contrasena": "MiPass123!",
  "confirmar_contrasena": "MiPass123!",
  "telefono_emergencia": "+573009876543",
  "nivel_satisfaccion": 8,
  "color_favorito": "#3498db",
  "codigo_postal": "110111",
  "acepta_terminos": true,
  "acepta_marketing": false
}
```

## 🎯 **Resultados Esperados**

- ✅ **Formulario se carga correctamente**
- ✅ **Todos los campos se muestran**
- ✅ **Validaciones funcionan en tiempo real**
- ✅ **Campos condicionales se muestran/ocultan**
- ✅ **Archivos se suben correctamente**
- ✅ **Datos se guardan en la base de datos**
- ✅ **Mensajes de error son claros y útiles**

## 🚀 **¡Listo para Probar!**

Accede al formulario en: **http://localhost:8000/form/formulario-validaciones-completas-1758661987**

¡Disfruta probando todas las validaciones implementadas en el sistema! 🎉
