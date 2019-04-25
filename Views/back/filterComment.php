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
        if(!empty($_POST['sortedBy']))
        {
            if($_POST['sortedBy'] == 'reports')
            {
                header('Location:commentsList-'.$_POST['sortedBy']);
                exit();
            }
            elseif($_POST['sortedBy'] == 'likes')
            {
                header('Location:commentsList-'.$_POST['sortedBy']);
                exit();
            }
            elseif($_POST['sortedBy'] == 'date')
            {
                header('Location:commentsList-'.$_POST['sortedBy']);
                exit();
            }
            else
            {
                header('Location:admin');
                exit();
            }
        }
        else
        {
            header('Location:admin');
            exit();
        }
    }
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}