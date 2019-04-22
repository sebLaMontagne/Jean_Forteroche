<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Confirmation de suppression';
    
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
                    header('Location: commentsList.php?sortedBy='.$_GET['redirect']);
                    exit();
                }
                elseif($_POST['confirmation'] == 'no')
                {
                    header('Location: commentsList.php?sortedBy='.$_GET['redirect']);
                    exit();
                }
            }
            else
            {
                $content  = '<p>Etes-vous sûr de vouloir supprimer ce commentaire</p>';
                $content .= '<form method="post" action="confirmCommentSuppression.php?id='.$_GET['id'].'&redirect='.$_GET['redirect'].'">';
                $content .= '<input type="radio" name="confirmation" value="yes" id="confirm-yes" required /><label for="confirm-yes">Oui</label>';
                $content .= '<input type="radio" name="confirmation" value="no" id="confirm-no" required /><label for="confirm-no">Non</label>';
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