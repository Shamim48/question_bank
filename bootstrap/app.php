<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'         => \App\Http\Middleware\AdminMiddleware::class,
            'student'       => \App\Http\Middleware\StudentMiddleware::class,
            'team.or.admin' => \App\Http\Middleware\TeamOrAdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                return back()
                    ->withInput($request->except('password', 'password_confirmation'))
                    ->with('error', 'Your session expired. Please try again.');
            }
        });
    })->create();
