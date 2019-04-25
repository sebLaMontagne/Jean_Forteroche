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
        if(!empty($_POST['show']))
        {
            if($_POST['show'] == 'all')
            {
                header('Location:usersList-'.$_POST['show']);
                exit();
            }
            elseif($_POST['show'] == 'admins')
            {
                header('Location:usersList-'.$_POST['show']);
                exit();
            }
            elseif($_POST['show'] == 'banned')
            {
                header('Location:usersList-'.$_POST['show']);
                exit();
            }
            elseif($_POST['show'] == 'users')
            {
                header('Location:usersList-'.$_POST['show']);
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