<?php
require_once __DIR__ . '/../core/Model.php';

use Model;

class AuthModel extends Model {
    protected $table = 'users'; // Table name

    /**
     * Check if the user exists with the given credentials.
     *
     * @param string $email The user's email.
     * @param string $password The user's password.
     * @return array|bool The user data if found, false otherwise.
     */
    public function authenticate($email, $password) {
        $user = $this->query("SELECT * FROM {$this->table} WHERE email = ?", [$email])->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    /**
     * Store a password reset token for the user.
     *
     * @param int $userId The user's ID.
     * @param string $token The password reset token.
     * @return void
     */
    public function storePasswordResetToken($userId, $token) {
        $this->query("UPDATE {$this->table} SET reset_token = ? WHERE id = ?", [$token, $userId]);
    }
}
