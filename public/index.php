<?php
// Start session
session_start();

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Load core classes
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Auth.php';

// Load controllers
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';

// Simple router
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Sanitize input using htmlspecialchars instead of the deprecated FILTER_SANITIZE_STRING
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

$controller = sanitizeInput($controller);
$action = sanitizeInput($action);

// Map controller names to classes
$controllerMap = [
    'auth' => 'AuthController',
    'admin' => 'AdminController',
    'user' => 'UserController'
];

// Check if controller exists in map
if (!isset($controllerMap[$controller])) {
    header("HTTP/1.0 404 Not Found");
    die("Controller not found");
}

$controllerClass = $controllerMap[$controller];
$controllerInstance = new $controllerClass();

// Check if method exists
if (!method_exists($controllerInstance, $action)) {
    header("HTTP/1.0 404 Not Found");
    die("Action not found");
}

// Call the method
$controllerInstance->$action();
