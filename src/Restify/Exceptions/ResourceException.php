<?php namespace ChicoRei\Packages\Restify\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ResourceException
 *
 * @package ChicoRei\Packages\Restify\Exceptions
 */
class ResourceException extends HttpException
{
    /**
     * MessageBag errors.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Create a new resource exception instance.
     *
     * @param string $message
     * @param \Illuminate\Support\MessageBag|array $errors
     * @param \Exception $previous
     * @param array $headers
     * @param int $code
     */
    public function __construct($message = null, $errors = null, Exception $previous = null, $headers = [], $code = 0)
    {
        if (is_null($errors))
        {
            $this->errors = new MessageBag;
        } else
        {
            $this->errors = is_array($errors) ? new MessageBag($errors) : $errors;
        }
        parent::__construct(422, $message, $previous, $headers, $code);
    }

    /**
     * Get the errors message bag.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return $this->getErrors();
    }

    /**
     * Get the errors message bag.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Determine if message bag has any errors.
     *
     * @return bool
     */
    public function hasErrors()
    {
        return !$this->errors->isEmpty();
    }
}
