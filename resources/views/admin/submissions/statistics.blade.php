@extends('layouts.admin')

@section('title', 'Estadísticas de Formularios')

@section('page-title', 'Estadísticas de Formularios')
@section('page-description', 'Análisis detallado de las respuestas y comportamiento de los usuarios')

<style>
/* Estilos adicionales para las gráficas */
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}

.chart-container canvas {
    max-height: 300px !important;
}

/* Animación de carga */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Mejoras para las tarjetas de estadísticas */
.admin-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.admin-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Estilos para los filtros */
.admin-input:focus {
    transform: scale(1.02);
}

/* Mejoras para las gráficas */
canvas {
    border-radius: 8px;
}
</style>

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filtros -->
        <div class="mb-8">
            <div class="admin-card">
                <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                    <h3 class="text-lg leading-6 font-medium admin-text">Filtros de Estadísticas</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.submissions.statistics') }}" class="space-y-4" id="statisticsForm">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Filtro por formulario -->
                            <div>
                                <label for="form_id" class="block text-sm font-medium admin-text-secondary mb-2">
                                    Formulario
                                </label>
                                <select id="form_id" name="form_id" class="admin-input w-full">
                                    <option value="">Todos los formularios</option>
                                    @foreach($forms as $form)
                                        <option value="{{ $form->id }}" {{ request('form_id') == $form->id ? 'selected' : '' }}>
                                            {{ $form->name }} ({{ $form->city->name ?? 'General' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro por ciudad -->
                            <div>
                                <label for="city_id" class="block text-sm font-medium admin-text-secondary mb-2">
                                    Evento
                                </label>
                                <select id="city_id" name="city_id" class="admin-input w-full">
                                    <option value="">Todos los eventos</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" {{ request('city_id') == $event->id ? 'selected' : '' }}>
                                            {{ $event->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro por fecha desde -->
                            <div>
                                <label for="date_from" class="block text-sm font-medium admin-text-secondary mb-2">
                                    Fecha Desde
                                </label>
                                <input type="date" 
                                       id="date_from" 
                                       name="date_from" 
                                       value="{{ request('date_from', now()->subDays(30)->format('Y-m-d')) }}"
                                       class="admin-input w-full">
                            </div>

                            <!-- Filtro por fecha hasta -->
                            <div>
                                <label for="date_to" class="block text-sm font-medium admin-text-secondary mb-2">
                                    Fecha Hasta
                                </label>
                                <input type="date" 
                                       id="date_to" 
                                       name="date_to" 
                                       value="{{ request('date_to', now()->format('Y-m-d')) }}"
                                       class="admin-input w-full">
                            </div>

                            <!-- Botones -->
                            <div class="flex items-end space-x-2">
                                <button type="submit" 
                                        class="admin-button-primary px-4 py-2 rounded-md text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Actualizar
                                </button>
                                <a href="{{ route('admin.submissions.statistics') }}" 
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

        <!-- Tarjetas de estadísticas principales -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
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
                            <dt class="text-sm font-medium admin-text-secondary truncate">Total Respuestas</dt>
                            <dd class="text-lg font-medium admin-text">{{ number_format($statistics['total_submissions']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #bb2558;">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Participantes Únicos</dt>
                            <dd class="text-lg font-medium admin-text">{{ number_format($statistics['unique_participants']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #00ffbd;">
                            <svg class="w-5 h-5" style="color: #000000;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Promedio por Día</dt>
                            <dd class="text-lg font-medium admin-text">{{ $statistics['avg_submissions_per_day'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="admin-card rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: #bb2558;">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium admin-text-secondary truncate">Tasa de Conversión</dt>
                            <dd class="text-lg font-medium admin-text">{{ $statistics['conversion_rate'] }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Gráfica de respuestas por fecha -->
            <div class="admin-card">
                <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                    <h3 class="text-lg leading-6 font-medium admin-text">Respuestas por Fecha</h3>
                    <p class="text-sm admin-text-secondary">Evolución temporal de las respuestas</p>
                </div>
                <div class="p-6">
                    <div class="chart-container">
                        <canvas id="submissionsByDateChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfica de respuestas por formulario -->
            <div class="admin-card">
                <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                    <h3 class="text-lg leading-6 font-medium admin-text">Respuestas por Formulario</h3>
                    <p class="text-sm admin-text-secondary">Distribución de respuestas por formulario</p>
                </div>
                <div class="p-6">
                    <div class="chart-container">
                        <canvas id="submissionsByFormChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Gráfica de respuestas por ciudad -->
            <div class="admin-card">
                <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                    <h3 class="text-lg leading-6 font-medium admin-text">Respuestas por Ciudad</h3>
                    <p class="text-sm admin-text-secondary">Distribución geográfica de las respuestas</p>
                </div>
                <div class="p-6">
                    <div class="chart-container">
                        <canvas id="submissionsByCityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfica de respuestas por hora -->
            <div class="admin-card">
                <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                    <h3 class="text-lg leading-6 font-medium admin-text">Respuestas por Hora del Día</h3>
                    <p class="text-sm admin-text-secondary">Patrones de actividad por hora</p>
                </div>
                <div class="p-6">
                    <div class="chart-container">
                        <canvas id="submissionsByHourChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfica de respuestas por día de la semana -->
        <div class="admin-card mb-8">
            <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                <h3 class="text-lg leading-6 font-medium admin-text">Respuestas por Día de la Semana</h3>
                <p class="text-sm admin-text-secondary">Patrones de actividad semanal</p>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="submissionsByDayOfWeekChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Información del período -->
        <div class="admin-card">
            <div class="px-6 py-4 border-b" style="border-color: var(--color-border);">
                <h3 class="text-lg leading-6 font-medium admin-text">Información del Período</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold admin-text">{{ \Carbon\Carbon::parse($statistics['date_range']['from'])->format('d/m/Y') }}</div>
                        <div class="text-sm admin-text-secondary">Fecha de Inicio</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold admin-text">{{ \Carbon\Carbon::parse($statistics['date_range']['to'])->format('d/m/Y') }}</div>
                        <div class="text-sm admin-text-secondary">Fecha de Fin</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold admin-text">{{ \Carbon\Carbon::parse($statistics['date_range']['from'])->diffInDays(\Carbon\Carbon::parse($statistics['date_range']['to'])) + 1 }} días</div>
                        <div class="text-sm admin-text-secondary">Período Analizado</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Loading statistics charts...');
    
    // Verificar que Chart.js esté cargado
    if (typeof Chart === 'undefined') {
        console.error('Chart.js no está cargado');
        return;
    }
    
    // Configuración de colores del tema
    const primaryColor = '#00ffbd';
    const secondaryColor = '#bb2558';
    const backgroundColor = 'rgba(0, 255, 189, 0.1)';
    const borderColor = 'rgba(0, 255, 189, 0.8)';
    
    // Datos de las estadísticas
    const statistics = @json($statistics);
    console.log('Statistics data:', statistics);
    
    // Configuración común para las gráficas
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#e2e8f0',
                    usePointStyle: true,
                    padding: 20
                }
            },
            tooltip: {
                backgroundColor: '#1a2332',
                titleColor: '#e2e8f0',
                bodyColor: '#a0aec0',
                borderColor: '#2d3748',
                borderWidth: 1
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#a0aec0'
                },
                grid: {
                    color: '#2d3748',
                    drawBorder: false
                },
                border: {
                    color: '#2d3748'
                }
            },
            y: {
                ticks: {
                    color: '#a0aec0'
                },
                grid: {
                    color: '#2d3748',
                    drawBorder: false
                },
                border: {
                    color: '#2d3748'
                }
            }
        }
    };

    // Gráfica de respuestas por fecha
    try {
        const submissionsByDateCtx = document.getElementById('submissionsByDateChart').getContext('2d');
        const submissionsByDateData = statistics.submissions_by_date.map(item => ({
            x: item.date,
            y: item.count
        }));
        
        console.log('Creating date chart with data:', submissionsByDateData);
        
        new Chart(submissionsByDateCtx, {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Respuestas',
                    data: submissionsByDateData,
                    borderColor: primaryColor,
                    backgroundColor: backgroundColor,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: primaryColor,
                    pointBorderColor: '#000000',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    ...chartOptions.scales,
                    x: {
                        ...chartOptions.scales.x,
                        type: 'time',
                        time: {
                            unit: 'day',
                            displayFormats: {
                                day: 'MMM dd'
                            }
                        }
                    }
                }
            }
        });
        console.log('Date chart created successfully');
    } catch (error) {
        console.error('Error creating date chart:', error);
    }

    // Gráfica de respuestas por formulario
    try {
        const submissionsByFormCtx = document.getElementById('submissionsByFormChart').getContext('2d');
        const formLabels = Object.keys(statistics.submissions_by_form);
        const formData = Object.values(statistics.submissions_by_form);
        
        console.log('Creating form chart with data:', { labels: formLabels, data: formData });
        
        new Chart(submissionsByFormCtx, {
            type: 'doughnut',
            data: {
                labels: formLabels,
                datasets: [{
                    data: formData,
                    backgroundColor: [
                        primaryColor,
                        secondaryColor,
                        '#ffc107',
                        '#17a2b8',
                        '#6f42c1',
                        '#fd7e14',
                        '#20c997',
                        '#e83e8c'
                    ],
                    borderColor: '#1a2332',
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#e2e8f0',
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1a2332',
                        titleColor: '#e2e8f0',
                        bodyColor: '#a0aec0',
                        borderColor: '#2d3748',
                        borderWidth: 1
                    }
                }
            }
        });
        console.log('Form chart created successfully');
    } catch (error) {
        console.error('Error creating form chart:', error);
    }

    // Gráfica de respuestas por ciudad
    try {
        const submissionsByCityCtx = document.getElementById('submissionsByCityChart').getContext('2d');
        const cityLabels = Object.keys(statistics.submissions_by_city);
        const cityData = Object.values(statistics.submissions_by_city);
        
        console.log('Creating city chart with data:', { labels: cityLabels, data: cityData });
        
        new Chart(submissionsByCityCtx, {
            type: 'bar',
            data: {
                labels: cityLabels,
                datasets: [{
                    label: 'Respuestas',
                    data: cityData,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: chartOptions
        });
        console.log('City chart created successfully');
    } catch (error) {
        console.error('Error creating city chart:', error);
    }

    // Gráfica de respuestas por hora
    try {
        const submissionsByHourCtx = document.getElementById('submissionsByHourChart').getContext('2d');
        const hourData = Array.from({length: 24}, (_, i) => {
            const hourData = statistics.submissions_by_hour.find(h => h.hour === i);
            return hourData ? hourData.count : 0;
        });
        
        console.log('Creating hour chart with data:', hourData);
        
        new Chart(submissionsByHourCtx, {
            type: 'bar',
            data: {
                labels: Array.from({length: 24}, (_, i) => `${i}:00`),
                datasets: [{
                    label: 'Respuestas',
                    data: hourData,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    ...chartOptions.scales,
                    x: {
                        ...chartOptions.scales.x,
                        ticks: {
                            ...chartOptions.scales.x.ticks,
                            maxTicksLimit: 12
                        }
                    }
                }
            }
        });
        console.log('Hour chart created successfully');
    } catch (error) {
        console.error('Error creating hour chart:', error);
    }

    // Gráfica de respuestas por día de la semana
    try {
        const submissionsByDayOfWeekCtx = document.getElementById('submissionsByDayOfWeekChart').getContext('2d');
        const dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        const dayData = Array.from({length: 7}, (_, i) => {
            const dayData = statistics.submissions_by_day_of_week.find(d => d.day_of_week === i + 1);
            return dayData ? dayData.count : 0;
        });
        
        console.log('Creating day of week chart with data:', { labels: dayNames, data: dayData });
        
        new Chart(submissionsByDayOfWeekCtx, {
            type: 'bar',
            data: {
                labels: dayNames,
                datasets: [{
                    label: 'Respuestas',
                    data: dayData,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: chartOptions
        });
        console.log('Day of week chart created successfully');
    } catch (error) {
        console.error('Error creating day of week chart:', error);
    }

    // Auto-submit del formulario cuando cambien los filtros
    const form = document.getElementById('statisticsForm');
    const formInputs = form.querySelectorAll('select, input[type="date"]');
    
    formInputs.forEach(input => {
        input.addEventListener('change', function() {
            form.submit();
        });
    });
    
    console.log('All charts loaded successfully!');
});
</script>

<!-- Loading indicator -->
<div id="chartsLoading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="admin-card p-8 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-acid-green mx-auto mb-4"></div>
        <p class="admin-text">Cargando gráficas...</p>
    </div>
</div>

<script>
// Mostrar indicador de carga
document.addEventListener('DOMContentLoaded', function() {
    const loadingIndicator = document.getElementById('chartsLoading');
    if (loadingIndicator) {
        loadingIndicator.classList.remove('hidden');
        
        // Ocultar después de 2 segundos
        setTimeout(() => {
            loadingIndicator.classList.add('hidden');
        }, 2000);
    }
});
</script>
@endsection
