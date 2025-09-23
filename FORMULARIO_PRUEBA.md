# Formulario de Prueba - Campos Condicionales y Validaciones

## 📋 Información del Formulario

- **ID**: 25
- **Nombre**: Formulario de Prueba - Campos Condicionales y Validaciones
- **Evento**: Smart Films Festival - Lima, 2024
- **Estado**: Activo

## 🎯 Características del Formulario

Este formulario de prueba incluye múltiples tipos de validaciones y campos condicionales para demostrar las capacidades del sistema:

### 1. **Campos con Validaciones de Longitud**

#### Nombre Completo
- **Tipo**: Texto
- **Validaciones**: 
  - Mínimo: 2 caracteres
  - Máximo: 50 caracteres
  - Solo letras y espacios permitidos
- **Requerido**: Sí

#### Teléfono
- **Tipo**: Texto
- **Validaciones**:
  - Formato: Teléfono internacional
  - Patrón: `^[+]?[0-9]{9,15}$`
  - Mínimo: 9 caracteres
  - Máximo: 15 caracteres
- **Requerido**: No

#### CIF de la Empresa (Condicional)
- **Tipo**: Texto
- **Validaciones**:
  - Patrón: `^[A-Z][0-9]{8}$`
  - Exactamente: 9 caracteres
- **Condición**: Solo visible si "¿Tienes una empresa?" = "Sí"

#### Descripción de la Empresa (Condicional)
- **Tipo**: Textarea
- **Validaciones**:
  - Mínimo: 10 palabras, máximo: 200 palabras
  - Mínimo: 50 caracteres, máximo: 1000 caracteres
- **Condición**: Solo visible si "¿Tienes una empresa?" = "Sí"

#### Dirección Completa (Condicional)
- **Tipo**: Textarea
- **Validaciones**:
  - Mínimo: 20 caracteres, máximo: 200 caracteres
  - Mínimo: 5 palabras, máximo: 50 palabras
- **Condición**: Solo visible si "Tipo de Registro" = "Premium" o "Empresarial"

#### Comentarios Adicionales
- **Tipo**: Textarea
- **Validaciones**:
  - Mínimo: 5 palabras, máximo: 100 palabras
  - Mínimo: 25 caracteres, máximo: 500 caracteres
- **Requerido**: No

### 2. **Campos Condicionales**

#### Campos que aparecen según el tipo de registro:

**Para "Registro Premium":**
- Teléfono de Emergencia
- Dirección Completa
- Código Postal
- Presupuesto Disponible

**Para "Registro Empresarial":**
- Dirección Completa
- Código Postal
- Número de Empleados
- Sector Empresarial
- Sitio Web de la Empresa
- Presupuesto Disponible

**Para "¿Tienes una empresa?" = "Sí":**
- Nombre de la Empresa
- CIF de la Empresa
- Descripción de la Empresa

### 3. **Validaciones Numéricas**

#### Edad
- **Tipo**: Number
- **Validaciones**:
  - Mínimo: 18
  - Máximo: 100
  - Paso: 1
- **Requerido**: Sí

#### Fecha de Nacimiento
- **Tipo**: Date
- **Validaciones**:
  - Edad mínima: 18 años
  - Edad máxima: 65 años
- **Requerido**: No

#### Número de Empleados (Condicional)
- **Tipo**: Number
- **Validaciones**:
  - Mínimo: 1
  - Máximo: 10,000
  - Paso: 1
- **Condición**: Solo visible si "Tipo de Registro" = "Empresarial"

#### Presupuesto Disponible (Condicional)
- **Tipo**: Number
- **Validaciones**:
  - Formato: Moneda
  - Decimales: 2
  - Mínimo: €100
  - Máximo: €100,000
  - Paso: 0.01
- **Condición**: Solo visible si "Tipo de Registro" = "Premium" o "Empresarial"

### 4. **Validaciones de Formato**

#### Email
- **Tipo**: Email
- **Validaciones**:
  - Formato: Email válido
  - Único: Sí
- **Requerido**: Sí

#### Código Postal (Condicional)
- **Tipo**: Texto
- **Validaciones**:
  - Formato: Código postal
  - Patrón: `^[0-9]{5}$`
  - Exactamente: 5 dígitos
- **Condición**: Solo visible si "Tipo de Registro" = "Premium" o "Empresarial"

#### Sitio Web (Condicional)
- **Tipo**: Texto
- **Validaciones**:
  - Formato: URL válida
  - Mínimo: 10 caracteres
  - Máximo: 100 caracteres
- **Condición**: Solo visible si "Tipo de Registro" = "Empresarial"

### 5. **Selecciones Múltiples**

#### Áreas de Interés
- **Tipo**: Select (múltiple)
- **Validaciones**:
  - Mínimo: 1 selección
  - Máximo: 5 selecciones
- **Opciones**: Tecnología, Marketing, Ventas, RRHH, Finanzas, Operaciones, Investigación, Desarrollo

## 🧪 Cómo Probar el Formulario

### Paso 1: Acceder al Formulario
1. Ve a la URL del formulario público
2. El formulario debería estar disponible en la ruta correspondiente al evento

### Paso 2: Probar Campos Básicos
1. **Nombre**: Prueba con menos de 2 caracteres (debería fallar)
2. **Nombre**: Prueba con más de 50 caracteres (debería fallar)
3. **Nombre**: Prueba con números o símbolos (debería fallar)
4. **Email**: Prueba con formato inválido (debería fallar)
5. **Edad**: Prueba con valores menores a 18 o mayores a 100 (debería fallar)

### Paso 3: Probar Campos Condicionales
1. **Selecciona "Registro Premium"**:
   - Deberían aparecer: Teléfono de Emergencia, Dirección, Código Postal, Presupuesto
   - Prueba las validaciones de estos campos

2. **Selecciona "Registro Empresarial"**:
   - Deberían aparecer: Dirección, Código Postal, Número de Empleados, Sector, Sitio Web, Presupuesto
   - Prueba las validaciones específicas

3. **Selecciona "¿Tienes una empresa?" = "Sí"**:
   - Deberían aparecer: Nombre de Empresa, CIF, Descripción
   - Prueba las validaciones de longitud y formato

### Paso 4: Probar Validaciones de Longitud
1. **Teléfono**: Prueba con menos de 9 dígitos o más de 15
2. **CIF**: Prueba con formato incorrecto (debería ser 1 letra + 8 dígitos)
3. **Descripción de Empresa**: Prueba con menos de 10 palabras o más de 200
4. **Comentarios**: Prueba con menos de 5 palabras o más de 100

### Paso 5: Probar Validaciones de Formato
1. **Código Postal**: Prueba con menos o más de 5 dígitos
2. **Sitio Web**: Prueba con URL inválida
3. **Presupuesto**: Prueba con valores fuera del rango permitido

## 🔍 Casos de Prueba Específicos

### Caso 1: Registro Básico
```
Tipo de Registro: Registro Básico
Nombre: Juan Pérez
Email: juan@ejemplo.com
Edad: 25
Tiene empresa: No
Acepta términos: ✓
```

### Caso 2: Registro Premium con Empresa
```
Tipo de Registro: Registro Premium
Nombre: María García López
Email: maria@empresa.com
Teléfono: +34 123 456 789
Edad: 30
Tiene empresa: Sí
Nombre empresa: Tech Solutions SL
CIF empresa: A12345678
Descripción empresa: Empresa de tecnología especializada en desarrollo de software y consultoría digital para empresas medianas y grandes.
Teléfono emergencia: +34 987 654 321
Dirección: Calle Mayor 123, Madrid, España
Código postal: 28001
Presupuesto: 5000.50
Intereses: Tecnología, Marketing
Comentarios: Estoy interesado en conocer más sobre las soluciones disponibles para mi empresa.
Acepta términos: ✓
```

### Caso 3: Registro Empresarial
```
Tipo de Registro: Registro Empresarial
Nombre: Carlos Rodríguez
Email: carlos@corporacion.com
Teléfono: +34 555 123 456
Edad: 45
Tiene empresa: Sí
Nombre empresa: Corporación Digital
CIF empresa: B87654321
Descripción empresa: Gran corporación multinacional especializada en transformación digital, servicios cloud, inteligencia artificial y automatización de procesos empresariales.
Dirección: Avenida de la Innovación 456, Barcelona, España
Código postal: 08001
Número empleados: 500
Sector: Tecnología
Sitio web: https://www.corporaciondigital.com
Presupuesto: 50000.00
Intereses: Tecnología, Finanzas, Operaciones
Comentarios: Buscamos soluciones integrales para digitalizar nuestros procesos y mejorar la eficiencia operativa.
Acepta términos: ✓
```

## ⚠️ Errores Esperados

Al probar estos casos, deberías ver errores de validación para:

1. **Nombres muy cortos o largos**
2. **Emails con formato inválido**
3. **Teléfonos con formato incorrecto**
4. **CIFs con formato incorrecto**
5. **Códigos postales con menos o más de 5 dígitos**
6. **URLs inválidas**
7. **Presupuestos fuera del rango**
8. **Edades fuera del rango permitido**
9. **Textos con menos palabras de las requeridas**
10. **Campos requeridos vacíos**

## 📝 Notas Importantes

- Los campos condicionales solo aparecen cuando se cumplen las condiciones especificadas
- Las validaciones se aplican tanto en el frontend como en el backend
- Los mensajes de error deberían ser claros y específicos
- El formulario mantiene los datos ingresados en caso de error de validación
- Los campos con validaciones de longitud muestran contadores en tiempo real (si está implementado)

¡Disfruta probando todas las funcionalidades del formulario! 🚀
