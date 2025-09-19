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
                                <a href="{{ route('admin.forms.index') }}" class="text-gray-400 hover:text-gray-500">
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
                                <span class="ml-4 text-sm font-medium text-gray-500">Crear Formulario</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Crear Nuevo Formulario
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Crea un nuevo formulario dinámico para una ciudad específica
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="mt-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Formulario</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.forms.store') }}" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- City -->
                            <div>
                                <label for="city_id" class="form-label">Ciudad</label>
                                <select name="city_id" id="city_id" class="form-input">
                                    <option value="">Seleccionar ciudad</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="form-label">Nombre del Formulario</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                       class="form-input" placeholder="Ej: Formulario de Registro">
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="form-label">Descripción</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="form-input" placeholder="Describe el propósito de este formulario">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Schema JSON -->
                        <div>
                            <label for="schema_json" class="form-label">Configuración del Formulario (JSON)</label>
                            <textarea name="schema_json" id="schema_json" rows="10" 
                                      class="form-input font-mono text-sm" 
                                      placeholder='{"fields": [{"key": "nombre", "label": "Nombre", "type": "text", "required": true}]}'>{{ old('schema_json', '{"fields": []}') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                Define los campos del formulario en formato JSON. Cada campo debe tener: key, label, type, required, placeholder (opcional).
                            </p>
                            @error('schema_json')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Version -->
                            <div>
                                <label for="version" class="form-label">Versión</label>
                                <input type="number" name="version" id="version" value="{{ old('version', 1) }}" 
                                       class="form-input" min="1">
                                @error('version')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Is Active -->
                            <div>
                                <label class="form-label">Estado</label>
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">Formulario activo</span>
                                    </label>
                                </div>
                                @error('is_active')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.forms.index') }}" 
                               class="btn-outline">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Ayuda: Estructura JSON</h3>
                </div>
                <div class="card-body">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Tipos de campos disponibles:</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li><code class="bg-gray-200 px-1 rounded">text</code> - Campo de texto</li>
                            <li><code class="bg-gray-200 px-1 rounded">email</code> - Campo de email</li>
                            <li><code class="bg-gray-200 px-1 rounded">number</code> - Campo numérico</li>
                            <li><code class="bg-gray-200 px-1 rounded">textarea</code> - Área de texto</li>
                            <li><code class="bg-gray-200 px-1 rounded">select</code> - Lista desplegable</li>
                            <li><code class="bg-gray-200 px-1 rounded">checkbox</code> - Casilla de verificación</li>
                            <li><code class="bg-gray-200 px-1 rounded">date</code> - Campo de fecha</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4 bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Ejemplo de estructura:</h4>
                        <pre class="text-xs text-gray-600 overflow-x-auto"><code>{
  "fields": [
    {
      "key": "nombre",
      "label": "Nombre Completo",
      "type": "text",
      "required": true,
      "placeholder": "Ingrese su nombre completo"
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
@endsection
