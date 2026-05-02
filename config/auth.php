<?php
session_start();
require_once __DIR__ . '/db.php';

// ======================
// CEK LOGIN
// ======================
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// ======================
// WAJIB LOGIN
// ======================
function requireLogin()
{
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

// ======================
// LOGIN USER (PAKAI DATABASE)
// ======================
function login($conn, $username, $password)
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        // SESSION BARU (INI YANG DIPAKAI INDEX.PHP)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        return true;
    }

    return false;
}

// ======================
// LOGOUT
// ======================
function logout()
{
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}