<?php

namespace Exceptions;

use Throwable;
use System\Loggers\ErrorLogger;

/**
 * Class DeleteException
 * @package App\Exceptions
 */
class DeleteException extends BaseException
{
    protected $code = 400;
    protected $message = 'Не удалось удалить элемент';

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        ErrorLogger::getInstance()->error($this);
    }
}
