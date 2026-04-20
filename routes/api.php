<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProspectController;
use App\Http\Controllers\Api\ProspectDocumentController;
use App\Http\Controllers\Api\CabangController;
use App\Http\Controllers\Api\UserController;

Route::prefix('v1')->group(function () {

    // ===== AUTH =====
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // ===== SAVE FCM TOKEN =====
        Route::post('/me/fcm-token', function (Request $request) {
            $request->validate([
                'token' => ['required', 'string'],
            ]);

            $user = $request->user();
            $user->fcm_token = $request->token;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'FCM token berhasil disimpan.',
            ]);
        });

        Route::delete('/me/fcm-token', function (Request $request) {
            $user = $request->user();
            $user->fcm_token = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'FCM token berhasil dihapus.',
            ]);
        });

        // ===== PROSPECTS =====
        Route::get('/prospects/summary', [ProspectController::class, 'summary']);
        Route::get('/prospects', [ProspectController::class, 'index']);
        Route::post('/prospects', [ProspectController::class, 'store']);
        Route::get('/prospects/{id}', [ProspectController::class, 'show']);
        Route::put('/prospects/{id}', [ProspectController::class, 'update']);
        Route::delete('/prospects/{id}', [ProspectController::class, 'destroy']);
        Route::post('/prospects/{id}/restore', [ProspectController::class, 'restore']);

        // ===== PROSPECT DOCUMENTS (FOTO) =====
        Route::get('/prospects/{id}/documents', [ProspectDocumentController::class, 'index']);
        Route::post('/prospects/{id}/documents', [ProspectDocumentController::class, 'store']);
        Route::delete('/prospect-documents/{docId}', [ProspectDocumentController::class, 'destroy']);

        // ===== CABANGS =====
        Route::get('/cabangs', [CabangController::class, 'index']);
        Route::post('/cabangs', [CabangController::class, 'store']);
        Route::put('/cabangs/{id}', [CabangController::class, 'update']);
        Route::patch('/cabangs/{id}/toggle', [CabangController::class, 'toggle']);
        Route::get('/cabangs/template', [CabangController::class, 'downloadTemplate']);
        Route::post('/cabangs/import', [CabangController::class, 'import']);

        // ===== USERS =====
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::patch('/users/{id}/toggle', [UserController::class, 'toggle']);
        Route::get('/users/{id}', [UserController::class, 'show']);
    });
});
