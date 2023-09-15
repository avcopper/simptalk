<?php

namespace Exceptions;

use Throwable;
use System\Loggers\ErrorLogger;

/**
 * Class UploaderException
 * @package App\Exceptions
 */
class UploadException extends BaseException
{
    protected $code = 400;
    protected $message = 'Не удалось загрузить файл';

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        ErrorLogger::getInstance()->error($this);
    }
}
