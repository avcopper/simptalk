<?php
namespace System\Loggers;

/**
 * Class ErrorLogger
 * @package System\Loggers
 */
class WarningLogger extends Logger
{
    protected function __construct()
    {
        parent::__construct();
        $this->resource = fopen(CONFIG['log']['warning'], 'a');
    }
}
