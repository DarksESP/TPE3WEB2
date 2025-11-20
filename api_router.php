<?php
require_once './jwt/JwtMiddleware.php';

require_once './middlewares/GuardApiMiddleware.php';
require_once './lib/router.php';
require_once './controllers/authController.php';
require_once './controllers/juegosController.php';


// instancio el router
$router = new Router();

$router->addMiddleware(new JWTMiddleware());

// defino los endpoints
$router->addRoute('login',     'POST',     'authController',    'login');


$router->addRoute('juegos',         'GET',      'juegosController',    'getJuegos');
$router->addRoute('juegos/:id',     'GET',      'juegosController',   'getJuego');

$router->addMiddleware(new GuardMiddleware());
$router->addRoute('juegos/:id',     'DELETE',   'juegosController',    'deleteJuego');
$router->addRoute('juegos',         'POST',     'juegosController',    'insertJuego');
$router->addRoute('juegos/:id',     'PUT',      'juegosController',    'updateJuego');

// rutea
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);



?>

