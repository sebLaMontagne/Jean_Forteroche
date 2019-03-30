<?php

$title = 'Liste des chapitres';
require('template.php');

$postManager = new PostManager();
$userManager = new UserManager();

$posts = $postManager->getAllPosts();
$content = '';

for($i = 0; $i < count($posts); $i++)
{
    $postDate = new DateTime($posts[$i]->Date());
    $postAuthor = $userManager->getUserById($posts[$i]->authorId());
    
    //Ajouter un extrait
    echo
    '<a href="chapter.php?id='.$posts[$i]->Id().'">
        <h3>Chapitre '.$posts[$i]->chapterNumber().' : '.$posts[$i]->Title().'</h3>
        <p>Publié le '.$postDate->format('d/m/Y à H:i:s').' par '.$postAuthor->name().'</p>
    </a>';
}