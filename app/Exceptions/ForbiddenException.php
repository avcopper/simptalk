<?php
namespace Exceptions;

use Throwable;
use System\Loggers\AccessLogger;

/**
 * Class ForbiddenException
 * @package App\Exceptions
 */
class ForbiddenException extends BaseException
{
    protected $code = 403;
    protected $message = 'Доступ запрещен';

    public function __construct($message = 'Forbidden', $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        AccessLogger::getInstance()->error($this);
    }
}
