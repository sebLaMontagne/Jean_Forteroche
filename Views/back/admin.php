<?php

try
{
    $title = 'Page d\'admin';
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
        $userManager = new UserManager();
        $commentManager = new CommentManager();

        $display  = '<p>Bienvenue sur le panneau d\'administration du site. Depuis cette section, vous pouvez rédiger des chapitres, gérer les commentaires et les utilisateurs</p>';

        $display .= '<p>Il y a actuellement : </p>';
        $display .= '<ul>';
        $display .= '<li>'.$postManager->getAllChaptersCount().' chapitres dont '.$postManager->getPublicChaptersCount().' publics et '.$postManager->getDraftChaptersCount().' brouillons &rarr; <a href="chaptersList.php">Gérer les chapitres</a></li>';
        $display .= '<li>'.$userManager->getAllUsersCount().' utilisateurs dont '.$userManager->getAdminUsersCount().' administrateurs, '.$userManager->getNormalUsersCount().' utilisateurs et '.$userManager->getBannedUsersCount().' bannis &rarr; <a href="usersList.php?show=all">Gérer les utilisateurs</a></li>';
        $display .= '<li>'.$commentManager->getAllCommentsCount().' commentaires &rarr; <a href="commentsList.php?sortedBy=reports">Gérer les commentaires</a></li>';
        $display .= '</ul>';

        echo $display;
    }
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}
