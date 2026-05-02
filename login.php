<?php
session_start();
require_once __DIR__ . '/config/auth.php';

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
    } elseif (checkCredentials($username, $password)) {
        $_SESSION['user'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Manajemen Keuangan</title>
    <link rel="stylesheet" href="assets/styles.css" />
</head>
<body class="page-login">
    <div class="auth-shell">
        <div class="auth-card">
            <h1>Masuk</h1>
            <p>Gunakan akun admin untuk mengakses aplikasi.</p>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" class="auth-form" id="loginForm">
                <label>Username</label>
                <input type="text" name="username" required />

                <label>Password</label>
                <input type="password" name="password" required />

                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
