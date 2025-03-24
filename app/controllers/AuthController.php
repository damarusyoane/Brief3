<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\Database;

class AuthController extends Controller
{
    private $userModel;
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->userModel = new User($this->db);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            $errors = [];

            // Validation des entrées
            if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
                $errors[] = "Tous les champs sont obligatoires.";
            }
            if ($password !== $confirm_password) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide.";
            }
            if (strlen($password) < 8) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
            }

            // Vérification de l'existence de l'utilisateur
            if ($this->userModel->findByUsername($username)) {
                $errors[] = "Le nom d'utilisateur est déjà pris.";
            }
            if ($this->userModel->findByEmail($email)) {
                $errors[] = "L'email est déjà utilisé.";
            }

            // Enregistrement de l'utilisateur
            if (empty($errors)) {
                $userData = [
                    'username' => $username,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role_id' => 2, // Default to user role
                    'status' => 'active'
                ];
                $this->userModel->createUser($userData);
                $_SESSION['message'] = "Inscription réussie. Vous pouvez vous connecter.";
                header("Location: ../home/dashboard");
            }

            $this->view('auth/register', ['errors' => $errors]);
        } else {
            $this->view('auth/register');
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';

            if (empty($username) || empty($password) || empty($role)) {
                $this->view('auth/login', ['error' => 'All fields are required']);
                return;
            }

            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                // Check if the selected role matches the user's actual role
                if (($role === 'admin' && $user['role_name'] !== 'admin') || 
                    ($role === 'user' && $user['role_name'] === 'admin')) {
                    $this->view('auth/login', ['error' => 'Invalid role selected']);
                    return;
                }

                if ($user['status'] === 'inactive') {
                    $this->view('auth/login', ['error' => 'Your account is inactive. Please contact an administrator.']);
                    return;
                }

                // Store session information
                $sessionData = [
                    'user_id' => $user['id'],
                    'session_id' => session_id(),
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT']
                ];
                
                if ($this->userModel->createSession($sessionData)) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_role'] = $user['role_name'];

                    if ($user['role_name'] === 'admin') {
                        $this->redirect('/users');
                    } else {
                        $this->redirect('/profile');
                    }
                } else {
                    $this->view('auth/login', ['error' => 'Failed to create session. Please try again.']);
                }
            } else {
                $this->view('auth/login', ['error' => 'Invalid username or password']);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function logout()
    {
        if (isset($_SESSION['user_id'])) {
            // Update session with logout time
            $this->userModel->updateSessionLogout($_SESSION['user_id'], session_id());
        }
        session_destroy();
        $this->redirect('/login');
    }

    public function profile()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            
            $userData = [
                'username' => $username,
                'email' => $email,
                'role_id' => $user['role_id'],
                'status' => $user['status']
            ];

            if (!empty($_POST['password'])) {
                $userData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if ($this->userModel->updateUser($user['id'], $userData)) {
                $_SESSION['username'] = $username;
                $this->redirect('/profile');
            } else {
                $this->view('auth/profile', ['user' => $user, 'error' => 'Failed to update profile']);
            }
        } else {
            $this->view('auth/profile', ['user' => $user]);
        }
    }

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            
            if (empty($email)) {
                $_SESSION['error'] = 'Please enter your email address';
                header('Location: /forgot-password');
                exit;
            }

            $user = $this->userModel->findByEmail($email);
            
            if (!$user) {
                $_SESSION['error'] = 'No account found with that email address';
                header('Location: /forgot-password');
                exit;
            }

            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Store reset token in database
            $this->userModel->storeResetToken($user['id'], $token, $expires);

            // Send reset email
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'damarusngankou@gmail.com'; // Replace with your email
                $mail->Password = 'btss mbkp ydjr thov'; // Replace with your app password
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                 // Désactiver la vérification SSL (développement uniquement)
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

                $mail->setFrom('no-reply@USM.com', 'User Management System');
                $mail->addAddress($email, $user['username']);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset-password?token=" . $token;
                $mail->Body = "
                    <h2>Password Reset Request</h2>
                    <p>You have requested to reset your password. Click the link below to proceed:</p>
                    <p><a href='{$resetLink}'>{$resetLink}</a></p>
                    <p>This link will expire in 1 hour.</p>
                    <p>If you didn't request this, please ignore this email.</p>
                ";

                $mail->send();
                $_SESSION['success'] = 'Password reset instructions have been sent to your email';
                header('Location: /login');
                exit;
            } catch (\Exception $e) {
                $_SESSION['error'] = 'Failed to send reset email. Please try again later.';
                header('Location: /forgot-password');
                exit;
            }
        }

        // Show forgot password form
        $this->view('auth/forgotpassword');
    }

    public function resetPassword()
    {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $_SESSION['error'] = 'Invalid reset token';
            header('Location: /forgot-password');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($password) || empty($confirmPassword)) {
                $_SESSION['error'] = 'Please fill in all fields';
                header('Location: /reset-password?token=' . $token);
                exit;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Passwords do not match';
                header('Location: /reset-password?token=' . $token);
                exit;
            }

            if (strlen($password) < 8) {
                $_SESSION['error'] = 'Password must be at least 8 characters long';
                header('Location: /reset-password?token=' . $token);
                exit;
            }

            // Verify token and update password
            $userId = $this->userModel->verifyResetToken($token);
            
            if (!$userId) {
                $_SESSION['error'] = 'Invalid or expired reset token';
                header('Location: /forgot-password');
                exit;
            }

            // Update password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->updatePassword($userId, $hashedPassword);

            // Clear reset token
            $this->userModel->clearResetToken($userId);

            $_SESSION['success'] = 'Your password has been reset successfully';
            header('Location: /login');
            exit;
        }

        // Show reset password form
        $this->view('auth/resetpassword', ['token' => $token]);
    }
}
