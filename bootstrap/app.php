<?php

// use App\Http\Middleware\EnsureTokenIsValid;

use GuzzleHttp\Exception\ServerException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            // 'auth' => EnsureTokenIsValid::class,
            'throttle' => ThrottleRequests::class,
            'sanctum' => EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'message' => 'Access token is missing or invalid',
                'error' => $e->getMessage(),
            ], 401);
        });

        $exceptions->render(function (AccessDeniedHttpException $e) {
            return response()->json([
                'message' => 'Access denied',
                'error' => $e->getMessage(),
            ], 403);
        });

        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json([
                'message' => 'Resource not found',
                'error' => $e->getMessage(),
            ], 404);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'message' => 'HTTP method not allowed',
                'error' => $e->getMessage(),
            ], 405);
        });

        $exceptions->render(function (ServerException $e) {
            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage(),
            ], 500);
        });

    })->create();
