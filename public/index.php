<?php
session_start();

require_once '../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\UserController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/') {
    if (isset($_SESSION['user_id'])) {
        $controller = new HomeController();
        $controller->index();
    } else {
        $controller = new AuthController();
        $controller->login();
    }
} elseif ($uri === '/login' || $uri === '/auth/login') {
    $controller = new AuthController();
    if ($method === 'POST') {
        $controller->login();
    } else {
        $controller->login();
    }
} elseif ($uri === '/auth/register') {
    $controller = new AuthController();
    if ($method === 'POST') {
        $controller->register();
    } else {
        $controller->register();
    }
} elseif ($uri === '/home/dashboard') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    $controller = new HomeController();
    $controller->dashboard();
} elseif ($uri === '/logout') {
    $controller = new AuthController();
    $controller->logout();
} elseif ($uri === '/users') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /login');
        exit;
    }
    $controller = new UserController();
    $controller->index();
} elseif ($uri === '/users/create') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /login');
        exit;
    }
    $controller = new UserController();
    if ($method === 'POST') {
        $controller->create();
    } else {
        $controller->create();
    }
} elseif (preg_match('/^\/users\/edit\/(\d+)$/', $uri, $matches)) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /login');
        exit;
    }
    $controller = new UserController();
    $controller->edit($matches[1]);
} elseif (preg_match('/^\/users\/delete\/(\d+)$/', $uri, $matches)) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /login');
        exit;
    }
    $controller = new UserController();
    $controller->delete($matches[1]);
} elseif ($uri === '/users/status') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /login');
        exit;
    }
    $controller = new UserController();
    $controller->updateStatus();
} elseif ($uri === '/profile') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    $controller = new AuthController();
    $controller->profile();
} else {
    // Rediriger vers une page par défaut si l'URL est invalide
    echo "Page not found!";
}
