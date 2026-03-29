<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Church Management System',
        'base_path' => '/kanisa/church-cms/public',
        'timezone' => 'Africa/Dar_es_Salaam',
        'debug' => true,
    ],
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'church_cms',
        'user' => 'root',
        'pass' => 'change-me',
        'charset' => 'utf8mb4',
    ],
    'security' => [
        'session_name' => 'church_cms_session',
    ],
];