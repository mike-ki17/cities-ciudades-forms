# Condiciones de Visibilidad Válidas

## ❌ Problema Identificado

La condición `"not_empty"` **NO es válida** en el sistema. Esto causaba que el campo barrio no se mostrara.

## ✅ Condiciones Válidas

El sistema solo acepta estas condiciones:

### 1. **equal** - Igual a
```json
"visible": {
  "model": "ciudad",
  "value": "bogota",
  "condition": "equal"
}
```
**Significado**: Aparece cuando el campo ciudad = "bogota"

### 2. **not_equal** - Diferente a
```json
"visible": {
  "model": "localidad_bogota",
  "value": "",
  "condition": "not_equal"
}
```
**Significado**: Aparece cuando el campo localidad NO está vacío

### 3. **contains** - Contiene
```json
"visible": {
  "model": "ciudad",
  "value": "bog",
  "condition": "contains"
}
```
**Significado**: Aparece cuando el campo ciudad contiene "bog"

### 4. **not_contains** - No contiene
```json
"visible": {
  "model": "ciudad",
  "value": "medellin",
  "condition": "not_contains"
}
```
**Significado**: Aparece cuando el campo ciudad NO contiene "medellin"

### 5. **greater_than** - Mayor que
```json
"visible": {
  "model": "edad",
  "value": "18",
  "condition": "greater_than"
}
```
**Significado**: Aparece cuando edad > 18

### 6. **less_than** - Menor que
```json
"visible": {
  "model": "edad",
  "value": "65",
  "condition": "less_than"
}
```
**Significado**: Aparece cuando edad < 65

### 7. **greater_equal** - Mayor o igual que
```json
"visible": {
  "model": "edad",
  "value": "18",
  "condition": "greater_equal"
}
```
**Significado**: Aparece cuando edad >= 18

### 8. **less_equal** - Menor o igual que
```json
"visible": {
  "model": "edad",
  "value": "65",
  "condition": "less_equal"
}
```
**Significado**: Aparece cuando edad <= 65

## 🔧 Solución para Campo Barrio

Para que el campo barrio aparezca cuando se seleccione **cualquier** localidad:

```json
"visible": {
  "model": "localidad_bogota",
  "value": "",
  "condition": "not_equal"
}
```

**Explicación**: 
- `"value": ""` = valor vacío
- `"condition": "not_equal"` = diferente a vacío
- **Resultado**: Aparece cuando localidad tiene cualquier valor (no está vacío)

## 🎯 Casos de Uso Comunes

### Ciudad → Localidad
```json
"visible": {
  "model": "ciudad",
  "value": "bogota",
  "condition": "equal"
}
```

### Localidad → Barrio
```json
"visible": {
  "model": "localidad_bogota",
  "value": "",
  "condition": "not_equal"
}
```

### Campo específico por localidad
```json
"visible": {
  "model": "localidad_bogota",
  "value": "suba",
  "condition": "equal"
}
```

## ⚠️ Condiciones NO Válidas

- ❌ `"not_empty"` - No existe
- ❌ `"empty"` - No existe  
- ❌ `"has_value"` - No existe
- ❌ `"is_filled"` - No existe

## ✅ Archivo Corregido

El archivo `formulario_con_visibilidad_condicional.json` ya está corregido con la condición válida.
