<?php

$title = 'Liste des chapitres';
require('template.php');

echo '<p><a href="write.php">Ecrire un nouveau chapitre</a></p>';

$postManager = new PostManager;
$list = $postManager->getAllPosts();

for($i = 0; $i < count($list); $i++)
{
    echo '
    <div>
        <p>Chapitre '.$list[$i]->chapterNumber().' : '.$list[$i]->title().'</p>
        <a href="write.php?chapter='.$list[$i]->chapterNumber().'">modifier</a><a href="delete.php?chapter='.$list[$i]->chapterNumber().'">supprimer</a>
    </div>';
    
}

echo '<p><a href="write.php">Ecrire un nouveau chapitre</a></p>';