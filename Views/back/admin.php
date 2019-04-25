<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Page d\'admin';
    
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
        $content .= '<p>Il y a actuellement : </p>';
        $content .= '<ul>';
        $content .= '<li>'.$postManager->getAllChaptersCount().' chapitres dont '.$postManager->getPublicChaptersCount().' publics et '.$postManager->getDraftChaptersCount().' brouillons &rarr; <a class="link-standard" href="chaptersList">Gérer les chapitres</a></li>';
        $content .= '<li>'.$userManager->getAllUsersCount().' utilisateurs dont '.$userManager->getAdminUsersCount().' administrateurs, '.$userManager->getNormalUsersCount().' utilisateurs et '.$userManager->getBannedUsersCount().' bannis &rarr; <a class="link-standard" href="usersList-all">Gérer les utilisateurs</a></li>';
        $content .= '<li>'.$commentManager->getAllCommentsCount().' commentaires &rarr; <a class="link-standard" href="commentsList-reports">Gérer les commentaires</a></li>';
        $content .= '</ul>';
        $content .= '</div>';
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}
