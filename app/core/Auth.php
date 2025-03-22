<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/SessionModel.php';

class Auth {
    private static $instance = null;
    private $userModel;
    private $sessionModel;
    private $user = null;

    private function __construct() {
        $this->userModel = new UserModel();
        $this->sessionModel = new SessionModel();
        $this->checkSession();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function checkSession() {
        if (isset($_SESSION['user_id'])) {
            $this->user = $this->userModel->getUserById($_SESSION['user_id']);
            if ($this->user) {
                $this->sessionModel->updateMAJ($this->user['id']);
            }
        }
    }

    public function login($username, $password) {
        try {
            $user = $this->userModel->getUserByUsername($username);
            
            if (!$user || !password_verify($password, $user['password'])) {
                return false;
            }

            if ($user['status'] !== 'active') {
                return false;
            }

            $_SESSION['user_id'] = $user['id'];
            $this->user = $user;
            
            // Create new session record
            $this->sessionModel->createSession(
                $user['id'],
                session_id(),
                $_SERVER['REMOTE_ADDR'],
                $_SERVER['HTTP_USER_AGENT']
            );
            
            return true;
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    public function logout() {
        if ($this->isLoggedIn()) {
            $this->sessionModel->updateLogoutTime($this->user['id']);
        }
        
        unset($_SESSION['user_id']);
        $this->user = null;
        session_destroy();
    }

    public function isLoggedIn() {
        return $this->user !== null;
    }

    public function getUser() {
        return $this->user;
    }

    public static function hasAdminRole() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
            return false;
        }
        return $_SESSION['role_id'] === 1;
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            $_SESSION['flash'] = [
                'message' => 'Please login to access this page.',
                'type' => 'warning'
            ];
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }

    public function requireAdmin() {
        if (!$this->isLoggedIn() || !$this->hasAdminRole()) {
            $_SESSION['flash'] = [
                'message' => 'You do not have permission to access this page.',
                'type' => 'error'
            ];
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }

    public function getOnlineUsers() {
        return $this->sessionModel->getOnlineUsers();
    }

    public function getRecentActivity($limit = 10) {
        return $this->sessionModel->getRecentActivity($limit);
    }
}