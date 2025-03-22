<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/SessionModel.php';
require_once __DIR__ . '/../../config/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../../config/PHPMailer/SMTP.php';
require_once __DIR__ . '/../../config/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class UserController extends Controller {
    private $userModel;
    private $sessionModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->sessionModel = new SessionModel();
    }

    private function checkUserAccess() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit();
        }
    }

    public function dashboard() {
        $this->checkUserAccess();
        
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $this->view('user/dashboard', ['user' => $user]);
    }

    public function sessions() {
        $this->checkUserAccess();
        
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $sessions = $this->sessionModel->getUserSessions($_SESSION['user_id']);
        $currentSession = $this->sessionModel->getSessionByToken(session_id());
        
        $this->view('user/sessions', [
            'user' => $user,
            'sessions' => $sessions,
            'currentSession' => $currentSession
        ]);
    }

    public function updateProfile() {
        $this->checkUserAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $username = $_POST['username'];
            $email = $_POST['email'];

            // Validate unique username and email
            if ($this->userModel->isUsernameEmailUnique($username, $email, $userId)) {
                $this->userModel->updateUser($userId, [
                    'username' => $username,
                    'email' => $email
                ]);
                
                $this->view('user/dashboard', [
                    'user' => $this->userModel->getUserById($userId),
                    'success' => 'Profile updated successfully'
                ]);
            } else {
                $this->view('user/dashboard', [
                    'user' => $this->userModel->getUserById($userId),
                    'error' => 'Username or email already exists'
                ]);
            }
        }
    }

    public function changePassword() {
        $this->checkUserAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            $user = $this->userModel->getUserById($userId);
            
            if (!password_verify($currentPassword, $user['password'])) {
                $this->view('user/change-password', [
                    'error' => 'Current password is incorrect'
                ]);
                return;
            }
            
            if ($newPassword !== $confirmPassword) {
                $this->view('user/change-password', [
                    'error' => 'New passwords do not match'
                ]);
                return;
            }
            
            $this->userModel->updateUser($userId, [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT)
            ]);
            
            $this->view('user/change-password', [
                'success' => 'Password updated successfully'
            ]);
        } else {
            $this->view('user/change-password');
        }
    }
}
