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

    protected $resource;

    protected function __construct()
    {
        parent::__construct();
        $this->resource = fopen(CONFIG['log']['access'], 'a');
    }
}
