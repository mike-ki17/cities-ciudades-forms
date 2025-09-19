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
                                <span class="ml-4 text-sm font-medium text-gray-500">Editar Formulario</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Editar Formulario: {{ $form->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Modifica la configuración del formulario
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
                    <form method="POST" action="{{ route('admin.forms.update', $form) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- City -->
                            <div>
                                <label for="city_id" class="form-label">Ciudad</label>
                                <select name="city_id" id="city_id" class="form-input">
                                    <option value="">Seleccionar ciudad</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id', $form->city_id) == $city->id ? 'selected' : '' }}>
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
                                <input type="text" name="name" id="name" value="{{ old('name', $form->name) }}" 
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
                                      class="form-input" placeholder="Describe el propósito de este formulario">{{ old('description', $form->description) }}</textarea>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Schema JSON -->
                        <div>
                            <label for="schema_json" class="form-label">Configuración del Formulario (JSON)</label>
                            <textarea name="schema_json" id="schema_json" rows="10" 
                                      class="form-input font-mono text-sm" 
                                      placeholder='{"fields": [{"key": "nombre", "label": "Nombre", "type": "text", "required": true}]}'>{{ old('schema_json', json_encode($form->schema_json, JSON_PRETTY_PRINT)) }}</textarea>
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
                                <input type="number" name="version" id="version" value="{{ old('version', $form->version) }}" 
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
                                               {{ old('is_active', $form->is_active) ? 'checked' : '' }}
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
                                Actualizar Formulario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Form Statistics -->
        <div class="mt-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Estadísticas del Formulario</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-blue-600 truncate">Total Submissions</dt>
                                        <dd class="text-lg font-medium text-blue-900">{{ $form->formSubmissions->count() }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-green-600 truncate">Última Submission</dt>
                                        <dd class="text-lg font-medium text-green-900">
                                            @if($form->formSubmissions->count() > 0)
                                                {{ $form->formSubmissions->sortByDesc('submitted_at')->first()->submitted_at->format('d/m/Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-purple-600 truncate">Fecha de Creación</dt>
                                        <dd class="text-lg font-medium text-purple-900">{{ $form->created_at->format('d/m/Y') }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
