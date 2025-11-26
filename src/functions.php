<?php

// Start session for all pages that include this file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple helper to safely escape output
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Helper to get a POST value (sanitised string)
function post(string $key, $default = '')
{
    $value = filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ($value === null || $value === false) {
        return $default;
    }
    return trim($value);
}

// Is user logged in?
function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

// Get current username
function current_username(): string
{
    return $_SESSION['username'] ?? '';
}

// Require login for protected pages
function require_login(): void
{
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Generate a simple math CAPTCHA like "3 + 5"
 * Stores the answer in session as `captcha_answer`
 */
function generate_captcha_question(): string
{
    $a = rand(1, 9);
    $b = rand(1, 9);
    $_SESSION['captcha_answer'] = $a + $b;
    return "{$a} + {$b}";
}

/**
 * Validate captcha input (integer compare)
 */
function validate_captcha(string $userAnswer): bool
{
    if (!isset($_SESSION['captcha_answer'])) {
        return false;
    }

    $expected = (int) $_SESSION['captcha_answer'];
    $given = (int) $userAnswer;

    unset($_SESSION['captcha_answer']);

    return $given === $expected;
}

/**
 * Render a Twig template with provided data.
 */
function render_template(string $template, array $data = []): void
{
    // Keep Twig environment in a static variable so it's created only once
    static $twigEnv = null;

    if ($twigEnv === null) {
        // This file defines $twig (Twig environment)
        require __DIR__ . '/../config/twig.php';
        $twigEnv = $twig;
    }

    echo $twigEnv->render($template, $data);
}
