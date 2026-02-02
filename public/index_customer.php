<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Helpers.php';
// Initialize session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Clear any existing role so the user must log in
unset($_SESSION['role']);
// Redirect to login page
header('Location: ' . base_url('auth/form'));
exit;