<?php

namespace Exceptions;

use Throwable;
use System\Logger;

/**
 * Class EditException
 * @package App\Exceptions
 */
class EditException extends BaseException
{
    protected $code = 400;
    protected $message = 'Не удалось сохранить изменения';

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        Logger::getInstance()->error($this);
    }
}
