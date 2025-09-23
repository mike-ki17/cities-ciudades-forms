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
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- JSON Editor -->
                        <div class="admin-field-group">
                            <label for="schema_json" class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Estructura del Formulario (JSON) *
                            </label>
                            <textarea name="schema_json" id="schema_json" rows="12" 
                                      class="admin-textarea w-full font-mono text-sm" 
                                      placeholder='{"fields": [{"key": "nombre", "label": "Nombre Completo", "type": "text", "required": true, "placeholder": "Ingresa tu nombre completo"}]}'>{{ old('schema_json', '{"fields": []}') }}</textarea>
                            <div class="admin-field-help">
                                <strong>Tipos de campos disponibles:</strong> text, email, number, date, select, textarea, checkbox<br>
                                <strong>Propiedades requeridas:</strong> key, label, type, required<br>
                                <strong>Propiedades opcionales:</strong> placeholder, options (para select), help
                            </div>
                            <div class="mt-2">
                                <button type="button" id="load-example" class="admin-button-outline text-xs px-3 py-1">
                                    <svg class="w-3 h-3 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Cargar Ejemplo
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
      "placeholder": "Ingrese su nombre"
    },
    {
      "key": "email",
      "label": "Correo Electrónico",
      "type": "email",
      "required": true,
      "placeholder": "ejemplo@correo.com"
    },
    {
      "key": "edad",
      "label": "Edad",
      "type": "number",
      "required": false,
      "placeholder": "Ingrese su edad"
    }
  ]
}</code></pre>
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
            console.log('Response headers:', response.headers);
            
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
    
    // Función para actualizar la previsualización
    function updatePreview() {
        console.log('Actualizando previsualización');
        
        try {
            const jsonText = schemaTextarea.value.trim();
            console.log('JSON text:', jsonText);
            
            if (!jsonText) {
                previewContainer.innerHTML = `
                    <div class="text-center admin-text-secondary text-sm py-8">
                        <svg class="w-8 h-8 mx-auto mb-2 admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Los campos aparecerán aquí mientras escribes el JSON</p>
                    </div>
                `;
                return;
            }
            
            const schema = JSON.parse(jsonText);
            console.log('Schema parseado:', schema);
            
            if (!schema.fields || !Array.isArray(schema.fields)) {
                previewContainer.innerHTML = `
                    <div class="text-center admin-alert-error text-sm py-8">
                        <svg class="w-8 h-8 mx-auto mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>Error: El JSON debe contener un array "fields"</p>
                    </div>
                `;
                return;
            }
            
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
            
        } catch (error) {
            console.error('Error en previsualización:', error);
            previewContainer.innerHTML = `
                <div class="text-center admin-alert-error text-sm py-8">
                    <svg class="w-8 h-8 mx-auto mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Error en el JSON: ${error.message}</p>
                </div>
            `;
        }
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
    
    // Actualizar previsualización inicial
    console.log('Ejecutando previsualización inicial');
    updatePreview();
});
</script>
@endpush
@endsection
