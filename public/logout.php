<?php
session_start();
require_once '../config/db_connect.php';
require_once '../src/SessionManager.php';

$sessionManager = new SessionManager($pdo);
if (isset($_SESSION['user_id'], $_SESSION['session_token'])) {
    $sessionManager->logoutUser($_SESSION['user_id'], $_SESSION['session_token']);
}
header("Location: login.php");
exit;
?>