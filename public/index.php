<?php
// defini le fichier de configuration
// demarer la session
session_start();

require_once '../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
// defini le chemin vers l'url des pages

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// l'url de la page d'accueil
// check  l'utilisitaeur est connecter et le dirige vers la page d'accueil en fonction de son profil
if ($uri === '/') {
    if (isset($_SESSION['user_id'])) {
        $controller = new HomeController();
        $controller->index();
    } // au cas contraire le dirige vers la page login
    else {
        $controller = new AuthController();
        $controller->login();
    }
} // check  l'url du login et affiche la page login 
 elseif ($uri === '/login' || $uri === '/auth/login') {
    $controller = new AuthController();
    if ($method === 'POST') {
        $controller->login();
    } else {
        $controller->login();
    }
} // check  l'url du enregistrer et affiche la page enregistrer 
elseif ($uri === '/auth/register') {
    $controller = new AuthController();
    if ($method === 'POST') {
        $controller->register();
    } else {
        $controller->register();
    }
} elseif ($uri === '/auth/forgot-password') {
    $controller = new AuthController();
    if ($method === 'POST') {
        $controller->forgotPassword();
    } else {
        $controller->forgotPassword();
    }
} elseif (strpos($uri, '/reset-password') === 0) {
    $controller = new AuthController();
    if ($method === 'POST') {
        $controller->resetPassword();
    } else {
        $controller->resetPassword();
    }
} // check  l'url du dashboard et affiche la page dashboard 
elseif ($uri === '/home/dashboard') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    $controller = new HomeController();
    $controller->dashboard();
} // check  l'url du login et vers tue la session de l'utilisateur et le redirige vers la page login
 elseif ($uri === '/logout') {
    $controller = new AuthController();
    $controller->logout();
} elseif ($uri === '/users') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /login');
        exit;
    }
    $controller = new UserController();
    $controller->index();
} // check l'url pour creer l'utilisitateur et l'affiche la page create user 
elseif ($uri === '/users/create') {
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
} // check si la personne essayant de se connecter est l'admin et le permet de modifier l'utilisateur 
elseif (preg_match('/^\/users\/edit\/(\d+)$/', $uri, $matches)) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /login');
        exit;
    }
    $controller = new UserController();
    $controller->edit($matches[1]);
}  // check si la personne essayant de se connecter est l'admin et le permet de supprimer l'utilisateur 
elseif (preg_match('/^\/users\/delete\/(\d+)$/', $uri, $matches)) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /login');
        exit;
    }
    $controller = new UserController();
    $controller->delete($matches[1]);
} // permet a un utilisateur de se connecter et de modifier ses coordonnees
 elseif ($uri === '/users/status') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /login');
        exit;
    }
    $controller = new UserController();
    $controller->updateStatus();
} elseif ($uri === '/users/sessions') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /login');
        exit;
    }
    $controller = new UserController();
    $controller->sessions();
} elseif ($uri === '/users/cleanup-sessions') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: /login');
        exit;
    }
    $controller = new UserController();
    $controller->cleanupSessions();
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

// Session management routes
// $router->get('/sessions', [UserController::class, 'sessions']);
// $router->post('/cleanup-sessions', [UserController::class, 'cleanupSessions']);
