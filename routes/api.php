<?php

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
        Route::post('/prospects/{id}/documents', [ProspectDocumentController::class, 'store']); // multipart
        Route::delete('/prospect-documents/{docId}', [ProspectDocumentController::class, 'destroy']);

        // ===== CABANGS =====
        Route::get('/cabangs', [CabangController::class, 'index']);
        Route::post('/cabangs', [CabangController::class, 'store']);
        Route::put('/cabangs/{id}', [CabangController::class, 'update']);
        Route::patch('/cabangs/{id}/toggle', [CabangController::class, 'toggle']);
        Route::get('/cabangs/template', [CabangController::class, 'downloadTemplate']);
        Route::post('/cabangs/import', [CabangController::class, 'import']); // multipart csv

        // ===== USERS (ADMIN only enforced inside controller) =====
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::patch('/users/{id}/toggle', [UserController::class, 'toggle']);
        Route::get('/users/{id}', [UserController::class, 'show']);
    });
});
