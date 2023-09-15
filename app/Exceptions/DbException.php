<?php

namespace Exceptions;

use Throwable;
use System\Loggers\ErrorLogger;

/**
 * Class DbException
 * @package App\Exceptions
 */
class DbException extends BaseException
{
    protected $code = 500;
    protected $message = 'Что-то пошло не так. Зайдите позже';

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        ErrorLogger::getInstance()->error($this);
    }
}
