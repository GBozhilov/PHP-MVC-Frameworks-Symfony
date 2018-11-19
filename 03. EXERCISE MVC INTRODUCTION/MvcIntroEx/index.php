<?php

spl_autoload_register();

$entryPointFileName = basename(__FILE__);
$self = $_SERVER['PHP_SELF'];
$junk = str_replace($entryPointFileName, '', $self);
$uri = $_SERVER['REQUEST_URI'];
$significantUri = str_replace([$junk, $_SERVER['QUERY_STRING'], '?'], '', $uri);
$uriParts = explode('/', $significantUri);

$controllerName = array_shift($uriParts);
$actionName = array_shift($uriParts);
$params = $uriParts;

$app = new \Core\Application($controllerName, $actionName, $params);
$app->addMapping(\Services\User\UserServiceInterface::class, \Services\User\UserService::class);
$app->addMapping(\Core\View\ViewInterface::class, \Core\View\View::class);
$app->addMapping(\Services\Mail\MailServiceInterface::class, \Services\Mail\PigeonMailService::class);

$app->start();