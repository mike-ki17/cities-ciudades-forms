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
                                <a href="{{ route('admin.submissions.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    <span class="sr-only">Submissions</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">Ver Submission</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Submission #{{ $submission->id }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $submission->form->name }} - {{ $submission->submitted_at->format('d/m/Y H:i') }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('admin.submissions.index') }}" 
                   class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <!-- Submission Details -->
        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Form Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Formulario</h3>
                    </div>
                    <div class="card-body">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Formulario</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $submission->form->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ciudad</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($submission->form->city)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $submission->form->city->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            General
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Versión</dt>
                                <dd class="mt-1 text-sm text-gray-900">v{{ $submission->form->version }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Envío</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $submission->submitted_at->format('d/m/Y H:i:s') }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $submission->form->description ?: 'Sin descripción' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Participant Information -->
                <div class="mt-8 card">
                    <div class="card-header">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Participante</h3>
                    </div>
                    <div class="card-body">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $submission->participant->first_name }} {{ $submission->participant->last_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $submission->participant->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $submission->participant->phone ?: 'No proporcionado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Documento</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $submission->participant->document_type }}: {{ $submission->participant->document_number }}
                                </dd>
                            </div>
                            @if($submission->participant->city)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ciudad</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $submission->participant->city->name }}</dd>
                                </div>
                            @endif
                            @if($submission->participant->birth_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Nacimiento</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $submission->participant->birth_date->format('d/m/Y') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Form Responses -->
                <div class="mt-8 card">
                    <div class="card-header">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Respuestas del Formulario</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($submission->data_json) && count($submission->data_json) > 0)
                            <div class="space-y-4">
                                @foreach($submission->data_json as $key => $value)
                                    @php
                                        $field = collect($submission->form->schema_json['fields'] ?? [])->firstWhere('key', $key);
                                        $label = $field['label'] ?? $key;
                                        $type = $field['type'] ?? 'text';
                                    @endphp
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">{{ $label }}</h4>
                                                <p class="text-sm text-gray-500">{{ $key }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $type }}
                                            </span>
                                        </div>
                                        <div class="mt-2">
                                            @if(is_array($value))
                                                <pre class="text-sm text-gray-900 bg-gray-50 p-2 rounded">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                            @elseif($type === 'checkbox')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $value ? 'Sí' : 'No' }}
                                                </span>
                                            @else
                                                <p class="text-sm text-gray-900">{{ $value ?: 'Sin respuesta' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay respuestas</h3>
                                <p class="mt-1 text-sm text-gray-500">Este submission no tiene respuestas registradas.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Submission Info -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Submission</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">ID</span>
                                <span class="text-sm text-gray-900">#{{ $submission->id }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Fecha de Envío</span>
                                <span class="text-sm text-gray-900">{{ $submission->submitted_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Hora de Envío</span>
                                <span class="text-sm text-gray-900">{{ $submission->submitted_at->format('H:i:s') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Total de Respuestas</span>
                                <span class="text-sm text-gray-900">{{ count($submission->data_json ?? []) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Raw Data -->
                <div class="mt-6 card">
                    <div class="card-header">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Datos Raw (JSON)</h3>
                    </div>
                    <div class="card-body">
                        <pre class="text-xs text-gray-600 bg-gray-50 p-3 rounded overflow-x-auto"><code>{{ json_encode($submission->data_json, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 card">
                    <div class="card-header">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Acciones</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            <a href="{{ route('admin.submissions.index') }}" 
                               class="w-full btn-outline flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver a Lista
                            </a>
                            
                            <a href="{{ route('admin.submissions.index', ['form_id' => $submission->form->id]) }}" 
                               class="w-full btn-primary flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Ver Otros del Mismo Formulario
                            </a>

                            <a href="{{ route('admin.forms.show', $submission->form) }}" 
                               class="w-full btn-outline flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver Formulario
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
