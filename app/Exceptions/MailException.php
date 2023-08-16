<?php

namespace Exceptions;

use Throwable;
use System\Logger;

/**
 * Class MailException
 * @package App\Exceptions
 */
class MailException extends BaseException
{
    protected $code = 400;
    protected $message = 'Не удалось отправить сообщение';

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        Logger::getInstance()->error($this);
    }
}
