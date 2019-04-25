<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Suppression d\'un article';

    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {
        $postManager = new PostManager();

        if(isset($_GET) && !empty($_GET['chapter']) && $postManager->isChapterExist($_GET['chapter']))
        {
            $content  = '<div class="content filler">';
            $content .= '<p style="text-align: center;">Etes-vous sûr de vouloir supprimer cet article ?</p>';
            $content .= '<form method="post" action="deletePost-'.$_GET['chapter'].'">';
            $content .= '<div class="radios"><label for="delete-yes">Oui</label><input id="delete-yes" type="radio" name="delete" value="yes" /></div>';
            $content .= '<div class="radios"><label for="delete-no">Non</label><input id="delete-no" type="radio" name="delete" value="no" /></div>';
            $content .= '<input type="submit" value="confirmer" />';
            $content .= '</form>';
            $content .= '</div>';

            if(isset($_POST['delete']) && $_POST['delete'] == 'yes')
            {
                $postManager->deletePost($_GET['chapter']);
                header('Location:chaptersList');
                exit();
            }

            if(isset($_POST['delete']) && $_POST['delete'] == 'no')
            {
                header('Location:chaptersList');
                exit();
            }
        }
        else
        {
            header('Location:admin');
            exit();
        }
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}