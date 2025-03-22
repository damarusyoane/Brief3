<?php
require_once __DIR__ . '/../core/Model.php';

class UserModel extends Model {
    protected $table = 'users'; // Table name

    /**
     * Create a new user.
     *
     * @param string $nom The user's name.
     * @param string $email The user's email.
     * @param string $password The user's password (hashed).
     * @return bool True if the user was created, false otherwise.
     */
    public function createUser($username, $email, $password, $role_id) {
        try {
            // Validate input
            if (empty($username) || empty($email) || empty($password) || empty($role_id)) {
                return false;
            }

            // Check if username or email already exists
            if (!$this->isUsernameEmailUnique($username, $email)) {
                return false;
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user in the database
            $sql = "INSERT INTO {$this->table} (username, email, password, role_id, status) 
                   VALUES (:username, :email, :password, :role_id, 'active')";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'role_id' => $role_id
            ]);
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByUsername($username) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE username = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['username' => $username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user by username: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a user by email.
     *
     * @param string $email The user's email.
     * @return array|bool The user data if found, false otherwise.
     */
    public function getUserByEmail($email) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user by email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a user by ID.
     *
     * @param int $id The user's ID.
     * @return array|bool The user data if found, false otherwise.
     */
    public function getUserById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a user exists with the given email.
     *
     * @param string $email The user's email.
     * @return bool True if the user exists, false otherwise.
     */
    public function userExists($email) {
        $user = $this->getUserByEmail($email);
        return $user !== false;
    }

    /**
     * Get a user by reset token.
     *
     * @param string $token The password reset token.
     * @return array|bool The user data if found, false otherwise.
     */
    public function getUserByResetToken($token) {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE reset_token = ?",
            [$token]
        )->fetch();
    }

    /**
    * Store a password reset token for the user.
    *
    * @param int $userId The user's ID.
    * @param string $token The password reset token.
    * @param string $expires The token expiration timestamp.
    * @return void
    */
   public function storePasswordResetToken($userId, $token, $expires) {
       try {
           $sql = "UPDATE {$this->table} SET reset_token = :token, reset_token_expires = :expires 
                  WHERE id = :id";
           $stmt = $this->db->prepare($sql);
           $stmt->execute([
               'token' => $token,
               'expires' => $expires,
               'id' => $userId
           ]);
       } catch (PDOException $e) {
           error_log("Error storing reset token: " . $e->getMessage());
       }
   }
   public function updatePassword($userId, $hashedPassword) {
    try {
        $sql = "UPDATE {$this->table} SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'password' => $hashedPassword,
            'id' => $userId
        ]);
    } catch (PDOException $e) {
        error_log("Error updating password: " . $e->getMessage());
        return false;
    }
}
public function getTotalUsers() {
    try {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    } catch (PDOException $e) {
        error_log("Error getting total users: " . $e->getMessage());
        return 0;
    }
}

public function getActiveUsers() {
    try {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting active users: " . $e->getMessage());
        return [];
    }
}

public function getOnlineUsers() {
    try {
        $sql = "SELECT DISTINCT u.* FROM {$this->table} u 
               INNER JOIN sessions s ON u.id = s.user_id 
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
               FROM {$this->table} u 
               INNER JOIN sessions s ON u.id = s.user_id 
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

public function getAllUsers() {
    try {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting all users: " . $e->getMessage());
        return [];
    }
}

public function updateUser($id, $data) {
    try {
        $updateFields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if (in_array($key, ['username', 'email', 'password', 'role_id', 'status'])) {
                $updateFields[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
        }

        if (empty($updateFields)) {
            return false;
        }

        $params['id'] = $id;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $updateFields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        error_log("Error updating user: " . $e->getMessage());
        return false;
    }
}

public function deleteUser($id) {
    try {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        error_log("Error deleting user: " . $e->getMessage());
        return false;
    }
}

public function isUsernameEmailUnique($username, $email, $excludeUserId = null) {
    try {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE (username = :username OR email = :email)";
        $params = ['username' => $username, 'email' => $email];

        if ($excludeUserId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeUserId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['count'] == 0;
    } catch (PDOException $e) {
        error_log("Error checking username/email uniqueness: " . $e->getMessage());
        return false;
    }
}

public function storeRememberToken($userId, $token) {
    try {
        $sql = "UPDATE {$this->table} SET remember_token = :token WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['token' => $token, 'id' => $userId]);
    } catch (PDOException $e) {
        error_log("Error storing remember token: " . $e->getMessage());
        return false;
    }
}

public function removeRememberToken($userId) {
    try {
        $sql = "UPDATE {$this->table} SET remember_token = NULL WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $userId]);
    } catch (PDOException $e) {
        error_log("Error removing remember token: " . $e->getMessage());
        return false;
    }
}

public function getUserByRememberToken($token) {
    try {
        $sql = "SELECT * FROM {$this->table} WHERE remember_token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting user by remember token: " . $e->getMessage());
        return false;
    }
}

public function updateUserStatus($userId, $status) {
    try {
        if (!in_array($status, ['active', 'inactive'])) {
            return false;
        }
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $status, 'id' => $userId]);
    } catch (PDOException $e) {
        error_log("Error updating user status: " . $e->getMessage());
        return false;
    }
}

public function createPasswordResetToken($email) {
    try {
        $user = $this->getUserByEmail($email);
        if (!$user) {
            return false;
        }

        // Generate a random token
        $token = bin2hex(random_bytes(32));
        
        // Set token expiration to 1 hour from now
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Update user with reset token
        $sql = "UPDATE {$this->table} 
               SET reset_token = :token, 
                   reset_token_expires = :expires 
               WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            'token' => $token,
            'expires' => $expires,
            'id' => $user['id']
        ]);

        return $success ? $token : false;
    } catch (PDOException $e) {
        error_log("Error creating password reset token: " . $e->getMessage());
        return false;
    }
}

public function validateResetToken($token) {
    try {
        $sql = "SELECT * FROM {$this->table} 
               WHERE reset_token = :token 
               AND reset_token_expires > CURRENT_TIMESTAMP 
               AND status = 'active'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error validating reset token: " . $e->getMessage());
        return false;
    }
}

public function resetPassword($token, $newPassword) {
    try {
        $user = $this->validateResetToken($token);
        if (!$user) {
            return false;
        }

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update password and clear reset token
        $sql = "UPDATE {$this->table} 
               SET password = :password, 
                   reset_token = NULL, 
                   reset_token_expires = NULL 
               WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'password' => $hashedPassword,
            'id' => $user['id']
        ]);
    } catch (PDOException $e) {
        error_log("Error resetting password: " . $e->getMessage());
        return false;
    }
}

public function clearExpiredResetTokens() {
    try {
        $sql = "UPDATE {$this->table} 
               SET reset_token = NULL, 
                   reset_token_expires = NULL 
               WHERE reset_token_expires < CURRENT_TIMESTAMP";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error clearing expired reset tokens: " . $e->getMessage());
        return false;
    }
}

public function markResetUsed($token) {
    try {
        $sql = "UPDATE {$this->table} SET reset_token = NULL, reset_token_expires = NULL 
               WHERE reset_token = :token";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['token' => $token]);
    } catch (PDOException $e) {
        error_log("Error marking reset token as used: " . $e->getMessage());
        return false;
    }
}
}
