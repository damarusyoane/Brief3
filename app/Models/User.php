<?php
namespace App\Models;

use App\Core\Database;

class User
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getAllUsers()
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserById($id)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function createUser($userData)
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password, role_id, status) 
            VALUES (:username, :email, :password, :role_id, :status)
        ");
        return $stmt->execute($userData);
    }

    public function updateUser($id, $userData)
    {
        $userData['id'] = $id;
        $sql = "UPDATE users SET 
                username = :username,
                email = :email,
                role_id = :role_id,
                status = :status";
        
        if (isset($userData['password'])) {
            $sql .= ", password = :password";
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($userData);
    }

    public function deleteUser($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.username = :username
        ");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.email = :email
        ");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public function updateResetToken($id, $token, $expires)
    {
        $stmt = $this->db->prepare("UPDATE users SET reset_token = :token, reset_token_expires = :expires WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'token' => $token,
            'expires' => $expires
        ]);
    }

    public function findByResetToken($token)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            WHERE reset_token = :token 
            AND reset_token_expires > NOW()
        ");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    public function updatePassword($id, $password)
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
            return $stmt->execute([
                'id' => $id,
                'password' => $hashedPassword
            ]);
        } catch (\Exception $e) {
            error_log("Password update error: " . $e->getMessage());
            return false;
        }
    }

    public function clearResetToken($id)
    {
        $stmt = $this->db->prepare("UPDATE users SET reset_token = NULL, reset_token_expires = NULL WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function createSession($sessionData)
    {
        $stmt = $this->db->prepare("
            INSERT INTO sessions (user_id, session_id, ip_address, user_agent, login_time)
            VALUES (:user_id, :session_id, :ip_address, :user_agent, NOW())
        ");
        return $stmt->execute($sessionData);
    }

    public function updateSessionLogout($userId, $sessionId)
    {
        $stmt = $this->db->prepare("
            UPDATE sessions 
            SET logout_time = NOW() 
            WHERE user_id = :user_id 
            AND session_id = :session_id 
            AND logout_time IS NULL
        ");
        return $stmt->execute([
            'user_id' => $userId,
            'session_id' => $sessionId
        ]);
    }

    public function getUserStats()
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_users,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_users
            FROM users
        ");
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getRecentSessions()
    {
        $stmt = $this->db->prepare("
            SELECT 
                s.*,
                u.username,
                u.email,
                TIMESTAMPDIFF(MINUTE, s.login_time, COALESCE(s.logout_time, NOW())) as duration_minutes
            FROM sessions s
            JOIN users u ON s.user_id = u.id
            ORDER BY s.login_time DESC
            LIMIT 10
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllSessions()
    {
        $stmt = $this->db->prepare("
            SELECT 
                s.*,
                u.username,
                u.email,
                TIMESTAMPDIFF(MINUTE, s.login_time, COALESCE(s.logout_time, NOW())) as duration_minutes
            FROM sessions s
            JOIN users u ON s.user_id = u.id
            ORDER BY s.login_time DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function cleanupOldSessions($days = 30)
    {
        $stmt = $this->db->prepare("
            DELETE FROM sessions 
            WHERE login_time < DATE_SUB(NOW(), INTERVAL :days DAY)
        ");
        return $stmt->execute(['days' => $days]);
    }

    public function storeResetToken($userId, $token, $expires)
    {
        $sql = "UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$token, $expires, $userId]);
    }

    public function verifyResetToken($token)
    {
        $sql = "SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$token]);
        $result = $stmt->fetch();
        return $result ? $result['id'] : false;
    }

    public function getUserSessions($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                s.*,
                TIMESTAMPDIFF(MINUTE, s.login_time, COALESCE(s.logout_time, NOW())) as duration_minutes
            FROM sessions s
            WHERE s.user_id = :user_id
            ORDER BY s.login_time DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

}
