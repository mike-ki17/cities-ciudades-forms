@extends('layouts.admin')

@section('title', 'Detalles del Campo JSON')

@section('page-title', 'Detalles del Campo JSON')
@section('page-description', 'Información completa del campo y su configuración')

@section('page-actions')
    <a href="{{ route('admin.fields-json.edit', $field) }}" class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Editar Campo
    </a>
    <a href="{{ route('admin.fields-json.index') }}" class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Información Básica -->
        <div class="admin-form-section">
            <div class="admin-form-section-title">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Información Básica
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium admin-text-secondary">Clave del Campo</label>
                        <div class="text-sm admin-text font-mono bg-gray-800 p-2 rounded">{{ $field->key }}</div>
                    </div>
                    <div>
                        <label class="text-sm font-medium admin-text-secondary">Tipo</label>
                        <div class="text-sm admin-text">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($field->type === 'text') bg-blue-900 text-blue-300
                                @elseif($field->type === 'email') bg-green-900 text-green-300
                                @elseif($field->type === 'number') bg-yellow-900 text-yellow-300
                                @elseif($field->type === 'select') bg-purple-900 text-purple-300
                                @elseif($field->type === 'textarea') bg-indigo-900 text-indigo-300
                                @elseif($field->type === 'checkbox') bg-pink-900 text-pink-300
                                @elseif($field->type === 'date') bg-red-900 text-red-300
                                @else bg-gray-700 text-gray-300
                                @endif">
                                {{ ucfirst($field->type) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium admin-text-secondary">Etiqueta</label>
                    <div class="text-sm admin-text">{{ $field->label }}</div>
                </div>

                @if($field->description)
                <div>
                    <label class="text-sm font-medium admin-text-secondary">Descripción</label>
                    <div class="text-sm admin-text">{{ $field->description }}</div>
                </div>
                @endif

                @if($field->placeholder)
                <div>
                    <label class="text-sm font-medium admin-text-secondary">Placeholder</label>
                    <div class="text-sm admin-text font-mono bg-gray-800 p-2 rounded">{{ $field->placeholder }}</div>
                </div>
                @endif

                @if($field->default_value)
                <div>
                    <label class="text-sm font-medium admin-text-secondary">Valor por Defecto</label>
                    <div class="text-sm admin-text font-mono bg-gray-800 p-2 rounded">{{ $field->default_value }}</div>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium admin-text-secondary">Requerido</label>
                        <div class="text-sm {{ $field->required ? 'text-green-400' : 'text-red-400' }}">
                            {{ $field->required ? 'Sí' : 'No' }}
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium admin-text-secondary">Estado</label>
                        <div class="text-sm {{ $field->is_active ? 'text-green-400' : 'text-red-400' }}">
                            {{ $field->is_active ? 'Activo' : 'Inactivo' }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium admin-text-secondary">Creado</label>
                        <div class="text-sm admin-text">{{ $field->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <label class="text-sm font-medium admin-text-secondary">Actualizado</label>
                        <div class="text-sm admin-text">{{ $field->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Validaciones -->
        @if($field->validations && count($field->validations) > 0)
        <div class="admin-form-section">
            <div class="admin-form-section-title">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Validaciones Configuradas
            </div>

            <div class="space-y-3">
                @foreach($field->validations as $key => $value)
                <div class="bg-gray-800 p-3 rounded-lg">
                    <div class="text-sm font-medium admin-text">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                    <div class="text-sm admin-text-secondary font-mono">{{ is_array($value) ? json_encode($value) : $value }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Visibilidad Condicional -->
        @if($field->visible && count($field->visible) > 0)
        <div class="admin-form-section">
            <div class="admin-form-section-title">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Visibilidad Condicional
            </div>

            <div class="space-y-3">
                @foreach($field->visible as $key => $value)
                <div class="bg-gray-800 p-3 rounded-lg">
                    <div class="text-sm font-medium admin-text">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                    <div class="text-sm admin-text-secondary font-mono">{{ is_array($value) ? json_encode($value) : $value }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Opciones (para campos select) -->
        @if($field->type === 'select')
        @php
            $options = $field->formFieldOrders->first()?->formCategory?->formOptions ?? collect();
        @endphp
        
        @if($options->count() > 0)
        <div class="admin-form-section">
            <div class="admin-form-section-title">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Opciones del Campo ({{ $options->count() }})
            </div>

            <div class="space-y-2 max-h-60 overflow-y-auto">
                @foreach($options as $option)
                <div class="flex items-center justify-between bg-gray-800 p-3 rounded-lg">
                    <div class="flex-1">
                        <div class="text-sm font-medium admin-text">{{ $option->label }}</div>
                        <div class="text-xs admin-text-secondary font-mono">{{ $option->value }}</div>
                        @if($option->description)
                        <div class="text-xs admin-text-secondary mt-1">{{ $option->description }}</div>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs {{ $option->is_active ? 'text-green-400' : 'text-red-400' }}">
                            {{ $option->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                        <span class="text-xs admin-text-secondary">#{{ $option->order }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endif

        <!-- Uso en Formularios -->
        @if($field->formFieldOrders()->count() > 0)
        <div class="admin-form-section">
            <div class="admin-form-section-title">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Uso en Formularios ({{ $field->formFieldOrders()->count() }})
            </div>

            <div class="space-y-2">
                @foreach($field->formFieldOrders as $fieldOrder)
                <div class="bg-gray-800 p-3 rounded-lg">
                    <div class="text-sm font-medium admin-text">{{ $fieldOrder->form->name }}</div>
                    <div class="text-xs admin-text-secondary">
                        Evento: {{ $fieldOrder->form->event->name ?? 'N/A' }} | 
                        Orden: {{ $fieldOrder->order }} | 
                        Creado: {{ $fieldOrder->form->created_at->format('d/m/Y') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- JSON Completo -->
    <div class="admin-form-section mt-6">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Estructura JSON Completa
        </div>

        <div class="bg-gray-900 p-4 rounded-lg">
            <pre class="text-sm admin-text font-mono overflow-x-auto"><code>{{ json_encode([
                'key' => $field->key,
                'label' => $field->label,
                'type' => $field->type,
                'required' => $field->required,
                'placeholder' => $field->placeholder,
                'validations' => $field->validations,
                'visible' => $field->visible,
                'default_value' => $field->default_value,
                'description' => $field->description,
                'options' => $field->formFieldOrders->first()?->formCategory?->formOptions?->map(function($option) {
                    return [
                        'value' => $option->value,
                        'label' => $option->label,
                        'description' => $option->description,
                        'is_active' => $option->is_active
                    ];
                })->toArray() ?? []
            ], JSON_PRETTY_PRINT) }}</code></pre>
        </div>
    </div>
@endsection
