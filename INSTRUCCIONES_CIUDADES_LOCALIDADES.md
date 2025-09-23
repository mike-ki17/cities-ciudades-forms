# 🏙️ Instrucciones: Formulario de Ciudades y Localidades

## 📋 Descripción

Este seeder crea un formulario completo con campos condicionales donde al seleccionar una ciudad se muestran las localidades correspondientes. El formulario utiliza el nuevo sistema relacional del proyecto.

## 🚀 Cómo Ejecutar el Seeder

### Opción 1: Ejecutar solo este seeder
```bash
cd form-ciudades
php artisan db:seed --class=CiudadesLocalidadesSeeder
```

### Opción 2: Agregar al DatabaseSeeder
1. Abrir `database/seeders/DatabaseSeeder.php`
2. Agregar en el método `run()`:
```php
$this->call([
    // ... otros seeders
    CiudadesLocalidadesSeeder::class,
]);
```
3. Ejecutar:
```bash
php artisan db:seed
```

## 🎯 Características del Formulario

### Campos Incluidos
- **Nombre Completo** (texto, requerido)
- **Correo Electrónico** (email, requerido)
- **Ciudad** (select, requerido) - Campo principal
- **Localidades Condicionales** (selects, requeridos) - Se muestran según la ciudad
- **Dirección** (textarea, requerido)
- **Teléfono** (tel, requerido)
- **Términos y Condiciones** (checkbox, requerido)

### Ciudades y Localidades

#### 🏛️ Bogotá D.C.
- 20 localidades: Usaquén, Chapinero, Santa Fe, San Cristóbal, Usme, Tunjuelito, Bosa, Kennedy, Fontibón, Engativá, Suba, Barrios Unidos, Teusaquillo, Los Mártires, Antonio Nariño, Puente Aranda, La Candelaria, Rafael Uribe Uribe, Ciudad Bolívar, Sumapaz

#### 🏔️ Medellín
- 16 comunas: Popular, Santa Cruz, Manrique, Aranjuez, Castilla, Doce de Octubre, Robledo, Villa Hermosa, Buenavista, La Candelaria, Laureles-Estadio, La América, San Javier, El Poblado, Guayabal, Belén

#### 🌴 Cali
- 22 comunas: Comuna 1 a Comuna 22 con nombres descriptivos

#### 🌊 Barranquilla
- 5 localidades: Riomar, Norte Centro Histórico, Sur Occidente, Metropolitana, Suroriente

#### 🏰 Cartagena
- 3 localidades: Historia y Caribe Norte, De la Virgen y Turística, Industrial y de la Bahía

#### ⛰️ Bucaramanga
- 17 comunas: Norte, Nororiente, Santander, García Rovira, Convención, Lácides Castro, Mutis, Morrorico, Sur, Suroccidente, Occidente, Provenza, Cabecera del Llano, Centro, Oriental, Pedregosa, Sureste

## 🔧 Cómo Funciona la Lógica Condicional

### Estructura de Visibilidad
```json
{
  "visible": {
    "model": "ciudad",
    "value": "bogota",
    "condition": "equal"
  }
}
```

### Comportamiento
1. **Inicialmente**: Solo se muestra el campo de ciudad
2. **Al seleccionar ciudad**: Aparece el campo de localidades correspondiente
3. **Al cambiar ciudad**: Se oculta la localidad anterior y aparece la nueva
4. **Validación**: Solo se validan los campos visibles

## 🌐 URLs de Acceso

Después de ejecutar el seeder, tendrás acceso a:

- **Formulario Público**: `http://localhost:8000/form/{slug-del-formulario}`
- **Panel de Administración**: `http://localhost:8000/admin/forms/{id-del-formulario}`

## 🎨 Personalización

### Agregar Más Ciudades
1. Editar el seeder `CiudadesLocalidadesSeeder.php`
2. Agregar opción al select de ciudad:
```php
['value' => 'nueva_ciudad', 'label' => 'Nueva Ciudad']
```
3. Crear campo condicional para las localidades:
```php
[
    'key' => 'localidad_nueva_ciudad',
    'label' => 'Localidad',
    'type' => 'select',
    'required' => true,
    'options' => [
        ['value' => 'localidad1', 'label' => 'Localidad 1'],
        // ... más localidades
    ],
    'visible' => [
        'model' => 'ciudad',
        'value' => 'nueva_ciudad',
        'condition' => 'equal'
    ]
]
```

### Modificar Validaciones
Editar las propiedades `validations` en cada campo:
```php
'validations' => [
    'min_length' => 2,
    'max_length' => 100,
    'pattern' => '^[a-zA-Z\s]+$'
]
```

## 🧪 Pruebas

### Probar Campos Condicionales
1. Acceder al formulario público
2. Seleccionar diferentes ciudades
3. Verificar que aparecen las localidades correctas
4. Cambiar de ciudad y verificar que se actualiza el campo de localidades

### Probar Validaciones
1. Intentar enviar sin completar campos requeridos
2. Probar formatos de email inválidos
3. Probar números de teléfono con formato incorrecto
4. Verificar que los campos condicionales se validan solo cuando son visibles

## 📊 Estructura de Base de Datos

El seeder crea registros en las siguientes tablas:
- `forms` - Información del formulario
- `fields_json` - Definición de campos
- `form_categories` - Categorías de campos
- `form_field_orders` - Orden y relación de campos
- `form_options` - Opciones para campos select/checkbox

## 🔍 Troubleshooting

### Error: "Formulario no se crea"
- Verificar que existe un evento en la base de datos
- Revisar logs de Laravel: `storage/logs/laravel.log`

### Error: "Campos condicionales no funcionan"
- Verificar que el JavaScript está cargado
- Revisar la consola del navegador para errores
- Verificar que los atributos `data-conditional-*` están presentes

### Error: "Validaciones no funcionan"
- Verificar que el `FormService` está configurado correctamente
- Revisar que las reglas de validación se generan correctamente

## 📝 Notas Importantes

- El seeder usa el nuevo sistema relacional, no `schema_json`
- Los campos condicionales funcionan tanto en frontend como backend
- Las validaciones se aplican solo a campos visibles
- El formulario es completamente funcional y listo para producción
