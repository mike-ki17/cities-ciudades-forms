<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Public\FormPageController;
use App\Http\Controllers\Public\FormSubmitController;
use App\Http\Controllers\Public\FormSlugController;
use App\Http\Controllers\Public\FormSlugSubmitController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\FieldController;
use App\Http\Controllers\Admin\SubmissionController;
use App\Http\Controllers\Admin\MetricsController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Public route for getting available fields (used by form creation)
Route::get('/api/fields/available', function () {
    try {
        $fields = \App\Models\FormCategory::with(['formOptions' => function ($query) {
            $query->where('is_active', true)->orderBy('order');
        }])
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

        $formattedFields = $fields->map(function ($field) {
            return [
                'id' => $field->id,
                'code' => $field->code,
                'name' => $field->name,
                'description' => $field->description,
                'options' => $field->formOptions->map(function ($option) {
                    return [
                        'value' => $option->value,
                        'label' => $option->label,
                        'description' => $option->description,
                    ];
                })->toArray(),
            ];
        });

        return response()->json([
            'success' => true,
            'fields' => $formattedFields,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
});

// Form routes by slug (public access without authentication)
Route::get('/form/{slug}', [FormSlugController::class, 'show'])
    ->name('public.forms.slug.show');
Route::post('/form/{slug}', [FormSlugSubmitController::class, 'store'])
    ->name('public.forms.slug.submit');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    // Route::get('/register', [RegisteredUserController::class, 'create'])
    //     ->name('register');
    // Route::post('/register', [RegisteredUserController::class, 'store']);
    
    // Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    //     ->name('password.request');
    // Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    //     ->name('password.email');
    
    // Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    //     ->name('password.reset');
    // Route::post('/reset-password', [NewPasswordController::class, 'store'])
    //     ->name('password.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Dashboard route
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Events management
    Route::resource('events', EventController::class);
    
    // Forms management
    Route::resource('forms', FormController::class);
    Route::post('/forms/{form}/activate', [FormController::class, 'activate'])->name('forms.activate');
    Route::post('/forms/{form}/deactivate', [FormController::class, 'deactivate'])->name('forms.deactivate');
    Route::get('/forms/available-fields', [FormController::class, 'getAvailableFields'])->name('forms.available-fields');
    
    // Fields management (Form Categories and Options)
    Route::resource('fields', FieldController::class);
    Route::post('/fields/{field}/toggle-status', [FieldController::class, 'toggleStatus'])->name('fields.toggle-status');
    Route::get('/fields/available', [FieldController::class, 'getAvailableFields'])->name('fields.available');
    
    // Field options management
    Route::get('/fields/{field}/options', [FieldController::class, 'options'])->name('fields.options');
    Route::get('/fields/{field}/options/create', [FieldController::class, 'createOption'])->name('fields.options.create');
    Route::post('/fields/{field}/options', [FieldController::class, 'storeOption'])->name('fields.options.store');
    Route::get('/fields/{field}/options/{option}/edit', [FieldController::class, 'editOption'])->name('fields.options.edit');
    Route::put('/fields/{field}/options/{option}', [FieldController::class, 'updateOption'])->name('fields.options.update');
    Route::delete('/fields/{field}/options/{option}', [FieldController::class, 'destroyOption'])->name('fields.options.destroy');
    Route::post('/fields/{field}/options/{option}/toggle-status', [FieldController::class, 'toggleOptionStatus'])->name('fields.options.toggle-status');
    Route::put('/fields/{field}/options/order', [FieldController::class, 'updateOptionOrder'])->name('fields.options.order');
    
    // JSON Fields management
    Route::resource('fields-json', \App\Http\Controllers\Admin\FieldJsonController::class);
    Route::post('/fields-json/upload-csv', [\App\Http\Controllers\Admin\FieldJsonController::class, 'uploadCsvOptions'])->name('fields-json.upload-csv');
    Route::post('/fields-json/{field}/toggle-status', [\App\Http\Controllers\Admin\FieldJsonController::class, 'toggleStatus'])->name('fields-json.toggle-status');
    
    // Submissions management
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/export/csv', [SubmissionController::class, 'export'])->name('submissions.export');
    Route::get('/submissions/statistics', [SubmissionController::class, 'statistics'])->name('submissions.statistics');
    Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
});

// Metrics routes (for restricted users)
Route::middleware(['auth', 'metrics.viewer'])->prefix('admin')->name('admin.metrics.')->group(function () {
    Route::get('/metrics', [MetricsController::class, 'index'])->name('index');
    Route::get('/metrics/statistics', [MetricsController::class, 'statistics'])->name('statistics');
});
