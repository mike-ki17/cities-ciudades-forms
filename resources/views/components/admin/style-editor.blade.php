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

        <!-- Image Sizing Configuration Section -->
        <div class="admin-field-group">
            <h4 class="admin-field-label mb-4">
                <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                </svg>
                Configuración de Tamaño de Imágenes
            </h4>
            
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Image 1 Sizing -->
                <div class="admin-card rounded-lg p-4">
                    <h5 class="admin-field-label mb-3">
                        <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Imagen Principal
                    </h5>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Width -->
                        <div class="admin-field-group">
                            <label for="style_image_1_width" class="admin-field-label text-sm">
                                Ancho (px)
                            </label>
                            <input type="number" name="style_json[image_1_width]" id="style_image_1_width" 
                                   value="{{ old('style_json.image_1_width', $styles['image_1_width'] ?? 200) }}"
                                   class="admin-input w-full" min="50" max="500" step="10">
                        </div>
                        
                        <!-- Height -->
                        <div class="admin-field-group">
                            <label for="style_image_1_height" class="admin-field-label text-sm">
                                Alto (px)
                            </label>
                            <input type="number" name="style_json[image_1_height]" id="style_image_1_height" 
                                   value="{{ old('style_json.image_1_height', $styles['image_1_height'] ?? 100) }}"
                                   class="admin-input w-full" min="30" max="300" step="10">
                        </div>
                    </div>
                    
                    <!-- Aspect Ratio Lock -->
                    <div class="mt-3">
                        <div class="flex items-center space-x-3 p-3 admin-card rounded-lg">
                            <input type="checkbox" name="style_json[image_1_lock_aspect]" value="1" 
                                   {{ old('style_json.image_1_lock_aspect', $styles['image_1_lock_aspect'] ?? true) ? 'checked' : '' }}
                                   id="style_image_1_lock_aspect"
                                   class="w-4 h-4 admin-input rounded focus:ring-2 focus:ring-acid-green">
                            <label for="style_image_1_lock_aspect" class="admin-text font-medium cursor-pointer">
                                Mantener proporción
                            </label>
                        </div>
                    </div>
                    
                    <!-- Object Fit -->
                    <div class="mt-3">
                        <label for="style_image_1_object_fit" class="admin-field-label text-sm">
                            Ajuste de imagen
                        </label>
                        <select name="style_json[image_1_object_fit]" id="style_image_1_object_fit" class="admin-select w-full">
                            <option value="contain" {{ old('style_json.image_1_object_fit', $styles['image_1_object_fit'] ?? 'contain') == 'contain' ? 'selected' : '' }}>Contener (sin recortar)</option>
                            <option value="cover" {{ old('style_json.image_1_object_fit', $styles['image_1_object_fit'] ?? 'contain') == 'cover' ? 'selected' : '' }}>Cubrir (puede recortar)</option>
                            <option value="fill" {{ old('style_json.image_1_object_fit', $styles['image_1_object_fit'] ?? 'contain') == 'fill' ? 'selected' : '' }}>Llenar (distorsionar)</option>
                            <option value="scale-down" {{ old('style_json.image_1_object_fit', $styles['image_1_object_fit'] ?? 'contain') == 'scale-down' ? 'selected' : '' }}>Reducir si es necesario</option>
                        </select>
                    </div>
                </div>

                <!-- Image 2 Sizing -->
                <div class="admin-card rounded-lg p-4">
                    <h5 class="admin-field-label mb-3">
                        <svg class="w-4 h-4 inline-block mr-1" style="color: #00ffbd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Imagen Secundaria
                    </h5>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Width -->
                        <div class="admin-field-group">
                            <label for="style_image_2_width" class="admin-field-label text-sm">
                                Ancho (px)
                            </label>
                            <input type="number" name="style_json[image_2_width]" id="style_image_2_width" 
                                   value="{{ old('style_json.image_2_width', $styles['image_2_width'] ?? 150) }}"
                                   class="admin-input w-full" min="50" max="400" step="10">
                        </div>
                        
                        <!-- Height -->
                        <div class="admin-field-group">
                            <label for="style_image_2_height" class="admin-field-label text-sm">
                                Alto (px)
                            </label>
                            <input type="number" name="style_json[image_2_height]" id="style_image_2_height" 
                                   value="{{ old('style_json.image_2_height', $styles['image_2_height'] ?? 80) }}"
                                   class="admin-input w-full" min="30" max="250" step="10">
                        </div>
                    </div>
                    
                    <!-- Aspect Ratio Lock -->
                    <div class="mt-3">
                        <div class="flex items-center space-x-3 p-3 admin-card rounded-lg">
                            <input type="checkbox" name="style_json[image_2_lock_aspect]" value="1" 
                                   {{ old('style_json.image_2_lock_aspect', $styles['image_2_lock_aspect'] ?? true) ? 'checked' : '' }}
                                   id="style_image_2_lock_aspect"
                                   class="w-4 h-4 admin-input rounded focus:ring-2 focus:ring-acid-green">
                            <label for="style_image_2_lock_aspect" class="admin-text font-medium cursor-pointer">
                                Mantener proporción
                            </label>
                        </div>
                    </div>
                    
                    <!-- Object Fit -->
                    <div class="mt-3">
                        <label for="style_image_2_object_fit" class="admin-field-label text-sm">
                            Ajuste de imagen
                        </label>
                        <select name="style_json[image_2_object_fit]" id="style_image_2_object_fit" class="admin-select w-full">
                            <option value="contain" {{ old('style_json.image_2_object_fit', $styles['image_2_object_fit'] ?? 'contain') == 'contain' ? 'selected' : '' }}>Contener (sin recortar)</option>
                            <option value="cover" {{ old('style_json.image_2_object_fit', $styles['image_2_object_fit'] ?? 'contain') == 'cover' ? 'selected' : '' }}>Cubrir (puede recortar)</option>
                            <option value="fill" {{ old('style_json.image_2_object_fit', $styles['image_2_object_fit'] ?? 'contain') == 'fill' ? 'selected' : '' }}>Llenar (distorsionar)</option>
                            <option value="scale-down" {{ old('style_json.image_2_object_fit', $styles['image_2_object_fit'] ?? 'contain') == 'scale-down' ? 'selected' : '' }}>Reducir si es necesario</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Responsive Options -->
            <div class="mt-6">
                <h5 class="admin-field-label mb-3">
                    <svg class="w-4 h-4 inline-block mr-1" style="color: #bb2558;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Opciones Responsivas
                </h5>
                
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <!-- Mobile Behavior -->
                    <div class="admin-field-group">
                        <label for="style_mobile_image_behavior" class="admin-field-label text-sm">
                            Comportamiento en móvil
                        </label>
                        <select name="style_json[mobile_image_behavior]" id="style_mobile_image_behavior" class="admin-select w-full">
                            <option value="stack" {{ old('style_json.mobile_image_behavior', $styles['mobile_image_behavior'] ?? 'stack') == 'stack' ? 'selected' : '' }}>Apilar verticalmente</option>
                            <option value="hide_secondary" {{ old('style_json.mobile_image_behavior', $styles['mobile_image_behavior'] ?? 'stack') == 'hide_secondary' ? 'selected' : '' }}>Ocultar imagen secundaria</option>
                            <option value="resize" {{ old('style_json.mobile_image_behavior', $styles['mobile_image_behavior'] ?? 'stack') == 'resize' ? 'selected' : '' }}>Redimensionar ambas</option>
                        </select>
                    </div>
                    
                    <!-- Mobile Scale Factor -->
                    <div class="admin-field-group">
                        <label for="style_mobile_scale" class="admin-field-label text-sm">
                            Escala en móvil (%)
                        </label>
                        <input type="number" name="style_json[mobile_scale]" id="style_mobile_scale" 
                               value="{{ old('style_json.mobile_scale', $styles['mobile_scale'] ?? 80) }}"
                               class="admin-input w-full" min="30" max="100" step="5">
                        <div class="admin-field-help text-xs">Porcentaje del tamaño original en dispositivos móviles</div>
                    </div>
                    
                    <!-- Image Spacing -->
                    <div class="admin-field-group">
                        <label for="style_image_spacing" class="admin-field-label text-sm">
                            Espaciado entre imágenes (px)
                        </label>
                        <input type="number" name="style_json[image_spacing]" id="style_image_spacing" 
                               value="{{ old('style_json.image_spacing', $styles['image_spacing'] ?? 16) }}"
                               class="admin-input w-full" min="0" max="50" step="2">
                    </div>
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
                <div id="style-preview" class="border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[200px] flex items-center justify-center flex-col" >
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

    // Aspect ratio lock functionality
    function setupAspectRatioLock(imageNumber) {
        const widthInput = document.getElementById(`style_image_${imageNumber}_width`);
        const heightInput = document.getElementById(`style_image_${imageNumber}_height`);
        const lockCheckbox = document.getElementById(`style_image_${imageNumber}_lock_aspect`);
        
        let isUpdating = false;
        let originalRatio = 0;
        
        // Calculate original ratio when lock is enabled
        function calculateRatio() {
            if (widthInput.value && heightInput.value) {
                originalRatio = widthInput.value / heightInput.value;
            }
        }
        
        // Update height when width changes (if locked)
        widthInput.addEventListener('input', function() {
            if (lockCheckbox.checked && !isUpdating && originalRatio > 0) {
                isUpdating = true;
                heightInput.value = Math.round(this.value / originalRatio);
                isUpdating = false;
                updatePreview();
            }
        });
        
        // Update width when height changes (if locked)
        heightInput.addEventListener('input', function() {
            if (lockCheckbox.checked && !isUpdating && originalRatio > 0) {
                isUpdating = true;
                widthInput.value = Math.round(this.value * originalRatio);
                isUpdating = false;
                updatePreview();
            }
        });
        
        // Handle lock checkbox changes
        lockCheckbox.addEventListener('change', function() {
            if (this.checked) {
                calculateRatio();
            }
        });
        
        // Initial ratio calculation
        calculateRatio();
    }
    
    // Setup aspect ratio locks for both images
    setupAspectRatioLock(1);
    setupAspectRatioLock(2);

    function updatePreview() {
        const preview = document.getElementById('style-preview');
        const backgroundColor = document.getElementById('style_background_color').value;
        const primaryColor = document.getElementById('style_primary_color').value;
        const borderRadius = document.getElementById('style_border_radius').value;
        const formShadow = document.querySelector('input[name="style_json[form_shadow]"]').checked;
        const headerImage1 = document.getElementById('style_header_image_1').value;
        const headerImage2 = document.getElementById('style_header_image_2').value;
        const backgroundTexture = document.getElementById('style_background_texture').value;
        
        // Get image sizing values
        const image1Width = document.getElementById('style_image_1_width').value || 200;
        const image1Height = document.getElementById('style_image_1_height').value || 100;
        const image1ObjectFit = document.getElementById('style_image_1_object_fit').value || 'contain';
        const image2Width = document.getElementById('style_image_2_width').value || 150;
        const image2Height = document.getElementById('style_image_2_height').value || 80;
        const image2ObjectFit = document.getElementById('style_image_2_object_fit').value || 'contain';
        const imageSpacing = document.getElementById('style_image_spacing').value || 16;
        const mobileBehavior = document.getElementById('style_mobile_image_behavior').value || 'stack';
        const mobileScale = document.getElementById('style_mobile_scale').value || 80;

        // Build preview HTML
        let previewHTML = '';
        preview.style.backgroundColor = backgroundColor;

        // Header images with dynamic sizing
        if (headerImage1 || headerImage2) {
            const spacing = `${imageSpacing}px`;
            previewHTML += `
                <div class="mb-4 flex justify-center items-center" style="gap: ${spacing};">
            `;
            
            if (headerImage1) {
                const mobileWidth = Math.round(image1Width * mobileScale / 100);
                const mobileHeight = Math.round(image1Height * mobileScale / 100);
                
                previewHTML += `
                    <img src="${headerImage1}" alt="Header 1" 
                         style="width: ${image1Width}px; height: ${image1Height}px; object-fit: ${image1ObjectFit}; border-radius: ${borderRadius};"
                         class="rounded transition-all duration-300"
                         onerror="this.style.display='none'"
                         onload="this.style.opacity='1'">
                `;
            }
            
            if (headerImage2 && mobileBehavior !== 'hide_secondary') {
                const mobileWidth = Math.round(image2Width * mobileScale / 100);
                const mobileHeight = Math.round(image2Height * mobileScale / 100);
                
                previewHTML += `
                    <img src="${headerImage2}" alt="Header 2" 
                         style="width: ${image2Width}px; height: ${image2Height}px; object-fit: ${image2ObjectFit}; border-radius: ${borderRadius};"
                         class="rounded transition-all duration-300"
                         onerror="this.style.display='none'"
                         onload="this.style.opacity='1'">
                `;
            }
            
            previewHTML += '</div>';
            
            // Add responsive behavior info
            if (mobileBehavior === 'stack') {
                previewHTML += `
                    <div class="text-center text-xs admin-text-secondary mb-2">
                        <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        En móvil: Se apilarán verticalmente
                    </div>
                `;
            } else if (mobileBehavior === 'hide_secondary') {
                previewHTML += `
                    <div class="text-center text-xs admin-text-secondary mb-2">
                        <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                        </svg>
                        En móvil: Solo se mostrará la imagen principal
                    </div>
                `;
            } else if (mobileBehavior === 'resize') {
                previewHTML += `
                    <div class="text-center text-xs admin-text-secondary mb-2">
                        <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                        </svg>
                        En móvil: Redimensionadas al ${mobileScale}%
                    </div>
                `;
            }
        }

        // Form preview
        previewHTML += `
            <div class=" p-4 rounded-lg border" 
                style="width:70%; background-color: #ffffff; border-radius: ${borderRadius}; ${formShadow ? 'box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);' : ''}">
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
