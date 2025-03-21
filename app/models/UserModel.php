<?php
require_once __DIR__ . '/app/core/Model.php';

use Model;
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
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        // Insert User in the database
        $sql = "INSERT INTO $this->table (username, email, password, role_id) VALUES (:username, :email, :password, :role_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword, 'role_id' => $role_id]);
        return $this->db->getPDO()->lastInsertId();
    }

    public function getUserByUsername($username) {
        // Get User by his Name
        $sql = "SELECT * FROM $this->table WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get a user by email.
     *
     * @param string $email The user's email.
     * @return array|bool The user data if found, false otherwise.
     */
    public function getUserByEmail($email) {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE email = ?",
            [$email]
        )->fetch();
    }

    /**
     * Get a user by ID.
     *
     * @param int $id The user's ID.
     * @return array|bool The user data if found, false otherwise.
     */
    public function getUserById($id) {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id]
        )->fetch();
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
    } /**
    * Store a password reset token for the user.
    *
    * @param int $userId The user's ID.
    * @param string $token The password reset token.
    * @return void
    */
   public function storePasswordResetToken($userId, $token) {
       $this->query("UPDATE {$this->table} SET reset_token = ? WHERE id = ?", [$token, $userId]);
   }
   public function updatePassword($userId, $hashedPassword) {
    $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashedPassword, $userId]);
}




}
