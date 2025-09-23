@extends('layouts.admin')

@section('title', 'Crear Opción')

@section('page-title', 'Crear Nueva Opción')
@section('page-description', 'Agrega una nueva opción para el campo: ' . $field->name)

@section('page-actions')
    <a href="{{ route('admin.fields.options', $field) }}" class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver
    </a>
@endsection

@section('content')
    <!-- Información del campo -->
    <div class="admin-form-section mb-6">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Campo: {{ $field->name }}
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-sm admin-text-secondary">Código del Campo</div>
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-700 text-gray-300">
                    {{ $field->code }}
                </div>
            </div>
            <div>
                <div class="text-sm admin-text-secondary">Opciones Existentes</div>
                <div class="admin-text font-medium">{{ $field->formOptions()->count() }} opciones</div>
            </div>
        </div>
    </div>

    <!-- Formulario de creación -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Información de la Opción
        </div>

        <form method="POST" action="{{ route('admin.fields.options.store', $field) }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Valor de la Opción -->
                <div class="admin-field-group">
                    <label for="value" class="admin-field-label">
                        Valor de la Opción <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           id="value" 
                           name="value" 
                           value="{{ old('value') }}"
                           class="admin-input w-full @error('value') border-red-500 @enderror"
                           placeholder="ej: masculino, femenino, otro"
                           required>
                    @error('value')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                    <div class="admin-field-help">
                        Valor interno que se guardará en la base de datos. Solo letras, números y guiones bajos.
                    </div>
                </div>

                <!-- Etiqueta de la Opción -->
                <div class="admin-field-group">
                    <label for="label" class="admin-field-label">
                        Etiqueta de la Opción <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           id="label" 
                           name="label" 
                           value="{{ old('label') }}"
                           class="admin-input w-full @error('label') border-red-500 @enderror"
                           placeholder="ej: Masculino, Femenino, Otro"
                           required>
                    @error('label')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                    <div class="admin-field-help">
                        Texto que se mostrará al usuario en el formulario.
                    </div>
                </div>
            </div>

            <!-- Orden -->
            <div class="admin-field-group">
                <label for="order" class="admin-field-label">
                    Orden
                </label>
                <input type="number" 
                       id="order" 
                       name="order" 
                       value="{{ old('order', $field->formOptions()->max('order') + 1) }}"
                       min="1"
                       class="admin-input w-full md:w-32 @error('order') border-red-500 @enderror">
                @error('order')
                    <div class="admin-field-error">{{ $message }}</div>
                @enderror
                <div class="admin-field-help">
                    Orden de aparición en el formulario. Si se deja vacío, se asignará automáticamente.
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
                          placeholder="Descripción opcional de la opción...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="admin-field-error">{{ $message }}</div>
                @enderror
                <div class="admin-field-help">
                    Descripción adicional que puede ayudar a entender mejor la opción.
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
                        Opción activa
                    </label>
                </div>
                <div class="admin-field-help">
                    Las opciones inactivas no estarán disponibles en los formularios.
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-600">
                <a href="{{ route('admin.fields.options', $field) }}" 
                   class="admin-button-outline px-6 py-2 rounded-md text-sm font-medium">
                    Cancelar
                </a>
                <button type="submit" 
                        class="admin-button-primary px-6 py-2 rounded-md text-sm font-medium">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Crear Opción
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
            <p>• El <strong>valor</strong> es el identificador interno que se guarda en la base de datos.</p>
            <p>• La <strong>etiqueta</strong> es el texto que verá el usuario en el formulario.</p>
            <p>• El <strong>orden</strong> determina la secuencia en que aparecerán las opciones.</p>
            <p>• Puedes cambiar el orden después de crear la opción arrastrando y soltando en la lista.</p>
        </div>
    </div>
@endsection
