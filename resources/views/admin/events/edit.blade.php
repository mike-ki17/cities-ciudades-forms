@extends('layouts.admin')

@section('title', 'Editar Evento')

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
                                <a href="{{ route('admin.events.index') }}" class="admin-text-muted hover:admin-text-secondary">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    <span class="sr-only">Eventos</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.events.show', $event) }}" class="ml-4 text-sm font-medium admin-text-secondary hover:admin-text">
                                    {{ $event->name }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-4 text-sm font-medium admin-text-secondary">Editar</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 admin-text sm:text-3xl sm:truncate">
                    Editar Evento: {{ $event->name }}
                </h2>
                <p class="mt-1 text-sm admin-text-secondary">
                    Modifica la información del evento
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="mt-8">
            <form method="POST" action="{{ route('admin.events.update', $event) }}" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Basic Information Section -->
                <div class="admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Información del Evento
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Name -->
                        <div class="admin-field-group">
                            <label for="name" class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9z"></path>
                                </svg>
                                Nombre del Evento *
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $event->name) }}" 
                                   class="admin-input w-full" placeholder="Ej: Smartfilms Festival">
                            <div class="admin-field-help">Un nombre descriptivo para identificar este evento</div>
                            @error('name')
                                <div class="admin-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- City -->
                        <div class="admin-field-group">
                            <label for="city" class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ciudad *
                            </label>
                            <input type="text" name="city" id="city" value="{{ old('city', $event->city) }}" 
                                   class="admin-input w-full" placeholder="Ej: Madrid, Barcelona, Valencia...">
                            <div class="admin-field-help">La ciudad donde se realizará el evento</div>
                            @error('city')
                                <div class="admin-field-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Year -->
                    <div class="admin-field-group">
                        <label for="year" class="admin-field-label">
                            <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9z"></path>
                            </svg>
                            Año *
                        </label>
                        <input type="number" name="year" id="year" value="{{ old('year', $event->year) }}" 
                               class="admin-input w-full" min="2000" max="{{ date('Y') + 10 }}" placeholder="2024">
                        <div class="admin-field-help">El año en que se realizará el evento ({{ date('Y') }} - {{ date('Y') + 10 }})</div>
                        @error('year')
                            <div class="admin-field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="admin-form-section">
                    <div class="admin-form-section-title">
                        <svg class="w-5 h-5 inline-block mr-2" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Vista Previa
                    </div>
                    
                    <div class="admin-card rounded-lg p-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: #00ffbd;">
                                    <svg class="w-6 h-6" style="color: #000000;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium admin-text" id="preview-name">
                                    {{ old('name', $event->name) }}
                                </h3>
                                <p class="text-sm admin-text-secondary" id="preview-full-name">
                                    {{ old('name', $event->name) }} - {{ old('city', $event->city) }}, {{ old('year', $event->year) }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(0, 255, 189, 0.1); color: #00ffbd;" id="preview-city">
                                    {{ old('city', $event->city) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="admin-field-help">
                        Esta vista previa se actualiza automáticamente mientras escribes
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t" style="border-color: var(--color-border);">
                    <a href="{{ route('admin.events.show', $event) }}" class="admin-button-outline px-6 py-3 rounded-lg text-sm font-medium">
                        <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" class="admin-button-primary px-6 py-3 rounded-lg text-sm font-medium">
                        <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Actualizar Evento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const cityInput = document.getElementById('city');
    const yearInput = document.getElementById('year');
    
    const previewName = document.getElementById('preview-name');
    const previewFullName = document.getElementById('preview-full-name');
    const previewCity = document.getElementById('preview-city');
    
    function updatePreview() {
        const name = nameInput.value || 'Nombre del Evento';
        const city = cityInput.value || 'Ciudad';
        const year = yearInput.value || new Date().getFullYear();
        
        previewName.textContent = name;
        previewFullName.textContent = `${name} - ${city}, ${year}`;
        previewCity.textContent = city;
    }
    
    // Event listeners
    nameInput.addEventListener('input', updatePreview);
    cityInput.addEventListener('input', updatePreview);
    yearInput.addEventListener('input', updatePreview);
    
    // Initial update
    updatePreview();
});
</script>
@endpush
@endsection
