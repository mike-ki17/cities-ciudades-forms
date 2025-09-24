<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smartfilms Forms</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />

        <!-- Styles / Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    
            <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #000000 url('{{ asset('build/img/TEXTURA.png') }}') center/cover no-repeat scroll;
            background-blend-mode: screen;
            background-size: 100% 100%;
            background-attachment: scroll;
            color: #ffffff;
            overflow-x: hidden;
        }
        
        .hero-section {
            min-height: 100vh;
            background: rgba(0, 0, 0, 0.1);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6rem 2rem 2rem 2rem;
            overflow: hidden;
        }
        
        
        .hero-content {
            max-width: 1200px;
            width: 100%;
            text-align: center;
            z-index: 2;
            position: relative;
        }
        
        .logo-container {
            margin-bottom: 3rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 3rem;
            flex-wrap: wrap;
        }
        
        .main-logo {
            max-width: 400px;
            width: 100%;
            height: auto;
            object-fit: contain;
            transition: all 0.3s ease;
        }
        
        .ccb-logo {
            max-width: 300px;
            width: 100%;
            height: auto;
            object-fit: contain;
            transition: all 0.3s ease;
        }
        
        .main-logo:hover,
        .ccb-logo:hover {
            transform: scale(1.02);
        }
        
        .hero-title {
            font-size: clamp(3.5rem, 8vw, 7rem);
            font-weight: 900;
            margin-bottom: 2rem;
            color: #00ffbd;
            line-height: 1.1;
        }
        
        .hero-subtitle {
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            font-weight: 400;
            margin-bottom: 3rem;
            color: #cccccc;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-buttons {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 4rem;
        }
        
        .btn {
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: #00ffbd;
            color: #000000;
            border: 2px solid #00ffbd;
        }
        
        .btn-primary:hover {
            background: #00e6a8;
            transform: translateY(-3px);
            border-color: #00e6a8;
        }
        
        .btn-secondary {
            background: transparent;
            color: #bb2558;
            border: 2px solid #bb2558;
        }
        
        .btn-secondary:hover {
            background: #bb2558;
            color: #ffffff;
            transform: translateY(-3px);
        }
        
        .forms-section {
            background: rgba(0, 0, 0, 0.2);
            padding: 6rem 2rem;
            min-height: 70vh;
            position: relative;
        }
        
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 800;
            text-align: center;
            margin-bottom: 4rem;
            color: #00ffbd;
            position: relative;
            z-index: 2;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #00ffbd;
            border-radius: 2px;
        }
        
        .forms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .form-card {
            background: #1a1a1a;
            border-radius: 24px;
            padding: 2.5rem;
            border: 1px solid #222222;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            z-index: 2;
        }
        
        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #00ffbd;
            border-radius: 24px 24px 0 0;
        }
        
        .form-card:hover {
            transform: translateY(-12px) scale(1.02);
            border-color: #00ffbd;
        }
        
        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #ffffff;
        }
        
        .form-city {
            color: #bb2558;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .form-description {
            color: #cccccc;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .form-btn {
            background: #bb2558;
            color: #ffffff;
            padding: 1rem 2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            position: relative;
            z-index: 3;
            border: 2px solid #bb2558;
        }
        
        .form-btn:hover {
            background: #a01e4a;
            transform: translateY(-3px);
            border-color: #a01e4a;
        }
        
        .no-forms {
            text-align: center;
            padding: 3rem;
            color: #666666;
            font-size: 1.2rem;
        }
        
        .admin-section {
            background: rgba(0, 0, 0, 0.2);
            padding: 6rem 2rem;
            text-align: center;
            position: relative;
        }
        
        
        .admin-card {
            background: #1a1a1a;
            border-radius: 24px;
            padding: 4rem;
            max-width: 700px;
            margin: 0 auto;
            border: 1px solid #222222;
            position: relative;
            z-index: 2;
        }
        
        .admin-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #bb2558;
            border-radius: 24px 24px 0 0;
        }
        
        .admin-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #bb2558;
        }
        
        .admin-description {
            color: #cccccc;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        
        .nav-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 1rem 2rem;
        }
        
        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.6rem;
            font-weight: 900;
            color: #00ffbd;
            letter-spacing: -0.5px;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .nav-link {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: #00ffbd;
        }
        
        .nav-btn {
            background: #bb2558;
            color: #ffffff;
            padding: 0.6rem 1.8rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #bb2558;
        }
        
        .nav-btn:hover {
            background: #a01e4a;
            transform: translateY(-2px);
            border-color: #a01e4a;
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 5rem 1rem 1rem 1rem;
            }
            
            .logo-container {
                gap: 2rem;
                flex-direction: column;
                margin-bottom: 2rem;
            }
            
            .main-logo {
                max-width: 350px;
            }
            
            .ccb-logo {
                max-width: 250px;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .forms-grid {
                grid-template-columns: 1fr;
            }
            
            .nav-links {
                display: none;
            }
        }
        
        @media (max-width: 480px) {
            .main-logo {
                max-width: 280px;
            }
            
            .ccb-logo {
                max-width: 200px;
            }
        }
            </style>
    </head>
<body>
    <!-- Navigation -->
    <nav class="nav-header">
        <div class="nav-content">
            <div class="logo flex items-center space-x-3">
                <img src="https://d1qdwr9la23cpt.cloudfront.net/uploads/2025/Isotipo.png" alt="Smartfilms Forms" class="h-8 w-8">
                <span>Smartfilms Forms</span>
            </div>
            <div class="nav-links">
                    @auth
                    <span class="nav-link">Hola, {{ Auth::user()->name }}</span>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-btn">Panel Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                            Cerrar Sesión
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="nav-link">Iniciar Sesión</a>
                    {{-- <a href="{{ route('register') }}" class="nav-btn">Registrarse</a> --}}
                @endauth
            </div>
        </div>
    </nav>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success" style="background: rgba(0, 255, 189, 0.1); color: #00ffbd; padding: 15px; margin: 20px; border-radius: 8px; border: 1px solid rgba(0, 255, 189, 0.3); text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" style="background: rgba(187, 37, 88, 0.1); color: #bb2558; padding: 15px; margin: 20px; border-radius: 8px; border: 1px solid rgba(187, 37, 88, 0.3); text-align: center;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="logo-container">
                <img src="https://deuouqyoujoig.cloudfront.net/uploads/2025/fomurlarios-ciudades/LOGO_BOGOT__.png" alt="Logo Bogotá" class="main-logo">
                <img src="https://deuouqyoujoig.cloudfront.net/uploads/2025/fomurlarios-ciudades/LOGO_CCB.png" alt="Logo CCB" class="ccb-logo">
            </div>
            <h1 class="hero-title">Smartfilms Forms</h1>
            <p class="hero-subtitle">
                Plataforma inteligente para la gestión de formularios municipales. 
                Accede a los formularios disponibles y administra el sistema de manera eficiente.
            </p>
            
            <div class="cta-buttons">
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                            Panel de Administración
                            </a>
                        @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        Iniciar Sesión
                    </a>
                    {{-- <a href="{{ route('register') }}" class="btn btn-secondary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                        </svg>
                        Registrarse
                    </a> --}}
                    @endauth
            </div>
        </div>
    </section>

    <!-- Forms Section -->
    {{-- <section class="forms-section">
        <div class="container">
            <h2 class="section-title">Formularios Disponibles</h2>
                    
                    @php
                        $forms = \App\Models\Form::with('event')->active()->get();
                    @endphp
                    
                    @if($forms->count() > 0)
                <div class="forms-grid">
                                @foreach($forms as $form)
                        <div class="form-card">
                            <h3 class="form-title">{{ $form->name }}</h3>
                            <p class="form-city">{{ $form->event ? $form->event->name : 'General' }}</p>
                            <p class="form-description">
                                {{ $form->description ?? 'Formulario municipal disponible para completar en línea.' }}
                            </p>
                            <a href="{{ route('public.forms.slug.show', ['slug' => $form->slug]) }}" class="form-btn">
                                Completar Formulario
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-left: 0.5rem;">
                                    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                                </svg>
                                        </a>
                                    </div>
                                @endforeach
                        </div>
                    @else
                <div class="no-forms">
                    <p>No hay formularios disponibles en este momento.</p>
                    <p>Vuelve pronto para acceder a los formularios municipales.</p>
                        </div>
                    @endif
                </div>
    </section> --}}

    <!-- Admin Section -->
    {{-- @guest
        <section class="admin-section">
            <div class="container">
                <div class="admin-card">
                    <h3 class="admin-title">Panel de Administración</h3>
                    <p class="admin-description">
                        ¿Eres administrador? Accede al panel de control para gestionar formularios, 
                        ver estadísticas y administrar el sistema completo.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                        Acceder al Panel
                    </a>
                </div>
        </div>
        </section>
    @endguest --}}
    </body>
</html>