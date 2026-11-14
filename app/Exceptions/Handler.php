<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Friendly copy shown instead of "419" / "Page Expired".
     */
    public const SESSION_EXPIRED_MESSAGE = 'Your session timed out for security. Please refresh the page and try again.';

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // TokenMismatch is converted to HttpException(419) before renderables run.
        $this->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            $message = self::SESSION_EXPIRED_MESSAGE;

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'success' => false,
                    'error' => 'session_expired',
                ], 400);
            }

            return response()->view('errors.419', [
                'exception' => $e,
                'message' => $message,
            ], 419);
        });
    }

    /**
     * Ensure CSRF mismatches carry a human-readable message (never blank / "419").
     *
     * @param  \Throwable  $e
     * @return \Throwable
     */
    protected function prepareException(Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            return new HttpException(419, self::SESSION_EXPIRED_MESSAGE, $e);
        }

        return parent::prepareException($e);
    }
}
