<?php
class SessionManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->cleanExpiredSessions();
    }

    private function generateSessionToken() {
        return bin2hex(random_bytes(32));
    }

    private function getDeviceInfo() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return hash('sha256', $userAgent . $ipAddress);
    }

    public function loginUser($userId) {
        $deviceInfo = $this->getDeviceInfo();
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $sessionToken = $this->generateSessionToken();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $this->pdo->prepare("SELECT COUNT(*) as session_count FROM user_sessions WHERE user_id = ? AND expires_at > NOW()");
        $stmt->execute([$userId]);
        $sessionCount = $stmt->fetchColumn();

        if ($sessionCount >= 2) {
            $stmt = $this->pdo->prepare("DELETE FROM user_sessions WHERE user_id = ? ORDER BY created_at ASC LIMIT 1");
            $stmt->execute([$userId]);
        }

        $stmt = $this->pdo->prepare("INSERT INTO user_sessions (user_id, session_token, device_info, ip_address, expires_at) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$userId, $sessionToken, $deviceInfo, $ipAddress, $expiresAt])) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['session_token'] = $sessionToken;
            return true;
        }
        return false;
    }

    public function verifySession() {
        if (!isset($_SESSION['user_id'], $_SESSION['session_token'])) {
            return false;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM user_sessions WHERE user_id = ? AND session_token = ? AND expires_at > NOW()");
        $stmt->execute([$_SESSION['user_id'], $_SESSION['session_token']]);
        if ($stmt->fetch()) {
            return true;
        }

        session_unset();
        session_destroy();
        return false;
    }

    public function logoutUser($userId, $sessionToken) {
        $stmt = $this->pdo->prepare("DELETE FROM user_sessions WHERE user_id = ? AND session_token = ?");
        $stmt->execute([$userId, $sessionToken]);
        session_unset();
        session_destroy();
    }

    private function cleanExpiredSessions() {
        $stmt = $this->pdo->prepare("DELETE FROM user_sessions WHERE expires_at < NOW()");
        $stmt->execute();
    }
}
?>