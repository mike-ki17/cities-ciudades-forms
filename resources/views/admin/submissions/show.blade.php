@extends('layouts.admin')

@section('title', 'Ver Submission')

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
                                <a href="{{ route('admin.submissions.index') }}" class="admin-text-muted hover:admin-text-secondary flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 mr-2" style="color: #00ffbd;" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Respuestas</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 admin-text-muted" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-4 text-sm font-medium admin-text-secondary">Detalles</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <div class="mt-4 flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full admin-alert-success flex items-center justify-center">
                            <svg class="h-6 w-6" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold leading-7 admin-text sm:text-3xl">
                            Respuesta #{{ $submission->id }}
                        </h2>
                        <p class="mt-1 text-sm admin-text-secondary">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            @if($submission->form)
                                {{ $submission->form->name }}
                            @else
                                <span class="text-red-500">Formulario no encontrado</span>
                            @endif
                        </p>
                        <p class="mt-1 text-sm admin-text-muted">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $submission->submitted_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('admin.submissions.index') }}" 
                   class="admin-button-outline px-6 py-3 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Lista
                </a>
            </div>
        </div>

        <!-- Submission Details -->
        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Form Information -->
                <div class="admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Información del Formulario
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Formulario
                            </label>
                            <div class="admin-text font-medium text-lg">
                                @if($submission->form)
                                    {{ $submission->form->name }}
                                @else
                                    <span class="text-red-500">Formulario no encontrado</span>
                                @endif
                            </div>
                        </div>

                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ciudad
                            </label>
                            <div class="mt-1">
                                @if($submission->form && $submission->form->event)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium admin-alert-success">
                                        <svg class="w-4 h-4 mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $submission->form->event->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium admin-alert-error">
                                        <svg class="w-4 h-4 mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        General
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Versión
                            </label>
                            <div class="admin-text font-medium">
                                @if($submission->form)
                                    v{{ $submission->form->version }}
                                @else
                                    <span class="text-red-500">N/A</span>
                                @endif
                            </div>
                        </div>

                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Fecha de Envío
                            </label>
                            <div class="admin-text font-medium">{{ $submission->submitted_at->format('d/m/Y H:i:s') }}</div>
                        </div>

                        @if($submission->form && $submission->form->description)
                            <div class="sm:col-span-2 admin-field-group">
                                <label class="admin-field-label">
                                    <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                    Descripción
                                </label>
                                <div class="admin-text">{{ $submission->form->description }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Participant Information -->
                <div class="mt-8 admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Información del Participante
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nombre Completo
                            </label>
                            <div class="admin-text font-medium text-lg">
                                @if($submission->participant)
                                    {{ $submission->participant->first_name }} {{ $submission->participant->last_name }}
                                @else
                                    <span class="text-red-500">Participante no encontrado</span>
                                @endif
                            </div>
                        </div>

                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email
                            </label>
                            <div class="admin-text font-medium">
                                @if($submission->participant)
                                    {{ $submission->participant->email }}
                                @else
                                    <span class="text-red-500">No disponible</span>
                                @endif
                            </div>
                        </div>

                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Teléfono
                            </label>
                            <div class="admin-text font-medium">
                                @if($submission->participant)
                                    {{ $submission->participant->phone ?: 'No proporcionado' }}
                                @else
                                    <span class="text-red-500">No disponible</span>
                                @endif
                            </div>
                        </div>

                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Documento
                            </label>
                            <div class="admin-text font-medium">
                                @if($submission->participant)
                                    {{ $submission->participant->document_type }}: {{ $submission->participant->document_number }}
                                @else
                                    <span class="text-red-500">No disponible</span>
                                @endif
                            </div>
                        </div>

                        @if($submission->participant && $submission->participant->birth_date)
                            <div class="admin-field-group">
                                <label class="admin-field-label">
                                    <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Fecha de Nacimiento
                                </label>
                                <div class="admin-text font-medium">{{ $submission->participant->birth_date->format('d/m/Y') }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Responses -->
                <div class="mt-8 admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Respuestas del Formulario
                    </div>
                    
                    @if(isset($submission->data_json) && count($submission->data_json) > 0)
                        <div class="space-y-6">
                            @foreach($submission->data_json as $key => $value)
                                @php
                                    $field = null;
                                    $label = $key;
                                    $type = 'text';
                                    
                                    if ($submission->form && isset($submission->form->schema_json['fields'])) {
                                        $field = collect($submission->form->schema_json['fields'])->firstWhere('key', $key);
                                        $label = $field['label'] ?? $key;
                                        $type = $field['type'] ?? 'text';
                                    }
                                @endphp
                                <div class="admin-card rounded-lg p-6 border-l-4" style="border-left-color: #00ffbd;">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold admin-text">{{ $label }}</h4>
                                            <p class="text-sm admin-text-secondary mt-1">
                                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                </svg>
                                                {{ $key }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium admin-alert-success">
                                            <svg class="w-4 h-4 mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            {{ ucfirst($type) }}
                                        </span>
                                    </div>
                                    
                                    <div class="mt-4">
                                        @if(is_array($value))
                                            <div class="admin-input p-4 rounded-lg">
                                                <pre class="text-sm admin-text whitespace-pre-wrap">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @elseif($type === 'checkbox')
                                            <div class="flex items-center">
                                                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ $value ? 'admin-alert-success' : 'admin-alert-error' }}">
                                                    <svg class="w-4 h-4 mr-2" style="color: {{ $value ? '#00ffbd' : '#bb2558' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        @if($value)
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        @endif
                                                    </svg>
                                                    {{ $value ? 'Sí' : 'No' }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="admin-input p-4 rounded-lg">
                                                <p class="text-sm admin-text font-medium">{{ $value ?: 'Sin respuesta' }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium admin-text">No hay respuestas</h3>
                            <p class="mt-2 text-sm admin-text-secondary">Este submission no tiene respuestas registradas.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Submission Info -->
                <div class="admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Información del Submission
                    </div>
                    
                    <div class="space-y-4">
                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                ID del Submission
                            </label>
                            <div class="admin-text font-bold text-lg">#{{ $submission->id }}</div>
                        </div>

                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Fecha de Envío
                            </label>
                            <div class="admin-text font-medium">{{ $submission->submitted_at->format('d/m/Y') }}</div>
                        </div>

                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Hora de Envío
                            </label>
                            <div class="admin-text font-medium">{{ $submission->submitted_at->format('H:i:s') }}</div>
                        </div>

                        <div class="admin-field-group">
                            <label class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Total de Respuestas
                            </label>
                            <div class="admin-text font-bold text-lg">{{ count($submission->data_json ?? []) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Raw Data -->
                <div class="admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        Datos Raw (JSON)
                    </div>
                    
                    <div class="admin-input p-4 rounded-lg">
                        <pre class="text-xs admin-text-secondary overflow-x-auto whitespace-pre-wrap"><code>{{ json_encode($submission->data_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                </div>

                <!-- Actions -->
                <div class="admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Acciones
                    </div>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.submissions.index') }}" 
                           class="w-full admin-button-outline px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver a Lista
                        </a>
                        
                        @if($submission->form)
                            <a href="{{ route('admin.submissions.index', ['form_id' => $submission->form->id]) }}" 
                               class="w-full admin-button-primary px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Ver Otros del Mismo Formulario
                            </a>

                            <a href="{{ route('admin.forms.show', $submission->form) }}" 
                               class="w-full admin-button-outline px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver Formulario
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


