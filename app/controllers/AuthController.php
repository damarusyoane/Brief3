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

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role_name'];

                if ($user['role_name'] === 'admin') {
                    $this->redirect('/users');
                } else {
                    $this->redirect('/profile');
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
}
