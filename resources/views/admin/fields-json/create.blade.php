@extends('layouts.admin')

@section('title', 'Crear Campo JSON')

@section('page-title', 'Crear Nuevo Campo JSON')
@section('page-description', 'Crea un nuevo campo individual usando estructura JSON')

@section('page-actions')
    <a href="{{ route('admin.fields-json.index') }}" class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver
    </a>
@endsection

@section('content')
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Configuración del Campo JSON
        </div>

        <form method="POST" action="{{ route('admin.fields-json.store') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- JSON Editor -->
                <div class="admin-field-group">
                    <label for="field_json" class="admin-field-label">
                        <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Estructura del Campo (JSON) *
                    </label>
                    <textarea name="field_json" id="field_json" rows="20" 
                              class="admin-textarea w-full font-mono text-sm" 
                              placeholder='{"key": "nombre", "label": "Nombre Completo", "type": "text", "required": true, "placeholder": "Ingresa tu nombre completo"}' required>{{ old('field_json', '') }}</textarea>
                    @error('field_json')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                    <div class="admin-field-help">
                        <strong>Propiedades requeridas:</strong> key, label, type, required<br>
                        <strong>Propiedades opcionales:</strong> placeholder, options (para select), help, validations, visible, default_value, description<br>
                        <strong>Tipos de campos disponibles:</strong> text, email, number, date, select, textarea, checkbox<br>
                        <strong>Validaciones disponibles:</strong> min_length, max_length, pattern, format, min_value, max_value, min_date, max_date, required_if, unique, allowed_chars, forbidden_chars, min_words, max_words, decimal_places, step
                    </div>
                </div>

                <!-- Preview and Examples -->
                <div class="admin-field-group">
                    <label class="admin-field-label">
                        <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Ejemplos y Ayuda
                    </label>
                    
                    <div class="space-y-4">
                        <!-- Campo de Texto Simple -->
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <h4 class="text-sm font-medium admin-text mb-2">Campo de Texto Simple</h4>
                            <pre class="text-xs admin-text-secondary overflow-x-auto"><code>{
  "key": "nombre",
  "label": "Nombre Completo",
  "type": "text",
  "required": true,
  "placeholder": "Ingresa tu nombre completo"
}</code></pre>
                        </div>

                        <!-- Campo Select con Opciones -->
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <h4 class="text-sm font-medium admin-text mb-2">Campo Select con Opciones</h4>
                            <pre class="text-xs admin-text-secondary overflow-x-auto"><code>{
  "key": "genero",
  "label": "Género",
  "type": "select",
  "required": true,
  "options": [
    {"value": "masculino", "label": "Masculino"},
    {"value": "femenino", "label": "Femenino"},
    {"value": "otro", "label": "Otro"}
  ]
}</code></pre>
                        </div>

                        <!-- Campo con Validaciones -->
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <h4 class="text-sm font-medium admin-text mb-2">Campo con Validaciones</h4>
                            <pre class="text-xs admin-text-secondary overflow-x-auto"><code>{
  "key": "edad",
  "label": "Edad",
  "type": "number",
  "required": true,
  "validations": {
    "min_value": 18,
    "max_value": 100
  }
}</code></pre>
                        </div>

                        <!-- Botón para cargar CSV -->
                        <div class="bg-blue-900/20 border border-blue-500/30 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-300 mb-2">💡 Tip: Cargar Opciones desde CSV</h4>
                            <p class="text-xs text-blue-200 mb-3">
                                Para campos select con muchas opciones, puedes crear el campo primero y luego cargar las opciones desde un archivo CSV.
                            </p>
                            <div class="text-xs text-blue-200">
                                <strong>Formato CSV:</strong><br>
                                value,label,description<br>
                                opcion1,Opción 1,Descripción opcional<br>
                                opcion2,Opción 2,Descripción opcional
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-600">
                <a href="{{ route('admin.fields-json.index') }}" 
                   class="admin-button-outline px-6 py-2 rounded-md text-sm font-medium">
                    Cancelar
                </a>
                <button type="submit" 
                        class="admin-button-primary px-6 py-2 rounded-md text-sm font-medium">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Crear Campo
                </button>
            </div>
        </form>
    </div>

    <!-- Información adicional -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Información sobre Campos JSON
        </div>
        <div class="admin-text-secondary text-sm space-y-2">
            <p>• Los campos JSON permiten crear campos individuales con la misma flexibilidad que los formularios completos.</p>
            <p>• Cada campo se almacena en la tabla <code>fields_json</code> y puede ser reutilizado en múltiples formularios.</p>
            <p>• Para campos select, puedes definir las opciones directamente en el JSON o cargarlas desde un archivo CSV después de crear el campo.</p>
            <p>• Las validaciones avanzadas permiten controlar la entrada de datos de manera granular.</p>
            <p>• Los campos pueden tener visibilidad condicional basada en otros campos del formulario.</p>
        </div>
    </div>

    <script>
        // Auto-format JSON as user types
        document.getElementById('field_json').addEventListener('input', function() {
            try {
                const json = JSON.parse(this.value);
                // JSON is valid, you could format it here if needed
            } catch (e) {
                // JSON is invalid, that's okay while typing
            }
        });

        // Add example buttons
        function insertExample(example) {
            document.getElementById('field_json').value = JSON.stringify(example, null, 2);
        }

        // Make examples clickable
        document.querySelectorAll('pre code').forEach(code => {
            code.style.cursor = 'pointer';
            code.addEventListener('click', function() {
                try {
                    const json = JSON.parse(this.textContent);
                    document.getElementById('field_json').value = JSON.stringify(json, null, 2);
                } catch (e) {
                    // Handle error
                }
            });
        });
    </script>
@endsection
