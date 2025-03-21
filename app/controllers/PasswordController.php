<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';

use Controller;
use Auth;

class PasswordController extends Controller {
    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $newPassword = $_POST['new_password'];

            // Validate the token and update the password
            $userModel = new UserModel();
            $user = $userModel->getUserByResetToken($token); // You need to implement this method

            if ($user) {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $userModel->updatePassword($user['id'], $hashedPassword); // Update the password
                header('Location: /auth/connexion?message=Your password has been updated successfully.');
            } else {
                $this->view('auth/motdepasse_reset', ['error' => 'Invalid token.']);
            }
        } else {
            $this->view('auth/motdepasse_reset');
        }
    }
}
