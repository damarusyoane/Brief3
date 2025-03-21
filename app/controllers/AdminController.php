<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/models/UserModel.php';

use Controller;
use UserModel;
class AdminController extends Controller {
    public function dashboard() {
        if ($_SESSION['role'] != 1) {
            header('Location: /auth/login');
        }

        $userModel = new UserModel();
        $username = $_SESSION['username'] ?? null; // Retrieve username from session or set to null
        $users = $userModel->getUserByUsername($username);
        $this->view('admin/dashboard', ['users' => $users]);
    }

    // Ajouter d'autres méthodes pour la gestion des utilisateurs, logs, etc.
}