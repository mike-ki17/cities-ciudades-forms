@extends('layouts.admin')

@section('title', 'Editar Campo')

@section('page-title', 'Editar Campo')
@section('page-description', 'Modifica la información del campo: {{ $field->name }}')

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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Editar Información del Campo
        </div>

        <form method="POST" action="{{ route('admin.fields.update', $field) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Código del Campo -->
                <div class="admin-field-group">
                    <label for="code" class="admin-field-label">
                        Código del Campo <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           value="{{ old('code', $field->code) }}"
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
                           value="{{ old('name', $field->name) }}"
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
                          placeholder="Descripción opcional del campo...">{{ old('description', $field->description) }}</textarea>
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
                           {{ old('is_active', $field->is_active) ? 'checked' : '' }}
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
                    Actualizar Campo
                </button>
            </div>
        </form>
    </div>

    <!-- Información del campo -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Información del Campo
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="admin-card p-4">
                <div class="text-sm admin-text-secondary">Opciones</div>
                <div class="text-2xl font-bold admin-text">{{ $field->formOptions()->count() }}</div>
            </div>
            <div class="admin-card p-4">
                <div class="text-sm admin-text-secondary">Estado</div>
                <div class="text-sm font-medium">
                    @if($field->is_active)
                        <span class="text-green-400">Activo</span>
                    @else
                        <span class="text-red-400">Inactivo</span>
                    @endif
                </div>
            </div>
            <div class="admin-card p-4">
                <div class="text-sm admin-text-secondary">Creado</div>
                <div class="text-sm admin-text">{{ $field->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Acciones adicionales -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Gestionar Opciones
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-sm font-medium admin-text">Opciones del Campo</h4>
                <p class="text-sm admin-text-secondary">
                    Gestiona las opciones disponibles para este campo
                </p>
            </div>
            <a href="{{ route('admin.fields.options', $field) }}" 
               class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Ver Opciones
            </a>
        </div>
    </div>
@endsection
