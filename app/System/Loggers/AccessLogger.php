<?php
namespace System\Loggers;

use Traits\Singleton;

/**
 * Class AccessLogger
 * @package System\Loggers
 */
class AccessLogger extends Logger
{
    use Singleton;

    protected function __construct()
    {
        $this->resource = fopen(CONFIG['log']['access'], 'a');
    }
}
