@extends('layouts.form')

@section('title', 'Formulario Enviado Exitosamente')

@php
    // Obtener estilos del formulario con valores por defecto
    $styles = $form->styles;
    $headerImage1 = $styles['header_image_1'] ?? null;
    $headerImage2 = $styles['header_image_2'] ?? null;
    $backgroundColor = $styles['background_color'] ?? '#ffffff';
    $backgroundTexture = $styles['background_texture'] ?? null;
    $primaryColor = $styles['primary_color'] ?? '#00ffbd';
    $borderRadius = $styles['border_radius'] ?? '8px';
    $formShadow = $styles['form_shadow'] ?? true;
    
    // Configuración de tamaño de imágenes
    $image1Width = $styles['image_1_width'] ?? 200;
    $image1Height = $styles['image_1_height'] ?? 100;
    $image1ObjectFit = $styles['image_1_object_fit'] ?? 'contain';
    $image2Width = $styles['image_2_width'] ?? 150;
    $image2Height = $styles['image_2_height'] ?? 80;
    $image2ObjectFit = $styles['image_2_object_fit'] ?? 'contain';
    $imageSpacing = $styles['image_spacing'] ?? 16;
    $mobileBehavior = $styles['mobile_image_behavior'] ?? 'stack';
    $mobileScale = $styles['mobile_scale'] ?? 80;
@endphp

@section('body-style')
    style="
        background-color: {{ $backgroundColor }};
        @if(!empty($backgroundTexture))
            background-image: url('{{ $backgroundTexture }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        @endif
    "
@endsection

@section('content')
<div class="form-styled max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    @if($headerImage1 || $headerImage2)
        <div class="card-header-image" style="display: flex; justify-content: center; align-items: center; gap: {{ $imageSpacing }}px; padding: 1rem 0;">
            @if($headerImage1)
                <img src="{{ $headerImage1 }}" alt="Header Image 1" 
                     style="width: {{ $image1Width }}px; height: {{ $image1Height }}px; object-fit: {{ $image1ObjectFit }}; border-radius: {{ $borderRadius }}; transition: all 0.3s ease;"
                     class="header-image-1"
                     onerror="this.style.display='none'">
            @endif
            
            @if($headerImage2 && $mobileBehavior !== 'hide_secondary')
                <img src="{{ $headerImage2 }}" alt="Header Image 2" 
                     style="width: {{ $image2Width }}px; height: {{ $image2Height }}px; object-fit: {{ $image2ObjectFit }}; border-radius: {{ $borderRadius }}; transition: all 0.3s ease;"
                     class="header-image-2"
                     onerror="this.style.display='none'">
            @endif
        </div>
    @endif
    
    <div class="card" 
         style="border-radius: {{ $borderRadius }}; {{ $formShadow ? 'box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);' : '' }}">
        
        <div class="card-header text-center">
            <h1 class="text-2xl font-bold text-gray-900">{{ $form->name }}</h1>
            @if($form->description)
                <p class="mt-2 text-sm text-gray-600">{{ $form->description }}</p>
            @endif
            @if($form->event)
                <p class="mt-1 text-sm font-medium" style="color: {{ $primaryColor }};">{{ $form->event ? $form->event->name : 'General' }}{{ $form->event && $form->event->city ? ', ' . $form->event->city : '' }}</p>
            @endif
        </div>

        <div class="card-body">
            {{-- Mensaje de éxito principal --}}
            <div class="success-message-container text-center py-12">
                <div class="success-icon mb-6">
                    <div class="success-checkmark" style="background-color: {{ $primaryColor }};">
                        <svg class="success-checkmark-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M9 12l2 2 4-4"></path>
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                    </div>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    ¡Formulario Enviado Exitosamente!
                </h2>
                
                <p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
                    Gracias por completar el formulario. Tu información ha sido recibida correctamente y será procesada en breve.
                </p>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8 max-w-2xl mx-auto">
                    <div class="flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-green-800">Confirmación de Envío</h3>
                    </div>
                    <div class="text-sm text-green-700 space-y-2">
                        <p><strong>Fecha de envío:</strong> {{ $latestSubmission->submitted_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Formulario:</strong> {{ $form->name }}</p>
                        @if($form->event)
                            <p><strong>Evento:</strong> {{ $form->event->name }}</p>
                        @endif
                        @if($participant)
                            <p><strong>Participante:</strong> {{ $participant->name }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 max-w-2xl mx-auto">
                    <div class="flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-blue-800">Próximos Pasos</h3>
                    </div>
                    <div class="text-sm text-blue-700 space-y-2">
                        <p>• Recibirás una notificación por correo electrónico con los detalles de tu participación</p>
                        <p>• Te contactaremos si necesitamos información adicional</p>
                        <p>• Mantente atento a futuras comunicaciones sobre el evento</p>
                    </div>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-6">
                        Si tienes alguna pregunta o necesitas hacer cambios, por favor contáctanos.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button onclick="window.print()" 
                                class="btn-secondary inline-flex items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                style="border-radius: {{ $borderRadius }};">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Imprimir Confirmación
                        </button>
                        
                        {{-- <a href="{{ route('public.forms.slug.show', ['slug' => $form->slug]) }}" 
                           class="btn-primary inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                           style="background-color: {{ $primaryColor }}; border-color: {{ $primaryColor }}; border-radius: {{ $borderRadius }};">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver al Formulario
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos personalizados del formulario */
.form-styled {
    --primary-color: {{ $primaryColor }};
    --border-radius: {{ $borderRadius }};
    --form-shadow: {{ $formShadow ? '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)' : 'none' }};
}

/* Estilos para el mensaje de éxito */
.success-message-container {
    animation: fadeInUp 0.6s ease-out;
}

.success-checkmark {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    animation: scaleIn 0.5s ease-out 0.2s both;
}

.success-checkmark-icon {
    width: 40px;
    height: 40px;
    color: white;
    animation: checkmark 0.6s ease-out 0.4s both;
}

/* Animaciones */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scaleIn {
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}

@keyframes checkmark {
    0% {
        stroke-dasharray: 0 50;
        stroke-dashoffset: 0;
    }
    100% {
        stroke-dasharray: 50 0;
        stroke-dashoffset: 0;
    }
}

/* Aplicar color principal a elementos interactivos */
.form-styled .btn-primary {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    border-radius: var(--border-radius) !important;
}

.form-styled .btn-primary:hover {
    background-color: color-mix(in srgb, var(--primary-color) 85%, black) !important;
    border-color: color-mix(in srgb, var(--primary-color) 85%, black) !important;
}

.form-styled .btn-primary:focus {
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 20%, transparent) !important;
}

/* Aplicar border radius a elementos */
.form-styled .card {
    box-shadow: var(--form-shadow) !important;
    border-radius: var(--border-radius) !important;
}

.form-styled .bg-green-50,
.form-styled .bg-blue-50 {
    border-radius: var(--border-radius) !important;
}

/* Estilos para las imágenes del header */
.form-styled .card-header-image {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
}

.form-styled .card-header-image img {
    border-radius: var(--border-radius);
    transition: transform 0.2s ease-in-out;
}

.form-styled .card-header-image img:hover {
    transform: scale(1.02);
}

/* Responsive: En pantallas pequeñas, las imágenes se apilan verticalmente */
@media (max-width: 768px) {
    .form-styled .card-header-image {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-styled .card-header-image img {
        max-width: 100% !important;
        max-height: 200px !important;
    }
    
    .success-checkmark {
        width: 60px;
        height: 60px;
    }
    
    .success-checkmark-icon {
        width: 30px;
        height: 30px;
    }
    
    .success-message-container h2 {
        font-size: 1.875rem;
    }
}

@media (max-width: 480px) {
    .card-header-image {
        padding: 0.5rem 0 !important;
    }
    
    @if($mobileBehavior === 'resize')
        .header-image-1 {
            width: {{ $image1Width * $mobileScale / 100 }}px !important;
            height: {{ $image1Height * $mobileScale / 100 }}px !important;
        }
        
        .header-image-2 {
            width: {{ $image2Width * $mobileScale / 100 }}px !important;
            height: {{ $image2Height * $mobileScale / 100 }}px !important;
        }
    @elseif($mobileBehavior === 'hide_secondary')
        .header-image-2 {
            display: none !important;
        }
    @endif
    
    .success-message-container h2 {
        font-size: 1.5rem;
    }
    
    .flex-col.sm\\:flex-row {
        flex-direction: column;
    }
}

/* Estilos para impresión */
@media print {
    .btn-secondary,
    .btn-primary {
        display: none !important;
    }
    
    .success-message-container {
        animation: none;
    }
    
    .success-checkmark {
        animation: none;
    }
    
    .success-checkmark-icon {
        animation: none;
    }
}
</style>
@endsection
