@extends('layouts.admin')

@section('title', 'Gestión de Eventos')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 admin-text sm:text-3xl sm:truncate">
                    Gestión de Eventos
                </h2>
                <p class="mt-1 text-sm admin-text-secondary">
                    Administra los eventos disponibles para cada ciudad
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.events.create') }}" 
                   class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo Evento
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #00ffbd;">
                            <svg class="w-5 h-5" style="color: #000000;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Total Eventos</dt>
                            <dd class="text-lg font-medium admin-text">{{ $events->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #bb2558;">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Ciudades Únicas</dt>
                            <dd class="text-lg font-medium admin-text">{{ $events->pluck('city')->unique()->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #00ffbd;">
                            <svg class="w-5 h-5" style="color: #000000;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Año Más Reciente</dt>
                            <dd class="text-lg font-medium admin-text">{{ $events->max('year') ?? 'N/A' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #bb2558;">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Formularios Totales</dt>
                            <dd class="text-lg font-medium admin-text">{{ $events->sum('forms_count') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros de búsqueda -->
        <div class="mt-8">
            <div class="admin-card">
                <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                    <h3 class="text-lg leading-6 font-medium admin-text">Filtros de Búsqueda</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.events.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Búsqueda general -->
                            <div>
                                <label for="search" class="block text-sm font-medium admin-text-secondary mb-2">
                                    Buscar por nombre, ciudad o año
                                </label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Ej: Smartfilms, Madrid, 2024..."
                                       class="admin-input w-full">
                            </div>

                            <!-- Filtro por año -->
                            <div>
                                <label for="year" class="block text-sm font-medium admin-text-secondary mb-2">
                                    Año
                                </label>
                                <select id="year" name="year" class="admin-input w-full">
                                    <option value="">Todos los años</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro por ciudad -->
                            <div>
                                <label for="city" class="block text-sm font-medium admin-text-secondary mb-2">
                                    Ciudad
                                </label>
                                <select id="city" name="city" class="admin-input w-full">
                                    <option value="">Todas las ciudades</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Botones -->
                            <div class="flex items-end space-x-2">
                                <button type="submit" 
                                        class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Buscar
                                </button>
                                <a href="{{ route('admin.events.index') }}" 
                                   class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Events Table -->
        <div class="mt-8">
            <div class="admin-card">
                <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium admin-text">Lista de Eventos</h3>
                        <div class="text-sm admin-text-secondary">
                            Mostrando {{ $events->firstItem() ?? 0 }} - {{ $events->lastItem() ?? 0 }} de {{ $events->total() }} eventos
                        </div>
                    </div>
                </div>
                <div class="p-0">
                    @if($events->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y" style="border-color: var(--color-border);">
                                <thead class="admin-table-header">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Evento
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Ciudad
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Año
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Formularios
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
                                    @foreach($events as $event)
                                        <tr class="admin-table-row">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium admin-text">
                                                        {{ $event->name }}
                                                    </div>
                                                    <div class="text-sm admin-text-secondary">
                                                        {{ $event->full_name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(0, 255, 189, 0.1); color: #00ffbd;">
                                                    {{ $event->city }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm admin-text">
                                                {{ $event->year }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm admin-text">
                                                {{ $event->forms->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm admin-text-secondary">
                                                {{ $event->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <a href="{{ route('admin.events.show', $event) }}" 
                                                       class="admin-link hover:admin-text">
                                                        Ver
                                                    </a>
                                                    <a href="{{ route('admin.events.edit', $event) }}" 
                                                       class="admin-link hover:admin-text">
                                                        Editar
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="admin-text-secondary hover:admin-text"
                                                                onclick="return confirm('¿Estás seguro de eliminar este evento? Esta acción no se puede deshacer.')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        @if($events->hasPages())
                            <div class="px-6 py-4 border-t" style="border-color: var(--color-border);">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm admin-text-secondary">
                                        Mostrando {{ $events->firstItem() ?? 0 }} - {{ $events->lastItem() ?? 0 }} de {{ $events->total() }} resultados
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        {{ $events->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium admin-text">
                                @if(request()->hasAny(['search', 'year', 'city']))
                                    No se encontraron eventos con los filtros aplicados
                                @else
                                    No hay eventos
                                @endif
                            </h3>
                            <p class="mt-1 text-sm admin-text-secondary">
                                @if(request()->hasAny(['search', 'year', 'city']))
                                    Intenta ajustar los filtros de búsqueda o <a href="{{ route('admin.events.index') }}" class="admin-link">limpiar los filtros</a>.
                                @else
                                    Comienza creando tu primer evento.
                                @endif
                            </p>
                            @if(!request()->hasAny(['search', 'year', 'city']))
                                <div class="mt-6">
                                    <a href="{{ route('admin.events.create') }}" class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Nuevo Evento
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
