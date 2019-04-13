<?php

$title = 'Page d\'admin';
require('template.php');

$_SESSION['refresh'] = 1;
unset($_SESSION['refresh']);
var_dump($_SESSION);


if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
{
    header("location:javascript://history.go(-1)");
}