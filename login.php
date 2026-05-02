<?php
session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';

// Kalau sudah login → langsung ke dashboard
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Username dan password harus diisi.';
    } else {
        // 🔥 pakai function login dari auth.php
        if (login($conn, $username, $password)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>