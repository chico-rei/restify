<?php namespace ChicoRei\Packages\Restify\Exceptions;

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
     * @param  \Throwable $e
     * @return void
     */
    public function report(\Throwable $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Throwable $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Throwable $e)
    {
        if ($request->wantsJson())
        {
            $responseFactory = new ResponseFactory();

            return $responseFactory->create($e);
        }

        return parent::render($request, $e);
    }
}
