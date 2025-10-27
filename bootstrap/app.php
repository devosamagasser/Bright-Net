<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\UnauthorizedException;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Modules\Shared\Support\Helper\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'locale' => SetLocale::class,
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (UnauthorizedException $e, $request) {
            return ApiResponse::unauthorized();
        });
        $exceptions->render(function (AuthenticationException $e, $request) {
            return ApiResponse::unauthorized(__('auth.invalid_credentials'));
        });

        $exceptions->render(function (AccessDeniedHttpException | AuthorizationException $e, $request) {
            return ApiResponse::forbidden();
        });

        $exceptions->render(function (NotFoundHttpException | ModelNotFoundException $e, $request) {
            return ApiResponse::notFound();
        });

        $exceptions->render(function (\DomainException $e, $request){
            return ApiResponse::message($e->getMessage(), 400);
        });

        $exceptions->render(function (HttpException $e, $request){
            return ApiResponse::message($e->getMessage(), $e->getStatusCode());
        });
    })->create();
