<?php namespace ChicoRei\Packages\Restify\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use ChicoRei\Packages\Restify\Factories\ResponseFactory;

/**
 * Class Handler
 *
 * @package ChicoRei\Packages\Restify\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->wantsJson())
        {
            $responseFactory = new ResponseFactory();

            return $responseFactory->create($e);
        }

        return parent::render($request, $e);
    }
}
