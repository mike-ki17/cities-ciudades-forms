@extends('layouts.admin')

@section('title', 'Ver Evento')

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
                                <span class="ml-4 text-sm font-medium admin-text-secondary">{{ $event->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 admin-text sm:text-3xl sm:truncate">
                    {{ $event->name }}
                </h2>
                <p class="mt-1 text-sm admin-text-secondary">
                    {{ $event->full_name }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('admin.events.edit', $event) }}" 
                   class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('admin.forms.create', ['event_id' => $event->id]) }}" 
                   class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo Formulario
                </a>
            </div>
        </div>

        <!-- Event Information -->
        <div class="mt-8">
            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 rounded-lg flex items-center justify-center" style="background: #00ffbd;">
                            <svg class="w-8 h-8" style="color: #000000;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold admin-text">{{ $event->name }}</h3>
                        <p class="text-sm admin-text-secondary">{{ $event->full_name }}</p>
                        <div class="mt-2 flex items-center space-x-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(0, 255, 189, 0.1); color: #00ffbd;">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $event->city }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(187, 37, 88, 0.1); color: #bb2558;">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9z"></path>
                                </svg>
                                {{ $event->year }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #00ffbd;">
                            <svg class="w-5 h-5" style="color: #000000;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Formularios</dt>
                            <dd class="text-lg font-medium admin-text">{{ $event->forms->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #bb2558;">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Formularios Activos</dt>
                            <dd class="text-lg font-medium admin-text">{{ $event->forms->where('is_active', true)->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #00ffbd;">
                            <svg class="w-5 h-5" style="color: #000000;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Participantes</dt>
                            <dd class="text-lg font-medium admin-text">{{ $event->participants_count }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #bb2558;">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Respuestas</dt>
                            <dd class="text-lg font-medium admin-text">{{ $event->forms->sum('formSubmissions_count') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms Section -->
        <div class="mt-8">
            <div class="admin-card">
                <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium admin-text">Formularios del Evento</h3>
                        <a href="{{ route('admin.forms.create', ['event_id' => $event->id]) }}" 
                           class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nuevo Formulario
                        </a>
                    </div>
                </div>
                <div class="p-0">
                    @if($event->forms->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y" style="border-color: var(--color-border);">
                                <thead class="admin-table-header">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Formulario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Versión
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Respuestas
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Fecha de Creación
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="admin-card divide-y" style="border-color: var(--color-border);">
                                    @foreach($event->forms as $form)
                                        <tr class="admin-table-row">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium admin-text">
                                                        {{ $form->name }}
                                                    </div>
                                                    <div class="text-sm admin-text-secondary">
                                                        {{ Str::limit($form->description, 50) }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($form->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(0, 255, 189, 0.1); color: #00ffbd;">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Activo
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(187, 37, 88, 0.1); color: #bb2558;">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Inactivo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm admin-text">
                                                v{{ $form->version }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm admin-text">
                                                {{ $form->formSubmissions->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm admin-text-secondary">
                                                {{ $form->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <a href="{{ route('admin.forms.show', $form) }}" 
                                                       class="admin-link hover:admin-text">
                                                        Ver
                                                    </a>
                                                    <a href="{{ route('admin.forms.edit', $form) }}" 
                                                       class="admin-link hover:admin-text">
                                                        Editar
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium admin-text">
                                No hay formularios para este evento
                            </h3>
                            <p class="mt-1 text-sm admin-text-secondary">
                                Crea el primer formulario para este evento.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('admin.forms.create', ['event_id' => $event->id]) }}" class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Nuevo Formulario
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Event Details -->
        <div class="mt-8">
            <div class="admin-card">
                <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                    <h3 class="text-lg leading-6 font-medium admin-text">Detalles del Evento</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium admin-text-secondary">ID del Evento</dt>
                            <dd class="mt-1 text-sm admin-text">{{ $event->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium admin-text-secondary">Fecha de Creación</dt>
                            <dd class="mt-1 text-sm admin-text">{{ $event->created_at->format('d/m/Y H:i:s') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium admin-text-secondary">Última Actualización</dt>
                            <dd class="mt-1 text-sm admin-text">{{ $event->updated_at->format('d/m/Y H:i:s') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium admin-text-secondary">Ciclos Asociados</dt>
                            <dd class="mt-1 text-sm admin-text">{{ $event->cycles->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
