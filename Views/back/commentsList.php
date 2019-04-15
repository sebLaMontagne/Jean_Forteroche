<?php

$title = 'Gestion des commentaires';
require('template.php');

$_SESSION['refresh'] = 1;
unset($_SESSION['refresh']);

if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
{
    header("location:javascript://history.go(-1)");
}
else
{
    $commentManager = new CommentManager();
    $comments = $commentManager->getAllComments();
    
    var_dump($comments);
}