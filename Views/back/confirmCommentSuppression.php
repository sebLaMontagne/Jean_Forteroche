<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Billet simple pour l\'Alaska - Suppression de commentaire';
    
    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {
        if(isset($_GET['id']) && intval($_GET['id']) > 0)
        {
            if(isset($_POST['confirmation']))
            {
                if($_POST['confirmation'] == 'yes')
                {
                    $commentManager = new CommentManager();
                    $commentManager->deleteComment($_GET['id']);
                    header('Location: commentsList-'.$_GET['redirect']);
                    exit();
                }
                elseif($_POST['confirmation'] == 'no')
                {
                    header('Location: commentsList-'.$_GET['redirect']);
                    exit();
                }
            }
            else
            {
                $content  = '<div class="content filler">';
                $content .= '<p style="text-align: center;">Etes-vous sûr de vouloir supprimer ce commentaire</p>';
                $content .= '<form method="post" action="confirmCommentSuppression-'.$_GET['id'].'-'.$_GET['redirect'].'">';
                $content .= '<div class="radios"><input type="radio" name="confirmation" value="yes" id="confirm-yes" required /><label for="confirm-yes">Oui</label></div>';
                $content .= '<div class="radios"><input type="radio" name="confirmation" value="no" id="confirm-no" required /><label for="confirm-no">Non</label></div>';
                $content .= '<input type="submit" value="valider" />';
                $content .= '</form>';
            }
        }
        else
        {
            header('Location: admin.php');
            exit();
        }
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}