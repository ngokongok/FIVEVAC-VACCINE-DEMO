<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Helpers.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Clear any existing role so the user must log in
unset($_SESSION['role']);
// Redirect to login page specifically for staff. Pass a flag to hide the navbar and disable signup.
// Use '&' instead of '?' because base_url already uses '?url='.
header('Location: ' . base_url('auth/login&staff=1'));
exit;