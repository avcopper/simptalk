<?php

namespace Exceptions;

use Throwable;
use System\Loggers\AccessLogger;

/**
 * Class UserException
 * @package App\Exceptions
 */
class UserException extends BaseException
{
    protected $code = 400;
    protected $message = 'Ошибка авторизации';

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        AccessLogger::getInstance()->error($this);
    }
}
