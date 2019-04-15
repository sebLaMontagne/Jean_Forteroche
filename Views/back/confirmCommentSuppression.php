<?php

$title = 'Confirmation de suppression';
require('template.php');

$_SESSION['refresh'] = 1;
unset($_SESSION['refresh']);

if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
{
    header("location:javascript://history.go(-1)");
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
                header('Location: commentsList.php');
            }
            elseif($_POST['confirmation'] == 'no')
            {
                header('Location: commentsList.php');
            }
        }
        else
        {
            echo'
            <p>Etes-vous sûr de vouloir supprimer ce commentaire</p>
            <form method="post" action="confirmCommentSuppression.php?id='.$_GET['id'].'">
                <input type="radio" name="confirmation" value="yes" id="confirm-yes" required /><label for="confirm-yes">Oui</label>
                <input type="radio" name="confirmation" value="no" id="confirm-no" required /><label for="confirm-no">Non</label>
                <input type="submit" value="valider" />
            </form>';
        }
    }
    else
    {
        header('Location: admin.php');
    }
}