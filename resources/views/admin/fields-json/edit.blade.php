@extends('layouts.admin')

@section('title', 'Editar Campo JSON')

@section('page-title', 'Editar Campo JSON')
@section('page-description', 'Modifica la configuración de un campo JSON existente')

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

        <form method="POST" action="{{ route('admin.fields-json.update', $field) }}">
            @csrf
            @method('PUT')

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
                              required>{{ old('field_json', json_encode([
                                'key' => $field->key,
                                'label' => $field->label,
                                'type' => $field->type,
                                'required' => $field->required,
                                'placeholder' => $field->placeholder,
                                'validations' => $field->validations,
                                'visible' => $field->visible,
                                'dynamic_options' => $field->dynamic_options,
                                'default_value' => $field->default_value,
                                'description' => $field->description,
                                'options' => $field->formFieldOrders->first()?->formCategory?->formOptions?->map(function($option) {
                                    return [
                                        'value' => $option->value,
                                        'label' => $option->label,
                                        'description' => $option->description,
                                        'is_active' => $option->is_active
                                    ];
                                })->toArray() ?? []
                            ], JSON_PRETTY_PRINT)) }}</textarea>
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

                <!-- CSV Upload for Select Fields -->
                @if($field->type === 'select')
                <div class="admin-field-group">
                    <label class="admin-field-label">
                        <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Cargar Opciones desde CSV
                    </label>
                    
                    <form method="POST" action="{{ route('admin.fields-json.upload-csv') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="field_id" value="{{ $field->id }}">
                        
                        <div class="flex items-center space-x-4">
                            <input type="file" 
                                   name="csv_file" 
                                   accept=".csv,.txt"
                                   class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                                   required>
                            <button type="submit" 
                                    class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Cargar CSV
                            </button>
                        </div>
                        
                        <div class="admin-field-help">
                            <strong>Formato CSV requerido:</strong><br>
                            • Primera columna: value (valor interno)<br>
                            • Segunda columna: label (texto mostrado)<br>
                            • Tercera columna: description (opcional)<br>
                            <strong>Ejemplo:</strong><br>
                            <code class="text-xs">masculino,Masculino,Persona de género masculino<br>femenino,Femenino,Persona de género femenino</code>
                        </div>
                    </form>

                    <!-- Current Options -->
                    @php
                        $currentOptions = $field->formFieldOrders->first()?->formCategory?->formOptions ?? collect();
                    @endphp
                    
                    @if($currentOptions->count() > 0)
                    <div class="mt-6">
                        <h4 class="text-sm font-medium admin-text mb-3">Opciones Actuales</h4>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            @foreach($currentOptions as $option)
                            <div class="flex items-center justify-between bg-gray-800 p-2 rounded">
                                <div>
                                    <span class="text-sm font-medium admin-text">{{ $option->label }}</span>
                                    <span class="text-xs admin-text-secondary ml-2">({{ $option->value }})</span>
                                </div>
                                <span class="text-xs {{ $option->is_active ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $option->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Field Information -->
                <div class="admin-field-group">
                    <label class="admin-field-label">
                        <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Información del Campo
                    </label>
                    
                    <div class="space-y-3">
                        <div class="bg-gray-800 p-3 rounded-lg">
                            <div class="text-sm admin-text-secondary">Clave del Campo</div>
                            <div class="text-sm font-medium admin-text">{{ $field->key }}</div>
                        </div>
                        
                        <div class="bg-gray-800 p-3 rounded-lg">
                            <div class="text-sm admin-text-secondary">Tipo</div>
                            <div class="text-sm font-medium admin-text">{{ ucfirst($field->type) }}</div>
                        </div>
                        
                        <div class="bg-gray-800 p-3 rounded-lg">
                            <div class="text-sm admin-text-secondary">Estado</div>
                            <div class="text-sm font-medium {{ $field->is_active ? 'text-green-400' : 'text-red-400' }}">
                                {{ $field->is_active ? 'Activo' : 'Inactivo' }}
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 p-3 rounded-lg">
                            <div class="text-sm admin-text-secondary">Creado</div>
                            <div class="text-sm font-medium admin-text">{{ $field->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        
                        @if($field->formFieldOrders()->count() > 0)
                        <div class="bg-blue-900/20 border border-blue-500/30 p-3 rounded-lg">
                            <div class="text-sm text-blue-300">⚠️ Campo en Uso</div>
                            <div class="text-xs text-blue-200 mt-1">
                                Este campo está siendo utilizado en {{ $field->formFieldOrders()->count() }} formulario(s).
                            </div>
                        </div>
                        @endif
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
                    Actualizar Campo
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
            Información sobre Edición
        </div>
        <div class="admin-text-secondary text-sm space-y-2">
            <p>• Al editar un campo JSON, se actualizarán automáticamente todas las referencias en los formularios que lo utilicen.</p>
            <p>• Para campos select, puedes modificar las opciones directamente en el JSON o usar la función de carga CSV.</p>
            <p>• La carga CSV reemplazará todas las opciones existentes del campo.</p>
            <p>• Los cambios en las validaciones se aplicarán inmediatamente a todos los formularios que usen este campo.</p>
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
    </script>
@endsection
