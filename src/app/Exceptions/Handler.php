<?php

namespace Payment\System\App\Exceptions;

use Throwable as ExceptionAlias;
use GraphQL\Error\Debug;
use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Rebing\GraphQL\Error\AuthorizationError;
use Rebing\GraphQL\Error\ValidationError;

class Handler extends ExceptionHandler
{
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
     * @param ExceptionAlias $exception
     * @return mixed|void
     * @throws ExceptionAlias
     */
    public function report(ExceptionAlias $exception)
    {
        parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param ExceptionAlias $exception
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ExceptionAlias
     */
    public function render($request, ExceptionAlias $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * @param array $errors
     * @param callable $formatter
     * @return array
     * @throws ExceptionAlias
     */
    public static function handleErrors(array $errors, callable $formatter)
    {
        $handler = app()->make(\Illuminate\Contracts\Debug\ExceptionHandler::class);
        foreach ($errors as $error) {
            // Try to unwrap exception
            $error = $error->getPrevious() ?: $error;
            // Don't report certain GraphQL errors
            if ($error instanceof ValidationError
                || $error instanceof AuthorizationError
                || ! ($error instanceof ExceptionAlias)) {
                continue;
            }
            $handler->report($error);
        }
        return array_map($formatter, $errors);
    }

    /**
     * @param Error $e
     * @return array
     */
    public static function formatError(Error $e): array
    {
        $debug = config('app.debug') ? (Debug::INCLUDE_DEBUG_MESSAGE) : 0;

        $formatter = FormattedError::prepareFormatter(null, $debug);
        $error = $formatter($e);
        $previous = $e->getPrevious();

        if ($previous && $previous instanceof ValidationError) {
            $error['extensions']['validation'] = $previous->getValidatorMessages();
        }

        if ($previous && $previous instanceof BusinessLogicException) {
            $error['extensions']['category'] = 'error';
            $error['extensions']['errorCode'] = $previous->statusCode();
            if (!$debug) {
                unset($error['debugMessage']);
            }
            $error['message'] = $previous->status();
        }

        return $error;
    }
}
