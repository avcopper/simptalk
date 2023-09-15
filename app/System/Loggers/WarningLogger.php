<?php
namespace System\Loggers;

use Traits\Singleton;

/**
 * Class ErrorLogger
 * @package System\Loggers
 */
class WarningLogger extends Logger
{
    use Singleton;

    protected $resource;

    protected function __construct()
    {
        parent::__construct();
        $this->resource = fopen(CONFIG['log']['warning'], 'a');
    }
}
