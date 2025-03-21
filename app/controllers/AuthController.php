<?php


// use Controller;
// use Auth;
// class AuthControllers extends Controller{
    require_once __DIR__ . '/../core/Controller.php';
    require_once __DIR__ . '/../core/Auth.php';
class AuthController extends Controller {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = new UserModel();
            $user = $userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role_id'];

                $sessionModel = new SessionModel();
                $sessionModel->createSession($user['id']);

                header('Location: /admin/dashboard');
            } else {
                $this->view('auth/login', ['error' => 'Identifiants incorrects']);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role_id = 2; // Par défaut, le rôle client

            $userModel = new UserModel();
            $userModel->createUser($username, $email, $password, $role_id);

            header('Location: /auth/login');
        } else {
            $this->view('auth/register');
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /auth/login');
    }
}
