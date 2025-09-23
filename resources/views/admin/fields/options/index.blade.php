@extends('layouts.admin')

@section('title', 'Opciones del Campo')

@section('page-title', 'Opciones del Campo: ' . $field->name)
@section('page-description', 'Gestiona las opciones disponibles para el campo')

@section('page-actions')
    <div class="flex space-x-2">
        <a href="{{ route('admin.fields.options.create', $field) }}" class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nueva Opción
        </a>
        <a href="{{ route('admin.fields.index') }}" class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a Campos
        </a>
    </div>
@endsection

@section('content')
    <!-- Información del campo -->
    <div class="admin-form-section mb-6">
        <div class="admin-form-section-title">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Información del Campo
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <div class="text-sm admin-text-secondary">Nombre</div>
                <div class="admin-text font-medium">{{ $field->name }}</div>
            </div>
            <div>
                <div class="text-sm admin-text-secondary">Código</div>
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                    {{ $field->code }}
                </div>
            </div>
            <div>
                <div class="text-sm admin-text-secondary">Estado</div>
                <div>
                    @if($field->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                            Activo
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                            Inactivo
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de opciones -->
    <div class="admin-card rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-600">
            <h3 class="text-lg font-medium admin-text">Lista de Opciones</h3>
            <p class="mt-1 text-sm admin-text-secondary">
                Gestiona las opciones disponibles para este campo
            </p>
        </div>

        @if($options->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-600">
                    <thead class="admin-table-header">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Orden
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Valor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Etiqueta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Descripción
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium admin-text-secondary uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-600" id="sortable-options">
                        @foreach($options as $option)
                            <tr class="admin-table-row" data-option-id="{{ $option->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400 cursor-move" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                        </svg>
                                        <span class="text-sm admin-text">{{ $option->order }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                        {{ $option->value }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium admin-text">{{ $option->label }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm admin-text-secondary">
                                        {{ $option->description ? Str::limit($option->description, 50) : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($option->is_active)
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
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- Editar -->
                                        <a href="{{ route('admin.fields.options.edit', [$field, $option]) }}" 
                                           class="admin-link hover:admin-text" 
                                           title="Editar opción">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        <!-- Toggle Status -->
                                        <form method="POST" action="{{ route('admin.fields.options.toggle-status', [$field, $option]) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="admin-link hover:admin-text" 
                                                    title="{{ $option->is_active ? 'Desactivar' : 'Activar' }} opción">
                                                @if($option->is_active)
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
                                        <form method="POST" action="{{ route('admin.fields.options.destroy', [$field, $option]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-400 hover:text-red-300" 
                                                    title="Eliminar opción"
                                                    onclick="return confirm('¿Estás seguro de eliminar esta opción? Esta acción no se puede deshacer.')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-600">
                {{ $options->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 admin-text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium admin-text">No hay opciones</h3>
                <p class="mt-1 text-sm admin-text-secondary">
                    Este campo no tiene opciones configuradas. Agrega la primera opción.
                </p>
                <div class="mt-6">
                    <a href="{{ route('admin.fields.options.create', $field) }}" class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nueva Opción
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortableElement = document.getElementById('sortable-options');
    
    if (sortableElement) {
        new Sortable(sortableElement, {
            handle: '.cursor-move',
            animation: 150,
            onEnd: function(evt) {
                const options = [];
                const rows = sortableElement.querySelectorAll('tr[data-option-id]');
                
                rows.forEach((row, index) => {
                    const optionId = row.getAttribute('data-option-id');
                    options.push({
                        id: optionId,
                        order: index + 1
                    });
                });
                
                // Update order via AJAX
                fetch('{{ route("admin.fields.options.order", $field) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ options: options })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update order numbers in the UI
                        rows.forEach((row, index) => {
                            const orderCell = row.querySelector('td:first-child span:last-child');
                            if (orderCell) {
                                orderCell.textContent = index + 1;
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error updating order:', error);
                    // Reload page to restore original order
                    location.reload();
                });
            }
        });
    }
});
</script>
@endpush
