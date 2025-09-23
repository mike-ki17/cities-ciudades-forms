@extends('layouts.admin')

@section('title', 'Detalles del Campo')

@section('page-title', 'Detalles del Campo')
@section('page-description', 'Información completa del campo: {{ $field->name }}')

@section('page-actions')
    <div class="flex space-x-2">
        <a href="{{ route('admin.fields.edit', $field) }}" class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Editar
        </a>
        <a href="{{ route('admin.fields.index') }}" class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver
        </a>
    </div>
@endsection

@section('content')
    <!-- Información del campo -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Información del Campo
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="admin-field-group">
                    <label class="admin-field-label">Nombre del Campo</label>
                    <div class="admin-text text-lg font-medium">{{ $field->name }}</div>
                </div>

                <div class="admin-field-group">
                    <label class="admin-field-label">Código del Campo</label>
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-700 text-gray-300">
                        {{ $field->code }}
                    </div>
                </div>

                @if($field->description)
                    <div class="admin-field-group">
                        <label class="admin-field-label">Descripción</label>
                        <div class="admin-text-secondary">{{ $field->description }}</div>
                    </div>
                @endif
            </div>

            <div>
                <div class="admin-field-group">
                    <label class="admin-field-label">Estado</label>
                    <div>
                        @if($field->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Activo
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Inactivo
                            </span>
                        @endif
                    </div>
                </div>

                <div class="admin-field-group">
                    <label class="admin-field-label">Fecha de Creación</label>
                    <div class="admin-text-secondary">{{ $field->created_at->format('d/m/Y H:i') }}</div>
                </div>

                <div class="admin-field-group">
                    <label class="admin-field-label">Última Actualización</label>
                    <div class="admin-text-secondary">{{ $field->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="admin-form-section">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Estadísticas
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="admin-card p-6 text-center">
                <div class="text-3xl font-bold admin-text">{{ $field->formOptions()->count() }}</div>
                <div class="text-sm admin-text-secondary mt-1">Total de Opciones</div>
            </div>
            <div class="admin-card p-6 text-center">
                <div class="text-3xl font-bold admin-text">{{ $field->formOptions()->where('is_active', true)->count() }}</div>
                <div class="text-sm admin-text-secondary mt-1">Opciones Activas</div>
            </div>
            <div class="admin-card p-6 text-center">
                <div class="text-3xl font-bold admin-text">{{ $field->formOptions()->where('is_active', false)->count() }}</div>
                <div class="text-sm admin-text-secondary mt-1">Opciones Inactivas</div>
            </div>
        </div>
    </div>

    <!-- Opciones del campo -->
    <div class="admin-form-section">
        <div class="flex items-center justify-between mb-4">
            <div class="admin-form-section-title">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Opciones del Campo
            </div>
            <a href="{{ route('admin.fields.options', $field) }}" 
               class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Gestionar Opciones
            </a>
        </div>

        @if($field->formOptions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-600">
                    <thead class="admin-table-header">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Orden
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Valor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Etiqueta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Estado
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-600">
                        @foreach($field->formOptions as $option)
                            <tr class="admin-table-row">
                                <td class="px-6 py-4 whitespace-nowrap text-sm admin-text">
                                    {{ $option->order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                        {{ $option->value }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm admin-text">{{ $option->label }}</div>
                                    @if($option->description)
                                        <div class="text-sm admin-text-secondary">{{ Str::limit($option->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($option->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 admin-text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium admin-text">No hay opciones</h3>
                <p class="mt-1 text-sm admin-text-secondary">
                    Este campo no tiene opciones configuradas.
                </p>
                <div class="mt-6">
                    <a href="{{ route('admin.fields.options.create', $field) }}" 
                       class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Agregar Primera Opción
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
