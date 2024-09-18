<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use ErrorException;


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

    //Jika Dalam Develop
/*      public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Customize reporting logic here
        });
    }  */

    //Jika Server Dinaikkan
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Logika untuk melaporkan exception
        });
    
        $this->renderable(function (ErrorException $e, $request) {
            return response()->view('errors.generic_error', ['exception' => $e], 500);
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
