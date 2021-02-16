<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */
 
 ini_set('session.cookie_lifetime', '864000'); //ten days in seconds

/**
 * Composer
 */
require '../vendor/autoload.php';


/**
* Twig
*/
Twig_Autoloader::register();
//$loader = new \Twig\Loader\FilesystemLoader('/path/to/templates');
//$twig = new \Twig\Environment($loader, [
//    'cache' => '/path/to/compilation_cache',
//]);

//$twig = new \Twig\Environment($loader, $options);

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
* Sessions
*/
session_start();

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'login']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('addincome', ['controller' => 'Addincome', 'action' => 'addincome']);
$router->add('addexpense', ['controller' => 'Addexpense', 'action' => 'addexpense']);
$router->add('balance', ['controller' => 'Balance', 'action' => 'balance']);
$router->add('showbalance', ['controller' => 'Balance', 'action' => 'showbalance']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('register/activate/{token:[\da-f]+}', ['controller' => 'Register', 'action' => 'activate']);
$router->add('{controller}/{action}');
    
$router->dispatch($_SERVER['QUERY_STRING']);
