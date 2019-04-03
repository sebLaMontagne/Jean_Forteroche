<?php

$title = 'Billet simple pour l\'Alaska - Accueil';
require('template.php');

if(isset($_GET) && !empty($_GET['id']))
{
    $postManager = new PostManager();
    $selectedPost = $postManager->getPost($_GET['id']);
    $postDate = new DateTime($selectedPost->Date());
    
    $userManager = new UserManager();
    $postAuthor = $userManager->getUserById($selectedPost->authorId());
    
    echo
    '<h3>Chapitre '.$selectedPost->chapterNumber().' : '.$selectedPost->Title().'</h3>
     <p>'.$selectedPost->Content().'</p>
     <p>Publié le '.$postDate->format('d/m/Y à H:i:s').' par '.$postAuthor->name().'</p>';
}

