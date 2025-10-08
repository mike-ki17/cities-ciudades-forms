<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileUploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API endpoint to get localities for a specific city
Route::get('/localities/{city}', function (Request $request, string $city) {
    $localities = [];
    
    switch (strtolower($city)) {
        case 'bogota':
            $localities = [
                ['value' => 'usaquen', 'label' => 'Usaquén'],
                ['value' => 'chapinero', 'label' => 'Chapinero'],
                ['value' => 'santa_fe', 'label' => 'Santa Fe'],
                ['value' => 'san_cristobal', 'label' => 'San Cristóbal'],
                ['value' => 'usme', 'label' => 'Usme'],
                ['value' => 'tunjuelito', 'label' => 'Tunjuelito'],
                ['value' => 'bosa', 'label' => 'Bosa'],
                ['value' => 'kennedy', 'label' => 'Kennedy'],
                ['value' => 'fontibon', 'label' => 'Fontibón'],
                ['value' => 'engativa', 'label' => 'Engativá'],
                ['value' => 'suba', 'label' => 'Suba'],
                ['value' => 'barrios_unidos', 'label' => 'Barrios Unidos'],
                ['value' => 'teusaquillo', 'label' => 'Teusaquillo'],
                ['value' => 'martires', 'label' => 'Los Mártires'],
                ['value' => 'antonio_narino', 'label' => 'Antonio Nariño'],
                ['value' => 'puente_aranda', 'label' => 'Puente Aranda'],
                ['value' => 'candelaria', 'label' => 'La Candelaria'],
                ['value' => 'rafael_uribe', 'label' => 'Rafael Uribe Uribe'],
                ['value' => 'ciudad_bolivar', 'label' => 'Ciudad Bolívar'],
                ['value' => 'sumapaz', 'label' => 'Sumapaz']
            ];
            break;
            
        case 'medellin':
            $localities = [
                ['value' => 'popular', 'label' => 'Popular'],
                ['value' => 'santa_cruz', 'label' => 'Santa Cruz'],
                ['value' => 'manrique', 'label' => 'Manrique'],
                ['value' => 'aranjuez', 'label' => 'Aranjuez'],
                ['value' => 'castilla', 'label' => 'Castilla'],
                ['value' => 'doce_octubre', 'label' => 'Doce de Octubre'],
                ['value' => 'robledo', 'label' => 'Robledo'],
                ['value' => 'villa_hermosa', 'label' => 'Villa Hermosa'],
                ['value' => 'buenavista', 'label' => 'Buenavista'],
                ['value' => 'la_candelaria', 'label' => 'La Candelaria'],
                ['value' => 'laureles', 'label' => 'Laureles-Estadio'],
                ['value' => 'la_america', 'label' => 'La América'],
                ['value' => 'san_javier', 'label' => 'San Javier'],
                ['value' => 'el_poblado', 'label' => 'El Poblado'],
                ['value' => 'guayabal', 'label' => 'Guayabal'],
                ['value' => 'belen', 'label' => 'Belén']
            ];
            break;
            
        case 'cali':
            $localities = [
                ['value' => 'comuna_1', 'label' => 'Comuna 1 - Popular'],
                ['value' => 'comuna_2', 'label' => 'Comuna 2 - Santa Rita'],
                ['value' => 'comuna_3', 'label' => 'Comuna 3 - Sucre'],
                ['value' => 'comuna_4', 'label' => 'Comuna 4 - Aranjuez'],
                ['value' => 'comuna_5', 'label' => 'Comuna 5 - Castilla'],
                ['value' => 'comuna_6', 'label' => 'Comuna 6 - Doce de Octubre'],
                ['value' => 'comuna_7', 'label' => 'Comuna 7 - Robledo'],
                ['value' => 'comuna_8', 'label' => 'Comuna 8 - Villa Hermosa'],
                ['value' => 'comuna_9', 'label' => 'Comuna 9 - Buenos Aires'],
                ['value' => 'comuna_10', 'label' => 'Comuna 10 - La Candelaria'],
                ['value' => 'comuna_11', 'label' => 'Comuna 11 - Laureles'],
                ['value' => 'comuna_12', 'label' => 'Comuna 12 - La América'],
                ['value' => 'comuna_13', 'label' => 'Comuna 13 - San Javier'],
                ['value' => 'comuna_14', 'label' => 'Comuna 14 - El Poblado'],
                ['value' => 'comuna_15', 'label' => 'Comuna 15 - Guayabal'],
                ['value' => 'comuna_16', 'label' => 'Comuna 16 - Belén'],
                ['value' => 'comuna_17', 'label' => 'Comuna 17 - Villa Hermosa'],
                ['value' => 'comuna_18', 'label' => 'Comuna 18 - Buenos Aires'],
                ['value' => 'comuna_19', 'label' => 'Comuna 19 - La Candelaria'],
                ['value' => 'comuna_20', 'label' => 'Comuna 20 - Laureles'],
                ['value' => 'comuna_21', 'label' => 'Comuna 21 - La América'],
                ['value' => 'comuna_22', 'label' => 'Comuna 22 - San Javier']
            ];
            break;
            
        case 'barranquilla':
            $localities = [
                ['value' => 'riomar', 'label' => 'Riomar'],
                ['value' => 'norte_centro_historico', 'label' => 'Norte Centro Histórico'],
                ['value' => 'sur_occidente', 'label' => 'Sur Occidente'],
                ['value' => 'metropolitana', 'label' => 'Metropolitana'],
                ['value' => 'suroriente', 'label' => 'Suroriente']
            ];
            break;
            
        case 'cartagena':
            $localities = [
                ['value' => 'historia_y_caribe', 'label' => 'Historia y Caribe Norte'],
                ['value' => 'de_la_virgen_y_turistica', 'label' => 'De la Virgen y Turística'],
                ['value' => 'industrial_y_de_la_bahia', 'label' => 'Industrial y de la Bahía']
            ];
            break;
            
        case 'bucaramanga':
            $localities = [
                ['value' => 'norte', 'label' => 'Norte'],
                ['value' => 'nororiente', 'label' => 'Nororiente'],
                ['value' => 'santander', 'label' => 'Santander'],
                ['value' => 'garcia_rovirosa', 'label' => 'García Rovira'],
                ['value' => 'convencion', 'label' => 'Convención'],
                ['value' => 'lacides_castro', 'label' => 'Lácides Castro'],
                ['value' => 'mutis', 'label' => 'Mutis'],
                ['value' => 'morrorico', 'label' => 'Morrorico'],
                ['value' => 'sur', 'label' => 'Sur'],
                ['value' => 'suroccidente', 'label' => 'Suroccidente'],
                ['value' => 'occidente', 'label' => 'Occidente'],
                ['value' => 'provenza', 'label' => 'Provenza'],
                ['value' => 'cabecera', 'label' => 'Cabecera del Llano'],
                ['value' => 'centro', 'label' => 'Centro'],
                ['value' => 'oriental', 'label' => 'Oriental'],
                ['value' => 'pedregosa', 'label' => 'Pedregosa'],
                ['value' => 'sureste', 'label' => 'Sureste']
            ];
            break;
            
        default:
            return response()->json(['error' => 'Ciudad no encontrada'], 404);
    }
    
    return response()->json([
        'success' => true,
        'city' => $city,
        'localities' => $localities
    ]);
});

// File upload routes
Route::prefix('files')->group(function () {
    Route::post('/upload', [FileUploadController::class, 'upload']);
    Route::get('/info', [FileUploadController::class, 'info']);
    Route::delete('/delete', [FileUploadController::class, 'delete']);
    
    // Test route
    Route::post('/test', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'API funcionando correctamente',
            'data' => $request->all()
        ]);
    });
    
    // Simple file test route
    Route::post('/test-file', function (Request $request) {
        try {
            $file = $request->file('file');
            if (!$file) {
                return response()->json(['success' => false, 'message' => 'No file provided'], 400);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'File received successfully',
                'file_info' => [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    });
});
