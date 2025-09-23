@extends('layouts.admin')

@section('title', 'Gestión de Submissions')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 admin-text sm:text-3xl sm:truncate">
                    Gestión de Submissions
                </h2>
                <p class="mt-1 text-sm admin-text-secondary">
                    Administra las respuestas de los formularios
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('admin.submissions.export') }}" 
                   class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar CSV
                </a>
                <a href="{{ route('admin.submissions.statistics') }}" 
                   class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Estadísticas
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="mt-8">
            <div class="admin-form-section">
                <div class="admin-form-section-title">
                    <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtros de Búsqueda
                </div>
                
                <form method="GET" action="{{ route('admin.submissions.index') }}" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <!-- City Filter -->
                        <div class="admin-field-group">
                            <label for="event_id" class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ciudad
                            </label>
                            <select name="event_id" id="event_id" class="admin-select w-full">
                                <option value="">Todos los eventos</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Form Filter -->
                        <div class="admin-field-group">
                            <label for="form_id" class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Formulario
                            </label>
                            <select name="form_id" id="form_id" class="admin-select w-full">
                                <option value="">Todos los formularios</option>
                                @foreach($forms as $form)
                                    <option value="{{ $form->id }}" {{ request('form_id') == $form->id ? 'selected' : '' }}>
                                        {{ $form->name }} ({{ $form->event ? $form->event->full_name : 'General' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date From -->
                        <div class="admin-field-group">
                            <label for="date_from" class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Fecha Desde
                            </label>
                            <input type="date" name="date_from" id="date_from" 
                                   value="{{ request('date_from') }}" class="admin-input w-full">
                        </div>

                        <!-- Date To -->
                        <div class="admin-field-group">
                            <label for="date_to" class="admin-field-label">
                                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Fecha Hasta
                            </label>
                            <input type="date" name="date_to" id="date_to" 
                                   value="{{ request('date_to') }}" class="admin-input w-full">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t" style="border-color: var(--color-border);">
                        <button type="submit" class="admin-button-primary px-6 py-3 rounded-lg text-sm font-medium">
                            <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Aplicar Filtros
                        </button>
                        
                        @if(request()->hasAny(['event_id', 'form_id', 'date_from', 'date_to']))
                            <a href="{{ route('admin.submissions.index') }}" class="admin-button-outline px-6 py-3 rounded-lg text-sm font-medium">
                                <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Limpiar Filtros
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="mt-8">
            <div class="admin-form-section">
                <div class="admin-form-section-title">
                    <svg class="w-5 h-5 inline-block mr-2" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Estadísticas de Respuestas
                </div>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="admin-card rounded-lg p-6 border-l-4" style="border-left-color: #00ffbd;">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium admin-text-secondary truncate">Total Respuestas</dt>
                                    <dd class="text-2xl font-bold admin-text">{{ $submissions->total() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card rounded-lg p-6 border-l-4" style="border-left-color: #bb2558;">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium admin-text-secondary truncate">Hoy</dt>
                                    <dd class="text-2xl font-bold admin-text">{{ $submissions->where('submitted_at', '>=', today())->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card rounded-lg p-6 border-l-4" style="border-left-color: #00ffbd;">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium admin-text-secondary truncate">Esta Semana</dt>
                                    <dd class="text-2xl font-bold admin-text">{{ $submissions->where('submitted_at', '>=', now()->startOfWeek())->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card rounded-lg p-6 border-l-4" style="border-left-color: #bb2558;">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium admin-text-secondary truncate">Este Mes</dt>
                                    <dd class="text-2xl font-bold admin-text">{{ $submissions->where('submitted_at', '>=', now()->startOfMonth())->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submissions Table -->
        <div class="mt-8">
            <div class="admin-form-section">
                <div class="admin-form-section-title">
                    <svg class="w-5 h-5 inline-block mr-2" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Lista de Respuestas
                </div>
                
                @if($submissions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y" style="border-color: var(--color-border);">
                            <thead class="admin-table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                        <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Participante
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                        <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Formulario
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                        <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Ciudad
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                        <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Fecha de Envío
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                        <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="admin-card divide-y" style="border-color: var(--color-border);">
                                @foreach($submissions as $submission)
                                    <tr class="admin-table-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full admin-alert-success flex items-center justify-center">
                                                        <svg class="h-5 w-5" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium admin-text">
                                                        @if($submission->participant)
                                                            {{ $submission->participant->first_name }} {{ $submission->participant->last_name }}
                                                        @else
                                                            <span class="text-red-500">Participante no encontrado</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-sm admin-text-secondary">
                                                        @if($submission->participant)
                                                            {{ $submission->participant->email }}
                                                        @else
                                                            <span class="text-red-500">No disponible</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm admin-text font-medium">
                                                @if($submission->form)
                                                    {{ $submission->form->name }}
                                                @else
                                                    <span class="text-red-500">Formulario no encontrado</span>
                                                @endif
                                            </div>
                                            <div class="text-sm admin-text-secondary">
                                                @if($submission->form)
                                                    v{{ $submission->form->version }}
                                                @else
                                                    <span class="text-red-500">N/A</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium admin-alert-success">
                                                <svg class="w-4 h-4 mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                @if($submission->form && $submission->form->event)
                                                    {{ $submission->form->event->full_name }}
                                                @else
                                                    General
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm admin-text font-medium">{{ $submission->submitted_at->format('d/m/Y') }}</div>
                                            <div class="text-sm admin-text-secondary">{{ $submission->submitted_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.submissions.show', $submission) }}" 
                                               class="admin-button-outline px-4 py-2 rounded-lg text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver Detalles
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="admin-card px-6 py-4 border-t" style="border-color: var(--color-border);">
                        {{ $submissions->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 admin-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium admin-text">No hay respuestas</h3>
                        <p class="mt-2 text-sm admin-text-secondary">No se encontraron respuestas con los filtros aplicados.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
