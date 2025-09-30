@extends('layouts.form')

@section('title', $form->name)

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
        
        {{-- Header con imágenes si están configuradas --}}
        
        
        <div class="card-header">
            <h1 class="text-2xl font-bold text-gray-900">{{ $form->name }}</h1>
            @if($form->description)
                <p class="mt-2 text-sm text-gray-600">{{ $form->description }}</p>
            @endif
            @if($form->event)
                <p class="mt-1 text-sm font-medium" style="color: {{ $primaryColor }};">{{ $form->event ? $form->event->name : 'General' }}{{ $form->event && $form->event->city ? ', ' . $form->event->city : '' }}</p>
            @endif
        </div>

        <div class="card-body">
            @if($hasSubmitted && $latestSubmission)
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Último envío realizado
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Tu último envío fue realizado el {{ $latestSubmission->submitted_at->format('d/m/Y H:i') }}.</p>
                                <p class="mt-1 font-medium">Puedes enviar otra respuesta si lo deseas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Mostrar errores generales del formulario (excepto documento duplicado que se muestra como modal) --}}
            @if($errors->any())
                @php
                    $filteredErrors = $errors->all();
                    $documentDuplicateError = 'Ya existe un participante registrado con este tipo y número de documento.';
                    $filteredErrors = array_filter($filteredErrors, function($error) use ($documentDuplicateError) {
                        return !str_contains($error, $documentDuplicateError);
                    });
                @endphp
                
                @if(count($filteredErrors) > 0)
                    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Por favor corrige los siguientes errores:
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($filteredErrors as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <form method="POST" action="{{ route('public.forms.slug.submit', ['id' => $form->id, 'slug' => $form->slug]) }}" class="space-y-6">
                @csrf
                
                {{-- Campos fijos del participante --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos del Participante</h3>
                    
                    {{-- Nombre --}}
                    <div class="form-group mb-6">
                        <label for="first_name" class="form-label">
                            Nombre
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name') }}"
                               placeholder="Ingresa tu nombre"
                               class="form-input @error('first_name') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                               required>
                        @error('first_name')
                            <div class="mt-1 flex items-center">
                                <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="form-error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    {{-- Apellidos --}}
                    <div class="form-group mb-6">
                        <label for="last_name" class="form-label">
                            Apellidos
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name') }}"
                               placeholder="Ingresa tus apellidos"
                               class="form-input @error('last_name') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                               required>
                        @error('last_name')
                            <div class="mt-1 flex items-center">
                                <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="form-error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group mb-6">
                        <label for="email" class="form-label">
                            Correo electrónico
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="tu@email.com"
                               class="form-input @error('email') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                               required>
                        @error('email')
                            <div class="mt-1 flex items-center">
                                <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="form-error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="form-group mb-6">
                        <label for="phone" class="form-label">
                            Teléfono
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               placeholder="1234567890"
                               pattern="[0-9]{7,12}"
                               minlength="7"
                               maxlength="12"
                               required
                               class="form-input @error('phone') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Ingresa entre 7 y 12 números</p>
                        @error('phone')
                            <div class="mt-1 flex items-center">
                                <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="form-error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    {{-- Tipo de documento --}}
                    <div class="form-group mb-6">
                        <label for="document_type" class="form-label">
                            Tipo de documento
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="document_type" 
                                name="document_type" 
                                class="form-input @error('document_type') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                            <option value="">Selecciona un tipo</option>
                            <option value="CC" {{ old('document_type') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía (CC)</option>
                            <option value="TI" {{ old('document_type') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad (TI)</option>
                            <option value="CE" {{ old('document_type') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería (CE)</option>
                            <option value="NIT" {{ old('document_type') == 'NIT' ? 'selected' : '' }}>NIT</option>
                            <option value="PASAPORTE" {{ old('document_type') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte (PA)</option>
                            <option value="RC" {{ old('document_type') == 'RC' ? 'selected' : '' }}>Registro Civil (RC)</option>
                            <option value="PEP" {{ old('document_type') == 'PEP' ? 'selected' : '' }}>Permiso Especial de Permanencia (PEP)</option>
                            <option value="PPT" {{ old('document_type') == 'PPT' ? 'selected' : '' }}>Permiso por Protección Temporal (PPT)</option>
                            <option value="OTRO" {{ old('document_type') == 'OTRO' ? 'selected' : '' }}>Otro (NUIP, Carné Diplomático, etc.)</option>
                        </select>
                        @error('document_type')
                            <div class="mt-1 flex items-center">
                                <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="form-error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    {{-- Número de documento --}}
                    <div class="form-group mb-6">
                        <label for="document_number" class="form-label">
                            Número de documento
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="document_number" 
                               name="document_number" 
                               value="{{ old('document_number') }}"
                               placeholder="Ingresa tu número de documento"
                               class="form-input @error('document_number') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                               required>
                        <div id="document_help" class="mt-1 text-sm text-gray-500">
                            Selecciona el tipo de documento para ver el formato requerido.
                        </div>
                        @error('document_number')
                            <div class="mt-1 flex items-center">
                                <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="form-error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    {{-- Fecha de nacimiento --}}
                    <div class="form-group mb-6">
                        <label for="birth_date" class="form-label">
                            Fecha de nacimiento
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="birth_date" 
                               name="birth_date" 
                               value="{{ old('birth_date') }}"
                               max="{{ now()->subYears(16)->format('Y-m-d') }}"
                               required
                               class="form-input @error('birth_date') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Debes tener al menos 16 años para participar</p>
                        @error('birth_date')
                            <div class="mt-1 flex items-center">
                                <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="form-error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    {{-- Campos de datos del representante legal (solo para menores de edad) --}}
                    <div id="representative-data-section" class="border-t border-gray-200 pt-6 mt-6" style="display: none;">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Datos del Representante Legal</h4>
                        <p class="text-sm text-gray-600 mb-4">Los siguientes campos son obligatorios para menores de edad (menores de 18 años).</p>
                        
                        {{-- Nombre del representante legal --}}
                        <div class="form-group mb-6">
                            <label for="representative_name" class="form-label">
                                Nombre completo del representante legal
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="representative_name" 
                                   name="representative_name" 
                                   value="{{ old('representative_name') }}"
                                   placeholder="Ingresa el nombre completo del representante legal"
                                   class="form-input @error('representative_name') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('representative_name')
                                <div class="mt-1 flex items-center">
                                    <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="form-error">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        {{-- Tipo de documento del representante legal --}}
                        <div class="form-group mb-6">
                            <label for="representative_document_type" class="form-label">
                                Tipo de documento del representante legal
                                <span class="text-red-500">*</span>
                            </label>
                            <select id="representative_document_type" 
                                    name="representative_document_type" 
                                    class="form-input @error('representative_document_type') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">Selecciona un tipo</option>
                                <option value="CC" {{ old('representative_document_type') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía (CC)</option>
                                <option value="TI" {{ old('representative_document_type') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad (TI)</option>
                                <option value="CE" {{ old('representative_document_type') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería (CE)</option>
                                <option value="NIT" {{ old('representative_document_type') == 'NIT' ? 'selected' : '' }}>NIT</option>
                                <option value="PASAPORTE" {{ old('representative_document_type') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte (PA)</option>
                                <option value="RC" {{ old('representative_document_type') == 'RC' ? 'selected' : '' }}>Registro Civil (RC)</option>
                                <option value="PEP" {{ old('representative_document_type') == 'PEP' ? 'selected' : '' }}>Permiso Especial de Permanencia (PEP)</option>
                                <option value="PPT" {{ old('representative_document_type') == 'PPT' ? 'selected' : '' }}>Permiso por Protección Temporal (PPT)</option>
                                <option value="OTRO" {{ old('representative_document_type') == 'OTRO' ? 'selected' : '' }}>Otro (NUIP, Carné Diplomático, etc.)</option>
                            </select>
                            @error('representative_document_type')
                                <div class="mt-1 flex items-center">
                                    <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="form-error">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        {{-- Número de documento del representante legal --}}
                        <div class="form-group mb-6">
                            <label for="representative_document_number" class="form-label">
                                Número de documento del representante legal
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="representative_document_number" 
                                   name="representative_document_number" 
                                   value="{{ old('representative_document_number') }}"
                                   placeholder="Ingresa el número de documento del representante legal"
                                   class="form-input @error('representative_document_number') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror">
                            <div id="representative_document_help" class="mt-1 text-sm text-gray-500">
                                Selecciona el tipo de documento del representante legal para ver el formato requerido.
                            </div>
                            @error('representative_document_number')
                                <div class="mt-1 flex items-center">
                                    <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="form-error">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        {{-- Dirección del representante legal --}}
                        <div class="form-group mb-6">
                            <label for="representative_address" class="form-label">
                                Dirección del representante legal
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="representative_address" 
                                   name="representative_address" 
                                   value="{{ old('representative_address') }}"
                                   placeholder="Ingresa la dirección completa del representante legal"
                                   class="form-input @error('representative_address') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('representative_address')
                                <div class="mt-1 flex items-center">
                                    <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="form-error">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        {{-- Teléfono del representante legal --}}
                        <div class="form-group mb-6">
                            <label for="representative_phone" class="form-label">
                                Teléfono del representante legal
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" 
                                   id="representative_phone" 
                                   name="representative_phone" 
                                   value="{{ old('representative_phone') }}"
                                   placeholder="Ingresa el teléfono del representante legal"
                                   class="form-input @error('representative_phone') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Solo números, entre 7 y 12 dígitos</p>
                            @error('representative_phone')
                                <div class="mt-1 flex items-center">
                                    <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="form-error">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        {{-- Correo electrónico del representante legal --}}
                        <div class="form-group mb-6">
                            <label for="representative_email" class="form-label">
                                Correo electrónico del representante legal
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="representative_email" 
                                   name="representative_email" 
                                   value="{{ old('representative_email') }}"
                                   placeholder="Ingresa el correo electrónico del representante legal"
                                   class="form-input @error('representative_email') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('representative_email')
                                <div class="mt-1 flex items-center">
                                    <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="form-error">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        {{-- Checkbox de autorización --}}
                        <div class="form-group mb-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="representative_authorization" 
                                           name="representative_authorization" 
                                           type="checkbox" 
                                           value="1"
                                           {{ old('representative_authorization') ? 'checked' : '' }}
                                           class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out @error('representative_authorization') border-red-500 @enderror">
                                </div>
                                <div class="ml-3">
                                    <label for="representative_authorization" class="text-sm text-gray-700 leading-relaxed" style="text-align: justify !important;">
                                        <span class="text-red-500">*</span>
                                        Como padre/madre/representante legal del menor autorizo de manera expresa, libre e informada la participación del menor al que represento en el Festival SMARTFILMS® Bogotá 2025, igualmente autorizo el tratamiento de los datos personales e imagen del menor de manera conjunta por parte de Valencia Producciones FX SAS, NIT.900.525.880-3, y de La Cámara de Comercio de Bogotá - CCB, NIT.860.007.322-9 conforme a las finalidades establecidas y en cumplimiento de la normativa vigente sobre protección de datos personales.
                                        <br><br>
                                        Declaro haber sido informado/a sobre el uso que se dará a dicha información, conforme a lo previsto en la política de protección de datos personales de Valencia Producciones FX S.AS. publicada en www.valenciaproducciones.com, así como la política de protección de datos de la Cámara de Comercio de Bogotá – CCB ubicada en www.ccb.org.co/proteccion-de-datos-personales.
                                    </label>
                                    @error('representative_authorization')
                                        <div class="mt-1 flex items-center">
                                            <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            <p class="form-error">{{ $message }}</p>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Campos dinámicos del formulario --}}
                @php
                    // Intentar obtener campos relacionales primero, si no hay, usar campos JSON
                    try {
                        $relationalFields = $form->getRelationalFields();
                        $dynamicFields = $relationalFields->count() > 0 ? $relationalFields : collect($form->getFieldsAttribute());
                    } catch (\Exception $e) {
                        // Si hay error con campos relacionales, usar campos JSON
                        $dynamicFields = collect($form->getFieldsAttribute());
                    }
                @endphp
                @if($dynamicFields->count() > 0)
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Adicional</h3>
                        
                        @foreach($dynamicFields as $field)
                    @if($field['type'] === 'section')
                        {{-- Renderizar campo de sección directamente --}}
                        <div class="section-field mt-10 mb-8">
                            @if(isset($field['level']) && $field['level'] == 'h1')
                                <h1 class="text-2xl font-bold text-gray-900 border-b-2 border-gray-200 pb-2">
                                    {{ $field['label'] }}
                                </h1>
                            @elseif(isset($field['level']) && $field['level'] == 'h2')
                                <h2 class="text-xl font-semibold text-gray-800 border-b border-gray-200 pb-2 mt-6">
                                    {{ $field['label'] }}
                                </h2>
                            @elseif(isset($field['level']) && $field['level'] == 'h3')
                                <h3 class="text-lg font-medium text-gray-700 border-b border-gray-100 pb-1 mt-4">
                                    {{ $field['label'] }}
                                </h3>
                            @else
                                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mt-6">
                                    {{ $field['label'] }}
                                </h3>
                            @endif
                            
                            @if(isset($field['description']) && !empty($field['description']))
                                <p class="mt-2 text-sm text-gray-600 leading-relaxed" style="text-align: justify !important;">
                                    {{ $field['description'] }}
                                </p>
                            @endif
                        </div>
                    @else
                        {{-- Renderizar campo normal --}}
                        <div class="form-group mb-6" 
                             @if(isset($field['visible']) && is_array($field['visible']) && isset($field['visible']['model']))
                                 data-conditional-field="true"
                                 data-conditional-model="{{ $field['visible']['model'] }}"
                                 data-conditional-value="{{ is_array($field['visible']['value'] ?? '') ? json_encode($field['visible']['value']) : ($field['visible']['value'] ?? '') }}"
                                 data-conditional-condition="{{ $field['visible']['condition'] ?? 'equal' }}"
                                 style="display: none;"
                             @endif>
                            <label for="{{ $field['key'] }}" class="form-label">
                                {{ $field['label'] }}
                                @if($field['required'] ?? false)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>

                        @switch($field['type'])
                            @case('text')
                                <input type="text" 
                                       id="{{ $field['key'] }}" 
                                       name="{{ $field['key'] }}" 
                                       value="{{ old($field['key'], $field['default_value'] ?? '') }}"
                                       placeholder="{{ $field['placeholder'] ?? '' }}"
                                       @if(isset($field['validations']['max_elements']))
                                           maxlength="{{ $field['validations']['max_elements'] }}"
                                           data-max-elements="{{ $field['validations']['max_elements'] }}"
                                       @endif
                                       class="form-input @error($field['key']) border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                                       {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                @break

                            @case('email')
                                <input type="email" 
                                       id="{{ $field['key'] }}" 
                                       name="{{ $field['key'] }}" 
                                       value="{{ old($field['key'], $field['default_value'] ?? '') }}"
                                       placeholder="{{ $field['placeholder'] ?? '' }}"
                                       @if(isset($field['validations']['max_elements']))
                                           maxlength="{{ $field['validations']['max_elements'] }}"
                                           data-max-elements="{{ $field['validations']['max_elements'] }}"
                                       @endif
                                       class="form-input @error($field['key']) border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                                       {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                @break

                            @case('number')
                                <input type="number" 
                                       id="{{ $field['key'] }}" 
                                       name="{{ $field['key'] }}" 
                                       value="{{ old($field['key'], $field['default_value'] ?? '') }}"
                                       min="{{ $field['validations']['min_value'] ?? '' }}"
                                       max="{{ $field['validations']['max_value'] ?? '' }}"
                                       @if(isset($field['validations']['max_elements']))
                                           data-max-elements="{{ $field['validations']['max_elements'] }}"
                                       @elseif(isset($field['validations']['max_digits']) && str_contains(strtolower($field['key']), 'document'))
                                           data-max-elements="{{ $field['validations']['max_digits'] }}"
                                       @endif
                                       class="form-input @error($field['key']) border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                                       {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                @break

                            @case('date')
                                <input type="date" 
                                       id="{{ $field['key'] }}" 
                                       name="{{ $field['key'] }}" 
                                       value="{{ old($field['key'], $field['default_value'] ?? '') }}"
                                       class="form-input @error($field['key']) border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                                       {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                @break

                            @case('select')
                                <select id="{{ $field['key'] }}" 
                                        name="{{ $field['key'] }}" 
                                        class="form-input @error($field['key']) border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                                        {{ ($field['required'] ?? false) ? 'required' : '' }}
                                        @if(isset($field['dynamic_options']) && $field['dynamic_options'])
                                            data-dynamic-options="true"
                                            data-api-endpoint="{{ $field['api_endpoint'] ?? '' }}"
                                        @endif>
                                    <option value="">Selecciona una opción</option>
                                    @if(isset($field['dynamic_options']) && $field['dynamic_options'])
                                        {{-- Las opciones se cargarán dinámicamente via JavaScript --}}
                                        <option value="" disabled>Cargando opciones...</option>
                                    @else
                                        @foreach($field['options'] ?? [] as $option)
                                            @if(is_array($option))
                                                {{-- Formato: [{"value": "valor", "label": "etiqueta"}] --}}
                                                <option value="{{ $option['value'] }}" 
                                                        {{ old($field['key'], $field['default_value'] ?? '') == $option['value'] ? 'selected' : '' }}>
                                                    {{ $option['label'] }}
                                                </option>
                                            @else
                                                {{-- Formato: ["valor1", "valor2"] --}}
                                                <option value="{{ $option }}" 
                                                        {{ old($field['key'], $field['default_value'] ?? '') == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @break

                            @case('textarea')
                                <textarea id="{{ $field['key'] }}" 
                                          name="{{ $field['key'] }}" 
                                          rows="4"
                                          placeholder="{{ $field['placeholder'] ?? '' }}"
                                          @if(isset($field['validations']['max_elements']))
                                              maxlength="{{ $field['validations']['max_elements'] }}"
                                              data-max-elements="{{ $field['validations']['max_elements'] }}"
                                          @endif
                                          class="form-input @error($field['key']) border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                                          {{ ($field['required'] ?? false) ? 'required' : '' }}>{{ old($field['key'], $field['default_value'] ?? '') }}</textarea>
                                @break

                            @case('checkbox')
                                @if(isset($field['options']) && is_array($field['options']) && count($field['options']) > 0)
                                    {{-- Múltiples checkboxes --}}
                                    <div class="space-y-2">
                                        @foreach($field['options'] as $index => $option)
                                            @php
                                                $optionValue = is_array($option) ? $option['value'] : $option;
                                                $optionLabel = is_array($option) ? $option['label'] : $option;
                                                $checkboxName = $field['key'] . '[]';
                                                $checkboxId = $field['key'] . '_' . $index;
                                                $oldValues = old($field['key'], []);
                                                $isChecked = in_array($optionValue, $oldValues);
                                            @endphp
                                            <div class="flex items-center">
                                                <input type="checkbox" 
                                                       id="{{ $checkboxId }}" 
                                                       name="{{ $checkboxName }}" 
                                                       value="{{ $optionValue }}"
                                                       {{ $isChecked ? 'checked' : '' }}
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error($field['key']) border-red-500 @enderror">
                                                <label for="{{ $checkboxId }}" class="ml-2 block text-sm text-gray-900">
                                                    {{ $optionLabel }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    {{-- Checkbox simple --}}
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="{{ $field['key'] }}" 
                                               name="{{ $field['key'] }}" 
                                               value="1"
                                               {{ old($field['key'], $field['default_value'] ?? false) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error($field['key']) border-red-500 @enderror"
                                               {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                        <label for="{{ $field['key'] }}" class="ml-2 block text-sm text-gray-900">
                                            {{ $field['label'] }}
                                        </label>
                                    </div>
                                @endif
                                @break
                        @endswitch

                        @error($field['key'])
                            <div class="mt-1 flex items-center">
                                <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="form-error">{{ $message }}</p>
                            </div>
                        @enderror

                        @if(isset($field['description']) && !empty($field['description']))
                            <p class="mt-1 text-sm text-gray-500">{{ $field['description'] }}</p>
                        @endif
                        
                        @if(isset($field['validations']['max_elements']))
                            <div class="mt-1 text-sm text-gray-500">
                                <span class="character-count" data-field="{{ $field['key'] }}">
                                    <span class="current-count">0</span> / {{ $field['validations']['max_elements'] }} caracteres
                                </span>
                            </div>
                        @endif
                    </div>
                    @endif
                        @endforeach
                    </div>
                @endif

                <div class="flex justify-end space-x-3">
                    <button type="submit" class="btn-primary" 
                            style="background-color: {{ $primaryColor }}; border-color: {{ $primaryColor }}; border-radius: {{ $borderRadius }};">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        @if($hasSubmitted)
                            Enviar Otra Respuesta
                        @else
                            Enviar Formulario
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para actualizar el contador de caracteres
    function updateCharacterCount(field) {
        const maxElements = field.getAttribute('data-max-elements');
        if (!maxElements) return;
        
        const currentValue = field.value || '';
        const currentCount = currentValue.length;
        const maxCount = parseInt(maxElements);
        
        // Buscar el contador correspondiente
        const counter = document.querySelector(`[data-field="${field.id}"] .current-count`);
        if (counter) {
            counter.textContent = currentCount;
            
            // Cambiar color según el límite
            const counterContainer = counter.parentElement;
            if (currentCount > maxCount) {
                counterContainer.classList.add('text-red-500');
                counterContainer.classList.remove('text-gray-500');
            } else if (currentCount > maxCount * 0.8) {
                counterContainer.classList.add('text-yellow-500');
                counterContainer.classList.remove('text-gray-500', 'text-red-500');
            } else {
                counterContainer.classList.add('text-gray-500');
                counterContainer.classList.remove('text-yellow-500', 'text-red-500');
            }
        }
        
        // Prevenir que se escriban más caracteres del límite
        if (currentCount >= maxCount) {
            field.value = currentValue.substring(0, maxCount);
        }
    }
    
    // Función para limitar caracteres en tiempo real
    function limitCharacters(field) {
        const maxElements = field.getAttribute('data-max-elements');
        if (!maxElements) return;
        
        const maxCount = parseInt(maxElements);
        
        field.addEventListener('input', function() {
            const currentValue = this.value || '';
            const currentCount = currentValue.length;
            
            if (currentCount > maxCount) {
                this.value = currentValue.substring(0, maxCount);
            }
            
            updateCharacterCount(this);
        });
        
        field.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const currentValue = this.value || '';
            const newValue = currentValue + paste;
            const maxCount = parseInt(maxElements);
            
            if (newValue.length <= maxCount) {
                this.value = newValue;
            } else {
                this.value = newValue.substring(0, maxCount);
            }
            
            updateCharacterCount(this);
        });
        
        // Actualizar contador inicial
        updateCharacterCount(field);
    }
    
    // Aplicar limitación a todos los campos con data-max-elements
    const fieldsWithMaxElements = document.querySelectorAll('[data-max-elements]');
    fieldsWithMaxElements.forEach(limitCharacters);
    
    // Validaciones específicas para campos fijos del participante
    function setupParticipantFieldValidations() {
        // Validación del campo nombre
        const firstNameField = document.getElementById('first_name');
        if (firstNameField) {
            firstNameField.addEventListener('input', function() {
                const value = this.value.trim();
                const isValid = value.length >= 2 && /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value);
                
                if (value && !isValid) {
                    this.setCustomValidity('El nombre debe tener al menos 2 letras y solo puede contener letras y espacios.');
                } else {
                    this.setCustomValidity('');
                }
            });
        }
        
        // Validación del campo apellidos
        const lastNameField = document.getElementById('last_name');
        if (lastNameField) {
            lastNameField.addEventListener('input', function() {
                const value = this.value.trim();
                const isValid = value.length >= 2 && /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value);
                
                if (value && !isValid) {
                    this.setCustomValidity('Los apellidos deben tener al menos 2 letras y solo pueden contener letras y espacios.');
                } else {
                    this.setCustomValidity('');
                }
            });
        }
        
        // Validación del campo tipo de documento
        const documentTypeField = document.getElementById('document_type');
        const documentNumberField = document.getElementById('document_number');
        const documentHelp = document.getElementById('document_help');
        
        if (documentTypeField && documentNumberField && documentHelp) {
            // Actualizar mensaje de ayuda cuando cambie el tipo de documento
            documentTypeField.addEventListener('change', function() {
                updateDocumentHelp(this.value);
                validateDocumentNumber();
            });
            
            // Validar número de documento cuando cambie
            documentNumberField.addEventListener('input', function() {
                validateDocumentNumber();
            });
            
            // Función para actualizar el mensaje de ayuda
            function updateDocumentHelp(documentType) {
                const helpMessages = {
                    'CC': 'Solo números. Entre 6 y 10 dígitos. Ejemplo: 1032456789',
                    'TI': 'Solo números. Entre 6 y 11 dígitos. Ejemplo: 1002345678',
                    'CE': 'Solo números. Entre 6 y 15 dígitos. Ejemplo: 987654321',
                    'NIT': 'Entre 9 y 15 dígitos. Puede incluir dígito de verificación con guión. Ejemplo: 900123456-7',
                    'PASAPORTE': 'Entre 6 y 12 caracteres alfanuméricos. Ejemplo: AB1234567',
                    'RC': 'Solo números. Entre 10 y 15 dígitos. Ejemplo: 123456789012',
                    'PEP': 'Entre 6 y 15 caracteres alfanuméricos. Ejemplo: PEP1234567',
                    'PPT': 'Entre 6 y 15 caracteres alfanuméricos. Ejemplo: PPT1234567',
                    'OTRO': 'Entre 6 y 15 caracteres alfanuméricos. Ejemplo: NUIP123456789'
                };
                
                if (documentType && helpMessages[documentType]) {
                    documentHelp.textContent = helpMessages[documentType];
                    documentHelp.className = 'mt-1 text-sm text-blue-600';
                } else {
                    documentHelp.textContent = 'Selecciona el tipo de documento para ver el formato requerido.';
                    documentHelp.className = 'mt-1 text-sm text-gray-500';
                }
            }
            
            // Función para validar el número de documento
            function validateDocumentNumber() {
                const documentType = documentTypeField.value;
                const documentNumber = documentNumberField.value.trim().toUpperCase();
                
                if (!documentType || !documentNumber) {
                    documentNumberField.setCustomValidity('');
                    return;
                }
                
                let isValid = false;
                let errorMessage = '';
                
                switch (documentType) {
                    case 'CC':
                        isValid = /^[0-9]{6,10}$/.test(documentNumber);
                        errorMessage = 'La Cédula de Ciudadanía debe contener solo números y tener entre 6 y 10 dígitos.';
                        break;
                    case 'TI':
                        isValid = /^[0-9]{6,11}$/.test(documentNumber);
                        errorMessage = 'La Tarjeta de Identidad debe contener solo números y tener entre 6 y 11 dígitos.';
                        break;
                    case 'CE':
                        isValid = /^[0-9]{6,15}$/.test(documentNumber);
                        errorMessage = 'La Cédula de Extranjería debe contener solo números y tener entre 6 y 15 dígitos.';
                        break;
                    case 'NIT':
                        isValid = /^[0-9]{9,15}(-[0-9])?$/.test(documentNumber);
                        errorMessage = 'El NIT debe contener entre 9 y 15 dígitos y puede incluir un dígito de verificación separado por guión.';
                        break;
                    case 'PASAPORTE':
                        isValid = /^[A-Z0-9]{6,12}$/.test(documentNumber);
                        errorMessage = 'El Pasaporte debe contener entre 6 y 12 caracteres alfanuméricos.';
                        break;
                    case 'RC':
                        isValid = /^[0-9]{10,15}$/.test(documentNumber);
                        errorMessage = 'El Registro Civil debe contener solo números y tener entre 10 y 15 dígitos.';
                        break;
                    case 'PEP':
                    case 'PPT':
                        isValid = /^[A-Z0-9]{6,15}$/.test(documentNumber);
                        errorMessage = 'El documento debe contener entre 6 y 15 caracteres alfanuméricos.';
                        break;
                    case 'OTRO':
                        isValid = /^[A-Z0-9]{6,15}$/.test(documentNumber);
                        errorMessage = 'El documento debe contener entre 6 y 15 caracteres alfanuméricos.';
                        break;
                }
                
                if (!isValid) {
                    documentNumberField.setCustomValidity(errorMessage);
                } else {
                    documentNumberField.setCustomValidity('');
                }
            }
            
            // Limpiar espacios automáticamente en el número de documento
            documentNumberField.addEventListener('input', function() {
                // Remover espacios y convertir a mayúsculas
                this.value = this.value.replace(/\s/g, '').toUpperCase();
            });
        }
        
        // Validación del campo teléfono
        const phoneField = document.getElementById('phone');
        if (phoneField) {
            // Solo permitir números
            phoneField.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                
                const value = this.value;
                const isValid = value.length >= 7 && value.length <= 12;
                
                if (value && !isValid) {
                    this.setCustomValidity('El teléfono debe contener entre 7 y 12 números.');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            // Prevenir entrada de caracteres no numéricos
            phoneField.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    e.preventDefault();
                }
            });
        }
        
        // Validación del campo fecha de nacimiento y lógica de campos del representante legal
        const birthDateField = document.getElementById('birth_date');
        const representativeDataSection = document.getElementById('representative-data-section');
        const representativeNameField = document.getElementById('representative_name');
        const representativeDocumentTypeField = document.getElementById('representative_document_type');
        const representativeDocumentNumberField = document.getElementById('representative_document_number');
        const representativeAddressField = document.getElementById('representative_address');
        const representativePhoneField = document.getElementById('representative_phone');
        const representativeEmailField = document.getElementById('representative_email');
        const representativeAuthorizationField = document.getElementById('representative_authorization');
        
        if (birthDateField) {
            birthDateField.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                const minDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());
                const adultDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
                
                // Validar edad mínima (16 años)
                if (this.value && selectedDate > minDate) {
                    this.setCustomValidity('Debes tener al menos 16 años para participar.');
                } else {
                    this.setCustomValidity('');
                }
                
                // Mostrar/ocultar campos del representante legal según la edad
                if (this.value && selectedDate > adultDate) {
                    // Es menor de edad (menos de 18 años) - mostrar campos del representante legal
                    if (representativeDataSection) {
                        representativeDataSection.style.display = 'block';
                    }
                    
                    // Hacer obligatorios los campos del representante legal
                    if (representativeNameField) representativeNameField.required = true;
                    if (representativeDocumentTypeField) representativeDocumentTypeField.required = true;
                    if (representativeDocumentNumberField) representativeDocumentNumberField.required = true;
                    if (representativeAddressField) representativeAddressField.required = true;
                    if (representativePhoneField) representativePhoneField.required = true;
                    if (representativeEmailField) representativeEmailField.required = true;
                    if (representativeAuthorizationField) representativeAuthorizationField.required = true;
                } else {
                    // Es mayor de edad (18 años o más) - ocultar campos del representante legal
                    if (representativeDataSection) {
                        representativeDataSection.style.display = 'none';
                    }
                    
                    // Limpiar y hacer opcionales los campos del representante legal
                    if (representativeNameField) {
                        representativeNameField.value = '';
                        representativeNameField.required = false;
                    }
                    if (representativeDocumentTypeField) {
                        representativeDocumentTypeField.value = '';
                        representativeDocumentTypeField.required = false;
                    }
                    if (representativeDocumentNumberField) {
                        representativeDocumentNumberField.value = '';
                        representativeDocumentNumberField.required = false;
                    }
                    if (representativeAddressField) {
                        representativeAddressField.value = '';
                        representativeAddressField.required = false;
                    }
                    if (representativePhoneField) {
                        representativePhoneField.value = '';
                        representativePhoneField.required = false;
                    }
                    if (representativeEmailField) {
                        representativeEmailField.value = '';
                        representativeEmailField.required = false;
                    }
                    if (representativeAuthorizationField) {
                        representativeAuthorizationField.checked = false;
                        representativeAuthorizationField.required = false;
                    }
                }
            });
            
            // Ejecutar la lógica al cargar la página si ya hay una fecha
            if (birthDateField.value) {
                birthDateField.dispatchEvent(new Event('change'));
            }
        }
        
        // Validación de campos del representante legal
        const representativeDocumentHelp = document.getElementById('representative_document_help');
        
        if (representativeDocumentTypeField && representativeDocumentNumberField && representativeDocumentHelp) {
            // Actualizar mensaje de ayuda cuando cambie el tipo de documento del representante legal
            representativeDocumentTypeField.addEventListener('change', function() {
                updateRepresentativeDocumentHelp(this.value);
                validateRepresentativeDocumentNumber();
            });
            
            // Validar número de documento del representante legal cuando cambie
            representativeDocumentNumberField.addEventListener('input', function() {
                validateRepresentativeDocumentNumber();
            });
            
            // Función para actualizar el mensaje de ayuda del representante legal
            function updateRepresentativeDocumentHelp(documentType) {
                const helpMessages = {
                    'CC': 'Solo números. Entre 6 y 10 dígitos. Ejemplo: 1032456789',
                    'TI': 'Solo números. Entre 6 y 11 dígitos. Ejemplo: 1002345678',
                    'CE': 'Solo números. Entre 6 y 15 dígitos. Ejemplo: 987654321',
                    'NIT': 'Entre 9 y 15 dígitos. Puede incluir dígito de verificación con guión. Ejemplo: 900123456-7',
                    'PASAPORTE': 'Entre 6 y 12 caracteres alfanuméricos. Ejemplo: AB1234567',
                    'RC': 'Solo números. Entre 10 y 15 dígitos. Ejemplo: 123456789012',
                    'PEP': 'Entre 6 y 15 caracteres alfanuméricos. Ejemplo: PEP1234567',
                    'PPT': 'Entre 6 y 15 caracteres alfanuméricos. Ejemplo: PPT1234567',
                    'OTRO': 'Entre 6 y 15 caracteres alfanuméricos. Ejemplo: NUIP123456789'
                };
                
                if (documentType && helpMessages[documentType]) {
                    representativeDocumentHelp.textContent = helpMessages[documentType];
                    representativeDocumentHelp.className = 'mt-1 text-sm text-blue-600';
                } else {
                    representativeDocumentHelp.textContent = 'Selecciona el tipo de documento del representante legal para ver el formato requerido.';
                    representativeDocumentHelp.className = 'mt-1 text-sm text-gray-500';
                }
            }
            
            // Función para validar el número de documento del representante legal
            function validateRepresentativeDocumentNumber() {
                const documentType = representativeDocumentTypeField.value;
                const documentNumber = representativeDocumentNumberField.value.trim().toUpperCase();
                
                if (!documentType || !documentNumber) {
                    representativeDocumentNumberField.setCustomValidity('');
                    return;
                }
                
                let isValid = false;
                let errorMessage = '';
                
                switch (documentType) {
                    case 'CC':
                        isValid = /^[0-9]{6,10}$/.test(documentNumber);
                        errorMessage = 'La Cédula de Ciudadanía del representante legal debe contener solo números y tener entre 6 y 10 dígitos.';
                        break;
                    case 'TI':
                        isValid = /^[0-9]{6,11}$/.test(documentNumber);
                        errorMessage = 'La Tarjeta de Identidad del representante legal debe contener solo números y tener entre 6 y 11 dígitos.';
                        break;
                    case 'CE':
                        isValid = /^[0-9]{6,15}$/.test(documentNumber);
                        errorMessage = 'La Cédula de Extranjería del representante legal debe contener solo números y tener entre 6 y 15 dígitos.';
                        break;
                    case 'NIT':
                        isValid = /^[0-9]{9,15}(-[0-9])?$/.test(documentNumber);
                        errorMessage = 'El NIT del representante legal debe contener entre 9 y 15 dígitos y puede incluir un dígito de verificación separado por guión.';
                        break;
                    case 'PASAPORTE':
                        isValid = /^[A-Z0-9]{6,12}$/.test(documentNumber);
                        errorMessage = 'El Pasaporte del representante legal debe contener entre 6 y 12 caracteres alfanuméricos.';
                        break;
                    case 'RC':
                        isValid = /^[0-9]{10,15}$/.test(documentNumber);
                        errorMessage = 'El Registro Civil del representante legal debe contener solo números y tener entre 10 y 15 dígitos.';
                        break;
                    case 'PEP':
                    case 'PPT':
                        isValid = /^[A-Z0-9]{6,15}$/.test(documentNumber);
                        errorMessage = 'El documento del representante legal debe contener entre 6 y 15 caracteres alfanuméricos.';
                        break;
                    case 'OTRO':
                        isValid = /^[A-Z0-9]{6,15}$/.test(documentNumber);
                        errorMessage = 'El documento del representante legal debe contener entre 6 y 15 caracteres alfanuméricos.';
                        break;
                }
                
                if (!isValid) {
                    representativeDocumentNumberField.setCustomValidity(errorMessage);
                } else {
                    representativeDocumentNumberField.setCustomValidity('');
                }
            }
            
            // Limpiar espacios automáticamente en el número de documento del representante legal
            representativeDocumentNumberField.addEventListener('input', function() {
                // Remover espacios y convertir a mayúsculas
                this.value = this.value.replace(/\s/g, '').toUpperCase();
            });
        }
        
        // Validación del teléfono del representante legal
        if (representativePhoneField) {
            representativePhoneField.addEventListener('input', function() {
                // Solo permitir números
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            
            representativePhoneField.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    e.preventDefault();
                }
            });
        }
    }
    
    // Inicializar validaciones de campos del participante
    setupParticipantFieldValidations();
    
    // Para campos de tipo number, también limitar en keydown
    const numberFields = document.querySelectorAll('input[type="number"][data-max-elements]');
    numberFields.forEach(function(field) {
        field.addEventListener('keydown', function(e) {
            const maxElements = parseInt(this.getAttribute('data-max-elements'));
            const currentValue = this.value || '';
            
            // Permitir teclas de control (backspace, delete, arrow keys, etc.)
            if ([8, 9, 27, 46, 110, 190].indexOf(e.keyCode) !== -1 ||
                (e.keyCode === 65 && e.ctrlKey === true) || // Ctrl+A
                (e.keyCode >= 35 && e.keyCode <= 40)) { // End, Home, arrow keys
                return;
            }
            
            // Si ya se alcanzó el límite, no permitir más caracteres
            if (currentValue.length >= maxElements) {
                e.preventDefault();
            }
        });
    });

    // Lógica para campos condicionales
    function checkConditionalFields() {
        const conditionalFields = document.querySelectorAll('[data-conditional-field="true"]');
        
        conditionalFields.forEach(function(field) {
            const model = field.getAttribute('data-conditional-model');
            const expectedValue = field.getAttribute('data-conditional-value');
            const condition = field.getAttribute('data-conditional-condition');
            
            // Buscar el campo de referencia
            const referenceField = document.querySelector(`[name="${model}"]`);
            if (!referenceField) return;
            
            const actualValue = referenceField.value;
            let shouldShow = false;
            
            // Aplicar la condición
            switch (condition) {
                case 'equal':
                    shouldShow = actualValue == expectedValue;
                    break;
                case 'not_equal':
                    shouldShow = actualValue != expectedValue;
                    break;
                case 'contains':
                    shouldShow = actualValue && actualValue.includes(expectedValue);
                    break;
                case 'not_contains':
                    shouldShow = actualValue && !actualValue.includes(expectedValue);
                    break;
                case 'greater_than':
                    shouldShow = parseFloat(actualValue) > parseFloat(expectedValue);
                    break;
                case 'less_than':
                    shouldShow = parseFloat(actualValue) < parseFloat(expectedValue);
                    break;
                case 'greater_equal':
                    shouldShow = parseFloat(actualValue) >= parseFloat(expectedValue);
                    break;
                case 'less_equal':
                    shouldShow = parseFloat(actualValue) <= parseFloat(expectedValue);
                    break;
            }
            
            // Mostrar u ocultar el campo
            if (shouldShow) {
                field.style.display = 'block';
                // Restaurar atributo required para campos visibles
                const requiredInputs = field.querySelectorAll('input, select, textarea');
                requiredInputs.forEach(function(input) {
                    const originalRequired = input.getAttribute('data-original-required');
                    if (originalRequired === 'true') {
                        input.setAttribute('required', 'required');
                    }
                });
                
                // Cargar opciones dinámicas si es necesario
                loadDynamicOptions(field, referenceField);
            } else {
                field.style.display = 'none';
                // Limpiar valores de campos ocultos
                const inputs = field.querySelectorAll('input, select, textarea');
                inputs.forEach(function(input) {
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        input.checked = false;
                    } else {
                        input.value = '';
                    }
                    // Remover atributo required de campos ocultos
                    input.removeAttribute('required');
                });
            }
        });
    }
    
    // Función para cargar opciones dinámicas
    async function loadDynamicOptions(field, referenceField) {
        const dynamicSelect = field.querySelector('[data-dynamic-options="true"]');
        if (!dynamicSelect) return;
        
        const apiEndpoint = dynamicSelect.getAttribute('data-api-endpoint');
        const cityValue = referenceField.value;
        
        if (!apiEndpoint || !cityValue) return;
        
        try {
            // Mostrar indicador de carga
            dynamicSelect.innerHTML = '<option value="">Cargando opciones...</option>';
            dynamicSelect.disabled = true;
            
            const response = await fetch(`${apiEndpoint}${cityValue}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success && data.localities) {
                // Limpiar opciones existentes
                dynamicSelect.innerHTML = '<option value="">Selecciona una opción</option>';
                
                // Agregar nuevas opciones
                data.localities.forEach(function(locality) {
                    const option = document.createElement('option');
                    option.value = locality.value;
                    option.textContent = locality.label;
                    dynamicSelect.appendChild(option);
                });
                
                dynamicSelect.disabled = false;
                
                console.log(`Opciones cargadas para ${cityValue}:`, data.localities.length);
            } else {
                throw new Error('Respuesta del servidor no válida');
            }
            
        } catch (error) {
            console.error('Error al cargar opciones dinámicas:', error);
            dynamicSelect.innerHTML = '<option value="">Error al cargar opciones</option>';
            dynamicSelect.disabled = true;
        }
    }
    
    // Aplicar lógica condicional a todos los campos de referencia
    const allInputs = document.querySelectorAll('input, select, textarea');
    allInputs.forEach(function(input) {
        // Guardar el estado original de required
        if (input.hasAttribute('required')) {
            input.setAttribute('data-original-required', 'true');
        }
        
        input.addEventListener('change', checkConditionalFields);
        input.addEventListener('input', checkConditionalFields);
    });
    
    // Verificar campos condicionales al cargar la página
    checkConditionalFields();
    
    // Manejar envío del formulario
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Verificar campos condicionales antes del envío
            checkConditionalFields();
            
            // Remover required de campos ocultos antes de la validación
            const hiddenFields = document.querySelectorAll('[data-conditional-field="true"][style*="display: none"]');
            hiddenFields.forEach(function(field) {
                const inputs = field.querySelectorAll('input, select, textarea');
                inputs.forEach(function(input) {
                    input.removeAttribute('required');
                });
            });
            
            // Filtrar campos vacíos antes del envío
            filterEmptyFields();
        });
    }
    
    // Función para filtrar campos vacíos antes del envío
    function filterEmptyFields() {
        const allInputs = document.querySelectorAll('input, select, textarea');
        
        allInputs.forEach(function(input) {
            // Si el campo está oculto, deshabilitarlo para que no se envíe
            const fieldContainer = input.closest('[data-conditional-field="true"]');
            const isHidden = fieldContainer && fieldContainer.style.display === 'none';
            
            // Solo deshabilitar campos ocultos, no campos vacíos
            // Los campos vacíos deben enviarse para que la validación del servidor funcione
            if (isHidden) {
                input.disabled = true;
            } else {
                input.disabled = false;
            }
        });
    }
});
</script>

<style>
/* Estilos personalizados del formulario */
.form-styled {
    --primary-color: {{ $primaryColor }};
    --border-radius: {{ $borderRadius }};
    --form-shadow: {{ $formShadow ? '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)' : 'none' }};
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

/* Aplicar border radius a inputs y selects */
.form-styled .form-input {
    border-radius: var(--border-radius) !important;
}

.form-styled .form-input:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 20%, transparent) !important;
}

/* Aplicar color principal a checkboxes y radios */
.form-styled input[type="checkbox"]:checked,
.form-styled input[type="radio"]:checked {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
}

/* Aplicar color principal a elementos de estado */
.form-styled .text-primary-600 {
    color: var(--primary-color) !important;
}

/* Aplicar sombra al formulario si está habilitada */
.form-styled .card {
    box-shadow: var(--form-shadow) !important;
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
}

/* Aplicar border radius a las secciones del formulario */
.form-styled .bg-gray-50,
.form-styled .bg-white {
    border-radius: var(--border-radius) !important;
}

/* Estilos para mensajes de éxito y error */
.form-styled .bg-green-50,
.form-styled .bg-red-50 {
    border-radius: var(--border-radius) !important;
}

/* Aplicar color principal a enlaces y elementos destacados */
.form-styled a {
    color: var(--primary-color) !important;
}

/* Estilos responsivos para imágenes del header */
@media (max-width: 768px) {
    .card-header-image {
        flex-direction: column !important;
        gap: {{ $imageSpacing / 2 }}px !important;
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
}

@media (max-width: 480px) {
    .card-header-image {
        padding: 0.5rem 0 !important;
    }
    
    @if($mobileBehavior === 'resize')
        .header-image-1 {
            width: {{ $image1Width * $mobileScale / 100 * 0.8 }}px !important;
            height: {{ $image1Height * $mobileScale / 100 * 0.8 }}px !important;
        }
        
        .header-image-2 {
            width: {{ $image2Width * $mobileScale / 100 * 0.8 }}px !important;
            height: {{ $image2Height * $mobileScale / 100 * 0.8 }}px !important;
        }
    @endif
}

.form-styled a:hover {
    color: color-mix(in srgb, var(--primary-color) 85%, black) !important;
}

/* Estilos para justificación de texto en secciones */
.section-field p {
    text-align: justify !important;
    text-justify: inter-word !important;
}

</style>

<!-- Modal de Éxito -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-60 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
    <div class="relative top-10 mx-auto p-0 border-0 w-11/12 md:w-2/3 lg:w-1/2 shadow-2xl rounded-2xl bg-white overflow-hidden">
        <!-- Header con gradiente -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-8 py-6 text-white">
            <div class="flex items-center justify-center mb-4">
                <div class="flex items-center justify-center h-20 w-20 rounded-full bg-white bg-opacity-20 backdrop-blur-sm">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-center mb-2">¡Registro Exitoso!</h3>
            <p class="text-lg text-green-100 text-center">Tu formulario ha sido procesado correctamente</p>
        </div>
        
        <!-- Contenido principal -->
        <div class="px-8 py-8">
            <div class="text-center mb-6">
                <div class="flex items-center justify-center mb-4">
                    <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <h4 class="text-xl font-semibold text-gray-800 mb-3">Notificación por Correo</h4>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Hemos enviado una confirmación a tu correo electrónico con los detalles de tu registro.
                </p>
                <p class="text-sm text-gray-500 mb-6">
                    Revisa tu bandeja de entrada y la carpeta de spam si no encuentras el mensaje.
                </p>
            </div>
            
            <!-- Información adicional -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <div class="flex items-center justify-center mb-2">
                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">¿Necesitas enviar otra respuesta?</span>
                </div>
                <p class="text-xs text-gray-500 text-center">
                    Puedes llenar el formulario nuevamente si lo deseas
                </p>
            </div>
            
            <!-- Botón de acción -->
            <div class="text-center">
                <button onclick="closeSuccessModal()" 
                        class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 px-10 rounded-xl text-lg transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-green-300"
                        style="background: linear-gradient(135deg, {{ $primaryColor }}, {{ $primaryColor }}dd); border-radius: {{ $borderRadius }};">
                    <span class="flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Continuar
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Error de Documento Duplicado -->
<div id="errorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Documento Ya Registrado</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-lg text-gray-600 mb-4">
                    Ya existe un participante registrado con este tipo y número de documento.
                </p>
                <p class="text-sm text-gray-500 mb-6">
                    Por favor, verifica tus datos o contacta al administrador si crees que esto es un error.
                </p>
                <button onclick="closeErrorModal()" 
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition duration-200 ease-in-out transform hover:scale-105">
                    Entendido
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para mostrar el modal de éxito
function showSuccessModal() {
    document.getElementById('successModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Función para cerrar el modal de éxito
function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Función para mostrar el modal de error
function showErrorModal() {
    document.getElementById('errorModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Función para cerrar el modal de error
function closeErrorModal() {
    document.getElementById('errorModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modales al hacer clic fuera de ellos
document.getElementById('successModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSuccessModal();
    }
});

document.getElementById('errorModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeErrorModal();
    }
});

// Mostrar modal de éxito si hay mensaje de éxito
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showSuccessModal();
    });
@endif

// Mostrar modal de error si hay error de documento duplicado
@if($errors->has('document_number'))
    @if(str_contains($errors->first('document_number'), 'Ya existe un participante registrado'))
        document.addEventListener('DOMContentLoaded', function() {
            showErrorModal();
        });
    @endif
@endif
</script>

@endsection
