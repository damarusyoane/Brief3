<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Database;
use App\Core\Controller;

class UserController extends Controller
{
    private $userModel;
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->userModel = new User($this->db);
    }

    public function index()
    {
        // Check if user is admin
        if (!$this->isAdmin()) {
            $this->redirect('/');
        }

        $users = $this->userModel->getAllUsers();
        $this->view('users/index', ['users' => $users]);
    }

    public function create()
    {
        // Check if user is admin
        if (!$this->isAdmin()) {
            $this->redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role_id = $_POST['role_id'] ?? 2; // Default to user role

            if (empty($username) || empty($email) || empty($password)) {
                $error = "All fields are required";
                $this->view('users/create', ['error' => $error]);
                return;
            }

            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role_id' => $role_id,
                'status' => 'active'
            ];

            if ($this->userModel->createUser($userData)) {
                $this->redirect('/users');
            } else {
                $error = "Failed to create user";
                $this->view('users/create', ['error' => $error]);
            }
        } else {
            $this->view('users/create');
        }
    }

    public function edit($id)
    {
        // Check if user is admin or if user is editing their own profile
        if (!$this->isAdmin() && $_SESSION['user_id'] != $id) {
            $this->redirect('/');
        }

        $user = $this->userModel->getUserById($id);
        if (!$user) {
            $this->redirect('/users');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $status = $_POST['status'] ?? 'active';
            $role_id = $_POST['role_id'] ?? $user['role_id'];

            $userData = [
                'username' => $username,
                'email' => $email,
                'status' => $status,
                'role_id' => $role_id
            ];

            // Only update password if provided
            if (!empty($_POST['password'])) {
                $userData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if ($this->userModel->updateUser($id, $userData)) {
                $this->redirect('/users');
            } else {
                $error = "Failed to update user";
                $this->view('users/edit', ['user' => $user, 'error' => $error]);
            }
        } else {
            $this->view('users/edit', ['user' => $user]);
        }
    }

    public function delete($id)
    {
        // Check if user is admin
        if (!$this->isAdmin()) {
            $this->redirect('/');
        }

        if ($this->userModel->deleteUser($id)) {
            $this->redirect('/users');
        } else {
            $error = "Failed to delete user";
            $this->view('users/index', ['error' => $error]);
        }
    }
    public function updateStatus()
{
    if (!$this->isAdmin()) {
        $this->redirect('/');
    }

    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? 'active';

    if ($id && $this->userModel->updateStatus($id, $status)) {
        $this->redirect('/users');
    } else {
        $error = "Failed to update user status";
        $this->view('users/index', ['error' => $error]);
    }
}

    private function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
} 