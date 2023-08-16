<?php

namespace Exceptions;

use Throwable;
use System\Logger;

/**
 * Class BaseException
 * @package App\Exceptions
 */
class BaseException extends \Exception
{
    protected $code = 0;
    protected $message = '';

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        $this->code = $code;
        $this->message = $message;
        parent::__construct($message, $code, $previous);
    }
}
