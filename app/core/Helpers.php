<?php
// app/core/Helpers.php
/**
 * Build a URL to a path relative to the application root.
 *
 * If the BASE_URL ends with "/public" (e.g. when running index.php directly without
 * rewrite rules), this helper will generate links using "index.php?url=..." so that
 * requests are correctly routed through the front controller. When BASE_URL does not
 * end with "/public" (e.g. when using a router or proper web server rewrite), it
 * simply concatenates the base and the relative path.
 *
 * @param string $path The internal path (controller/action) to link to. Should not start with a slash.
 * @return string The fully qualified URL.
 */
function base_url($path = '') {
    $base = rtrim(BASE_URL, '/');
    // Normalize the path by trimming leading slashes
    $trimmed = ltrim($path, '/');
    // Root URL (no path): return the base URL with trailing slash
    if ($trimmed === '' || $trimmed === '/') {
        return $base . '/';
    }
    // Determine if the path likely refers to a static asset. We treat anything under
    // the assets directory or any path containing an extension as a static file.
    $isAsset = (strpos($trimmed, 'assets/') === 0) || preg_match('/\.[a-zA-Z0-9]+$/', $trimmed);
    // When the base ends with '/public' (e.g. running index.php directly without
    // rewrite rules) and the target is NOT a static asset, include index.php?url=...
    if (substr($base, -7) === '/public' && !$isAsset) {
        return $base . '/index.php?url=' . $trimmed;
    }
    // Otherwise, append the path normally
    return $base . '/' . $trimmed;
}
function redirect($path) {
    header('Location: ' . base_url($path));
    exit();
}
function is_post() { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function sanitize($str) { return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8'); }

function current_user_role() {
    return $_SESSION['role'] ?? 'guest';
}

/**
 * Get the currently logged in user's ID.
 *
 * This helper reads the `user_id` from the session, which should be set
 * during login/registration. If not present, null is returned.
 *
 * @return string|null The user ID or null if not logged in
 */
function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get the currently logged in user's full name.
 *
 * If the `user_name` is already stored in the session, that value is
 * returned directly to avoid unnecessary database queries. Otherwise, if
 * `user_id` exists, this helper fetches the name from the `nguoidung` table
 * and caches it back into the session. Should the lookup fail, null is
 * returned.
 *
 * @return string|null The user's full name or null if unknown
 */
function current_user_name() {
    // Return cached name if available
    if (!empty($_SESSION['user_name'])) {
        return $_SESSION['user_name'];
    }
    // If user ID is set, attempt to fetch the name from DB
    if (!empty($_SESSION['user_id'])) {
        try {
            $db = new Database();
            $db->query("SELECT HoVaTen FROM nguoidung WHERE MaND = :id LIMIT 1");
            $db->bind(':id', $_SESSION['user_id']);
            $row = $db->single();
            if ($row && isset($row['HoVaTen'])) {
                $_SESSION['user_name'] = $row['HoVaTen'];
                return $row['HoVaTen'];
            }
        } catch (Exception $e) {
            // Suppress any database errors silently
        }
    }
    return null;
}
/**
 * Enforce that the currently logged in user has one of the given roles.
 *
 * If the user's role is not contained in the `$roles` list, they will be
 * redirected to the appropriate login page. Pages intended only for staff
 * or admin should send users to the staff login endpoint (with the
 * `&staff=1` query string) so that they see the correct login form. Pages
 * that also allow customers will fall back to the standard login page.
 *
 * @param array $roles List of allowed roles for the current page
 */
function require_role($roles = []){
    $current = current_user_role();
    // If the current user doesn't match any of the allowed roles, redirect.
    if (!in_array($current, $roles)) {
        // Normalise role names to lower-case for comparison
        $normalized = array_map('strtolower', $roles);
        // Determine whether this page is exclusively for staff/admin. If
        // neither 'customer' nor 'guest' appear in the list of allowed
        // roles but 'staff' or 'admin' does, then direct to the staff
        // login page. Otherwise default to the customer login page.
        $requiresStaff = (in_array('staff', $normalized) || in_array('admin', $normalized))
                         && !in_array('customer', $normalized) && !in_array('guest', $normalized);
        if ($requiresStaff) {
            redirect('auth/login&staff=1');
        } else {
            redirect('auth/login');
        }
    }
}
function logout() {
    // Capture the current role before clearing the session. This allows us to
    // determine the appropriate login page after logout. Once the session
    // is destroyed, we can no longer access role information.
    $role = $_SESSION['role'] ?? null;
    // Clear all session data and destroy the session
    $_SESSION = [];
    session_destroy();
    // Redirect based on the captured role. Staff and admin users should be
    // returned to the staff login page with the `staff` query flag. All
    // other users are redirected to the standard login page. This ensures
    // that both customer service staff (nhanviencskh) and administrators
    // always land on the staff login form after logging out. Previously
    // administrators were redirected to the customer login page, causing
    // confusion when attempting to log back in as staff.
    if ($role === 'staff' || $role === 'admin') {
        redirect('auth/login&staff=1');
    } else {
        redirect('auth/login');
    }
}


/**
 * Validate a Vietnamese phone number for registration.
 * Only accepts 10-digit numbers starting with 0 (e.g. 0901234567).
 * Returns true if the phone number matches the pattern, false otherwise.
 *
 * @param string $p The phone number to validate
 * @return bool
 */
function validate_phone($p){
    return preg_match('/^0\d{9}$/', $p);
}
function validate_nonempty($s){ return isset($s) && trim($s) !== ''; }
function flash($k,$v){ $_SESSION['flash'][$k]=$v; }
function flash_get($k){ $v=$_SESSION['flash'][$k]??null; unset($_SESSION['flash'][$k]); return $v; }
function require_any_role($roles=[]){
    return require_role($roles);
}
