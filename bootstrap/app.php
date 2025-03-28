<?php

use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

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
        ]);

        $middleware->api([
            EnsureFrontendRequestsAreStateful::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (AuthenticationException $e): JsonResponse {
            return response()->json([
                'message' => 'Access token is missing or invalid',
                'error' => $e->getMessage(),
            ], 401);
        });

        $exceptions->render(function (AccessDeniedHttpException $e): JsonResponse {
            return response()->json([
                'message' => 'Access denied',
                'error' => $e->getMessage(),
            ], 403);
        });

        $exceptions->render(function (NotFoundHttpException $e): JsonResponse {
            return response()->json([
                'message' => 'Resource not found',
                'error' => $e->getMessage(),
            ], 404);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e): JsonResponse {
            return response()->json([
                'message' => 'HTTP method not allowed',
                'error' => $e->getMessage(),
            ], 405);
        });

        $exceptions->render(function (TooManyRequestsHttpException $e): JsonResponse {
            return response()->json([
                'message' => 'Too many requests, please try again later',
                'error' => $e->getMessage(),
            ], 429);
        });

        $exceptions->render(function (\Throwable $e): JsonResponse {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        });

    })->create();
