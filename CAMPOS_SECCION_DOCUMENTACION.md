# Campos de Sección - Documentación

## 🎯 Resumen

Se ha implementado un nuevo tipo de campo llamado `section` que permite agregar títulos y subtítulos descriptivos en los formularios, organizando mejor la información y mejorando la experiencia del usuario.

## ✨ Características del Campo Section

### **Tipo de Campo**: `section`

### **Propiedades Disponibles**:

#### **Propiedades Requeridas**:
- `key`: Identificador único del campo
- `label`: Texto del título/sección
- `type`: Debe ser `"section"`

#### **Propiedades Opcionales**:
- `level`: Nivel del título (`h1`, `h2`, `h3`)
- `description`: Texto descriptivo que aparece debajo del título

### **Niveles de Título**:

#### **H1** - Título Principal
```json
{
  "key": "titulo_principal",
  "label": "Formulario de Inscripción",
  "type": "section",
  "level": "h1",
  "description": "Complete todos los campos requeridos para su inscripción."
}
```
**Renderizado**: Título grande con borde inferior grueso

#### **H2** - Sección Principal
```json
{
  "key": "seccion_datos_personales",
  "label": "Datos Personales",
  "type": "section",
  "level": "h2",
  "description": "Información básica del participante."
}
```
**Renderizado**: Título mediano con borde inferior

#### **H3** - Subsección
```json
{
  "key": "subtitulo_contacto",
  "label": "Información de Contacto",
  "type": "section",
  "level": "h3",
  "description": "Datos para comunicación directa."
}
```
**Renderizado**: Título pequeño con borde inferior sutil

## 🎨 Estilos Visuales

### **H1 - Título Principal**
- Tamaño: `text-2xl`
- Peso: `font-bold`
- Color: `text-gray-900`
- Borde: `border-b-2 border-gray-200`
- Espaciado: `pb-2`

### **H2 - Sección Principal**
- Tamaño: `text-xl`
- Peso: `font-semibold`
- Color: `text-gray-800`
- Borde: `border-b border-gray-200`
- Espaciado: `pb-2 mt-6`

### **H3 - Subsección**
- Tamaño: `text-lg`
- Peso: `font-medium`
- Color: `text-gray-700`
- Borde: `border-b border-gray-100`
- Espaciado: `pb-1 mt-4`

### **Descripción**
- Tamaño: `text-sm`
- Color: `text-gray-600`
- Espaciado: `mt-2`
- Interlineado: `leading-relaxed`

## 📋 Ejemplos de Uso

### **Ejemplo 1: Formulario Básico con Secciones**
```json
{
  "fields": [
    {
      "key": "seccion_personal",
      "label": "Información Personal",
      "type": "section",
      "level": "h2",
      "description": "Complete sus datos personales básicos."
    },
    {
      "key": "nombre",
      "label": "Nombre Completo",
      "type": "text",
      "required": true
    },
    {
      "key": "seccion_contacto",
      "label": "Datos de Contacto",
      "type": "section",
      "level": "h2",
      "description": "Información para comunicarnos con usted."
    },
    {
      "key": "email",
      "label": "Correo Electrónico",
      "type": "email",
      "required": true
    }
  ]
}
```

### **Ejemplo 2: Formulario Complejo con Múltiples Niveles**
```json
{
  "fields": [
    {
      "key": "titulo_formulario",
      "label": "Formulario de Inscripción SMARTFILMS®",
      "type": "section",
      "level": "h1",
      "description": "Complete todos los campos para participar en el festival."
    },
    {
      "key": "seccion_datos_personales",
      "label": "Datos Personales",
      "type": "section",
      "level": "h2",
      "description": "Información básica del participante."
    },
    {
      "key": "nombre",
      "label": "Nombre Completo",
      "type": "text",
      "required": true
    },
    {
      "key": "subtitulo_ubicacion",
      "label": "Ubicación Geográfica",
      "type": "section",
      "level": "h3",
      "description": "Seleccione su ciudad y localidad."
    },
    {
      "key": "ciudad",
      "label": "Ciudad",
      "type": "select",
      "required": true,
      "options": [
        {"value": "bogota", "label": "Bogotá D.C."},
        {"value": "medellin", "label": "Medellín"}
      ]
    }
  ]
}
```

### **Ejemplo 3: Sección con Texto Legal Largo**
```json
{
  "key": "seccion_terminos",
  "label": "Términos y Condiciones",
  "type": "section",
  "level": "h2",
  "description": "Al completar este formulario, usted acepta los siguientes términos y condiciones:\n\n1. Los datos proporcionados serán utilizados únicamente para fines del evento.\n2. Se compromete a proporcionar información veraz y actualizada.\n3. Acepta recibir comunicaciones relacionadas con el evento.\n\nPara más información, consulte nuestra política de privacidad en www.ejemplo.com"
}
```

## 🔧 Implementación Técnica

### **Renderizado en Frontend**
Los campos de tipo `section` se renderizan de manera especial:

1. **No se procesan como campos de entrada** - No tienen inputs, selects, etc.
2. **Se renderizan como elementos HTML** - `<h1>`, `<h2>`, `<h3>` con estilos
3. **No se validan** - No tienen validaciones ni son requeridos
4. **No se envían en el formulario** - Son solo elementos visuales

### **Estructura HTML Generada**
```html
<div class="section-field mt-8 mb-6">
  <h2 class="text-xl font-semibold text-gray-800 border-b border-gray-200 pb-2 mt-6">
    Datos Personales
  </h2>
  <p class="mt-2 text-sm text-gray-600 leading-relaxed">
    Complete sus datos personales básicos.
  </p>
</div>
```

## 🎯 Casos de Uso Recomendados

### **1. Organización de Formularios Largos**
- Dividir formularios extensos en secciones lógicas
- Mejorar la navegación y comprensión del usuario
- Reducir la sensación de abrumamiento

### **2. Información Legal y Términos**
- Agrupar términos y condiciones
- Explicar políticas de privacidad
- Proporcionar contexto legal

### **3. Instrucciones y Ayuda**
- Explicar el propósito de cada sección
- Proporcionar instrucciones específicas
- Guiar al usuario en el proceso

### **4. Formularios Condicionales**
- Agrupar campos que aparecen bajo ciertas condiciones
- Organizar información relacionada
- Mejorar la experiencia de usuario

## 📱 Responsive Design

Los campos de sección son completamente responsivos:

- **Desktop**: Títulos con espaciado completo
- **Tablet**: Mantiene la jerarquía visual
- **Mobile**: Se adapta al ancho de pantalla
- **Accesibilidad**: Mantiene la semántica HTML correcta

## 🚀 Cómo Usar

### **1. En la Creación de Formularios**
1. Ir al panel de administración
2. Crear o editar un formulario
3. En el JSON, agregar campos de tipo `section`
4. Especificar el `level` y `description` según necesidades

### **2. Ejemplo de Implementación**
```json
{
  "key": "mi_seccion",
  "label": "Mi Sección",
  "type": "section",
  "level": "h2",
  "description": "Descripción de la sección"
}
```

### **3. Mejores Prácticas**
- Usar `h1` solo para el título principal del formulario
- Usar `h2` para secciones principales
- Usar `h3` para subsecciones
- Mantener descripciones concisas pero informativas
- Organizar campos relacionados bajo la misma sección

## 🎉 Beneficios

### **Para Usuarios**
- **Mejor organización visual** del formulario
- **Comprensión clara** de cada sección
- **Navegación más fácil** en formularios largos
- **Menos fatiga visual** al completar formularios

### **Para Administradores**
- **Formularios más profesionales** y organizados
- **Flexibilidad** para estructurar contenido
- **Mejor experiencia** de usuario
- **Fácil implementación** con JSON simple

### **Para el Sistema**
- **Semántica HTML correcta** con títulos apropiados
- **Accesibilidad mejorada** para lectores de pantalla
- **SEO friendly** con estructura de títulos
- **Mantenimiento sencillo** del código

## 🔮 Próximas Mejoras

- **Iconos en secciones** - Agregar iconos a los títulos
- **Colores personalizables** - Permitir personalizar colores de secciones
- **Animaciones** - Efectos de transición entre secciones
- **Navegación lateral** - Índice de secciones para formularios largos
- **Progreso visual** - Barra de progreso por secciones

---

**¡Los campos de sección están listos para usar!** 🚀

Ahora puedes crear formularios más organizados y profesionales con títulos y subtítulos descriptivos que mejoran significativamente la experiencia del usuario.
