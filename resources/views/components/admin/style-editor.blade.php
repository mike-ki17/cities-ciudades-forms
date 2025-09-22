@props(['styles' => [], 'errors' => []])

<div class="admin-form-section">
    <div class="admin-form-section-title">
        <svg class="w-5 h-5 inline-block mr-2" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
        </svg>
        Configuración de Estilos
    </div>
    
    <div class="space-y-6">
        <!-- Header Images Section -->
        <div class="admin-field-group">
            <h4 class="admin-field-label mb-4">
                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Imágenes del Header
            </h4>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Header Image 1 -->
                <div class="admin-field-group">
                    <label for="style_header_image_1" class="admin-field-label">
                        Imagen Principal del Header *
                    </label>
                    <input type="url" name="style_json[header_image_1]" id="style_header_image_1" 
                           value="{{ old('style_json.header_image_1', $styles['header_image_1'] ?? '') }}"
                           class="admin-input w-full" placeholder="https://ejemplo.com/imagen.jpg">
                    <div class="admin-field-help">URL de la imagen principal que aparecerá en el header del formulario</div>
                    @error('style_json.header_image_1')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Header Image 2 -->
                <div class="admin-field-group">
                    <label for="style_header_image_2" class="admin-field-label">
                        Imagen Secundaria del Header
                    </label>
                    <input type="url" name="style_json[header_image_2]" id="style_header_image_2" 
                           value="{{ old('style_json.header_image_2', $styles['header_image_2'] ?? '') }}"
                           class="admin-input w-full" placeholder="https://ejemplo.com/imagen2.jpg">
                    <div class="admin-field-help">URL de una imagen secundaria opcional para el header</div>
                    @error('style_json.header_image_2')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Background Section -->
        <div class="admin-field-group">
            <h4 class="admin-field-label mb-4">
                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2zM6 7h12M6 17h12"></path>
                </svg>
                Configuración de Fondo
            </h4>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Background Color -->
                <div class="admin-field-group">
                    <label for="style_background_color" class="admin-field-label">
                        Color de Fondo
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="color" name="style_json[background_color]" id="style_background_color" 
                               value="{{ old('style_json.background_color', $styles['background_color'] ?? '#ffffff') }}"
                               class="w-12 h-10 rounded border-2 border-gray-300 cursor-pointer">
                        <input type="text" id="style_background_color_text" 
                               value="{{ old('style_json.background_color', $styles['background_color'] ?? '#ffffff') }}"
                               class="admin-input flex-1 font-mono text-sm" placeholder="#ffffff">
                    </div>
                    <div class="admin-field-help">Color de fondo del formulario</div>
                    @error('style_json.background_color')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Background Texture -->
                <div class="admin-field-group">
                    <label for="style_background_texture" class="admin-field-label">
                        Imagen de Textura de Fondo
                    </label>
                    <input type="url" name="style_json[background_texture]" id="style_background_texture" 
                           value="{{ old('style_json.background_texture', $styles['background_texture'] ?? '') }}"
                           class="admin-input w-full" placeholder="https://ejemplo.com/textura.jpg">
                    <div class="admin-field-help">URL de una imagen de textura opcional para el fondo</div>
                    @error('style_json.background_texture')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Styling Section -->
        <div class="admin-field-group">
            <h4 class="admin-field-label mb-4">
                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Estilo del Formulario
            </h4>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <!-- Primary Color -->
                <div class="admin-field-group">
                    <label for="style_primary_color" class="admin-field-label">
                        Color Principal
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="color" name="style_json[primary_color]" id="style_primary_color" 
                               value="{{ old('style_json.primary_color', $styles['primary_color'] ?? '#00ffbd') }}"
                               class="w-12 h-10 rounded border-2 border-gray-300 cursor-pointer">
                        <input type="text" id="style_primary_color_text" 
                               value="{{ old('style_json.primary_color', $styles['primary_color'] ?? '#00ffbd') }}"
                               class="admin-input flex-1 font-mono text-sm" placeholder="#00ffbd">
                    </div>
                    <div class="admin-field-help">Color principal para botones y elementos destacados</div>
                    @error('style_json.primary_color')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Border Radius -->
                <div class="admin-field-group">
                    <label for="style_border_radius" class="admin-field-label">
                        Border Radius
                    </label>
                    <select name="style_json[border_radius]" id="style_border_radius" class="admin-select w-full">
                        <option value="0px" {{ old('style_json.border_radius', $styles['border_radius'] ?? '8px') == '0px' ? 'selected' : '' }}>Sin bordes redondeados (0px)</option>
                        <option value="4px" {{ old('style_json.border_radius', $styles['border_radius'] ?? '8px') == '4px' ? 'selected' : '' }}>Pequeño (4px)</option>
                        <option value="8px" {{ old('style_json.border_radius', $styles['border_radius'] ?? '8px') == '8px' ? 'selected' : '' }}>Mediano (8px)</option>
                        <option value="12px" {{ old('style_json.border_radius', $styles['border_radius'] ?? '8px') == '12px' ? 'selected' : '' }}>Grande (12px)</option>
                        <option value="16px" {{ old('style_json.border_radius', $styles['border_radius'] ?? '8px') == '16px' ? 'selected' : '' }}>Muy grande (16px)</option>
                        <option value="24px" {{ old('style_json.border_radius', $styles['border_radius'] ?? '8px') == '24px' ? 'selected' : '' }}>Extra grande (24px)</option>
                    </select>
                    <div class="admin-field-help">Nivel de redondeo de las esquinas del formulario</div>
                    @error('style_json.border_radius')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Shadow -->
                <div class="admin-field-group">
                    <label class="admin-field-label">
                        Sombra del Formulario
                    </label>
                    <div class="flex items-center space-x-3 p-4 admin-card rounded-lg">
                        <input type="hidden" name="style_json[form_shadow]" value="0">
                        <input type="checkbox" name="style_json[form_shadow]" value="1" 
                               {{ old('style_json.form_shadow', $styles['form_shadow'] ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 admin-input rounded focus:ring-2 focus:ring-acid-green">
                        <div>
                            <div class="admin-text font-medium">Activar Sombra</div>
                            <div class="admin-text-secondary text-sm">Añade una sombra sutil al formulario</div>
                        </div>
                    </div>
                    @error('style_json.form_shadow')
                        <div class="admin-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="admin-field-group">
            <h4 class="admin-field-label mb-4">
                <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Vista Previa
            </h4>
            
            <div class="admin-card rounded-lg p-6">
                <div id="style-preview" class="border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[200px] flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <p class="admin-text-secondary">La vista previa se actualizará automáticamente</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color picker synchronization
    function syncColorInputs(colorInput, textInput) {
        colorInput.addEventListener('input', function() {
            textInput.value = this.value;
            updatePreview();
        });
        
        textInput.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                colorInput.value = this.value;
                updatePreview();
            }
        });
    }

    // Sync color inputs
    syncColorInputs(
        document.getElementById('style_background_color'),
        document.getElementById('style_background_color_text')
    );
    
    syncColorInputs(
        document.getElementById('style_primary_color'),
        document.getElementById('style_primary_color_text')
    );

    // Update preview on any style change
    const styleInputs = document.querySelectorAll('input[name^="style_json"], select[name^="style_json"]');
    styleInputs.forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });

    function updatePreview() {
        const preview = document.getElementById('style-preview');
        const backgroundColor = document.getElementById('style_background_color').value;
        const primaryColor = document.getElementById('style_primary_color').value;
        const borderRadius = document.getElementById('style_border_radius').value;
        const formShadow = document.querySelector('input[name="style_json[form_shadow]"]').checked;
        const headerImage1 = document.getElementById('style_header_image_1').value;
        const headerImage2 = document.getElementById('style_header_image_2').value;
        const backgroundTexture = document.getElementById('style_background_texture').value;

        // Build preview HTML
        let previewHTML = '';
        
        // Header images
        if (headerImage1 || headerImage2) {
            previewHTML += '<div class="mb-4 text-center">';
            if (headerImage1) {
                previewHTML += `<img src="${headerImage1}" alt="Header 1" class="mx-auto max-h-16 rounded mb-2" onerror="this.style.display='none'">`;
            }
            if (headerImage2) {
                previewHTML += `<img src="${headerImage2}" alt="Header 2" class="mx-auto max-h-12 rounded" onerror="this.style.display='none'">`;
            }
            previewHTML += '</div>';
        }

        // Form preview
        previewHTML += `
            <div class="max-w-md mx-auto p-4 rounded-lg border" 
                 style="background-color: ${backgroundColor}; border-radius: ${borderRadius}; ${formShadow ? 'box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);' : ''}">
                <h3 class="text-lg font-semibold mb-3" style="color: ${primaryColor};">Vista Previa del Formulario</h3>
                <div class="space-y-3">
                    <input type="text" placeholder="Campo de ejemplo" class="w-full p-2 border rounded" style="border-radius: ${borderRadius};">
                    <button class="w-full p-2 text-white rounded" style="background-color: ${primaryColor}; border-radius: ${borderRadius};">Botón de Ejemplo</button>
                </div>
            </div>
        `;

        // Background texture
        if (backgroundTexture) {
            preview.style.backgroundImage = `url(${backgroundTexture})`;
            preview.style.backgroundSize = 'cover';
            preview.style.backgroundPosition = 'center';
        } else {
            preview.style.backgroundImage = 'none';
        }

        preview.innerHTML = previewHTML;
    }

    // Initial preview update
    updatePreview();
});
</script>
