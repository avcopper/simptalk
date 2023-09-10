<?php

namespace Exceptions;

use Throwable;
use System\Logger;

/**
 * Class UserException
 * @package App\Exceptions
 */
class SystemException extends BaseException
{
    protected $code = 500;
    protected $message = 'Систменая ошибка';

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        Logger::getInstance()->error($this);
    }
}
