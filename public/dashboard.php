<?php
session_start();
require_once '../config/db_connect.php';
require_once '../src/SessionManager.php';

$sessionManager = new SessionManager($pdo);
if (!$sessionManager->verifySession()) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>儀表板</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">歡迎，<?php echo htmlspecialchars($user['username']); ?>！</h1>
        <p>這是你的儀表板。</p>
        <a href="logout.php" class="inline-block bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">登出</a>
    </div>
</body>
</html>