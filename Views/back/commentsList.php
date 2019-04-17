<?php

$title = 'Gestion des commentaires';
require('template.php');

$_SESSION['refresh'] = 1;
unset($_SESSION['refresh']);

if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
{
    header("location:javascript://history.go(-1)");
}
else
{
    $postManager = new PostManager();
    $commentManager = new CommentManager();
    $userManager = new UserManager();
    
    echo '<p>Ici, vous pouvez supprimer des commentaires ou bannir des utilisateurs pour leur mauvais comportement et filtrer les commentaires en fonction de plusieurs critères</p>';
    
    $sorter  = '<p>Trier les commentaires par : </p>';
    $sorter .= '<form action="commentsList" method="get">';
    $sorter .= '<select name="sortedBy">';
    $sorter .= '<option value="date">date</option>';
    $sorter .= '<option value="users">utilisateur</option>';
    $sorter .= '<option value="likes">nombre de likes</option>';
    $sorter .= '<option value="reports">nombre de reports</option>';
    $sorter .= '</select>';
    $sorter .= '<input type="submit" value="Confirmer"/>';
    $sorter .= '</form>';
    
    echo $sorter;
    
    if(empty($_GET['sortedBy']) || ($_GET['sortedBy'] != 'reports' && $_GET['sortedBy'] != 'likes' && $_GET['sortedBy'] != 'users' && $_GET['sortedBy'] != 'date'))
    {
        header('Location:admin.php');
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
        }
    }
    
    for($i=0; $i < count($comments); $i++)
    {
        $commentDate = new DateTime($comments[$i]->date());
        $commentAuthor = $userManager->getUserById($comments[$i]->userId());
        
        $display = '<div>';
        
        if($commentAuthor->isBanned())
        {
            $display .= '<p>'.$commentAuthor->name().' (Utilisateur banni)</p>';
        }
        else
        {
            $display .= '<p>'.$commentAuthor->name().'</p>';
        }
        
        $display .= '<p>a écrit le '.$commentDate->format('d/m/Y à H:i:s').' :</p>';
        $display .= '<p>Sur le chapitre '.$postManager->getChapterByPostId($comments[$i]->postId()).'</p>';
        $display .= '<p>'.$comments[$i]->content().'</p>';
        $display .= '<p>'.$comments[$i]->likes().' likes '.$comments[$i]->reports().' reports</p>';
        $display .= '<p>';
        $display .= '<a href="confirmCommentSuppression.php?id='.$comments[$i]->id().'">supprimer le commentaire</a>';
        
        if($commentAuthor->isBanned())
        {
            $display .= '<a href="confirmBanUser.php?action=unban&id='.$commentAuthor->id().'&redirect=commentsList.php">Débannir</a>';
        }
        elseif($commentAuthor->isAdmin())
        {
            
        }
        else
        {
            $display .= '<a href="confirmBanUser.php?action=ban&id='.$commentAuthor->id().'&redirect=commentsList.php">bannir l\'utilisateur</a>';
        }
        
        $display .= '</p>';
        $display .= '<p>Voir tous les commentaires de cet utilisateur triés par : </p>';
        $display .= '<form action="commentsList" method="get">';
        $display .= '<select name="sortedBy">';
        $display .= '<option value="date">date</option>';
        $display .= '<option value="users">utilisateur</option>';
        $display .= '<option value="likes">nombre de likes</option>';
        $display .= '<option value="reports">nombre de reports</option>';
        $display .= '</select>';
        $display .= '<input type="hidden" name="id" value="'.$commentAuthor->id().'" />';
        $display .= '<input type="submit" value="confirmer" />';
        $display .= '</form>';
        $display .= '</div>';
        $display .= '<hr />';

        echo $display;
    }
}