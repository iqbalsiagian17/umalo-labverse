<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


class Handler extends ExceptionHandler
{
    protected $levels = [
        // Define custom log levels here
    ];

    protected $dontReport = [
        // Specify the exceptions that should not be reported
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Customize reporting logic here
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
    
            if (view()->exists("errors.{$code}")) {
                return response()->view("errors.{$code}", [], $code);
            }
        }
    
        return parent::render($request, $exception);
    }
}
