@extends('layouts.admin')

@section('title', 'Gestión de Campos JSON')

@section('page-title', 'Gestión de Campos JSON')
@section('page-description', 'Administra los campos individuales creados con estructura JSON')

@section('page-actions')
    <a href="{{ route('admin.fields-json.create') }}" class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nuevo Campo JSON
    </a>
    <a href="{{ route('admin.fields.index') }}" class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        Campos Tradicionales
    </a>
@endsection

@section('content')
    <div class="admin-card rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-600">
            <h3 class="text-lg font-medium admin-text">Lista de Campos JSON</h3>
            <p class="mt-1 text-sm admin-text-secondary">
                Gestiona los campos individuales creados con estructura JSON que pueden ser utilizados en los formularios
            </p>
        </div>

        @if($fields->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-600">
                    <thead class="admin-table-header">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Campo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Clave
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Creado
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-600">
                        @foreach($fields as $field)
                            <tr class="admin-table-row">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium admin-text">
                                            {{ $field->label }}
                                        </div>
                                        @if($field->description)
                                            <div class="text-sm admin-text-secondary">
                                                {{ Str::limit($field->description, 50) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                        {{ $field->key }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($field->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm admin-text-secondary">
                                    {{ $field->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- Ver detalles -->
                                        <a href="{{ route('admin.fields-json.show', $field) }}" 
                                           class="admin-link hover:admin-text" 
                                           title="Ver detalles">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        <!-- Editar -->
                                        <a href="{{ route('admin.fields-json.edit', $field) }}" 
                                           class="admin-link hover:admin-text" 
                                           title="Editar campo">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        <!-- Toggle Status -->
                                        <form method="POST" action="{{ route('admin.fields-json.toggle-status', $field) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="admin-link hover:admin-text" 
                                                    title="{{ $field->is_active ? 'Desactivar' : 'Activar' }} campo">
                                                @if($field->is_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>

                                        <!-- Eliminar -->
                                        @if($field->formFieldOrders()->count() == 0)
                                            <form method="POST" action="{{ route('admin.fields-json.destroy', $field) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-400 hover:text-red-300" 
                                                        title="Eliminar campo"
                                                        onclick="return confirm('¿Estás seguro de eliminar este campo? Esta acción no se puede deshacer.')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-600">
                {{ $fields->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 admin-text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium admin-text">No hay campos JSON</h3>
                <p class="mt-1 text-sm admin-text-secondary">
                    Comienza creando tu primer campo con estructura JSON.
                </p>
                <div class="mt-6">
                    <a href="{{ route('admin.fields-json.create') }}" class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nuevo Campo JSON
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
