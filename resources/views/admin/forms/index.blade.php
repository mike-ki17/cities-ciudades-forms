@extends('layouts.admin')

@section('title', 'Gestión de Formularios')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 admin-text sm:text-3xl sm:truncate">
                    Gestión de Formularios
                </h2>
                <p class="mt-1 text-sm admin-text-secondary">
                    Administra los formularios disponibles para cada ciudad
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.forms.create') }}" 
                   class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo Formulario
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Total Formularios</dt>
                            <dd class="text-lg font-medium admin-text">{{ $forms->count() }}</dd>
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
                            <dd class="text-lg font-medium admin-text">{{ $forms->where('is_active', true)->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #00ffbd;">
                            <svg class="w-5 h-5" style="color: #000000;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Formularios Inactivos</dt>
                            <dd class="text-lg font-medium admin-text">{{ $forms->where('is_active', false)->count() }}</dd>
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
                            <dt class="text-sm font-medium admin-text-secondary truncate">Ciudades con Formularios</dt>
                            <dd class="text-lg font-medium admin-text">{{ $forms->pluck('city.name')->filter()->unique()->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms Table -->
        <div class="mt-8">
            <div class="admin-card">
                <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                    <h3 class="text-lg leading-6 font-medium admin-text">Lista de Formularios</h3>
                </div>
                <div class="p-0">
                    @if($forms->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y" style="border-color: var(--color-border);">
                                <thead class="admin-table-header">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Formulario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Ciudad
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Versión
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            Submissions
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                            URL Pública
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
                                    @foreach($forms as $form)
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
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(0, 255, 189, 0.1); color: #00ffbd;">
                                                    {{ $form->city ? $form->city->name : 'Sin ciudad' }}
                                                </span>
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
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <input type="text" 
                                                           value="{{ route('public.forms.slug.show', $form->slug) }}" 
                                                           readonly 
                                                           class="w-48 text-xs admin-input rounded px-2 py-1">
                                                    <button onclick="copyToClipboard('{{ route('public.forms.slug.show', $form->slug) }}')" 
                                                            class="text-xs px-2 py-1 admin-button-outline rounded">
                                                        Copiar
                                                    </button>
                                                </div>
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
                                                    @if($form->is_active)
                                                        <form method="POST" action="{{ route('admin.forms.deactivate', $form) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="admin-text-secondary hover:admin-text"
                                                                    onclick="return confirm('¿Estás seguro de desactivar este formulario?')">
                                                                Desactivar
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('admin.forms.activate', $form) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="admin-link hover:admin-text"
                                                                    onclick="return confirm('¿Estás seguro de activar este formulario?')">
                                                                Activar
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form method="POST" action="{{ route('admin.forms.destroy', $form) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="admin-text-secondary hover:admin-text"
                                                                onclick="return confirm('¿Estás seguro de eliminar este formulario? Esta acción no se puede deshacer.')">
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
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium admin-text">No hay formularios</h3>
                            <p class="mt-1 text-sm admin-text-secondary">Comienza creando tu primer formulario.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.forms.create') }}" class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
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
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = '¡Copiado!';
        button.classList.add('bg-green-100', 'text-green-800');
        button.classList.remove('bg-blue-100', 'text-blue-800');
        
        setTimeout(function() {
            button.textContent = originalText;
            button.classList.remove('bg-green-100', 'text-green-800');
            button.classList.add('bg-blue-100', 'text-blue-800');
        }, 2000);
    }).catch(function(err) {
        console.error('Error al copiar: ', err);
        alert('Error al copiar la URL');
    });
}
</script>
@endsection
