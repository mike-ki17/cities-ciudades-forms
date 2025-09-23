<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smartfilms Forms - Admin - @yield('title', 'Panel de Administración')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --color-acid-green: #00ffbd;
            --color-magenta: #bb2558;
            --color-black: #000000;
            --color-dark-bg: #0f1419;
            --color-card-bg: #1a2332;
            --color-border: #2d3748;
            --color-text-primary: #e2e8f0;
            --color-text-secondary: #a0aec0;
            --color-text-muted: #718096;
        }
        
        body {
            background: var(--color-dark-bg);
            color: var(--color-text-primary);
        }
        
        .admin-sidebar {
            background: var(--color-card-bg);
            border-right: 1px solid var(--color-border);
        }
        
        .admin-header {
            background: var(--color-card-bg);
            border-bottom: 1px solid var(--color-border);
        }
        
        .admin-card {
            background: var(--color-card-bg);
            border: 1px solid var(--color-border);
        }
        
        .admin-text {
            color: var(--color-text-primary);
        }
        
        .admin-text-secondary {
            color: var(--color-text-secondary);
        }
        
        .admin-text-muted {
            color: var(--color-text-muted);
        }
        
        .admin-link {
            color: var(--color-acid-green);
        }
        
        .admin-link:hover {
            color: #00e6a8;
        }
        
        .admin-button-primary {
            background: var(--color-acid-green);
            color: var(--color-black);
            border: 1px solid var(--color-acid-green);
        }
        
        .admin-button-primary:hover {
            background: #00e6a8;
            border-color: #00e6a8;
        }
        
        .admin-button-secondary {
            background: var(--color-magenta);
            color: #ffffff;
            border: 1px solid var(--color-magenta);
        }
        
        .admin-button-secondary:hover {
            background: #a01e4a;
            border-color: #a01e4a;
        }
        
        .admin-button-outline {
            border: 1px solid var(--color-acid-green);
            color: var(--color-acid-green);
            background: transparent;
        }
        
        .admin-button-outline:hover {
            background: var(--color-acid-green);
            color: var(--color-black);
        }
        
        .admin-input {
            background: var(--color-card-bg);
            border: 1px solid var(--color-border);
            color: var(--color-text-primary);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .admin-input:focus {
            border-color: var(--color-acid-green);
            box-shadow: 0 0 0 3px rgba(0, 255, 189, 0.1);
            outline: none;
            background: rgba(0, 255, 189, 0.02);
        }
        
        .admin-input::placeholder {
            color: var(--color-text-muted);
        }
        
        .admin-input:hover {
            border-color: var(--color-acid-green);
        }
        
        .admin-textarea {
            background: var(--color-card-bg);
            border: 1px solid var(--color-border);
            color: var(--color-text-primary);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.2s ease;
            resize: vertical;
            min-height: 100px;
        }
        
        .admin-textarea:focus {
            border-color: var(--color-acid-green);
            box-shadow: 0 0 0 3px rgba(0, 255, 189, 0.1);
            outline: none;
            background: rgba(0, 255, 189, 0.02);
        }
        
        .admin-select {
            background: var(--color-card-bg);
            border: 1px solid var(--color-border);
            color: var(--color-text-primary);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .admin-select:focus {
            border-color: var(--color-acid-green);
            box-shadow: 0 0 0 3px rgba(0, 255, 189, 0.1);
            outline: none;
        }
        
        .admin-select:hover {
            border-color: var(--color-acid-green);
        }
        
        .admin-table-header {
            background: var(--color-border);
        }
        
        .admin-table-row:hover {
            background: rgba(0, 255, 189, 0.05);
        }
        
        .admin-form-group {
            margin-bottom: 1rem;
        }
        
        .admin-form-label {
            color: var(--color-text-secondary);
            font-weight: 500;
        }
        
        .admin-alert-success {
            background: rgba(0, 255, 189, 0.1);
            border: 1px solid var(--color-acid-green);
            color: var(--color-acid-green);
            border-radius: 12px;
            padding: 16px 20px;
            margin: 16px 0;
        }
        
        .admin-alert-error {
            background: rgba(187, 37, 88, 0.1);
            border: 1px solid var(--color-magenta);
            color: var(--color-magenta);
            border-radius: 12px;
            padding: 16px 20px;
            margin: 16px 0;
        }
        
        .admin-alert-warning {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid #ffc107;
            color: #ffc107;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 16px 0;
        }
        
        .admin-alert-info {
            background: rgba(13, 202, 240, 0.1);
            border: 1px solid #0dcaf0;
            color: #0dcaf0;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 16px 0;
        }
        
        .admin-confirm-dialog {
            background: var(--color-card-bg);
            border: 1px solid var(--color-border);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .admin-confirm-title {
            color: var(--color-text-primary);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .admin-confirm-message {
            color: var(--color-text-secondary);
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .admin-confirm-buttons {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }
        
        .admin-form-section {
            background: var(--color-card-bg);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .admin-form-section-title {
            color: var(--color-text-primary);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--color-border);
        }
        
        .admin-field-group {
            margin-bottom: 20px;
        }
        
        .admin-field-label {
            display: block;
            color: var(--color-text-secondary);
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .admin-field-help {
            color: var(--color-text-muted);
            font-size: 12px;
            margin-top: 4px;
        }
        
        .admin-field-error {
            color: var(--color-magenta);
            font-size: 12px;
            margin-top: 4px;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div class="flex flex-col flex-grow pt-5 admin-sidebar overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                        <img src="https://d1qdwr9la23cpt.cloudfront.net/uploads/2025/Isotipo.png" alt="Smartfilms Forms" class="h-8 w-8">
                        <span class="text-xl font-bold admin-link">Smartfilms Forms</span>
                    </a>
                </div>
                <div class="mt-5 flex-grow flex flex-col">
                    <nav class="flex-1 px-2 pb-4 space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'admin-button-primary' : 'admin-text-secondary hover:admin-button-outline' }}">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                            </svg>
                            Dashboard
                        </a>

                        <!-- Events -->
                        <a href="{{ route('admin.events.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.events.*') ? 'admin-button-primary' : 'admin-text-secondary hover:admin-button-outline' }}">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V9z" />
                            </svg>
                            Eventos
                        </a>

                        <!-- Forms -->
                        <a href="{{ route('admin.forms.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.forms.*') ? 'admin-button-primary' : 'admin-text-secondary hover:admin-button-outline' }}">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Formularios
                        </a>

                        <!-- Submissions -->
                        <a href="{{ route('admin.submissions.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.submissions.*') ? 'admin-button-primary' : 'admin-text-secondary hover:admin-button-outline' }}">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Respuestas
                        </a>

                        <!-- Statistics -->
                        <a href="{{ route('admin.submissions.statistics') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.submissions.statistics') ? 'admin-button-primary' : 'admin-text-secondary hover:admin-button-outline' }}">
                            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Estadísticas
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Top bar -->
            <div class="relative z-10 flex-shrink-0 flex h-16 admin-header shadow">
                <button type="button" class="px-4 border-r border-gray-600 admin-text-secondary focus:outline-none focus:ring-2 focus:ring-inset focus:ring-acid-green md:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex-1 px-4 flex justify-end">
                    <div class="flex items-center">
                        <!-- Profile dropdown -->
                        <div class="ml-3 relative">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm admin-text">Hola, {{ Auth::user()->name }}</span>
                                <a href="{{ url('/') }}" class="text-sm admin-link hover:admin-text">
                                    Ver Sitio
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm admin-text-secondary hover:admin-text focus:outline-none">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        <!-- Page header -->
                        <div class="md:flex md:items-center md:justify-between">
                            <div class="flex-1 min-w-0">
                                <h2 class="text-2xl font-bold leading-7 admin-text sm:text-3xl sm:truncate">
                                    @yield('page-title', 'Panel de Administración')
                                </h2>
                                @hasSection('page-description')
                                    <p class="mt-1 text-sm admin-text-secondary">
                                        @yield('page-description')
                                    </p>
                                @endif
                            </div>
                            @hasSection('page-actions')
                                <div class="mt-4 flex md:mt-0 md:ml-4">
                                    @yield('page-actions')
                                </div>
                            @endif
                        </div>

                        <!-- Flash Messages -->
                        @if(session('success'))
                            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mt-4" role="alert">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mt-4" role="alert">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Page content -->
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Confirmation Dialog -->
    <div id="confirmDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="admin-confirm-dialog max-w-md w-full mx-4">
            <div class="admin-confirm-title" id="confirmTitle">Confirmar Acción</div>
            <div class="admin-confirm-message" id="confirmMessage">¿Estás seguro de realizar esta acción?</div>
            <div class="admin-confirm-buttons">
                <button type="button" id="confirmCancel" class="admin-button-outline px-4 py-2 rounded-md text-sm font-medium">
                    Cancelar
                </button>
                <button type="button" id="confirmOk" class="admin-button-secondary px-4 py-2 rounded-md text-sm font-medium">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Enhanced confirmation dialog
        function showConfirmDialog(title, message, onConfirm) {
            const dialog = document.getElementById('confirmDialog');
            const titleEl = document.getElementById('confirmTitle');
            const messageEl = document.getElementById('confirmMessage');
            const cancelBtn = document.getElementById('confirmCancel');
            const okBtn = document.getElementById('confirmOk');
            
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            dialog.classList.remove('hidden');
            
            const cleanup = () => {
                dialog.classList.add('hidden');
                cancelBtn.onclick = null;
                okBtn.onclick = null;
            };
            
            cancelBtn.onclick = cleanup;
            okBtn.onclick = () => {
                cleanup();
                onConfirm();
            };
        }
        
        // Enhanced form confirmations
        document.addEventListener('DOMContentLoaded', function() {
            // Replace default confirm dialogs
            const deleteButtons = document.querySelectorAll('button[onclick*="confirm"]');
            deleteButtons.forEach(button => {
                const originalOnclick = button.getAttribute('onclick');
                if (originalOnclick && originalOnclick.includes('confirm')) {
                    button.removeAttribute('onclick');
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        const form = this.closest('form');
                        const action = this.textContent.trim();
                        
                        let title, message;
                        if (action.includes('eliminar') || action.includes('Eliminar')) {
                            title = 'Eliminar Formulario';
                            message = '¿Estás seguro de eliminar este formulario? Esta acción no se puede deshacer y se perderán todos los datos asociados.';
                        } else if (action.includes('desactivar') || action.includes('Desactivar')) {
                            title = 'Desactivar Formulario';
                            message = '¿Estás seguro de desactivar este formulario? Los usuarios no podrán acceder a él hasta que lo reactives.';
                        } else if (action.includes('activar') || action.includes('Activar')) {
                            title = 'Activar Formulario';
                            message = '¿Estás seguro de activar este formulario? Los usuarios podrán acceder a él inmediatamente.';
                        } else {
                            title = 'Confirmar Acción';
                            message = '¿Estás seguro de realizar esta acción?';
                        }
                        
                        showConfirmDialog(title, message, () => {
                            if (form) {
                                form.submit();
                            }
                        });
                    });
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
