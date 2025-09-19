@extends('layouts.app')

@section('title', $form->name)

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="card">
        <div class="card-header">
            <h1 class="text-2xl font-bold text-gray-900">{{ $form->name }}</h1>
            @if($form->description)
                <p class="mt-2 text-sm text-gray-600">{{ $form->description }}</p>
            @endif
            @if($form->city)
                <p class="mt-1 text-sm text-primary-600 font-medium">{{ $form->city->name }}, {{ $form->city->country }}</p>
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


            <form method="POST" action="{{ route('public.forms.slug.submit', ['slug' => $form->slug]) }}" class="space-y-6">
                @csrf
                
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
                                       class="form-input @error($field['key']) border-red-300 @enderror"
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
                                       class="form-input @error($field['key']) border-red-300 @enderror"
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
                                       class="form-input @error($field['key']) border-red-300 @enderror"
                                       {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                @break

                            @case('date')
                                <input type="date" 
                                       id="{{ $field['key'] }}" 
                                       name="{{ $field['key'] }}" 
                                       value="{{ old($field['key']) }}"
                                       class="form-input @error($field['key']) border-red-300 @enderror"
                                       {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                @break

                            @case('select')
                                <select id="{{ $field['key'] }}" 
                                        name="{{ $field['key'] }}" 
                                        class="form-input @error($field['key']) border-red-300 @enderror"
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
                                          class="form-input @error($field['key']) border-red-300 @enderror"
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
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error($field['key']) border-red-300 @enderror">
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
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error($field['key']) border-red-300 @enderror"
                                               {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                        <label for="{{ $field['key'] }}" class="ml-2 block text-sm text-gray-900">
                                            {{ $field['label'] }}
                                        </label>
                                    </div>
                                @endif
                                @break
                        @endswitch

                        @error($field['key'])
                            <p class="form-error">{{ $message }}</p>
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

                <div class="flex justify-end space-x-3">
                    <button type="submit" class="btn-primary">
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
                // Marcar campos requeridos como válidos si están ocultos
                const requiredInputs = field.querySelectorAll('input[required], select[required], textarea[required]');
                requiredInputs.forEach(function(input) {
                    input.removeAttribute('required');
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
                });
                // Restaurar atributo required para validación del servidor
                const requiredInputs = field.querySelectorAll('input, select, textarea');
                requiredInputs.forEach(function(input) {
                    const originalRequired = input.getAttribute('data-original-required');
                    if (originalRequired === 'true') {
                        input.setAttribute('required', 'required');
                    }
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
});
</script>
@endsection
