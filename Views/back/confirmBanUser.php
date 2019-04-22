<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Gestion des commentaires';

    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {
        if(isset($_GET['action']) && ($_GET['action'] == 'ban' || $_GET['action'] == 'unban') && isset($_GET['id']) && intval($_GET['id']) > 0 && isset($_GET['redirect']))
        {
            $userManager = new UserManager();

            if($_GET['action'] == 'ban')
            {
                $userManager->banUser($_GET['id']);
                header('Location: '.$_GET['redirect']);
                exit();
            }
            elseif($_GET['action'] == 'unban')
            {
                $userManager->unbanUser($_GET['id']);
                header('Location: '.$_GET['redirect']);
                exit();
            }
        }
        else
        {
            header('Location: commentsList.php');
            exit();
        }
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}