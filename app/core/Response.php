<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    public static function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function view(string $viewPath, array $data = []): void
    {
        $data['viewPath'] = $viewPath;
        $data['baseUrl']  = defined('BASE_URL') ? BASE_URL : '';
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/layouts/app.php';
        exit;
    }

    public static function redirect(string $to): void
    {
        $base = defined('BASE_URL') ? BASE_URL : '';
        header('Location: ' . $base . $to);
        exit;
    }
}
