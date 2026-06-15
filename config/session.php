<?php
function start_session_once()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function require_login()
{
    start_session_once();

    if (!isset($_SESSION['login'])) {
        header("Location: index.php");
        exit;
    }
}

function require_admin()
{
    require_login();

    if (($_SESSION['status'] ?? '') !== 'admin') {
        header("Location: index.php");
        exit;
    }
}

function redirect_authenticated_user()
{
    start_session_once();

    if (!isset($_SESSION['login'])) {
        return;
    }

    if (($_SESSION['status'] ?? '') === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: menu.php");
    }
    exit;
}
?>
