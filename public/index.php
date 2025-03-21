<?php
// Charger la configuration
require_once '../config/config.php';

// Charger les classes core
require_once '../app/core/Controller.php';
require_once '../app/core/Model.php';
require_once '../app/core/Database.php';

// Gérer les routes
$url = $_GET['url'] ?? 'auth/login';
$url = explode('/', $url);

$controllerName = ucfirst($url[0]) . 'Controller';
$actionName = $url[1] ?? 'index';

echo "Controller Name: $controllerName\n";
echo "Action Name: $actionName\n\n";
// Inclure le contrôleur correspondant

if (file_exists("../app/controllers/$controllerName.php")) {
    require_once "../app/controllers/$controllerName.php";
    $controller = new $controllerName();

    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        die("L'action n'existe pas.");
    }
} else {
    die("Le contrôleur n'existe pas.");
}
