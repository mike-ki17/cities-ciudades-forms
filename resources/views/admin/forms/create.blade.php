@extends('layouts.admin')

@section('title', 'Crear Formulario')

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
                                <span class="ml-4 text-sm font-medium admin-text-secondary">Crear Formulario</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 admin-text sm:text-3xl sm:truncate">
                    Crear Nuevo Formulario
                </h2>
                <p class="mt-1 text-sm admin-text-secondary">
                    Crea un nuevo formulario dinámico para una ciudad específica
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="mt-8">
            <form method="POST" action="{{ route('admin.forms.store') }}" class="space-y-8">
                @csrf
                
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
                                Ciudad *
                            </label>
                            <select name="event_id" id="event_id" class="admin-select w-full">
                                <option value="">Seleccionar evento</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="admin-field-help">Selecciona la ciudad para la cual será este formulario</div>
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
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
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
                                  class="admin-textarea w-full" placeholder="Describe el propósito y objetivo de este formulario">{{ old('description') }}</textarea>
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
                    
                    <!-- Validation Help Section -->
                    <div class="admin-field-group mb-6">
                        <div class="admin-card rounded-lg p-4 border-l-4" style="border-left-color: #00ffbd;">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 mt-0.5" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="admin-text font-medium mb-2">Validaciones Disponibles</h4>
                                    <p class="admin-text-secondary text-sm mb-3">
                                        Puedes agregar validaciones avanzadas a cada campo del formulario. Las validaciones se aplican automáticamente cuando los usuarios envían el formulario.
                                    </p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 text-xs">
                                        <div class="flex items-center space-x-1">
                                            <code class="admin-alert-success px-1 py-0.5 rounded text-xs">min_length</code>
                                            <span class="admin-text-secondary">Longitud mínima</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <code class="admin-alert-success px-1 py-0.5 rounded text-xs">max_length</code>
                                            <span class="admin-text-secondary">Longitud máxima</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <code class="admin-alert-success px-1 py-0.5 rounded text-xs">pattern</code>
                                            <span class="admin-text-secondary">Expresión regular</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <code class="admin-alert-success px-1 py-0.5 rounded text-xs">format</code>
                                            <span class="admin-text-secondary">Formato predefinido</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <code class="admin-alert-success px-1 py-0.5 rounded text-xs">min_value</code>
                                            <span class="admin-text-secondary">Valor mínimo</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <code class="admin-alert-success px-1 py-0.5 rounded text-xs">max_value</code>
                                            <span class="admin-text-secondary">Valor máximo</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <code class="admin-alert-success px-1 py-0.5 rounded text-xs">min_date</code>
                                            <span class="admin-text-secondary">Fecha mínima</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <code class="admin-alert-success px-1 py-0.5 rounded text-xs">max_date</code>
                                            <span class="admin-text-secondary">Fecha máxima</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <code class="admin-alert-success px-1 py-0.5 rounded text-xs">required_if</code>
                                            <span class="admin-text-secondary">Requerido condicional</span>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="button" id="show-validation-examples" class="admin-button-outline text-xs px-3 py-1">
                                            <svg class="w-3 h-3 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Ver Ejemplos Completos
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                          placeholder='{"fields": [{"key": "nombre", "label": "Nombre Completo", "type": "text", "required": true, "placeholder": "Ingresa tu nombre completo"}]}'>{{ old('schema_json', '{"fields": []}') }}</textarea>
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
                                <strong>Tipos de campos disponibles:</strong> text, email, number, date, select, textarea, checkbox, section, tel<br>
                                <strong>Propiedades requeridas:</strong> key, label, type, required (excepto para section)<br>
                                <strong>Propiedades opcionales:</strong> placeholder, options (para select), help, validations, level (para section), description<br>
                                <strong>Validaciones disponibles:</strong> min_length, max_length, pattern, format, min_value, max_value, min_date, max_date, required_if, unique, allowed_chars, forbidden_chars, min_words, max_words, decimal_places, step<br>
                                <strong>Para campos section:</strong> level (h1, h2, h3), description (texto descriptivo)
                            </div>
                            <div class="mt-2 flex space-x-2">
                                <button type="button" id="load-example" class="admin-button-outline text-xs px-3 py-1">
                                    <svg class="w-3 h-3 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Cargar Ejemplo
                                </button>
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
                <x-admin.style-editor :styles="old('style_json', [])" :errors="$errors" />

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
                            <input type="number" name="version" id="version" value="{{ old('version', 1) }}" 
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
                                       {{ old('is_active', true) ? 'checked' : '' }}
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
                        Crear Formulario
                    </button>
                </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- JSON Schema Helper -->
        <div class="mt-8">
            <div class="admin-form-section">
                <div class="admin-form-section-title">
                    <svg class="w-5 h-5 inline-block mr-2" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Ayuda: Estructura JSON
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="admin-field-group">
                        <h4 class="admin-field-label mb-3">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Tipos de Campos Disponibles
                        </h4>
                        <div class="admin-card rounded-lg p-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="flex items-center space-x-2">
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">text</code>
                                    <span class="admin-text-secondary text-sm">Campo de texto</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">email</code>
                                    <span class="admin-text-secondary text-sm">Campo de email</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">number</code>
                                    <span class="admin-text-secondary text-sm">Campo numérico</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">textarea</code>
                                    <span class="admin-text-secondary text-sm">Área de texto</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">select</code>
                                    <span class="admin-text-secondary text-sm">Lista desplegable</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">checkbox</code>
                                    <span class="admin-text-secondary text-sm">Casilla de verificación</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">date</code>
                                    <span class="admin-text-secondary text-sm">Campo de fecha</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="admin-field-group">
                        <h4 class="admin-field-label mb-3">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            Ejemplo de Estructura
                        </h4>
                        <div class="admin-card rounded-lg p-4">
                            <pre class="text-xs admin-text-secondary overflow-x-auto"><code>{
  "fields": [
    {
      "key": "nombre",
      "label": "Nombre Completo",
      "type": "text",
      "required": true,
      "placeholder": "Ingrese su nombre",
      "validations": {
        "min_length": 2,
        "max_length": 100
      }
    },
    {
      "key": "email",
      "label": "Correo Electrónico",
      "type": "email",
      "required": true,
      "placeholder": "ejemplo@correo.com",
      "validations": {
        "format": "email",
        "unique": true
      }
    },
    {
      "key": "edad",
      "label": "Edad",
      "type": "number",
      "required": false,
      "validations": {
        "min_value": 18,
        "max_value": 100
      }
    }
  ]
}</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Validation Examples Section -->
        <div class="mt-8">
            <div class="admin-form-section">
                <div class="admin-form-section-title">
                    <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Ejemplos de Validaciones Avanzadas
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="admin-field-group">
                        <h4 class="admin-field-label mb-3">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Validaciones de Texto y Formato
                        </h4>
                        <div class="admin-card rounded-lg p-4">
                            <div class="space-y-3 text-xs">
                                <div>
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">DNI</code>
                                    <pre class="mt-1 text-xs admin-text-secondary">"validations": {
  "format": "dni",
  "pattern": "^[0-9]{8}[A-Z]$"
}</pre>
                                </div>
                                <div>
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">Teléfono</code>
                                    <pre class="mt-1 text-xs admin-text-secondary">"validations": {
  "format": "phone",
  "pattern": "^[+]?[0-9]{9,15}$"
}</pre>
                                </div>
                                <div>
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">Longitud</code>
                                    <pre class="mt-1 text-xs admin-text-secondary">"validations": {
  "min_length": 2,
  "max_length": 100
}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="admin-field-group">
                        <h4 class="admin-field-label mb-3">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Validaciones Numéricas y de Fecha
                        </h4>
                        <div class="admin-card rounded-lg p-4">
                            <div class="space-y-3 text-xs">
                                <div>
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">Rango Numérico</code>
                                    <pre class="mt-1 text-xs admin-text-secondary">"validations": {
  "min_value": 18,
  "max_value": 100,
  "step": 1
}</pre>
                                </div>
                                <div>
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">Edad por Fecha</code>
                                    <pre class="mt-1 text-xs admin-text-secondary">"validations": {
  "min_age": 18,
  "max_age": 65
}</pre>
                                </div>
                                <div>
                                    <code class="admin-alert-success px-2 py-1 rounded text-xs">Rango de Fechas</code>
                                    <pre class="mt-1 text-xs admin-text-secondary">"validations": {
  "min_date": "2024-01-01",
  "max_date": "2024-12-31"
}</pre>
                                </div>
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
console.log('Script de previsualización cargado');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando previsualización');
    
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
            const url = '/api/fields/available';
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
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('No autorizado. Por favor, inicia sesión nuevamente.');
                } else if (response.status === 403) {
                    throw new Error('No tienes permisos para acceder a esta funcionalidad.');
                } else if (response.status === 404) {
                    throw new Error('Endpoint no encontrado. Verifica que la ruta esté configurada correctamente.');
                } else if (response.status === 500) {
                    throw new Error('Error interno del servidor. Verifica los logs.');
                } else {
                    throw new Error(`Error del servidor: ${response.status}`);
                }
            }
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (data.success) {
                availableFields = data.fields;
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

    // Función para renderizar la lista de campos
    function renderFieldsList() {
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
            if (this.checked) {
                selectedFields.add(field.id);
            } else {
                selectedFields.delete(field.id);
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
            if (currentJson) {
                currentSchema = JSON.parse(currentJson);
                if (!currentSchema.fields) {
                    currentSchema.fields = [];
                }
            }

            // Filtrar campos que no están en la selección actual
            currentSchema.fields = currentSchema.fields.filter(field => {
                // Mantener campos que no fueron agregados por el selector
                return !field._addedBySelector;
            });

            // Agregar campos seleccionados
            selectedFields.forEach(fieldId => {
                const field = availableFields.find(f => f.id === fieldId);
                if (field) {
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

            // Actualizar el textarea
            schemaTextarea.value = JSON.stringify(currentSchema, null, 2);
            
            // Actualizar la previsualización
            updatePreview();
            
            console.log('JSON actualizado con campos seleccionados:', selectedFields);
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
                    const validTypes = ['text', 'email', 'number', 'textarea', 'select', 'checkbox', 'date', 'section', 'tel'];
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
    
    // Botón para cargar ejemplo
    const loadExampleBtn = document.getElementById('load-example');
    if (loadExampleBtn) {
        loadExampleBtn.addEventListener('click', function() {
            console.log('Cargando ejemplo');
            const exampleJson = {
                "fields": [
                    {
                        "key": "nombre",
                        "label": "Nombre Completo",
                        "type": "text",
                        "required": true,
                        "placeholder": "Ingresa tu nombre completo",
                        "help": "Este campo es obligatorio"
                    },
                    {
                        "key": "email",
                        "label": "Correo Electrónico",
                        "type": "email",
                        "required": true,
                        "placeholder": "ejemplo@correo.com"
                    },
                    {
                        "key": "telefono",
                        "label": "Teléfono",
                        "type": "text",
                        "required": false,
                        "placeholder": "Número de teléfono"
                    },
                    {
                        "key": "fecha_nacimiento",
                        "label": "Fecha de Nacimiento",
                        "type": "date",
                        "required": false
                    },
                    {
                        "key": "genero",
                        "label": "Género",
                        "type": "select",
                        "required": false,
                        "options": [
                            {"value": "masculino", "label": "Masculino"},
                            {"value": "femenino", "label": "Femenino"},
                            {"value": "otro", "label": "Otro"}
                        ]
                    },
                    {
                        "key": "comentarios",
                        "label": "Comentarios Adicionales",
                        "type": "textarea",
                        "required": false,
                        "placeholder": "Escribe aquí cualquier comentario adicional..."
                    },
                    {
                        "key": "acepta_terminos",
                        "label": "Acepto los términos y condiciones",
                        "type": "checkbox",
                        "required": true
                    }
                ]
            };
            
            schemaTextarea.value = JSON.stringify(exampleJson, null, 2);
            updatePreview();
        });
    }
    
    // Botón para mostrar ejemplos de validaciones
    const showValidationExamplesBtn = document.getElementById('show-validation-examples');
    if (showValidationExamplesBtn) {
        showValidationExamplesBtn.addEventListener('click', function() {
            const validationExamples = {
                "fields": [
                    {
                        "key": "nombre",
                        "label": "Nombre Completo",
                        "type": "text",
                        "required": true,
                        "placeholder": "Ingrese su nombre completo",
                        "validations": {
                            "min_length": 2,
                            "max_length": 100,
                            "allowed_chars": "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz "
                        }
                    },
                    {
                        "key": "email",
                        "label": "Correo Electrónico",
                        "type": "email",
                        "required": true,
                        "placeholder": "ejemplo@correo.com",
                        "validations": {
                            "format": "email",
                            "unique": true
                        }
                    },
                    {
                        "key": "dni",
                        "label": "DNI",
                        "type": "text",
                        "required": true,
                        "placeholder": "12345678A",
                        "validations": {
                            "format": "dni",
                            "pattern": "^[0-9]{8}[A-Z]$"
                        }
                    },
                    {
                        "key": "telefono",
                        "label": "Teléfono",
                        "type": "text",
                        "required": false,
                        "placeholder": "+34 123 456 789",
                        "validations": {
                            "format": "phone",
                            "pattern": "^[+]?[0-9]{9,15}$"
                        }
                    },
                    {
                        "key": "fecha_nacimiento",
                        "label": "Fecha de Nacimiento",
                        "type": "date",
                        "required": true,
                        "validations": {
                            "min_age": 18,
                            "max_age": 65
                        }
                    },
                    {
                        "key": "edad",
                        "label": "Edad",
                        "type": "number",
                        "required": false,
                        "validations": {
                            "min_value": 18,
                            "max_value": 100,
                            "step": 1
                        }
                    },
                    {
                        "key": "precio",
                        "label": "Precio",
                        "type": "number",
                        "required": false,
                        "validations": {
                            "format": "currency",
                            "decimal_places": 2,
                            "min_value": 0.01,
                            "max_value": 9999.99
                        }
                    },
                    {
                        "key": "genero",
                        "label": "Género",
                        "type": "select",
                        "required": false,
                        "options": [
                            {"value": "masculino", "label": "Masculino"},
                            {"value": "femenino", "label": "Femenino"},
                            {"value": "otro", "label": "Otro"}
                        ]
                    },
                    {
                        "key": "intereses",
                        "label": "Intereses",
                        "type": "select",
                        "required": false,
                        "validations": {
                            "min_selections": 1,
                            "max_selections": 3
                        },
                        "options": [
                            {"value": "deportes", "label": "Deportes"},
                            {"value": "musica", "label": "Música"},
                            {"value": "arte", "label": "Arte"},
                            {"value": "tecnologia", "label": "Tecnología"}
                        ]
                    },
                    {
                        "key": "comentarios",
                        "label": "Comentarios Adicionales",
                        "type": "textarea",
                        "required": false,
                        "placeholder": "Escribe aquí cualquier comentario adicional...",
                        "validations": {
                            "max_words": 200,
                            "min_words": 5
                        }
                    },
                    {
                        "key": "telefono_emergencia",
                        "label": "Teléfono de Emergencia",
                        "type": "text",
                        "required": false,
                        "validations": {
                            "required_if": {
                                "field": "tiene_emergencia",
                                "value": "si"
                            }
                        }
                    },
                    {
                        "key": "acepta_terminos",
                        "label": "Acepto los términos y condiciones",
                        "type": "checkbox",
                        "required": true
                    }
                ]
            };
            
            schemaTextarea.value = JSON.stringify(validationExamples, null, 2);
            updatePreview();
            
            // Scroll to the preview section
            document.getElementById('form-preview').scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        });
    }
    
    // Actualizar previsualización inicial
    console.log('Ejecutando previsualización inicial');
    updatePreview();
});
</script>
@endpush
@endsection
