<?php
session_start();
require_once 'config/database.php';

// Autoloader simple
spl_autoload_register(function ($class) {
    $f = __DIR__ . '/controllers/' . $class . '.php';
    if (file_exists($f)) require_once $f;
});

$page = $_GET['page'] ?? 'auth';
action:
$action = $_GET['action'] ?? null;

// Default actions
$map = [
    'auth' => 'AuthController',
    'admin' => 'AdminController',
    'ecole' => 'EcoleController',
    'entreprise' => 'EntrepriseController',
    'quiz' => 'QuizController',
    'user' => 'UserController'
];

if (!isset($map[$page])) {
    echo "Erreur 404 : page inconnue";
    exit;
}
$ctrlName = $map[$page];
$ctrlFile = __DIR__ . "/controllers/{$ctrlName}.php";
if (!file_exists($ctrlFile)) {
    echo "Erreur 404 : contrôleur manquant";
    exit;
}
$controller = new $ctrlName();
if (!$action) {
    
    $defaults = [
        'auth' => 'login',
        'admin' => 'dashboard',
        'ecole' => 'dashboard',
        'entreprise' => 'dashboard',
        'quiz' => 'list',
        'user' => 'dashboard'
    ];
    $action = $defaults[$page] ?? 'index';
}


if ($page !== 'auth' && !isset($_SESSION['user'])) {
    header('Location: index.php?page=auth&action=login');
    exit;
}

if (!method_exists($controller, $action)) {
    echo "Erreur 404 : action introuvable";
    exit;
}
$controller->$action();
