<?
session_start();
#Routing to the requested controller
require 'app/inc/messages.php';
require 'app/class/autoloader.class.php';

$baseDir = rtrim(array_shift(str_replace($_SERVER['DOCUMENT_ROOT'], '', pathinfo($_SERVER['SCRIPT_FILENAME']))), '/');

$router = Router::getInstance(); // init router
$router->init($baseDir); // execute router

#get controller or use index controller
$controller = $router->getController();
DEFINE('CURRENT_CONTROLLER', $controller);
DEFINE('BASE_DIR', $baseDir);

if (CURRENT_CONTROLLER != 'index' && $_SESSION['c_autorized'] != 1) {
	header("Location: /admin/index/login");
}

$action = $router->getAction();

#get the controller filename
$controllerFile = 'app/controllers/'. $controller . '_controller.php';

#include the controller if exists
if (is_file('./'.$controllerFile))
	require_once './'.$controllerFile;
else {
	header("HTTP/1.0 404 Not Found");
	die('El controlador no existe - 404 not found');
}

#Init the controller
$controllerClass = $controller.'Controller';
$controllerInstance = new $controllerClass($baseDir);

#and call the action... if not action specified call index
$controllerInstance->$action();