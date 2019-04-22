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
        $postManager = new PostManager();
        $commentManager = new CommentManager();
        $userManager = new UserManager();

        $content  = '<p>Ici, vous pouvez supprimer des commentaires ou bannir des utilisateurs pour leur mauvais comportement et filtrer les commentaires en fonction de plusieurs critères</p>';

        $content .= '<p>Trier les commentaires par : </p>';
        $content .= '<form action="commentsList.php" method="get">';
        $content .= '<select name="sortedBy">';
        $content .= '<option value="date">date</option>';
        $content .= '<option value="likes">nombre de likes</option>';
        $content .= '<option value="reports">nombre de reports</option>';
        $content .= '</select>';
        $content .= '<input type="submit" value="Confirmer"/>';
        $content .= '</form>';

        if(empty($_GET['sortedBy']) || ($_GET['sortedBy'] != 'reports' && $_GET['sortedBy'] != 'likes' && $_GET['sortedBy'] != 'date'))
        {
            header('Location:admin.php');
            exit();
        }
        else
        {
            if(empty($_GET['id']))
            {
                $comments = $commentManager->getAllCommentsSortedBy($_GET['sortedBy']);
            }
            elseif(!empty($_GET['id']) && $userManager->isUserExist($_GET['id']))
            {
                $comments = $commentManager->getAllUserCommentsSortedBy($_GET['id'], $_GET['sortedBy']);
            }
            else
            {
                header('Location:admin.php');
                exit();
            }
        }

        for($i=0; $i < count($comments); $i++)
        {
            $commentDate = new DateTime($comments[$i]->date());
            $commentAuthor = $userManager->getUserById($comments[$i]->userId());

            $content .= '<div>';

            if($commentAuthor->isBanned())
            {
                $content .= '<p>'.$commentAuthor->name().' (Utilisateur banni)</p>';
            }
            else
            {
                $content .= '<p>'.$commentAuthor->name().'</p>';
            }

            $content .= '<p>a écrit le '.$commentDate->format('d/m/Y à H:i:s').' :</p>';
            $content .= '<p>Sur le chapitre '.$postManager->getChapterByPostId($comments[$i]->postId()).'</p>';
            $content .= '<p>'.$comments[$i]->content().'</p>';
            $content .= '<p>'.$comments[$i]->likes().' likes '.$comments[$i]->reports().' reports</p>';
            $content .= '<p>';
            $content .= '<a href="confirmCommentSuppression.php?id='.$comments[$i]->id().'&redirect='.$_GET['sortedBy'].'">supprimer le commentaire</a>';

            if($commentAuthor->isBanned())
            {
                $content .= '<a href="confirmBanUser.php?action=unban&id='.$commentAuthor->id().'&redirect=commentsList.php">Débannir</a>';
            }
            elseif($commentAuthor->isAdmin())
            {

            }
            else
            {
                $content .= '<a href="confirmBanUser.php?action=ban&id='.$commentAuthor->id().'&redirect=commentsList.php">bannir l\'utilisateur</a>';
            }

            $content .= '</p>';
            $content .= '<p>Voir tous les commentaires de cet utilisateur triés par : </p>';
            $content .= '<form action="commentsList.php" method="get">';
            $content .= '<select name="sortedBy">';
            $content .= '<option value="date">date</option>';
            $content .= '<option value="likes">nombre de likes</option>';
            $content .= '<option value="reports">nombre de reports</option>';
            $content .= '</select>';
            $content .= '<input type="hidden" name="id" value="'.$commentAuthor->id().'" />';
            $content .= '<input type="submit" value="confirmer" />';
            $content .= '</form>';
            $content .= '</div>';
            $content .= '<hr />';
        }
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}