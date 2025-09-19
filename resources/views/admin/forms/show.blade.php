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
                                <span class="ml-4 text-sm font-medium text-gray-500">Ver Formulario</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ $form->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $form->description }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('admin.forms.edit', $form) }}" 
                   class="btn-outline">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('admin.forms.index') }}" 
                   class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <!-- Form Details -->
        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Main Info -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Formulario</h3>
                    </div>
                    <div class="card-body">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $form->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ciudad</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $form->city->name }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($form->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Inactivo
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Versión</dt>
                                <dd class="mt-1 text-sm text-gray-900">v{{ $form->version }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">URL Pública</dt>
                                <dd class="mt-1">
                                    <div class="flex items-center space-x-2">
                                        <input type="text" 
                                               value="{{ route('public.forms.slug.show', $form->slug) }}" 
                                               readonly 
                                               class="flex-1 text-sm bg-gray-50 border border-gray-300 rounded-md px-3 py-2 text-gray-900">
                                        <button onclick="copyToClipboard('{{ route('public.forms.slug.show', $form->slug) }}')" 
                                                class="btn-outline text-xs px-3 py-2">
                                            Copiar
                                        </button>
                                    </div>
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $form->description ?: 'Sin descripción' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="mt-8 card">
                    <div class="card-header">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Campos del Formulario</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($form->schema_json['fields']) && count($form->schema_json['fields']) > 0)
                            <div class="space-y-4">
                                @foreach($form->schema_json['fields'] as $index => $field)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                                    {{ $index + 1 }}
                                                </span>
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900">{{ $field['label'] }}</h4>
                                                    <p class="text-sm text-gray-500">{{ $field['key'] }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $field['type'] }}
                                                </span>
                                                @if($field['required'] ?? false)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Requerido
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if(isset($field['placeholder']))
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">
                                                    <span class="font-medium">Placeholder:</span> {{ $field['placeholder'] }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay campos definidos</h3>
                                <p class="mt-1 text-sm text-gray-500">Este formulario no tiene campos configurados.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Estadísticas</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Total Submissions</span>
                                <span class="text-lg font-semibold text-gray-900">{{ $form->formSubmissions->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Última Submission</span>
                                <span class="text-sm text-gray-900">
                                    @if($form->formSubmissions->count() > 0)
                                        {{ $form->formSubmissions->sortByDesc('submitted_at')->first()->submitted_at->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Fecha de Creación</span>
                                <span class="text-sm text-gray-900">{{ $form->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Última Actualización</span>
                                <span class="text-sm text-gray-900">{{ $form->updated_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 card">
                    <div class="card-header">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Acciones</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            <a href="{{ route('admin.forms.edit', $form) }}" 
                               class="w-full btn-outline flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar Formulario
                            </a>
                            
                            @if($form->is_active)
                                <form method="POST" action="{{ route('admin.forms.deactivate', $form) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full btn-outline text-yellow-600 border-yellow-300 hover:bg-yellow-50 flex items-center justify-center"
                                            onclick="return confirm('¿Estás seguro de desactivar este formulario?')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                        Desactivar
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.forms.activate', $form) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full btn-outline text-green-600 border-green-300 hover:bg-green-50 flex items-center justify-center"
                                            onclick="return confirm('¿Estás seguro de activar este formulario?')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Activar
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('admin.submissions.index', ['form_id' => $form->id]) }}" 
                               class="w-full btn-primary flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Ver Submissions
                            </a>

                            <form method="POST" action="{{ route('admin.forms.destroy', $form) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full btn-outline text-red-600 border-red-300 hover:bg-red-50 flex items-center justify-center"
                                        onclick="return confirm('¿Estás seguro de eliminar este formulario? Esta acción no se puede deshacer.')">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target;
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
