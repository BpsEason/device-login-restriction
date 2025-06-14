<?php
session_start();
require_once '../config/db_connect.php';
require_once '../src/SessionManager.php';

$sessionManager = new SessionManager($pdo);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($sessionManager->loginUser($user['id'])) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "無法登錄，請稍後重試。";
        }
    } else {
        $error = "用戶名或密碼錯誤。";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>登錄</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">登錄</h2>
        <?php if ($error): ?>
            <p class="text-red-500 mb-4"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700">用戶名</label>
                <input type="text" name="username" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700">密碼</label>
                <input type="password" name="password" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">登錄</button>
        </form>
    </div>
</body>
</html>