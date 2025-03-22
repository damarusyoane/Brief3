<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/SessionModel.php';

class AdminController extends Controller {
    private $userModel;
    private $sessionModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->sessionModel = new SessionModel();
    }

    private function checkAdminAccess() {
        if (!hasAdminRole()) {
            header('Location: /auth/login');
            exit();
        }
    }

    public function dashboard() {
        $this->checkAdminAccess();

        $totalUsers = $this->userModel->getTotalUsers();
        $activeUsers = $this->userModel->getActiveUsers();
        $onlineUsers = $this->sessionModel->getOnlineUsers();
        $recentActivity = $this->userModel->getRecentActivity();
        $admin = $this->userModel->getUserById($_SESSION['user_id']);

        $this->view('admin/dashboard', [
            'totalUsers' => $totalUsers,
            'activeUsers' => count($activeUsers),
            'onlineUsers' => count($onlineUsers),
            'onlineUsersList' => $onlineUsers,
            'recentActivity' => $recentActivity,
            'admin' => $admin
        ]);
    }

    public function users() {
        $this->checkAdminAccess();
        
        $users = $this->userModel->getAllUsers();
        $this->view('admin/users', ['users' => $users]);
    }

    public function editUser($id) {
        $this->checkAdminAccess();

        $user = $this->userModel->getUserById($id);
        if (!$user) {
            header('Location: /admin/users');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'role_id' => $_POST['role']
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            $this->userModel->updateUser($id, $data);
            header('Location: /admin/users');
            return;
        }

        $this->view('admin/edit_user', ['user' => $user]);
    }

    public function toggleUserStatus($id) {
        $this->checkAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->userModel->getUserById($id);
            if ($user) {
                $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
                $this->userModel->updateUser($id, ['status' => $newStatus]);
                echo json_encode(['success' => true]);
                return;
            }
        }
        echo json_encode(['success' => false]);
    }

    public function deleteUser($id) {
        $this->checkAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->deleteUser($id)) {
                echo json_encode(['success' => true]);
                return;
            }
        }
        echo json_encode(['success' => false]);
    }

    public function logs() {
        $this->checkAdminAccess();
        
        $sessions = $this->sessionModel->getAllSessions();
        $this->view('admin/logs', ['sessions' => $sessions]);
    }

    public function ajaxGetOnlineUsers() {
        $this->checkAdminAccess();
        
        $onlineUsers = $this->sessionModel->getOnlineUsers();
        echo json_encode($onlineUsers);
    }

    public function createUser() {
        $this->checkAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $role = (int)$_POST['role'];

            // Validate input
            if (empty($username) || empty($email) || empty($password) || empty($role)) {
                $_SESSION['error_message'] = "All fields are required.";
                header('Location: /admin/create_user');
                exit();
            }

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = "Please enter a valid email address.";
                header('Location: /admin/create_user');
                exit();
            }

            // Validate password length
            if (strlen($password) < PASSWORD_MIN_LENGTH) {
                $_SESSION['error_message'] = "Password must be at least " . PASSWORD_MIN_LENGTH . " characters long.";
                header('Location: /admin/create_user');
                exit();
            }

            // Check if username or email already exists
            if ($this->userModel->getUserByUsername($username) || $this->userModel->getUserByEmail($email)) {
                $_SESSION['error_message'] = "Username or email already exists.";
                header('Location: /admin/create_user');
                exit();
            }

            try {
                $result = $this->userModel->createUser($username, $email, $password, $role);
                if ($result) {
                    $_SESSION['success_message'] = "User created successfully.";
                    header('Location: /admin/users');
                    exit();
                } else {
                    throw new Exception("Failed to create user in database.");
                }
            } catch (Exception $e) {
                error_log("Error creating user: " . $e->getMessage());
                $_SESSION['error_message'] = "Failed to create user. Please try again.";
                header('Location: /admin/create_user');
                exit();
            }
        }

        $this->view('admin/create_user');
    }

    public function createAdminUser() {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $role_id = 1;
            
            // Validate input
            if (empty($username) || empty($email) || empty($password)) {
                $_SESSION['error_message'] = "All fields are required.";
                header('Location: /admin/create_user');
                exit();
            }
            
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = "Please enter a valid email address.";
                header('Location: /admin/create_user');
                exit();
            }
            
            // Check if username or email already exists
            if ($this->userModel->getUserByUsername($username) || $this->userModel->getUserByEmail($email)) {
                $_SESSION['error_message'] = "Username or email already exists.";
                header('Location: /admin/create_user');
                exit();
            }
            
            // Validate password length
            if (strlen($password) < PASSWORD_MIN_LENGTH) {
                $_SESSION['error_message'] = "Password must be at least " . PASSWORD_MIN_LENGTH . " characters long.";
                header('Location: /admin/create_user');
                exit();
            }
           
            $result = $this->userModel->createUser($username, $email, $password, $role_id);
            if ($result) {
                $_SESSION['success_message'] = "Admin user created successfully.";
                header('Location: /admin/dashboard');
                exit();
            } else {
                $_SESSION['error_message'] = "Failed to create admin user. Please try again.";
                header('Location: /admin/create_user');
                exit();
            }
        }

        $this->view('admin/create_user', ['is_admin' => true]);
    }
}
