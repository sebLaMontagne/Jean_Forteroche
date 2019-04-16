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
    
    if(isset($_GET['user']) && intval($_GET['user'] > 0))
    {
        $comments = $commentManager->getAllUserCommentsSortedByReports($_GET['user']);
    }
    else
    {
        $comments = $commentManager->getAllCommentsSortedByReports();
    }
    
    for($i=0; $i < count($comments); $i++)
    {
        $commentDate = new DateTime($comments[$i]->date());
        $commentAuthor = $userManager->getUserById($comments[$i]->userId());

        $display  = '';
        $display .= '<div>';
        
        if($commentAuthor->isBanned())
        {
            $display .= '<p>'.$commentAuthor->name().' (Utilisateur banni)</p>';
        }
        else
        {
            $display .= '<p>'.$commentAuthor->name().'</p>';
        }
        
        $display .=     '<p>a écrit le '.$commentDate->format('d/m/Y à H:i:s').' :</p>';
        $display .=     '<p>Sur le chapitre '.$postManager->getChapterByPostId($comments[$i]->postId()).'</p>';
        $display .=     '<p>'.$comments[$i]->content().'</p>';
        $display .=     '<p>'.$comments[$i]->likes().' likes '.$comments[$i]->reports().' reports</p>';
        $display .=     '<p>';
        $display .=         '<a href="confirmCommentSuppression.php?id='.$comments[$i]->id().'">supprimer le commentaire</a>';
        
        if($commentAuthor->isBanned())
        {
            $display .= '<a href="confirmBanUser.php?action=unban&id='.$commentAuthor->id().'">Débannir</a>';
        }
        else
        {
            $display .= '<a href="confirmBanUser.php?action=ban&id='.$commentAuthor->id().'">bannir l\'utilisateur</a>';
        }
        
        $display .=     '</p>';
        $display .= '<p><a href="commentsList.php?user='.$commentAuthor->id().'">Voir tous les commentaires de cet utilisateur</a></p>';
        $display .= '<div>';
        $display .= '<hr />';

        echo $display;
    }
}