<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProspectController;
use App\Http\Controllers\Api\ProspectDocumentController;
use App\Http\Controllers\Api\CabangController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProspectRecapController;

Route::prefix('v1')->group(function () {

    // public
    Route::get('/ping', function () {
        return response()->json([
            'success' => true,
            'message' => 'API aktif',
        ]);
    });

    // private pakai 1 token tetap
    Route::middleware('static.api.token')->group(function () {

        // prospects - wajib token
        Route::get('/prospects/summary', [ProspectController::class, 'summary']);
        Route::get('/prospects', [ProspectController::class, 'index']);
        Route::get('/prospects/{id}', [ProspectController::class, 'show']);

        // update status prospect langsung
        Route::patch('/prospects/{id}/status', [ProspectController::class, 'updateStatus']);

        // rekap prospect
        Route::get('/prospects-recap/per-kc', [ProspectRecapController::class, 'perKc']);
        Route::get('/prospects-recap/per-kc/{cabangId}/detail', [ProspectRecapController::class, 'detailPerKc']);

        Route::get('/prospects-recap/per-pegawai', [ProspectRecapController::class, 'perPegawai']);
        Route::get('/prospects-recap/pengaju', [ProspectRecapController::class, 'pengaju']);
        Route::get('/prospects-recap/per-pegawai/{userId}/detail', [ProspectRecapController::class, 'detailPegawai']);

        // prospect documents - wajib token
        Route::get('/prospects/{id}/documents', [ProspectDocumentController::class, 'index']);
        Route::post('/prospects/{id}/documents', [ProspectDocumentController::class, 'store']);
        Route::delete('/prospect-documents/{docId}', [ProspectDocumentController::class, 'destroy']);

        // write prospects
        Route::post('/prospects', [ProspectController::class, 'store']);
        Route::put('/prospects/{id}', [ProspectController::class, 'update']);
        Route::delete('/prospects/{id}', [ProspectController::class, 'destroy']);
        Route::post('/prospects/{id}/restore', [ProspectController::class, 'restore']);

        // cabangs
        Route::get('/cabangs', [CabangController::class, 'index']);
        Route::post('/cabangs', [CabangController::class, 'store']);
        Route::put('/cabangs/{id}', [CabangController::class, 'update']);
        Route::patch('/cabangs/{id}/toggle', [CabangController::class, 'toggle']);
        Route::get('/cabangs/template', [CabangController::class, 'downloadTemplate']);
        Route::post('/cabangs/import', [CabangController::class, 'import']);

        // users
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::patch('/users/{id}/toggle', [UserController::class, 'toggle']);
        Route::get('/users/{id}', [UserController::class, 'show']);
    });
});
