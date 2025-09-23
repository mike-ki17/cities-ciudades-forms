@extends('layouts.admin')

@section('title', 'Ver Formulario')

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
                                <span class="ml-4 text-sm font-medium admin-text-secondary">Ver Formulario</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 admin-text sm:text-3xl sm:truncate">
                    {{ $form->name }}
                </h2>
                <p class="mt-1 text-sm admin-text-secondary">
                    {{ $form->description }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('admin.forms.edit', $form) }}" 
                   class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('admin.forms.index') }}" 
                   class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <!-- Form Details -->
        <div class="mt-8 space-y-8">
            <!-- Main Info -->
            <div class="admin-form-section">
                <div class="admin-form-section-title">
                    <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Información del Formulario
                </div>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="admin-field-group">
                        <label class="admin-field-label">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Nombre del Formulario
                        </label>
                        <div class="admin-text font-semibold text-lg">{{ $form->name }}</div>
                    </div>
                    
                    <div class="admin-field-group">
                        <label class="admin-field-label">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ciudad
                        </label>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium admin-alert-success">
                            <svg class="w-4 h-4 mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $form->event ? $form->event->name : 'General' }}
                        </div>
                    </div>
                    
                    <div class="admin-field-group">
                        <label class="admin-field-label">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Estado
                        </label>
                        <div>
                            @if($form->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium admin-alert-success">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium admin-alert-error">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Inactivo
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="admin-field-group">
                        <label class="admin-field-label">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v18a1 1 0 01-1 1H4a1 1 0 01-1-1V1a1 1 0 011-1h2a1 1 0 011 1v3m0 0h8"></path>
                            </svg>
                            Versión
                        </label>
                        <div class="admin-text font-semibold">v{{ $form->version }}</div>
                    </div>
                    
                    <div class="admin-field-group sm:col-span-2">
                        <label class="admin-field-label">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            URL Pública
                        </label>
                        <div class="flex items-center space-x-2">
                            <input type="text" 
                                   value="{{ route('public.forms.slug.show', ['slug' => $form->slug]) }}" 
                                   readonly 
                                   class="flex-1 admin-input text-sm">
                            <button onclick="copyToClipboard('{{ route('public.forms.slug.show', ['slug' => $form->slug]) }}', this)" 
                                    class="admin-button-outline px-4 py-2 rounded-lg text-sm font-medium">
                                <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Copiar
                            </button>
                        </div>
                    </div>
                    
                    <div class="admin-field-group sm:col-span-2">
                        <label class="admin-field-label">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            Descripción
                        </label>
                        <div class="admin-text">{{ $form->description ?: 'Sin descripción' }}</div>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="admin-form-section">
                <div class="admin-form-section-title">
                    <svg class="w-5 h-5 inline-block mr-2" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Campos del Formulario
                </div>
                
                @if(isset($form->schema_json['fields']) && count($form->schema_json['fields']) > 0)
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($form->schema_json['fields'] as $index => $field)
                            <div class="admin-card rounded-lg p-6 border-l-4" style="border-left-color: #bb2558;">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center justify-center w-8 h-8 admin-alert-success text-sm font-bold rounded-full">
                                            {{ $index + 1 }}
                                        </span>
                                        <div>
                                            <h4 class="text-lg font-semibold admin-text">{{ $field['label'] }}</h4>
                                            <p class="text-sm admin-text-secondary font-mono">{{ $field['key'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium admin-alert-success">
                                            <svg class="w-4 h-4 mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $field['type'] }}
                                        </span>
                                        @if($field['required'] ?? false)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium admin-alert-error">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                Requerido
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if(isset($field['placeholder']))
                                    <div class="mt-4 p-3 admin-card rounded-lg">
                                        <p class="text-sm admin-text-secondary">
                                            <span class="font-medium admin-text">Placeholder:</span> {{ $field['placeholder'] }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium admin-text">No hay campos definidos</h3>
                        <p class="mt-2 text-sm admin-text-secondary">Este formulario no tiene campos configurados.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.forms.edit', $form) }}" class="admin-button-primary px-6 py-3 rounded-lg text-sm font-medium">
                                <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Agregar Campos
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            </div>

            <!-- Statistics -->
            <div class="admin-form-section">
                <div class="admin-form-section-title">
                    <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Estadísticas
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="admin-card rounded-lg p-4 border-l-4" style="border-left-color: #00ffbd;">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <svg class="h-6 w-6" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm font-medium admin-text-secondary">Total Envíos</span>
                            </div>
                            <span class="text-xl font-bold admin-text">{{ $form->formSubmissions->count() }}</span>
                        </div>
                    </div>
                    
                    <div class="admin-card rounded-lg p-4 border-l-4" style="border-left-color: #bb2558;">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <svg class="h-6 w-6" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium admin-text-secondary">Último Envío</span>
                            </div>
                            <span class="text-sm font-semibold admin-text">
                                @if($form->formSubmissions->count() > 0)
                                    {{ $form->formSubmissions->sortByDesc('submitted_at')->first()->submitted_at->format('d/m/Y') }}
                                @else
                                    <span class="admin-text-muted">Sin envíos</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="admin-card rounded-lg p-4 border-l-4" style="border-left-color: #00ffbd;">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <svg class="h-6 w-6" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium admin-text-secondary">Creado</span>
                            </div>
                            <span class="text-sm font-semibold admin-text">{{ $form->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="admin-form-section">
                <div class="admin-form-section-title">
                    <svg class="w-5 h-5 inline-block mr-2" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Acciones
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.forms.edit', $form) }}" 
                       class="w-full admin-button-outline px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Formulario
                    </a>
                    
                    @if($form->is_active)
                        <form method="POST" action="{{ route('admin.forms.deactivate', $form) }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full admin-button-outline px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center"
                                    style="border-color: #ffc107; color: #ffc107;">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                </svg>
                                Desactivar
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.forms.activate', $form) }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full admin-button-outline px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center"
                                    style="border-color: #00ffbd; color: #00ffbd;">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Activar
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.submissions.index', ['form_id' => $form->id]) }}" 
                       class="w-full admin-button-primary px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Ver Submissions
                    </a>

                    <form method="POST" action="{{ route('admin.forms.destroy', $form) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full admin-button-outline px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center"
                                style="border-color: #bb2558; color: #bb2558;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const originalText = button.textContent;
        button.textContent = '¡Copiado!';
        button.classList.add('bg-green-100', 'text-green-800', 'border-green-300');
        
        setTimeout(function() {
            button.textContent = originalText;
            button.classList.remove('bg-green-100', 'text-green-800', 'border-green-300');
        }, 2000);
    }).catch(function(err) {
        console.error('Error al copiar: ', err);
        alert('Error al copiar la URL');
    });
}
</script>
@endsection
