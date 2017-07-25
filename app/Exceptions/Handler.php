<?php

namespace App\Exceptions;

use Exception, Raygun;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    private $sentryID;    
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if(env('APP_ENV') ==  'test' || env('APP_ENV') ==  'dev')
        {
            if ($this->shouldReport($exception)) {
                app('sentry')->captureException($exception);
            }
        }
        
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        
        if(env('APP_ENV') ==  'test' || env('APP_ENV') ==  'dev')
        {
            return response()->view('errors.500', [
                'sentryID' => $this->sentryID,
            ], 500);
        }
        
        if(env('APP_ENV') ==  'production')
        {
            if ($exception instanceof CustomException)
            {
              // Do your stuff here
              return response()->view('errors.'.$exception->getStatusCode(), [], $exception->getStatusCode());
            }
            elseif ($exception instanceof \ErrorException)
            {
                return redirect(SITE_LANG.'/400');
            }
            elseif ($exception instanceof \Illuminate\Session\TokenMismatchException)
            {
                return redirect(SITE_LANG.'/400');
            }
            elseif ($exception instanceof \ModelNotFoundException)
            {
                return redirect(SITE_LANG.'/400');
            }
            elseif ($this->isHttpException($exception))
            {
                return redirect(SITE_LANG.'/400');
            }    
        }
        
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(SITE_LANG.'/login');
    }
}






