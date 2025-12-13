<?php
/**
 * Lightweight file logger for application events and errors.
 * Writes to logs/app.log (creates directory if needed).
 */

// Set logging timezone to Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

function app_log(string $message, array $context = []): void
{
    $logDir  = __DIR__ . '/../logs';
    $logFile = $logDir . '/app.log';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $timestamp = date('Y-m-d H:i:s');
    $entry = [
        'time'    => $timestamp,
        'message' => $message,
        'context' => $context,
    ];

    $line = json_encode($entry, JSON_UNESCAPED_SLASHES) . PHP_EOL;
    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

/**
 * Log a web request (manual page hits/clicks).
 * Call near the top of pages/actions you want to track.
 */
function app_log_request(string $label = 'page_hit'): void
{
    $context = [
        'method' => $_SERVER['REQUEST_METHOD'] ?? '',
        'uri'    => $_SERVER['REQUEST_URI'] ?? '',
        'ip'     => $_SERVER['REMOTE_ADDR'] ?? '',
    ];

    app_log($label, $context);
}

