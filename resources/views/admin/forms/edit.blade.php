@extends('layouts.admin')

@section('title', 'Editar Formulario')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <div>
                                <a href="{{ route('admin.forms.index') }}" class="admin-text-muted hover:admin-text-secondary">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    <span class="sr-only">Formularios</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-4 text-sm font-medium admin-text-secondary">Editar Formulario</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 admin-text sm:text-3xl sm:truncate">
                    Editar Formulario: {{ $form->name }}
                </h2>
                <p class="mt-1 text-sm admin-text-secondary">
                    Modifica la configuración del formulario
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="mt-8">
            <form method="POST" action="{{ route('admin.forms.update', $form) }}" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Basic Information Section -->
                <div class="admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Información Básica
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- City -->
                        <div class="admin-field-group">
                            <label for="event_id" class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Evento *
                            </label>
                            <select name="event_id" id="event_id" class="admin-select w-full">
                                <option value="">Seleccionar evento</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id', $form->event_id) == $event->id ? 'selected' : '' }}>
                                        {{ $event->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="admin-field-help">Selecciona el evento para el cual será este formulario</div>
                            @error('event_id')
                                <div class="admin-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="admin-field-group">
                            <label for="name" class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Nombre del Formulario *
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $form->name) }}" 
                                   class="admin-input w-full" placeholder="Ej: Formulario de Registro Municipal">
                            <div class="admin-field-help">Un nombre descriptivo para identificar este formulario</div>
                            @error('name')
                                <div class="admin-field-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="admin-field-group">
                        <label for="description" class="admin-field-label">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            Descripción
                        </label>
                        <textarea name="description" id="description" rows="3" 
                                  class="admin-textarea w-full" placeholder="Describe el propósito y objetivo de este formulario">{{ old('description', $form->description) }}</textarea>
                        <div class="admin-field-help">Proporciona una descripción clara de lo que hace este formulario</div>
                        @error('description')
                            <div class="admin-field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Field Selection Section -->
                <div class="admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Selección de Campos
                    </div>
                    
                    <div class="admin-field-group">
                        <label class="admin-field-label">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Campos Disponibles
                        </label>
                        <div class="admin-card rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <p class="admin-text-secondary text-sm">
                                    Selecciona los campos que quieres agregar al formulario. Se añadirán automáticamente al JSON.
                                </p>
                                <button type="button" id="load-fields" class="admin-button-outline text-xs px-3 py-1">
                                    <svg class="w-3 h-3 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Cargar Campos
                                </button>
                            </div>
                            
                            <div id="fields-container" class="space-y-3">
                                <div class="text-center admin-text-secondary text-sm py-4">
                                    <svg class="w-6 h-6 mx-auto mb-2 admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <p>Haz clic en "Cargar Campos" para ver los campos disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="admin-field-help">
                            Los campos seleccionados se agregarán automáticamente al JSON del formulario con sus opciones correspondientes.
                        </div>
                    </div>
                </div>

                <!-- Form Configuration Section -->
                <div class="admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Configuración del Formulario
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- JSON Editor -->
                        <div class="admin-field-group">
                            <label for="schema_json" class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Estructura del Formulario (JSON) *
                            </label>
                            <div class="relative">
                                <textarea name="schema_json" id="schema_json" rows="12" 
                                          class="admin-textarea w-full font-mono text-sm" 
                                          placeholder='{"fields": [{"key": "nombre", "label": "Nombre Completo", "type": "text", "required": true, "placeholder": "Ingresa tu nombre completo"}]}'>{{ old('schema_json', json_encode($schemaJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                                <!-- JSON Error Indicator -->
                                <div id="json-error-indicator" class="absolute top-2 right-2 hidden">
                                    <div class="flex items-center space-x-2 bg-red-600 text-white px-2 py-1 rounded text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Error JSON</span>
                                    </div>
                                </div>
                                <!-- JSON Success Indicator -->
                                <div id="json-success-indicator" class="absolute top-2 right-2 hidden">
                                    <div class="flex items-center space-x-2 bg-green-600 text-white px-2 py-1 rounded text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>JSON Válido</span>
                                    </div>
                                </div>
                            </div>
                            <!-- JSON Error Details -->
                            <div id="json-error-details" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-red-800">Error en el JSON:</h4>
                                        <p id="json-error-message" class="text-sm text-red-700 mt-1"></p>
                                        <div id="json-error-suggestions" class="text-xs text-red-600 mt-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="admin-field-help">
                                <strong>Tipos de campos disponibles:</strong> text, email, number, date, select, textarea, checkbox<br>
                                <strong>Propiedades requeridas:</strong> key, label, type, required<br>
                                <strong>Propiedades opcionales:</strong> placeholder, options (para select), help, validations<br>
                                <strong>Validaciones disponibles:</strong> min_length, max_length, pattern, format, min_value, max_value, min_date, max_date, required_if, unique, allowed_chars, forbidden_chars, min_words, max_words, decimal_places, step
                            </div>
                            <div class="mt-2 flex space-x-2">
                                <button type="button" id="format-json" class="admin-button-outline text-xs px-3 py-1">
                                    <svg class="w-3 h-3 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Formatear JSON
                                </button>
                                <button type="button" id="validate-json" class="admin-button-outline text-xs px-3 py-1">
                                    <svg class="w-3 h-3 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Validar JSON
                                </button>
                            </div>
                            @error('schema_json')
                                <div class="admin-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Live Preview -->
                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Previsualización en Tiempo Real
                            </label>
                            <div class="admin-card rounded-lg p-4 border-2 border-dashed" style="border-color: var(--color-border);">
                                <div id="form-preview" class="space-y-4">
                                    <div class="text-center admin-text-secondary text-sm py-8">
                                        <svg class="w-8 h-8 mx-auto mb-2 admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p>Los campos aparecerán aquí mientras escribes el JSON</p>
                                    </div>
                                </div>
                            </div>
                            <div class="admin-field-help">
                                Esta previsualización se actualiza automáticamente mientras editas el JSON
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Style Configuration Section -->
                <x-admin.style-editor :styles="old('style_json', $form->styles)" :errors="$errors" />

                <!-- Advanced Configuration Section -->
                <div class="admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                        Configuración Avanzada
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Version -->
                        <div class="admin-field-group">
                            <label for="version" class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v18a1 1 0 01-1 1H4a1 1 0 01-1-1V1a1 1 0 011-1h2a1 1 0 011 1v3m0 0h8"></path>
                                </svg>
                                Versión
                            </label>
                            <input type="number" name="version" id="version" value="{{ old('version', $form->version) }}" 
                                   class="admin-input w-full" min="1">
                            <div class="admin-field-help">Número de versión para control de cambios</div>
                            @error('version')
                                <div class="admin-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Is Active -->
                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Estado del Formulario
                            </label>
                            <div class="flex items-center space-x-3 p-4 admin-card rounded-lg">
                                <input type="checkbox" name="is_active" value="1" 
                                       {{ old('is_active', $form->is_active) ? 'checked' : '' }}
                                       class="w-4 h-4 admin-input rounded focus:ring-2 focus:ring-acid-green">
                                <div>
                                    <div class="admin-text font-medium">Formulario Activo</div>
                                    <div class="admin-text-secondary text-sm">Los formularios activos son visibles para los usuarios</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t" style="border-color: var(--color-border);">
                    <a href="{{ route('admin.forms.index') }}" class="admin-button-outline px-6 py-3 rounded-lg text-sm font-medium">
                        <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" class="admin-button-primary px-6 py-3 rounded-lg text-sm font-medium">
                        <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Actualizar Formulario
                    </button>
                </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Form Statistics -->
        <div class="mt-8">
            <div class="admin-form-section">
                <div class="admin-form-section-title">
                    <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Estadísticas del Formulario
                </div>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div class="admin-card rounded-lg p-6 border-l-4" style="border-left-color: #00ffbd;">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium admin-text-secondary truncate">Total de Envíos</dt>
                                    <dd class="text-2xl font-bold admin-text">{{ $form->formSubmissions->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card rounded-lg p-6 border-l-4" style="border-left-color: #bb2558;">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium admin-text-secondary truncate">Último Envío</dt>
                                    <dd class="text-lg font-semibold admin-text">
                                        @if($form->formSubmissions->count() > 0)
                                            {{ $form->formSubmissions->sortByDesc('submitted_at')->first()->submitted_at->format('d/m/Y') }}
                                        @else
                                            <span class="admin-text-muted">Sin envíos</span>
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card rounded-lg p-6 border-l-4" style="border-left-color: #00ffbd;">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium admin-text-secondary truncate">Fecha de Creación</dt>
                                    <dd class="text-lg font-semibold admin-text">{{ $form->created_at->format('d/m/Y') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
console.log('Script de edición de formulario cargado');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando edición de formulario');
    
    const schemaTextarea = document.getElementById('schema_json');
    const previewContainer = document.getElementById('form-preview');
    const fieldsContainer = document.getElementById('fields-container');
    const loadFieldsBtn = document.getElementById('load-fields');
    
    console.log('Elementos encontrados:', {
        textarea: schemaTextarea,
        preview: previewContainer,
        fieldsContainer: fieldsContainer,
        loadFieldsBtn: loadFieldsBtn
    });
    
    if (!schemaTextarea || !previewContainer) {
        console.error('No se encontraron los elementos necesarios');
        return;
    }

    // Variables globales
    let availableFields = [];
    let selectedFields = new Set();

    // Función para cargar campos disponibles
    async function loadAvailableFields() {
        try {
            console.log('Cargando campos disponibles...');
            const url = '/test-fields'; // Temporal para probar
            console.log('URL:', url);
            
            // Mostrar indicador de carga
            fieldsContainer.innerHTML = `
                <div class="text-center admin-text-secondary text-sm py-4">
                    <svg class="w-6 h-6 mx-auto mb-2 admin-text-muted animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <p>Cargando campos disponibles...</p>
                </div>
            `;
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin' // Incluir cookies de sesión
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('No autorizado. Por favor, inicia sesión nuevamente.');
                } else if (response.status === 403) {
                    throw new Error('No tienes permisos para acceder a esta funcionalidad.');
                } else if (response.status === 404) {
                    throw new Error('Endpoint no encontrado. Verifica que la ruta esté configurada correctamente.');
                } else {
                    throw new Error(`Error del servidor: ${response.status}`);
                }
            }
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (data.success) {
                availableFields = data.fields;
                // Cargar campos existentes del formulario
                loadExistingFields();
                renderFieldsList();
                console.log('Campos cargados:', availableFields);
            } else {
                console.error('Error al cargar campos:', data);
                fieldsContainer.innerHTML = `
                    <div class="text-center admin-alert-error text-sm py-4">
                        <svg class="w-6 h-6 mx-auto mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>Error: ${data.message || 'Respuesta del servidor no exitosa'}</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error al cargar campos:', error);
            fieldsContainer.innerHTML = `
                <div class="text-center admin-alert-error text-sm py-4">
                    <svg class="w-6 h-6 mx-auto mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Error al cargar los campos disponibles</p>
                    <p class="text-xs mt-2">${error.message}</p>
                </div>
            `;
        }
    }

    // Función para cargar campos existentes del formulario
    function loadExistingFields() {
        try {
            const currentJson = schemaTextarea.value.trim();
            console.log('JSON actual del formulario:', currentJson);
            
            if (currentJson) {
                const schema = JSON.parse(currentJson);
                console.log('Schema parseado:', schema);
                
                if (schema.fields && Array.isArray(schema.fields)) {
                    console.log('Campos en el formulario:', schema.fields);
                    
                    // Buscar campos que coincidan con los campos disponibles
                    schema.fields.forEach(field => {
                        console.log('Buscando coincidencia para campo:', field.key);
                        const matchingField = availableFields.find(af => af.code === field.key);
                        if (matchingField) {
                            console.log('Campo encontrado:', matchingField.name, 'ID:', matchingField.id);
                            selectedFields.add(matchingField.id);
                        } else {
                            console.log('No se encontró coincidencia para:', field.key);
                        }
                    });
                    console.log('Campos existentes cargados:', Array.from(selectedFields));
                }
            }
        } catch (error) {
            console.error('Error al cargar campos existentes:', error);
        }
    }

    // Función para renderizar la lista de campos
    function renderFieldsList() {
        console.log('Renderizando lista de campos. Total disponibles:', availableFields.length);
        console.log('Campos seleccionados actualmente:', Array.from(selectedFields));
        
        if (availableFields.length === 0) {
            fieldsContainer.innerHTML = `
                <div class="text-center admin-text-secondary text-sm py-4">
                    <svg class="w-6 h-6 mx-auto mb-2 admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>No hay campos disponibles. Crea algunos campos primero.</p>
                </div>
            `;
            return;
        }

        fieldsContainer.innerHTML = '';
        
        availableFields.forEach(field => {
            const fieldElement = createFieldElement(field);
            fieldsContainer.appendChild(fieldElement);
            
            // Verificar el estado del checkbox después de crearlo
            const checkbox = fieldElement.querySelector('.field-checkbox');
            console.log(`Checkbox para ${field.name}: checked = ${checkbox.checked}, selectedFields.has(${field.id}) = ${selectedFields.has(field.id)}`);
        });
    }

    // Función para crear un elemento de campo
    function createFieldElement(field) {
        const fieldDiv = document.createElement('div');
        fieldDiv.className = 'admin-card rounded-lg p-4 border border-gray-600 hover:border-acid-green transition-colors';
        fieldDiv.dataset.fieldId = field.id;

        const isSelected = selectedFields.has(field.id);
        
        fieldDiv.innerHTML = `
            <div class="flex items-start space-x-3">
                <input type="checkbox" 
                       id="field-${field.id}" 
                       class="field-checkbox mt-1 h-4 w-4 text-acid-green focus:ring-acid-green border-gray-600 rounded bg-gray-700"
                       ${isSelected ? 'checked' : ''}>
                <div class="flex-1">
                    <label for="field-${field.id}" class="cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="admin-text font-medium">${field.name}</h4>
                                <p class="admin-text-secondary text-sm">${field.description || 'Sin descripción'}</p>
                                <div class="flex items-center mt-2 space-x-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-gray-300">
                                        ${field.code}
                                    </span>
                                    <span class="admin-text-secondary text-xs">
                                        ${field.options.length} opciones
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" class="view-options-btn text-acid-green hover:text-acid-green-light text-sm">
                                    Ver opciones
                                </button>
                            </div>
                        </div>
                    </label>
                    <div class="options-preview mt-2 hidden">
                        <div class="admin-text-secondary text-xs mb-1">Opciones:</div>
                        <div class="flex flex-wrap gap-1">
                            ${field.options.map(option => `
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-600 text-gray-300">
                                    ${option.label}
                                </span>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Event listeners
        const checkbox = fieldDiv.querySelector('.field-checkbox');
        const viewOptionsBtn = fieldDiv.querySelector('.view-options-btn');
        const optionsPreview = fieldDiv.querySelector('.options-preview');

        checkbox.addEventListener('change', function() {
            console.log(`Checkbox cambiado para campo ${field.name} (ID: ${field.id}): ${this.checked}`);
            if (this.checked) {
                selectedFields.add(field.id);
                console.log('Campo agregado a selección. Total seleccionados:', selectedFields.size);
            } else {
                selectedFields.delete(field.id);
                console.log('Campo removido de selección. Total seleccionados:', selectedFields.size);
            }
            updateFormJSON();
        });

        viewOptionsBtn.addEventListener('click', function() {
            optionsPreview.classList.toggle('hidden');
            this.textContent = optionsPreview.classList.contains('hidden') ? 'Ver opciones' : 'Ocultar opciones';
        });

        return fieldDiv;
    }

    // Función para actualizar el JSON del formulario
    function updateFormJSON() {
        try {
            let currentSchema = { fields: [] };
            
            // Intentar parsear el JSON actual
            const currentJson = schemaTextarea.value.trim();
            console.log('JSON actual antes de actualizar:', currentJson);
            
            if (currentJson) {
                currentSchema = JSON.parse(currentJson);
                if (!currentSchema.fields) {
                    currentSchema.fields = [];
                }
            }

            console.log('Campos seleccionados:', Array.from(selectedFields));

            // Filtrar campos que no están en la selección actual
            // Mantener solo campos que no fueron agregados por el selector
            currentSchema.fields = currentSchema.fields.filter(field => {
                const wasAddedBySelector = field._addedBySelector === true;
                console.log(`Campo ${field.key}: _addedBySelector = ${wasAddedBySelector}`);
                return !wasAddedBySelector;
            });

            console.log('Campos después de filtrar:', currentSchema.fields);

            // Agregar campos seleccionados
            selectedFields.forEach(fieldId => {
                const field = availableFields.find(f => f.id === fieldId);
                if (field) {
                    console.log('Agregando campo:', field.name);
                    
                    const fieldConfig = {
                        key: field.code,
                        label: field.name,
                        type: 'select', // Por defecto, los campos de la base de datos son select
                        required: false,
                        _addedBySelector: true,
                        options: field.options.map(option => ({
                            value: option.value,
                            label: option.label
                        }))
                    };

                    // Si no tiene opciones, cambiar a tipo text
                    if (field.options.length === 0) {
                        fieldConfig.type = 'text';
                        delete fieldConfig.options;
                    }

                    currentSchema.fields.push(fieldConfig);
                }
            });

            console.log('Schema final:', currentSchema);

            // Actualizar el textarea
            schemaTextarea.value = JSON.stringify(currentSchema, null, 2);
            
            // Actualizar la previsualización
            updatePreview();
            
            console.log('JSON actualizado exitosamente');
        } catch (error) {
            console.error('Error al actualizar JSON:', error);
        }
    }

    // Función para renderizar un campo individual
    function renderField(field) {
        console.log('Renderizando campo:', field);
        
        const fieldDiv = document.createElement('div');
        fieldDiv.className = 'admin-field-group';
        
        // Crear label
        const label = document.createElement('label');
        label.className = 'admin-field-label';
        label.textContent = field.label;
        if (field.required) {
            const requiredSpan = document.createElement('span');
            requiredSpan.className = 'text-red-500 ml-1';
            requiredSpan.textContent = '*';
            label.appendChild(requiredSpan);
        }
        
        // Crear input según el tipo
        let input;
        switch (field.type) {
            case 'text':
            case 'email':
            case 'number':
            case 'date':
                input = document.createElement('input');
                input.type = field.type;
                input.className = 'admin-input w-full';
                if (field.placeholder) {
                    input.placeholder = field.placeholder;
                }
                break;
                
            case 'textarea':
                input = document.createElement('textarea');
                input.rows = 3;
                input.className = 'admin-textarea w-full';
                if (field.placeholder) {
                    input.placeholder = field.placeholder;
                }
                break;
                
            case 'select':
                input = document.createElement('select');
                input.className = 'admin-select w-full';
                
                // Agregar opción por defecto
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Seleccionar...';
                input.appendChild(defaultOption);
                
                // Agregar opciones si existen
                if (field.options && Array.isArray(field.options)) {
                    field.options.forEach(option => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option.value || option;
                        optionElement.textContent = option.label || option;
                        input.appendChild(optionElement);
                    });
                }
                break;
                
            case 'checkbox':
                const checkboxContainer = document.createElement('div');
                checkboxContainer.className = 'flex items-center space-x-3 p-3 admin-card rounded-lg';
                
                input = document.createElement('input');
                input.type = 'checkbox';
                input.className = 'w-4 h-4 admin-input rounded focus:ring-2 focus:ring-acid-green';
                
                const checkboxLabel = document.createElement('label');
                checkboxLabel.className = 'admin-text font-medium cursor-pointer';
                checkboxLabel.textContent = field.label;
                
                checkboxContainer.appendChild(input);
                checkboxContainer.appendChild(checkboxLabel);
                
                fieldDiv.appendChild(checkboxContainer);
                return fieldDiv;
                
            default:
                input = document.createElement('input');
                input.type = 'text';
                input.className = 'admin-input w-full';
                if (field.placeholder) {
                    input.placeholder = field.placeholder;
                }
        }
        
        // Agregar elementos al contenedor
        fieldDiv.appendChild(label);
        if (field.type !== 'checkbox') {
            fieldDiv.appendChild(input);
        }
        
        // Agregar texto de ayuda si existe
        if (field.help) {
            const helpText = document.createElement('div');
            helpText.className = 'admin-field-help';
            helpText.textContent = field.help;
            fieldDiv.appendChild(helpText);
        }
        
        return fieldDiv;
    }
    
    // Función para validar JSON en tiempo real
    function validateJSON() {
        const jsonText = schemaTextarea.value.trim();
        const errorIndicator = document.getElementById('json-error-indicator');
        const successIndicator = document.getElementById('json-success-indicator');
        const errorDetails = document.getElementById('json-error-details');
        const errorMessage = document.getElementById('json-error-message');
        const errorSuggestions = document.getElementById('json-error-suggestions');
        
        // Ocultar indicadores
        errorIndicator.classList.add('hidden');
        successIndicator.classList.add('hidden');
        errorDetails.classList.add('hidden');
        
        if (!jsonText) {
            return { valid: false, error: 'El JSON no puede estar vacío' };
        }
        
        try {
            const schema = JSON.parse(jsonText);
            
            // Validar estructura básica
            if (!schema.fields || !Array.isArray(schema.fields)) {
                showJSONError('El JSON debe contener un array "fields"', [
                    'Asegúrate de que tu JSON tenga la estructura: {"fields": []}',
                    'El campo "fields" debe ser un array, no un objeto'
                ]);
                return { valid: false, error: 'Estructura inválida' };
            }
            
            // Validar cada campo
            const fieldErrors = [];
            schema.fields.forEach((field, index) => {
                const fieldPrefix = `Campo ${index + 1}`;
                
                if (!field.key || field.key.trim() === '') {
                    fieldErrors.push(`${fieldPrefix}: Falta la propiedad "key"`);
                }
                
                if (!field.label || field.label.trim() === '') {
                    fieldErrors.push(`${fieldPrefix}: Falta la propiedad "label"`);
                }
                
                if (!field.type || field.type.trim() === '') {
                    fieldErrors.push(`${fieldPrefix}: Falta la propiedad "type"`);
                } else {
                    const validTypes = ['text', 'email', 'number', 'textarea', 'select', 'checkbox', 'date'];
                    if (!validTypes.includes(field.type)) {
                        fieldErrors.push(`${fieldPrefix}: Tipo "${field.type}" no válido. Tipos válidos: ${validTypes.join(', ')}`);
                    }
                }
                
                // Validar campos select
                if (field.type === 'select') {
                    if (!field.options || !Array.isArray(field.options) || field.options.length === 0) {
                        fieldErrors.push(`${fieldPrefix}: Los campos de tipo "select" deben tener opciones`);
                    }
                }
                
                // Validar validaciones si existen
                if (field.validations) {
                    const validationErrors = validateFieldValidations(field.validations, fieldPrefix);
                    fieldErrors.push(...validationErrors);
                }
            });
            
            if (fieldErrors.length > 0) {
                showJSONError('Errores en la estructura de campos:', fieldErrors);
                return { valid: false, error: 'Campos inválidos' };
            }
            
            // JSON válido
            showJSONSuccess();
            return { valid: true, schema: schema };
            
        } catch (error) {
            let errorMsg = error.message;
            let suggestions = [];
            
            // Proporcionar sugerencias específicas según el tipo de error
            if (errorMsg.includes('Unexpected token')) {
                suggestions = [
                    'Verifica que todas las comillas estén cerradas correctamente',
                    'Asegúrate de que no haya comas extra al final de objetos o arrays',
                    'Revisa que los nombres de propiedades estén entre comillas dobles'
                ];
            } else if (errorMsg.includes('Unexpected end of JSON input')) {
                suggestions = [
                    'El JSON parece estar incompleto',
                    'Verifica que todos los corchetes y llaves estén cerrados',
                    'Asegúrate de que no haya texto después del último carácter del JSON'
                ];
            } else if (errorMsg.includes('Expected property name')) {
                suggestions = [
                    'Los nombres de propiedades deben estar entre comillas dobles',
                    'Verifica que no haya comas antes de los nombres de propiedades'
                ];
            }
            
            showJSONError(errorMsg, suggestions);
            return { valid: false, error: errorMsg };
        }
    }
    
    // Función para validar validaciones de campos
    function validateFieldValidations(validations, fieldPrefix) {
        const errors = [];
        
        // Validar fechas
        if (validations.min_date && !isValidDate(validations.min_date)) {
            errors.push(`${fieldPrefix}: Fecha mínima inválida. Formato: YYYY-MM-DD`);
        }
        if (validations.max_date && !isValidDate(validations.max_date)) {
            errors.push(`${fieldPrefix}: Fecha máxima inválida. Formato: YYYY-MM-DD`);
        }
        
        // Validar valores numéricos
        if (validations.min_length && (!isNumeric(validations.min_length) || validations.min_length < 0)) {
            errors.push(`${fieldPrefix}: min_length debe ser un número positivo`);
        }
        if (validations.max_length && (!isNumeric(validations.max_length) || validations.max_length < 0)) {
            errors.push(`${fieldPrefix}: max_length debe ser un número positivo`);
        }
        if (validations.min_value && !isNumeric(validations.min_value)) {
            errors.push(`${fieldPrefix}: min_value debe ser un número`);
        }
        if (validations.max_value && !isNumeric(validations.max_value)) {
            errors.push(`${fieldPrefix}: max_value debe ser un número`);
        }
        
        // Validar edades
        if (validations.min_age && (!isNumeric(validations.min_age) || validations.min_age < 0)) {
            errors.push(`${fieldPrefix}: min_age debe ser un número positivo`);
        }
        if (validations.max_age && (!isNumeric(validations.max_age) || validations.max_age < 0)) {
            errors.push(`${fieldPrefix}: max_age debe ser un número positivo`);
        }
        
        return errors;
    }
    
    // Funciones auxiliares
    function isValidDate(dateString) {
        const regex = /^\d{4}-\d{2}-\d{2}$/;
        if (!regex.test(dateString)) return false;
        const date = new Date(dateString);
        return date instanceof Date && !isNaN(date);
    }
    
    function isNumeric(value) {
        return !isNaN(parseFloat(value)) && isFinite(value);
    }
    
    // Función para mostrar error JSON
    function showJSONError(message, suggestions = []) {
        const errorIndicator = document.getElementById('json-error-indicator');
        const successIndicator = document.getElementById('json-success-indicator');
        const errorDetails = document.getElementById('json-error-details');
        const errorMessage = document.getElementById('json-error-message');
        const errorSuggestions = document.getElementById('json-error-suggestions');
        
        errorIndicator.classList.remove('hidden');
        successIndicator.classList.add('hidden');
        errorDetails.classList.remove('hidden');
        
        errorMessage.textContent = message;
        
        if (suggestions.length > 0) {
            errorSuggestions.innerHTML = '<strong>Sugerencias:</strong><ul class="list-disc list-inside mt-1">' +
                suggestions.map(s => `<li>${s}</li>`).join('') + '</ul>';
        } else {
            errorSuggestions.innerHTML = '';
        }
        
        // Cambiar estilo del textarea
        schemaTextarea.classList.add('border-red-500', 'focus:border-red-500');
        schemaTextarea.classList.remove('border-gray-600', 'focus:border-acid-green');
    }
    
    // Función para mostrar éxito JSON
    function showJSONSuccess() {
        const errorIndicator = document.getElementById('json-error-indicator');
        const successIndicator = document.getElementById('json-success-indicator');
        const errorDetails = document.getElementById('json-error-details');
        
        errorIndicator.classList.add('hidden');
        successIndicator.classList.remove('hidden');
        errorDetails.classList.add('hidden');
        
        // Restaurar estilo del textarea
        schemaTextarea.classList.remove('border-red-500', 'focus:border-red-500');
        schemaTextarea.classList.add('border-gray-600', 'focus:border-acid-green');
    }
    
    // Función para formatear JSON
    function formatJSON() {
        const validation = validateJSON();
        if (validation.valid) {
            schemaTextarea.value = JSON.stringify(validation.schema, null, 2);
            updatePreview();
        } else {
            alert('No se puede formatear JSON inválido. Corrige los errores primero.');
        }
    }
    
    // Función para actualizar la previsualización
    function updatePreview() {
        console.log('Actualizando previsualización');
        
        const validation = validateJSON();
        
        if (!validation.valid) {
            previewContainer.innerHTML = `
                <div class="text-center admin-alert-error text-sm py-8">
                    <svg class="w-8 h-8 mx-auto mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Error en el JSON: ${validation.error}</p>
                    <p class="text-xs mt-2">Corrige los errores para ver la previsualización</p>
                </div>
            `;
            return;
        }
        
        const schema = validation.schema;
        console.log('Schema parseado:', schema);
        
        if (schema.fields.length === 0) {
            previewContainer.innerHTML = `
                <div class="text-center admin-text-secondary text-sm py-8">
                    <svg class="w-8 h-8 mx-auto mb-2 admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>No hay campos definidos. Agrega campos al array "fields"</p>
                </div>
            `;
            return;
        }
        
        // Limpiar contenedor
        previewContainer.innerHTML = '';
        
        // Renderizar cada campo
        schema.fields.forEach(field => {
            if (field.key && field.label && field.type) {
                const fieldElement = renderField(field);
                previewContainer.appendChild(fieldElement);
            }
        });
        
        console.log('Previsualización actualizada con', schema.fields.length, 'campos');
    }
    
    // Event listeners
    schemaTextarea.addEventListener('input', updatePreview);
    schemaTextarea.addEventListener('paste', function() {
        // Pequeño delay para que se procese el paste
        setTimeout(updatePreview, 10);
    });

    // Event listener para cargar campos
    if (loadFieldsBtn) {
        loadFieldsBtn.addEventListener('click', loadAvailableFields);
    }
    
    // Event listener para formatear JSON
    const formatJsonBtn = document.getElementById('format-json');
    if (formatJsonBtn) {
        formatJsonBtn.addEventListener('click', formatJSON);
    }
    
    // Event listener para validar JSON
    const validateJsonBtn = document.getElementById('validate-json');
    if (validateJsonBtn) {
        validateJsonBtn.addEventListener('click', function() {
            const validation = validateJSON();
            if (validation.valid) {
                alert('✅ JSON válido! El formulario se puede guardar correctamente.');
            } else {
                alert('❌ JSON inválido: ' + validation.error);
            }
        });
    }
    
    // Actualizar previsualización inicial
    console.log('Ejecutando previsualización inicial');
    updatePreview();
});
</script>
@endpush
@endsection
