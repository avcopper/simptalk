<?php

namespace System;

class ErrorSupervisor
{
    public function __construct()
    {
        set_error_handler([$this, 'OtherErrorCatcher']); // регистрация ошибок
        register_shutdown_function([$this, 'FatalErrorCatcher']); // перехват критических ошибок
        ob_start(); // создание буфера вывода
    }

    public function OtherErrorCatcher($errno, $errstr, $errfile = null, $errline = null)
    {
        Response::apiResult(500, false, $errstr);
    }

    public function FatalErrorCatcher()
    {
        $error = error_get_last();
        if (!empty($error) && in_array($error['type'], [E_ERROR, E_PARSE, E_COMPILE_ERROR, E_CORE_ERROR])) {
            ob_end_clean();    // сбросить буфер, завершить работу буфера

            Response::apiResult(500, false, 'Undefined error');
            // контроль критических ошибок:
            // - записать в лог
            // - вернуть заголовок 500
            // - вернуть после заголовка данные для пользователя
        } else ob_end_flush();	// вывод буфера, завершить работу буфера
    }
}
