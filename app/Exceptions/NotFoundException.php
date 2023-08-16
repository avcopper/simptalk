<?php

namespace Exceptions;

use Throwable;
use System\Logger;

/**
 * Class NotFoundException
 * @package App\Exceptions
 */
class NotFoundException extends BaseException
{
    protected $code = 404;
    protected $message = 'Страница не найдена';

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        Logger::getInstance()->error($this);
    }
}
