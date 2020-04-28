<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        switch(true) {
            case $exception instanceof ValidationException:
                return $this->convertValidationExceptionToResponse($exception, $request);
                break;
            case $exception instanceof ModelNotFoundException:
                $model = strtolower(class_basename($exception->getModel()));
                return $this->errorResponse("There is no {$model} with the id specified", 404);
                break;
            case $exception instanceof AuthenticationException:
                return $this->unauthenticated($request, $exception);
                break;
            case $exception instanceof NotFoundHttpException:
                return $this->errorResponse("URL specified not found", 404);
                break;
            case $exception instanceof AuthorizationException:
                return $this->errorResponse("You have no permission to execute this action", 403);
                break;
            case $exception instanceof MethodNotAllowedHttpException:
                return $this->errorResponse("Method specified is not valid", 405);
                break;
            case $exception instanceof HttpException:
                return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
                break;
            default:
                if (config('app.debug')) {
                    return parent::render($request, $exception);
                } else {
                    return $this->errorResponse("Unexpected error. Please try later", 500);
                }
        }
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse($exception->getMessage(), 401);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        return $this->errorResponse($e->errors(), $e->status);
    }
}
