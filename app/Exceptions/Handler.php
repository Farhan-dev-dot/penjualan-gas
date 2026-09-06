<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (TokenMismatchException $exception, Request $request): ?Response {
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->route('admin.login')->with(
                    'error',
                    'Sesi formulir telah berakhir. Silakan masuk kembali untuk melanjutkan.'
                );
            }

            return null;
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
