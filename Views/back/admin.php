<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Billet simple pour l\'Alaska - Admin';
    
    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {
        $postManager = new PostManager();
        $userManager = new UserManager();
        $commentManager = new CommentManager();

        $content  = '<div class="content filler">';
        $content .= '<p>Bienvenue sur le panneau d\'administration du site. Depuis cette section, vous pouvez rédiger des chapitres, gérer les commentaires et les utilisateurs</p>';
      	$content .= '<p style="text-align: center;">Il y a actuellement : </p>';
        $content .= '<div class="container">';
      	$content .= '<div>';
        $content .= '<a href="chaptersList"><img src="../../Ressources/img/chapters.png" /></a>';
      	$content .= '<ul>';
      	$content .= '<li>'.$postManager->getPublicChaptersCount().' chapitres publics </li>';
        $content .= '<li>'.$postManager->getDraftChaptersCount().' brouillons </li>';
      	$content .= '</ul>';
      	$content .= '</div>';
      	$content .= '<div>';
        $content .= '<a href="commentsList-reports"><img src="../../Ressources/img/comments.png" /></a>';
      	$content .= '<ul>';
      	$content .= '<li>'.$commentManager->getAllCommentsCount().' commentaires</li>';
      	$content .= '</ul>';
      	$content .= '</div>';
      	$content .= '<div>';
        $content .= '<a href="usersList-all"><img src="../../Ressources/img/users.png" /></a>';
      	$content .= '<ul>';
      	$content .= '<li>'.$userManager->getAdminUsersCount().' administrateurs</li>';
      	$content .= '<li>'.$userManager->getNormalUsersCount().' utilisateurs</li>';
      	$content .= '<li>'.$userManager->getBannedUsersCount().' bannis</li>';
      	$content .= '</ul>';
      	$content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}
