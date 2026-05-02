<?php
function isLoggedIn()
{
    return isset($_SESSION['user']);
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function checkCredentials($username, $password)
{
    $users = [
        'admin' => 'admin123',
    ];

    return isset($users[$username]) && $users[$username] === $password;
}
