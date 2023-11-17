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
        'error'   => date('d') . '.log',
        'access'  => date('d') . '.log',
        'system'  => date('d') . '.log',
        'warning' => date('d') . '.log',
    ]
];
