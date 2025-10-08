<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FieldJson;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Upload a file for a specific form field
     */
    public function upload(Request $request): JsonResponse
    {
        \Log::info('File upload request received', [
            'form_id' => $request->form_id,
            'field_key' => $request->field_key,
            'file_name' => $request->file('file') ? $request->file('file')->getClientOriginalName() : 'No file',
            'all_data' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'form_id' => 'required|exists:forms,id',
            'field_key' => 'required|string',
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            \Log::error('File upload validation failed', [
                'errors' => $validator->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \Log::info('Starting file upload process');
            
            // Get form and field information
            $form = Form::findOrFail($request->form_id);
            \Log::info('Form found', ['form_id' => $form->id, 'form_name' => $form->name]);
            
            $field = FieldJson::where('key', $request->field_key)->first();
            \Log::info('Field lookup', ['field_key' => $request->field_key, 'field_found' => $field ? 'yes' : 'no']);

            if (!$field) {
                \Log::error('Field not found', ['field_key' => $request->field_key]);
                return response()->json([
                    'success' => false,
                    'message' => 'Campo no encontrado'
                ], 404);
            }

            if ($field->type !== 'file') {
                \Log::error('Field is not file type', ['field_type' => $field->type]);
                return response()->json([
                    'success' => false,
                    'message' => 'El campo especificado no es de tipo archivo'
                ], 400);
            }

            // Get field validations
            $validations = $field->validations ?? [];
            \Log::info('Field validations', ['validations' => $validations]);

            // Upload file
            \Log::info('Starting file upload service');
            $fileInfo = $this->fileUploadService->uploadFile(
                $request->file('file'),
                $form->id,
                $field->key,
                $validations
            );
            \Log::info('File upload completed', ['file_info' => $fileInfo]);

            return response()->json([
                'success' => true,
                'message' => 'Archivo subido exitosamente',
                'file_info' => $fileInfo
            ]);

        } catch (\InvalidArgumentException $e) {
            \Log::error('File upload validation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        } catch (\Exception $e) {
            \Log::error('File upload server error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download a file (for local storage)
     */
    public function download(Request $request, string $encodedPath)
    {
        try {
            $path = base64_decode($encodedPath);
            
            if (!$path || !Storage::disk('form_files')->exists($path)) {
                abort(404, 'Archivo no encontrado');
            }

            return Storage::disk('form_files')->download($path);

        } catch (\Exception $e) {
            abort(404, 'Archivo no encontrado');
        }
    }

    /**
     * Get file information
     */
    public function info(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
            'disk' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $fileInfo = $this->fileUploadService->getFileInfo(
                $request->path,
                $request->disk
            );

            if (!$fileInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Archivo no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'file_info' => $fileInfo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información del archivo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a file
     */
    public function delete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
            'disk' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $deleted = $this->fileUploadService->deleteFile(
                $request->path,
                $request->disk
            );

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Archivo eliminado exitosamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el archivo'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el archivo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}