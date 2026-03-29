<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function boot(array $config): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name($config['security']['session_name']);
            session_start();
        }
    }

    public static function login(array $user): void
    {
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'full_name' => $user['full_name'],
            'role' => $user['role_name'],
        ];
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function hasRole(array $roles): bool
    {
        if (!self::check()) {
            return false;
        }

        return in_array($_SESSION['user']['role'], $roles, true);
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
