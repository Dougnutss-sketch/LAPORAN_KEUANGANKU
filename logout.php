<?php
session_start();

// Hapus semua data session
$_SESSION = [];

// Hapus cookie session (penting untuk keamanan)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy session
session_destroy();

// Redirect
header("Location: login.php");
exit;