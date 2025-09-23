<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - Smartfilms Forms</title>
    
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
            min-height: 100vh;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }
        
        .login-card {
            background: #1a1a1a;
            border-radius: 24px;
            padding: 3rem;
            max-width: 450px;
            width: 100%;
            border: 1px solid #222222;
            position: relative;
            z-index: 2;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #00ffbd;
            border-radius: 24px 24px 0 0;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            display: block;
        }
        
        .login-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: #00ffbd;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .login-subtitle {
            color: #cccccc;
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem 1.5rem;
            background: #2a2a2a;
            border: 2px solid #333333;
            border-radius: 12px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #00ffbd;
            background: #333333;
            box-shadow: 0 0 0 3px rgba(0, 255, 189, 0.1);
        }
        
        .form-input::placeholder {
            color: #888888;
        }
        
        .form-input.error {
            border-color: #bb2558;
        }
        
        .error-message {
            color: #bb2558;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: block;
        }
        
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .checkbox {
            width: 20px;
            height: 20px;
            accent-color: #00ffbd;
            margin-right: 0.75rem;
        }
        
        .checkbox-label {
            color: #cccccc;
            font-size: 0.95rem;
            cursor: pointer;
        }
        
        .login-button {
            width: 100%;
            background: #00ffbd;
            color: #000000;
            border: 2px solid #00ffbd;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .login-button:hover {
            background: #00e6a8;
            border-color: #00e6a8;
            transform: translateY(-2px);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .back-link {
            text-align: center;
            margin-top: 2rem;
        }
        
        .back-link a {
            color: #00ffbd;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .back-link a:hover {
            color: #00e6a8;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 1rem;
            }
            
            .login-card {
                padding: 2rem;
            }
            
            .login-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-container">
                <img src="https://d1qdwr9la23cpt.cloudfront.net/uploads/2025/Isotipo.png" alt="Smartfilms Forms" class="logo">
                <h1 class="login-title">Smartfilms Forms</h1>
                <p class="login-subtitle">Iniciar Sesión</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="form-input @error('email') error @enderror" 
                           placeholder="Correo electrónico" value="{{ old('email') }}">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="form-input @error('password') error @enderror" 
                           placeholder="Contraseña">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="checkbox-container">
                    <input id="remember" name="remember" type="checkbox" class="checkbox">
                    <label for="remember" class="checkbox-label">Recordarme</label>
                </div>
                
                <button type="submit" class="login-button">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    Iniciar Sesión
                </button>
            </form>
            
            <div class="back-link">
                <a href="{{ url('/') }}">← Volver al inicio</a>
            </div>
        </div>
    </div>
</body>
</html>
