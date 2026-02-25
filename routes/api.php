<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AscDirectiveController;
use App\Http\Controllers\API\AscParticipationController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PolDeploymentController;
use App\Http\Controllers\API\VipController;
use App\Http\Controllers\API\WAscDeploymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes - Authentication
Route::prefix('v1/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Auth routes (protected)
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);

    // Get authenticated user (alternative endpoint)
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => $request->user(),
        ]);
    });

    // POL Deployment routes
    Route::apiResource('pol-deployments', PolDeploymentController::class);
    Route::get('/pol-deployments/{id}/vips', [PolDeploymentController::class, 'getVips']);
    Route::post('/pol-deployments/{id}/vips', [PolDeploymentController::class, 'addVip']);
    Route::delete('/pol-deployments/{id}/vips/{vipId}', [PolDeploymentController::class, 'removeVip']);

    // W ASC Deployment routes
    Route::apiResource('w-asc-deployments', WAscDeploymentController::class);
    // Officers management for W ASC Deployments
    Route::get('/w-asc-deployments/{id}/officers', [WAscDeploymentController::class, 'getOfficers']);
    Route::post('/w-asc-deployments/{id}/officers', [WAscDeploymentController::class, 'addOfficer']);
    Route::put('/w-asc-deployments/{id}/officers/{officerId}', [WAscDeploymentController::class, 'updateOfficer']);
    Route::delete('/w-asc-deployments/{id}/officers/{officerId}', [WAscDeploymentController::class, 'removeOfficer']);
    // VIPs management for W ASC Deployments
    Route::get('/w-asc-deployments/{id}/vips', [WAscDeploymentController::class, 'getVips']);
    Route::post('/w-asc-deployments/{id}/vips', [WAscDeploymentController::class, 'addVip']);
    Route::delete('/w-asc-deployments/{id}/vips/{vipId}', [WAscDeploymentController::class, 'removeVip']);

    // VIPs routes
    Route::get('/vips/check-exists', [VipController::class, 'checkExists']);
    Route::apiResource('vips', VipController::class);

    // ASC Directives routes (polymorphic - supports both deployment types)
    Route::get('/{deploymentType}/{deploymentId}/asc-directives', [AscDirectiveController::class, 'index'])
        ->where('deploymentType', 'pol-deployment|w-asc-deployment');
    Route::post('/{deploymentType}/{deploymentId}/asc-directives', [AscDirectiveController::class, 'store'])
        ->where('deploymentType', 'pol-deployment|w-asc-deployment');
    Route::get('/asc-directives/{ascDirective}', [AscDirectiveController::class, 'show']);
    Route::put('/asc-directives/{ascDirective}', [AscDirectiveController::class, 'update']);
    Route::delete('/asc-directives/{ascDirective}', [AscDirectiveController::class, 'destroy']);

    // ASC Participation routes (polymorphic - supports both deployment types)
    Route::get('/{deploymentType}/{deploymentId}/asc-participation', [AscParticipationController::class, 'index'])
        ->where('deploymentType', 'pol-deployment|w-asc-deployment');
    Route::post('/{deploymentType}/{deploymentId}/asc-participation', [AscParticipationController::class, 'store'])
        ->where('deploymentType', 'pol-deployment|w-asc-deployment');
    Route::get('/asc-participation/{ascParticipation}', [AscParticipationController::class, 'show']);
    Route::put('/asc-participation/{ascParticipation}', [AscParticipationController::class, 'update']);
    Route::delete('/asc-participation/{ascParticipation}', [AscParticipationController::class, 'destroy']);

    // Admin management routes (superadmin only)
    Route::apiResource('admins', AdminController::class);

    // Deprecated - Old Event routes (kept for reference, will be removed after migration)
    // Route::apiResource('events', EventController::class);
    // Route::get('/events/{event}/vips', [EventController::class, 'getVips']);
    // Route::post('/events/{event}/vips', [EventController::class, 'addVip']);
    // Route::delete('/events/{event}/vips/{vipId}', [EventController::class, 'removeVip']);
});
