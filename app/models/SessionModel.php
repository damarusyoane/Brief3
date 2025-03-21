<?php
require_once __DIR__ . '/app/core/Model.php';

use Model;
class SessionModel extends Model {
    protected $table = 'sessions';

    public function createSession($user_id) {
        $sql = "INSERT INTO $this->table (user_id) VALUES (:user_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $this->db->lastInsertId();
    }

    public function updateLogoutTime($session_id) {
        $sql = "UPDATE $this->table SET logout_time = CURRENT_TIMESTAMP WHERE id = :session_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['session_id' => $session_id]);
    }
}