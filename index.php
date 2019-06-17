<?php

require_once('autoloader.php');

$controller = new Controller();

if(empty($_GET['action']))
{
    $_GET['action'] = 'home';
}

if(!empty($_GET['action']))
{
    if(method_exists($controller, $_GET['action']))
    {
        $controller->{$_GET['action']}();
    }
    else
    {
        $controller->notFound();
    }
}