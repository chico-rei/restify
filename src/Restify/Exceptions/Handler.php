<?php namespace ChicoRei\Packages\Restify\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Psr\Log\LoggerInterface;
use ChicoRei\Packages\Restify\Factories\ResponseFactory;

class Handler extends ExceptionHandler
{

    /**
     * @var
     */
    protected $responseFactory;

    /**
     * @param LoggerInterface $log
     * @param ResponseFactory $responseFactory
     */
    public function __construct(LoggerInterface $log, ResponseFactory $responseFactory)
    {
        parent::__construct($log);

        $this->responseFactory = $responseFactory;
    }

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
            return $this->responseFactory->create($e);
        }

        return parent::render($request, $e);
    }
}
