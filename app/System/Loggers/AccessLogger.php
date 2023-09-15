<?php
namespace System\Loggers;

/**
 * Class AccessLogger
 * @package System\Loggers
 */
class AccessLogger extends Logger
{
    protected function __construct()
    {
        parent::__construct();
        $this->resource = fopen(CONFIG['log']['access'], 'a');
    }
}
