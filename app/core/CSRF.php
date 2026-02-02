<?php
// app/core/CSRF.php
function csrf_token(){
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_field(){
    $t = csrf_token();
    echo '<input type="hidden" name="csrf_token" value="' . $t . '">';
}
function verify_csrf(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $in = $_POST['csrf_token'] ?? '';
        $ok = hash_equals($_SESSION['csrf_token'] ?? '', $in);
        if (!$ok) { http_response_code(419); die('CSRF token không hợp lệ. Vui lòng tải lại trang.'); }
    }
}