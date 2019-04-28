<?php

try
{
    require_once('../autoloader.php');
    
    $title = 'gestion des appreciations';

    if(isset($_SESSION['isAdmin']))
    {
        if(isset($_GET['appreciation']) && isset($_GET['id']))
        {
            $appreciationManager = new AppreciationManager();

            if(($_GET['appreciation'] == 'like' || $_GET['appreciation'] == 'report') && intval($_GET['id']) > 0 && !$appreciationManager->isAppreciationExist($_SESSION['id'], $_GET['id']))
            {
                $appreciationManager->addAppreciation($_GET['id'], $_SESSION['id'], $_GET['appreciation']);
            }
            elseif(($_GET['appreciation'] == 'reset' && $appreciationManager->isAppreciationExist($_SESSION['id'], $_GET['id'])))
            {
                $appreciationManager->deleteAppreciation($_SESSION['id'], $_GET['id']);
            }

            $commentManager = new CommentManager();
            header('Location:chapter-'.$commentManager->getChapterNumberByCommentId($_GET['id']));
            exit();
        }
        else
        {
            header('Location:home.php');
            exit();
        }
    }
    else
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}