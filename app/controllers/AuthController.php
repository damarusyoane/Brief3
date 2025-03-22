<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/SessionModel.php';

class AuthController extends Controller {
    private $userModel;
    private $sessionModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->sessionModel = new SessionModel();
    }

    public function login() {
        // If user is already logged in, redirect to appropriate dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $remember = isset($_POST['remember']);

            // Validate input
            if (empty($username) || empty($password)) {
                $this->view('auth/login', ['error' => 'Please fill in all fields']);
                return;
            }

            $user = $this->userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] !== 'active') {
                    $this->view('auth/login', ['error' => 'Your account has been deactivated. Please contact an administrator.']);
                    return;
                }

                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['username'] = $user['username'];

                // Update last login time
                $this->userModel->updateUser($user['id'], [
                    'last_login' => date('Y-m-d H:i:s')
                ]);

                // Create session record
                $this->sessionModel->createSession(
                    $user['id'],
                    session_id(),
                    $_SERVER['REMOTE_ADDR'],
                    $_SERVER['HTTP_USER_AGENT']
                );

                // Set remember me cookie if requested
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
                    $this->userModel->storeRememberToken($user['id'], $token);
                }

                $this->redirectToDashboard();
            } else {
                // Use a generic error message for security
                $this->view('auth/login', ['error' => 'Invalid credentials']);
            }
        } else {
            $this->view('auth/login');
        }
    }

    private function redirectToDashboard() {
        if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
            header('Location: index.php?controller=admin&action=dashboard');
        } else {
            header('Location: index.php?controller=user&action=dashboard');
        }
        exit();
    }

    public function logout() {
        if (isset($_SESSION['user_id'])) {
            // Deactivate current session
            $this->sessionModel->deactivateSession(session_id());
            
            // Remove remember me cookie if exists
            if (isset($_COOKIE['remember_token'])) {
                $this->userModel->removeRememberToken($_SESSION['user_id']);
                setcookie('remember_token', '', time() - 3600, '/');
            }
        }
        
        // Destroy session
        session_destroy();
        
        // Redirect to login page
        header('Location: /auth/login');
        exit();
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->view('auth/forgot-password', ['error' => 'Please enter a valid email address']);
                return;
            }

            $user = $this->userModel->getUserByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $this->userModel->storePasswordResetToken($user['id'], $token, $expires);
                
                $resetLink = APP_URL . "auth/reset-password?token=" . $token;
                
                // Send email with reset link
                $to = $email;
                $subject = "Password Reset Request";
                $message = "Hello,\n\nYou have requested to reset your password. Click the link below to reset it:\n\n";
                $message .= $resetLink . "\n\n";
                $message .= "This link will expire in 1 hour.\n\n";
                $message .= "If you did not request this reset, please ignore this email.\n";
                
                $headers = "From: " . APP_NAME . " <noreply@" . $_SERVER['HTTP_HOST'] . ">\r\n";
                
                mail($to, $subject, $message, $headers);
            }
            
            // Show same message whether email exists or not (security best practice)
            $this->view('auth/forgot-password', ['success' => 'If an account exists with this email, a password reset link has been sent.']);
        } else {
            $this->view('auth/forgot-password');
        }
    }

    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            header('Location: /auth/login');
            return;
        }
        
        $reset = $this->userModel->validateResetToken($token);
        
        if (!$reset) {
            $this->view('auth/reset-password', ['error' => 'Invalid or expired reset token']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            
            if (strlen($password) < 8) {
                $this->view('auth/reset-password', ['error' => 'Password must be at least 8 characters long', 'token' => $token]);
                return;
            }
            
            if ($password !== $confirmPassword) {
                $this->view('auth/reset-password', ['error' => 'Passwords do not match', 'token' => $token]);
                return;
            }
            
            $this->userModel->updatePassword($reset['email'], password_hash($password, PASSWORD_DEFAULT));
            $this->userModel->markResetUsed($token);
            
            $this->view('auth/login', ['success' => 'Password has been reset successfully. Please login with your new password.']);
        } else {
            $this->view('auth/reset-password', ['token' => $token]);
        }
    }
}
