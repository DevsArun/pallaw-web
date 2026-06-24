<?php
/**
 * Global configuration.
 * Adjust DB credentials to match your hosting (cPanel / localhost / etc.)
 */

// ---- Database credentials ----
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('DB_NAME') ?: 'nexora_institute');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// ---- App ----
date_default_timezone_set('Asia/Kolkata');

// Auto-detect base URL path (works in subfolders too)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
// If we are inside /admin or /student, go one level up to the project root
$basePath  = preg_replace('#/(admin|student)$#', '', $scriptDir);
$basePath  = ($basePath === '' || $basePath === '/') ? '' : $basePath;
define('BASE_URL', $basePath);

// Error reporting: turn off display in production
ini_set('display_errors', '1');
error_reporting(E_ALL);
