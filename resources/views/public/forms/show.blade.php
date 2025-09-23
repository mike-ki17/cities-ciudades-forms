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
                <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">
                                ¡Formulario enviado exitosamente!
                            </h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Tu formulario fue enviado el {{ $latestSubmission->submitted_at->format('d/m/Y H:i') }}.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Mostrar errores generales del formulario --}}
            @if($errors->any())
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
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('public.forms.slug.submit', ['id' => $form->id, 'slug' => $form->slug]) }}" class="space-y-6">
                @csrf
                
                {{-- Campos fijos del participante --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos del Participante</h3>
                    
                    {{-- Nombre --}}
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Nombre completo
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="Ingresa tu nombre completo"
                               class="form-input @error('name') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                               required>
                        @error('name')
                            <div class="mt-1 flex items-center">
                                <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="form-error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
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
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            Teléfono
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               placeholder="+1 (555) 123-4567"
                               class="form-input @error('phone') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror">
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
                    <div class="form-group">
                        <label for="document_type" class="form-label">
                            Tipo de documento
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="document_type" 
                                name="document_type" 
                                class="form-input @error('document_type') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                            <option value="">Selecciona un tipo</option>
                            <option value="CC" {{ old('document_type') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                            <option value="CE" {{ old('document_type') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                            <option value="DNI" {{ old('document_type') == 'DNI' ? 'selected' : '' }}>DNI</option>
                            <option value="PASAPORTE" {{ old('document_type') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                            <option value="NIT" {{ old('document_type') == 'NIT' ? 'selected' : '' }}>NIT</option>
                            <option value="CEDULA" {{ old('document_type') == 'CEDULA' ? 'selected' : '' }}>Cédula</option>
                            <option value="OTRO" {{ old('document_type') == 'OTRO' ? 'selected' : '' }}>Otro</option>
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
                    <div class="form-group">
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
                    <div class="form-group">
                        <label for="birth_date" class="form-label">
                            Fecha de nacimiento
                        </label>
                        <input type="date" 
                               id="birth_date" 
                               name="birth_date" 
                               value="{{ old('birth_date') }}"
                               class="form-input @error('birth_date') border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('birth_date')
                            <div class="mt-1 flex items-center">
                                <svg class="h-4 w-4 text-red-400 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="form-error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Campos dinámicos del formulario --}}
                @if(count($form->fields) > 0)
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Adicional</h3>
                        
                        @foreach($form->fields as $field)
                    <div class="form-group" 
                         @if(isset($field['visible']))
                             data-conditional-field="true"
                             data-conditional-model="{{ $field['visible']['model'] }}"
                             data-conditional-value="{{ $field['visible']['value'] }}"
                             data-conditional-condition="{{ $field['visible']['condition'] }}"
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
                                       value="{{ old($field['key']) }}"
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
                                       value="{{ old($field['key']) }}"
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
                                       value="{{ old($field['key']) }}"
                                       min="{{ $field['validations']['min'] ?? '' }}"
                                       max="{{ $field['validations']['max'] ?? '' }}"
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
                                       value="{{ old($field['key']) }}"
                                       class="form-input @error($field['key']) border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                                       {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                @break

                            @case('select')
                                <select id="{{ $field['key'] }}" 
                                        name="{{ $field['key'] }}" 
                                        class="form-input @error($field['key']) border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 @enderror"
                                        {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                    <option value="">Selecciona una opción</option>
                                    @foreach($field['options'] ?? [] as $option)
                                        @if(is_array($option))
                                            {{-- Formato: [{"value": "valor", "label": "etiqueta"}] --}}
                                            <option value="{{ $option['value'] }}" 
                                                    {{ old($field['key']) == $option['value'] ? 'selected' : '' }}>
                                                {{ $option['label'] }}
                                            </option>
                                        @else
                                            {{-- Formato: ["valor1", "valor2"] --}}
                                            <option value="{{ $option }}" 
                                                    {{ old($field['key']) == $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endif
                                    @endforeach
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
                                          {{ ($field['required'] ?? false) ? 'required' : '' }}>{{ old($field['key']) }}</textarea>
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
                                               {{ old($field['key']) ? 'checked' : '' }}
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

                        @if(isset($field['help']))
                            <p class="mt-1 text-sm text-gray-500">{{ $field['help'] }}</p>
                        @endif
                        
                        @if(isset($field['validations']['max_elements']))
                            <div class="mt-1 text-sm text-gray-500">
                                <span class="character-count" data-field="{{ $field['key'] }}">
                                    <span class="current-count">0</span> / {{ $field['validations']['max_elements'] }} caracteres
                                </span>
                            </div>
                        @endif
                    </div>
                        @endforeach
                    </div>
                @endif

                <div class="flex justify-end space-x-3">
                    <button type="submit" class="btn-primary" 
                            style="background-color: {{ $primaryColor }}; border-color: {{ $primaryColor }}; border-radius: {{ $borderRadius }};">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Enviar Formulario
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
</style>
@endsection
