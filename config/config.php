<?php

return [
    'db' => [
        'host'     => 'localhost',
        'dbprefix' => 'talk_',
        'dbname'   => 'mesigo',
        'user'     => 'root',
        'password' => 'root'
    ],
    'log' => [
        'error'    => DIR_LOGS . DIRECTORY_SEPARATOR . 'error' . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log',
        'access'    => DIR_LOGS . DIRECTORY_SEPARATOR . 'access' . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log',
        'system'    => DIR_LOGS . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log',
        'warning'    => DIR_LOGS . DIRECTORY_SEPARATOR . 'warning' . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log',
    ]
];
