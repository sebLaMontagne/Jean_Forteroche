<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Billet simple pour l\'Alaska - Validation d\'un chapitre';
    
    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {
        $postManager = new PostManager();                                                                                 
        $postManager->updatePost($postManager->getPostIDbyChapter($_POST['chapterNumber']), $_POST['title'], $postManager->encode($_POST['content']), $_POST['publish']);
        header('Location:chaptersList');
        exit();
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}