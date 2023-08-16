<?php

namespace Exceptions;

use Throwable;
use System\Access;

/**
 * Class ForbiddenException
 * @package App\Exceptions
 */
class ForbiddenException extends BaseException
{
    protected $code = 403;
    protected $message = 'Доступ запрещен';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        Access::getInstance()->error($this);
    }
}
