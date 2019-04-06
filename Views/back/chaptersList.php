<?php

$title = 'Liste des chapitres';
require('template.php');

$postManager = new PostManager;
$list = $postManager->getAllPosts();

for($i = 0; $i < count($list); $i++)
{
    echo '
    <div>
        <p>Chapitre '.$list[$i]->chapterNumber().' : '.$list[$i]->title().'</p>
        <a href="chapter.php?chapter='.$list[$i]->chapterNumber().'">modifier</a><a href="delete.php?chapter='.$list[$i]->chapterNumber().'">supprimer</a>
    </div>';
    
}