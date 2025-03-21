<?php
require_once __DIR__ . '/../core/Model.php';

use Model;
class PasswordModel extends Model{
    protected $table = 'users'; // Table name

    /**
     * Generate a password reset token and save it in the database.
     *
     * @param string $email The user's email.
     * @return string|bool The generated token or false if the user doesn't exist.
     */
    public function generateResetToken($email) {
        // Check if the user exists
        $user = $this->findBy('email', $email);
        if ($user) {
            // Generate a secure token
            $token = bin2hex(random_bytes(50));
            $expires = date("Y-m-d H:i:s", time() + 3600); // Token expires in 1 hour

            // Save the token in the database
            $this->query(
                "UPDATE {$this->table} SET reset_token = ?, reset_token_expires = ? WHERE id = ?",
                [$token, $expires, $user['id']]
            );

            return $token;
        }
        return false;
    }

     /**
     * Check if a reset token is valid.
     *
     * @param string $token The reset token.
     * @return array|bool The user data if the token is valid, false otherwise.
     */
    public function isTokenValid($token) {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE reset_token = ? AND reset_token_expires > NOW()",
            [$token]
        )->fetch();
    }

        /**
     * Reset the user's password.
     *
     * @param string $token The reset token.
     * @param string $newPassword The new password.
     * @return bool True if the password was reset, false otherwise.
     */
    public function resetPassword($token, $newPassword) {
        $user = $this->isTokenValid($token);
        if ($user) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            // Update the password and clear the reset token
            $this->query(
                "UPDATE {$this->table} SET motdepasse = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?",
                [$hashedPassword, $user['id']]
            );
            return true;
        }
        return false;
    }
}