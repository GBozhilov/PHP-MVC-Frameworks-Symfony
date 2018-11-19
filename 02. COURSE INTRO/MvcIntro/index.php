<?php

use Core\App\Application;
use Core\View\View;

spl_autoload_register();

$self = str_replace(basename(__FILE__), '', $_SERVER['PHP_SELF']);
$url = str_replace($self, '', $_SERVER['REQUEST_URI']);
$urlParts = explode('/', $url);

$controllerName = ucfirst(array_shift($urlParts));
$actionName = array_shift($urlParts);
$params = $urlParts;

$view = new View($controllerName, $actionName);

$app = new Application($controllerName, $actionName, $params);
$app->run($view);
