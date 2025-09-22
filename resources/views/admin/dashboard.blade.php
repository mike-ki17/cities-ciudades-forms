@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Resumen general del sistema de formularios')

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
        <div class="admin-card overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6" style="color: #00ffbd;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Ciudades</dt>
                            <dd class="text-lg font-medium admin-text">{{ $totalCities }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6" style="color: #bb2558;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Formularios</dt>
                            <dd class="text-lg font-medium admin-text">{{ $totalForms }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6" style="color: #00ffbd;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Formularios Activos</dt>
                            <dd class="text-lg font-medium admin-text">{{ $activeForms }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6" style="color: #bb2558;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Participantes</dt>
                            <dd class="text-lg font-medium admin-text">{{ $totalParticipants }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6" style="color: #00ffbd;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Respuestas</dt>
                            <dd class="text-lg font-medium admin-text">{{ $totalSubmissions }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent Submissions -->
        <div class="admin-card shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium admin-text mb-4">Respuestas Recientes</h3>
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @forelse($recentSubmissions as $submission)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full" style="background: #00ffbd; color: #000000;" class="flex items-center justify-center">
                                            <span class="text-sm font-medium">
                                                {{ substr($submission->participant->first_name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium admin-text truncate">
                                            {{ $submission->participant->full_name }}
                                        </p>
                                        <p class="text-sm admin-text-secondary truncate">
                                            {{ $submission->form->name }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 text-sm admin-text-secondary">
                                        {{ $submission->submitted_at->diffForHumans() }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-center admin-text-secondary">
                                No hay respuestas recientes
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.submissions.index') }}" class="w-full flex justify-center items-center px-4 py-2 admin-button-outline shadow-sm text-sm font-medium rounded-md">
                        Ver todas las respuestas
                    </a>
                </div>
            </div>
        </div>

        <!-- Top Forms -->
        <div class="admin-card shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium admin-text mb-4">Formularios Más Populares</h3>
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @forelse($topForms as $form)
                            <li class="py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium admin-text truncate">
                                            {{ $form->name }}
                                        </p>
                                        <p class="text-sm admin-text-secondary truncate">
                                            {{ $form->event?->full_name ?? 'General' }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 text-sm admin-text-secondary">
                                        {{ $form->form_submissions_count }} respuestas
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-center admin-text-secondary">
                                No hay formularios con respuestas
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.forms.index') }}" class="w-full flex justify-center items-center px-4 py-2 admin-button-outline shadow-sm text-sm font-medium rounded-md">
                        Gestionar formularios
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions by City -->
    <div class="admin-card shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium admin-text mb-4">Respuestas por Ciudad</h3>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead style="background: #333333;">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Ciudad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Formularios Activos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Participantes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Respuestas</th>
                        </tr>
                    </thead>
                    <tbody class="admin-card divide-y" style="border-color: #333333;">
                        @forelse($submissionsByEvent as $event)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium admin-text">
                                    {{ $event->full_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm admin-text-secondary">
                                    {{ $event->forms_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm admin-text-secondary">
                                    {{ $event->participants_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm admin-text-secondary">
                                    {{ $event->submissions_count }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center admin-text-secondary">
                                    No hay datos disponibles
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
