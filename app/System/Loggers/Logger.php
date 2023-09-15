<?php
namespace System\Loggers;

use Traits\Singleton;
use Psr\Log\AbstractLogger;

/**
 * Class Logger
 * @package App\System
 */
abstract class Logger extends AbstractLogger
{
    protected function __construct()
    {
    }

    /**
     * Формирует строку с описанием пойманного исключения и записывает ее в лог-файл
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $date = date('Y-m-d H:i:s');
        $level = ucfirst($level);
        $log = "[{$date}] {$level}: $message IP: {$_SERVER['REMOTE_ADDR']}\n";

        if (!empty($context) && is_array($context)) {
            foreach ($context as $item) {
                $log .= $item . "\n";
            }
        }

        $log .= "==================================================\n";
        fwrite($this->resource, $log);
    }
}
