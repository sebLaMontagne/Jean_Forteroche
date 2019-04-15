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
    
    $comments = $commentManager->getAllCommentsSortedByReports();
    
    for($i=0; $i < count($comments); $i++)
    {
        $commentDate = new DateTime($comments[$i]->date());
        $commentAuthor = $userManager->getUserById($comments[$i]->userId());
        
        $display  = '';
        $display .= '<div>';
        $display .=     '<p>'.$commentAuthor->name().'</p>';
        $display .=     '<p>a écrit le '.$commentDate->format('d/m/Y à H:i:s').' :</p>';
        $display .=     '<p>Sur le chapitre '.$postManager->getChapterByPostId($comments[$i]->postId()).'</p>';
        $display .=     '<p>'.$comments[$i]->content().'</p>';
        $display .=     '<p>'.$comments[$i]->likes().' likes '.$comments[$i]->reports().' reports</p>';
        $display .=     '<p>';
        $display .=         '<a href="confirmCommentSuppression.php?id='.$comments[$i]->id().'">supprimer le commentaire</a>';
        $display .=         '<a href="confirmBanUser">bannir l\'utilisateur</a>';
        $display .=     '</p>';
        $display .= '<div>';
        $display .= '<hr />';
        
        echo $display;
    }
    var_dump($comments);
}