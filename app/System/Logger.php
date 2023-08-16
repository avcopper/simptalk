<?php

namespace System;

use Traits\Singleton;
use Psr\Log\AbstractLogger;

/**
 * Class Logger
 * @package App\System
 */
class Logger extends AbstractLogger
{
    use Singleton;

    protected $res;

    protected function __construct()
    {
        $this->res = fopen(CONFIG['log']['error'], 'a');
    }

    /**
     * Формирует строку с описанием пойманного исключения и записывает ее в лог-файл
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $log = '[' . date('Y-m-d H:i:s') . '] ' . ucfirst($level) . ': ' . (string)$message . " IP: {$_SERVER['REMOTE_ADDR']}" . "\n";
        foreach ($context as $item) {
            $log .= (string)$item . "\n";
        }
        $log .= "==================================================\n";
        fwrite($this->res, $log);
    }
}
