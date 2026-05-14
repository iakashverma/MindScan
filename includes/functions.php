<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function post_string(string $key, string $default = ''): string
{
    return isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;
}

function post_int(string $key, int $default = 0): int
{
    if (!isset($_POST[$key])) {
        return $default;
    }

    return (int)$_POST[$key];
}

function clamp_int(int $value, int $min, int $max): int
{
    return max($min, min($max, $value));
}

function require_fields(array $keys): array
{
    $missing = [];
    foreach ($keys as $key) {
        if (!isset($_POST[$key]) || trim((string)$_POST[$key]) === '') {
            $missing[] = $key;
        }
    }

    return $missing;
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}
