# Campos de Archivo - Implementación Completa

## 🎯 Resumen

Se ha implementado completamente el soporte para campos de tipo "file" en el sistema de formularios dinámicos. Los campos de archivo permiten subir archivos con validaciones personalizables, almacenamiento en S3 o local, y integración completa con el sistema de campos condicionales.

## ✨ Características Implementadas

### 1. **Tipos de Archivos Configurables**
- Configuración dinámica de tipos permitidos desde JSON
- Soporte para imágenes, documentos, archivos de texto y más
- Validación de tipos MIME para seguridad

### 2. **Tamaño Máximo Dinámico**
- Configuración del tamaño máximo desde JSON (en KB)
- Validación automática en frontend y backend
- Mensajes de error informativos

### 3. **Almacenamiento Flexible**
- Soporte para almacenamiento local y S3
- Configuración automática según variables de entorno
- URLs seguras para descarga de archivos

### 4. **Información Completa de Archivos**
- Almacenamiento de información completa en JSON
- Incluye: ruta, nombre original, tamaño, tipo MIME, fecha de subida
- Integración con el sistema de respuestas existente

### 5. **Interfaz de Usuario Intuitiva**
- Botón de selección simple
- Preview de información del archivo
- Mensajes de estado (cargando, éxito, error)
- Validación en tiempo real

### 6. **Integración con Campos Condicionales**
- Funciona perfectamente con el sistema de visibilidad condicional
- Campos aparecen/desaparecen según otros campos
- Validaciones respetan las condiciones de visibilidad

## 🏗️ Estructura de Campo de Archivo

### Configuración Básica
```json
{
  "key": "documento_identidad",
  "label": "Documento de Identidad",
  "type": "file",
  "required": true,
  "description": "Sube una copia de tu documento de identidad",
  "validations": {
    "file_types": ["jpg", "jpeg", "png", "pdf"],
    "max_file_size": 2048
  }
}
```

### Configuración Avanzada
```json
{
  "key": "portafolio",
  "label": "Portafolio de Trabajos",
  "type": "file",
  "required": false,
  "description": "Sube tu portafolio en formato PDF",
  "validations": {
    "file_types": ["pdf", "doc", "docx"],
    "max_file_size": 10240
  },
  "visible": {
    "model": "tipo_participante",
    "value": "profesional",
    "condition": "equal"
  }
}
```

## 📋 Validaciones Disponibles

### Tipos de Archivo Permitidos
```json
{
  "validations": {
    "file_types": ["jpg", "jpeg", "png", "gif", "webp", "pdf", "doc", "docx", "txt"]
  }
}
```

### Tamaño Máximo
```json
{
  "validations": {
    "max_file_size": 2048  // En KB (2MB)
  }
}
```

### Tipos MIME Validados Automáticamente
- **Imágenes**: image/jpeg, image/png, image/gif, image/webp, image/svg+xml
- **Documentos**: application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document
- **Textos**: text/plain, text/csv, application/rtf
- **Archivos**: application/zip, application/x-rar-compressed

## 🔧 Configuración del Sistema

### Variables de Entorno
```env
# Para usar S3
FORM_FILES_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name

# Para usar almacenamiento local (por defecto)
FORM_FILES_DISK=local
```

### Configuración de Storage
El sistema está configurado con dos discos:
- `form_files`: Almacenamiento local
- `s3_form_files`: Almacenamiento en S3

## 📊 Estructura de Datos

### Información de Archivo Almacenada
```json
{
  "documento_identidad": {
    "original_name": "cedula_juan_perez.pdf",
    "stored_name": "uuid-generated-name.pdf",
    "path": "forms/123/documento_identidad/2024/01/15/uuid-generated-name.pdf",
    "url": "https://s3.amazonaws.com/bucket/path/to/file.pdf",
    "size": 1048576,
    "mime_type": "application/pdf",
    "extension": "pdf",
    "uploaded_at": "2024-01-15T10:30:00.000Z",
    "disk": "s3_form_files"
  }
}
```

## 🚀 API Endpoints

### Subir Archivo
```http
POST /api/files/upload
Content-Type: multipart/form-data

form_id: 123
field_key: documento_identidad
file: [archivo]
```

### Obtener Información de Archivo
```http
GET /api/files/info?path=forms/123/documento_identidad/file.pdf&disk=s3_form_files
```

### Eliminar Archivo
```http
DELETE /api/files/delete
Content-Type: application/json

{
  "path": "forms/123/documento_identidad/file.pdf",
  "disk": "s3_form_files"
}
```

### Descargar Archivo (Local)
```http
GET /form/file/download/{encoded_path}
```

## 💻 Uso en Frontend

### HTML Generado
```html
<div class="file-upload-container">
    <input type="file" 
           id="documento_identidad" 
           name="documento_identidad" 
           data-field-key="documento_identidad"
           data-form-id="123"
           data-file-types="jpg,jpeg,png,pdf"
           data-max-size="2048"
           required>
    
    <div class="file-info mt-2" id="file-info-documento_identidad" style="display: none;">
        <!-- Información del archivo subido -->
    </div>
    
    <div class="file-error mt-2" id="file-error-documento_identidad" style="display: none;">
        <!-- Mensajes de error -->
    </div>
</div>
```

### JavaScript Automático
El sistema incluye JavaScript automático que:
- Valida archivos en tiempo real
- Muestra progreso de subida
- Maneja errores y mensajes de éxito
- Almacena información del archivo para envío del formulario

## 🔒 Seguridad

### Validaciones Implementadas
1. **Validación de tipos MIME**: Verificación real del tipo de archivo
2. **Validación de extensiones**: Verificación de extensiones permitidas
3. **Validación de tamaño**: Límites de tamaño configurables
4. **Nombres únicos**: Generación de nombres únicos para evitar conflictos
5. **Rutas organizadas**: Archivos organizados por formulario, campo y fecha

### Tipos de Archivo Seguros
El sistema valida automáticamente los tipos MIME para prevenir la subida de archivos maliciosos.

## 📝 Ejemplos de Uso

### Ejemplo 1: Campo de Imagen Simple
```json
{
  "key": "foto_perfil",
  "label": "Foto de Perfil",
  "type": "file",
  "required": true,
  "validations": {
    "file_types": ["jpg", "jpeg", "png"],
    "max_file_size": 1024
  }
}
```

### Ejemplo 2: Campo de Documento con Condición
```json
{
  "key": "certificado_estudios",
  "label": "Certificado de Estudios",
  "type": "file",
  "required": true,
  "validations": {
    "file_types": ["pdf", "jpg", "jpeg", "png"],
    "max_file_size": 5120
  },
  "visible": {
    "model": "nivel_educacion",
    "value": ["universitario", "tecnico"],
    "condition": "contains"
  }
}
```

### Ejemplo 3: Campo de Portafolio Múltiples Formatos
```json
{
  "key": "portafolio",
  "label": "Portafolio de Trabajos",
  "type": "file",
  "required": false,
  "description": "Sube tu portafolio en PDF, Word o imágenes",
  "validations": {
    "file_types": ["pdf", "doc", "docx", "jpg", "jpeg", "png"],
    "max_file_size": 10240
  }
}
```

## 🎨 Interfaz de Usuario

### Estados Visuales
1. **Estado Inicial**: Campo de archivo vacío
2. **Selección**: Usuario selecciona archivo
3. **Validación**: Verificación de tipo y tamaño
4. **Subida**: Progreso de subida al servidor
5. **Éxito**: Archivo subido correctamente
6. **Error**: Mensaje de error con detalles

### Mensajes Informativos
- Tipos de archivo permitidos
- Tamaño máximo permitido
- Estado de subida
- Información del archivo subido

## 🔄 Integración con Sistema Existente

### Compatibilidad
- ✅ Funciona con campos condicionales
- ✅ Integra con validaciones existentes
- ✅ Compatible con estructura relacional
- ✅ Funciona con formularios JSON legacy
- ✅ Integra con sistema de notificaciones por email

### Base de Datos
- Uso de la columna existente `data_json` en `form_submissions`
- Almacenamiento JSON de información de archivos
- Compatible con estructura existente

## 🚀 Próximas Mejoras

### Funcionalidades Futuras
1. **Validación con IA**: Integración con OpenAI para validación de contenido
2. **Múltiples archivos**: Soporte para subir múltiples archivos por campo
3. **Preview de imágenes**: Vista previa de imágenes subidas
4. **Compresión automática**: Reducción automática de tamaño de imágenes
5. **Watermark**: Aplicación automática de marcas de agua

## 📚 Archivos Modificados

### Backend
- `app/Http/Requests/Field/StoreFieldJsonRequest.php` - Validaciones de campos
- `app/Services/FormService.php` - Lógica de validación
- `app/Services/FileUploadService.php` - Servicio de subida (nuevo)
- `app/Http/Controllers/Api/FileUploadController.php` - API de archivos (nuevo)
- `app/Http/Controllers/Public/FormSlugSubmitController.php` - Manejo de archivos
- `app/Models/FormSubmission.php` - Modelo actualizado
- `config/filesystems.php` - Configuración de storage
- ~~`database/migrations/2025_10_07_213321_add_file_info_to_form_submissions_table.php`~~ - Migración eliminada (se usa `data_json` existente)

### Frontend
- `resources/views/public/forms/show.blade.php` - Renderizado de campos
- `routes/api.php` - Rutas de API
- `routes/web.php` - Rutas de descarga

### Documentación
- `CAMPOS_ARCHIVO_IMPLEMENTADOS.md` - Este archivo

## ✅ Estado de Implementación

- [x] Validaciones de tipos de archivo
- [x] Validaciones de tamaño
- [x] Almacenamiento local y S3
- [x] Interfaz de usuario
- [x] API de subida de archivos
- [x] Integración con formularios
- [x] Campos condicionales
- [x] Documentación completa
- [ ] Validación con IA (futuro)
- [ ] Múltiples archivos (futuro)
- [ ] Preview de imágenes (futuro)

## 🎉 Conclusión

El sistema de campos de archivo está completamente implementado y listo para uso en producción. Proporciona una solución robusta, segura y flexible para la subida de archivos en formularios dinámicos, con integración completa al sistema existente.
