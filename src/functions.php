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

// Helper to get a POST value with trimming
function post(string $key, $default = '')
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
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
