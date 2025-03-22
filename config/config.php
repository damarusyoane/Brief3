<?php

// Application Configuration
define('APP_NAME', 'User Management System');
define('APP_URL', 'http://localhost:8000/');
define('APP_VERSION', '1.0.0');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'gestion_utilisateurs');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.gc_maxlifetime', 1800); // 30 minutes
    session_start();
}

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Time Zone
date_default_timezone_set('UTC');

// Security
define('CSRF_TOKEN_SECRET', bin2hex(random_bytes(32)));
define('PASSWORD_MIN_LENGTH', 8);
define('SESSION_TIMEOUT', 1800); // 30 minutes in seconds
define('REMEMBER_ME_DURATION', 30 * 24 * 60 * 60); // 30 days in seconds

// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'damarusngankou@gmail.com');
define('SMTP_PASSWORD', 'btss mbkp ydjr thov');
define('SMTP_FROM_EMAIL', 'damarusngankou@gmail.com');
define('SMTP_FROM_NAME', 'User Management System');

// Create logs directory if it doesn't exist
if (!file_exists(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0777, true);
}

// Helper Functions
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function buildUrl($controller, $action = '', $params = []) {
    $url = '?controller=' . urlencode($controller);
    
    if ($action) {
        $url .= '&action=' . urlencode($action);
    }
    
    if (!empty($params)) {
        foreach ($params as $key => $value) {
            $url .= '&' . urlencode($key) . '=' . urlencode($value);
        }
    }

    return $url;
}

function redirect($controller, $action = '', $params = [], $message = '', $type = 'info') {
    $url = buildUrl($controller, $action, $params);
    
    if ($message) {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type
        ];
    }
    
    header('Location: ' . $url);
    exit();
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function hasAdminRole() {
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1;
}

function requireAuth() {
    if (!isLoggedIn()) {
        redirect('auth', 'login', [], 'Please login to access this page.', 'warning');
    }
}

function requireAdmin() {
    if (!isLoggedIn() || !hasAdminRole()) {
        redirect('auth', 'login', [], 'You do not have permission to access this page.', 'error');
    }
}