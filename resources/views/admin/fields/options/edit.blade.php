@extends('layouts.admin')

@section('title', 'Editar Opción')

@section('page-title', 'Editar Opción')
@section('page-description', 'Modifica la información de la opción: ' . $option->label)

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
                <div class="text-sm admin-text-secondary">Total de Opciones</div>
                <div class="admin-text font-medium">{{ $field->formOptions()->count() }} opciones</div>
            </div>
        </div>
    </div>

    <!-- Formulario de edición -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Editar Información de la Opción
        </div>

        <form method="POST" action="{{ route('admin.fields.options.update', [$field, $option]) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Valor de la Opción -->
                <div class="admin-field-group">
                    <label for="value" class="admin-field-label">
                        Valor de la Opción <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           id="value" 
                           name="value" 
                           value="{{ old('value', $option->value) }}"
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
                           value="{{ old('label', $option->label) }}"
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
                       value="{{ old('order', $option->order) }}"
                       min="1"
                       class="admin-input w-full md:w-32 @error('order') border-red-500 @enderror">
                @error('order')
                    <div class="admin-field-error">{{ $message }}</div>
                @enderror
                <div class="admin-field-help">
                    Orden de aparición en el formulario. También puedes cambiar el orden arrastrando y soltando en la lista.
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
                          placeholder="Descripción opcional de la opción...">{{ old('description', $option->description) }}</textarea>
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
                           {{ old('is_active', $option->is_active) ? 'checked' : '' }}
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
                    Actualizar Opción
                </button>
            </div>
        </form>
    </div>

    <!-- Información de la opción -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Información de la Opción
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="admin-card p-4">
                <div class="text-sm admin-text-secondary">Estado</div>
                <div class="text-sm font-medium">
                    @if($option->is_active)
                        <span class="text-green-400">Activa</span>
                    @else
                        <span class="text-red-400">Inactiva</span>
                    @endif
                </div>
            </div>
            <div class="admin-card p-4">
                <div class="text-sm admin-text-secondary">Creada</div>
                <div class="text-sm admin-text">{{ $option->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="admin-card p-4">
                <div class="text-sm admin-text-secondary">Actualizada</div>
                <div class="text-sm admin-text">{{ $option->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
@endsection
