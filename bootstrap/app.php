<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, $request) {
            // Always return JSON for API routes - prioritize this check
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'error' => 'authentication_required',
                    'status' => 401
                ], 401);
            }

            // Check if the request expects JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'error' => 'authentication_required',
                    'status' => 401
                ], 401);
            }

            // For any other route, still return JSON since this is an API-only app
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'authentication_required',
                'status' => 401
            ], 401);
        });

        // Handle other exceptions for API routes
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->is('api/*')) {
                // Don't interfere with 404s and other handled exceptions
                return null;
            }
            return null;
        });
    })->create();
