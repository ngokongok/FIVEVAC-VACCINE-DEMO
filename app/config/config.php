<?php
// app/config/config.php  — SAFE & SIMPLE
define('APP_NAME', 'Fivevac');

// Auto-detect BASE_URL theo request hiện tại (không phụ thuộc tên thư mục/port)
$scheme     = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host       = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/';            // vd: /fivevac_pro/public/index.php
$scriptDir  = rtrim(str_replace('\\', '/', dirname($scriptName)), '/'); // vd: /fivevac_pro/public
$autoBase   = $scheme . '://' . $host . $scriptDir;
define('BASE_URL', rtrim(getenv('FIVEVAC_BASE_URL') ?: $autoBase, '/'));

// Thông số MySQL
define('DB_HOST', getenv('FIVEVAC_DB_HOST') ?: 'localhost');
define('DB_USER', getenv('FIVEVAC_DB_USER') ?: 'root');
define('DB_PASS', getenv('FIVEVAC_DB_PASS') ?: '');
define('DB_NAME', getenv('FIVEVAC_DB_NAME') ?: 'fivevac_db');

// Bật session
if (session_status() === PHP_SESSION_NONE) { session_start(); }