@extends('layouts.admin')

@section('title', 'Crear Campo')

@section('page-title', 'Crear Nuevo Campo')
@section('page-description', 'Agrega un nuevo campo que puede ser utilizado en los formularios')

@section('page-actions')
    <a href="{{ route('admin.fields.index') }}" class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Información del Campo
        </div>

        <form method="POST" action="{{ route('admin.fields.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Código del Campo -->
                <div class="admin-field-group">
                    <label for="code" class="admin-field-label">
                        Código del Campo <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           value="{{ old('code') }}"
                           class="admin-input w-full @error('code') border-red-500 @enderror"
                           placeholder="ej: genero, edad, ciudad"
                           required>
                    @error('code')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                    <div class="admin-field-help">
                        Código único para identificar el campo. Solo letras, números y guiones bajos.
                    </div>
                </div>

                <!-- Nombre del Campo -->
                <div class="admin-field-group">
                    <label for="name" class="admin-field-label">
                        Nombre del Campo <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="admin-input w-full @error('name') border-red-500 @enderror"
                           placeholder="ej: Género, Edad, Ciudad de residencia"
                           required>
                    @error('name')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                    <div class="admin-field-help">
                        Nombre descriptivo que se mostrará en los formularios.
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div class="admin-field-group">
                <label for="description" class="admin-field-label">
                    Descripción
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          class="admin-textarea w-full @error('description') border-red-500 @enderror"
                          placeholder="Descripción opcional del campo...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="admin-field-error">{{ $message }}</div>
                @enderror
                <div class="admin-field-help">
                    Descripción adicional que ayudará a entender el propósito del campo.
                </div>
            </div>

            <!-- Estado -->
            <div class="admin-field-group">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-acid-green focus:ring-acid-green border-gray-600 rounded bg-gray-700">
                    <label for="is_active" class="ml-2 block text-sm admin-text-secondary">
                        Campo activo
                    </label>
                </div>
                <div class="admin-field-help">
                    Los campos inactivos no estarán disponibles para usar en nuevos formularios.
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-600">
                <a href="{{ route('admin.fields.index') }}" 
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
            Información
        </div>
        <div class="admin-text-secondary text-sm space-y-2">
            <p>• Los campos son categorías que agrupan opciones relacionadas (ej: "Género" puede tener opciones como "Masculino", "Femenino", "Otro").</p>
            <p>• Después de crear el campo, podrás agregar las opciones específicas desde la página de gestión del campo.</p>
            <p>• El código del campo debe ser único y se utilizará internamente para identificar el campo en los formularios.</p>
        </div>
    </div>
@endsection
