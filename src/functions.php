<?php

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
