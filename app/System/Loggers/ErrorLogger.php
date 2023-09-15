<?php
namespace System\Loggers;

use Traits\Singleton;

/**
 * Class ErrorLogger
 * @package System\Loggers
 */
class ErrorLogger extends Logger
{
    use Singleton;

    protected function __construct()
    {
        $this->resource = fopen(CONFIG['log']['error'], 'a');
    }
}
