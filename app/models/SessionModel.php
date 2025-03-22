<?php
require_once __DIR__ . '/../core/Model.php';

class SessionModel extends Model {
    protected $table = 'sessions';

    public function createSession($userId, $sessionId, $ipAddress, $userAgent) {
        try {
            $sql = "INSERT INTO {$this->table} (user_id, session_id, ip_address, user_agent) 
                   VALUES (:user_id, :session_id, :ip_address, :user_agent)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent
            ]);
        } catch (PDOException $e) {
            error_log("Error creating session: " . $e->getMessage());
            return false;
        }
    }

    public function updateLogoutTime($userId) {
        try {
            $sql = "UPDATE {$this->table} SET logout_time = CURRENT_TIMESTAMP 
                   WHERE user_id = :user_id AND logout_time IS NULL";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['user_id' => $userId]);
        } catch (PDOException $e) {
            error_log("Error updating logout time: " . $e->getMessage());
            return false;
        }
    }

    public function updateMAJ($userId) {
        try {
            $sql = "UPDATE {$this->table} SET MAJ = CURRENT_TIMESTAMP 
                   WHERE user_id = :user_id AND logout_time IS NULL";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['user_id' => $userId]);
        } catch (PDOException $e) {
            error_log("Error updating MAJ: " . $e->getMessage());
            return false;
        }
    }

    public function getActiveSessions($userId) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                   WHERE user_id = :user_id AND logout_time IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting active sessions: " . $e->getMessage());
            return [];
        }
    }

    public function getSessionHistory($userId) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                   WHERE user_id = :user_id 
                   ORDER BY login_time DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting session history: " . $e->getMessage());
            return [];
        }
    }

    public function getOnlineUsers() {
        try {
            $sql = "SELECT DISTINCT u.* FROM users u 
                   INNER JOIN {$this->table} s ON u.id = s.user_id 
                   WHERE s.logout_time IS NULL 
                   AND s.MAJ >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 5 MINUTE)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting online users: " . $e->getMessage());
            return [];
        }
    }

    public function getRecentActivity($limit = 10) {
        try {
            $sql = "SELECT u.*, s.login_time, s.MAJ 
                   FROM users u 
                   INNER JOIN {$this->table} s ON u.id = s.user_id 
                   ORDER BY s.MAJ DESC LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting recent activity: " . $e->getMessage());
            return [];
        }
    }

    public function getUserSessions($userId) {
        $sql = "SELECT * FROM sessions WHERE user_id = ? ORDER BY created_at DESC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting user sessions: " . $e->getMessage());
            return [];
        }
    }

    public function getAllSessions() {
        $sql = "SELECT s.*, u.username FROM sessions s 
                JOIN users u ON s.user_id = u.id 
                ORDER BY s.created_at DESC";
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting all sessions: " . $e->getMessage());
            return [];
        }
    }

    public function deactivateSession($sessionId) {
        try {
            $sql = "UPDATE {$this->table} SET logout_time = CURRENT_TIMESTAMP 
                   WHERE session_id = :session_id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['session_id' => $sessionId]);
        } catch (PDOException $e) {
            error_log("Error deactivating session: " . $e->getMessage());
            return false;
        }
    }

    public function getSessionByToken($sessionId) {
        try {
            $sql = "SELECT s.*, u.username FROM {$this->table} s 
                   JOIN users u ON s.user_id = u.id 
                   WHERE s.session_id = :session_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['session_id' => $sessionId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Error getting session by token: " . $e->getMessage());
            return null;
        }
    }
}