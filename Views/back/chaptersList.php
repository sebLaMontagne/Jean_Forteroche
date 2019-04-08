<?php

$title = 'Liste des chapitres';
require('template.php');

echo '<p><a href="write.php">Ecrire un nouveau chapitre</a></p>';

$postManager = new PostManager;
$list = $postManager->getAllPosts();

//Dans le cas d'un author, interdire l'accès aux chapitres dont ils ne sont pas auteurs

for($i = 0; $i < count($list); $i++)
{
    if($list[$i]->isPublished())
    {
        echo '<div><p>Public</p>';
    }
    else
    {
        echo '<div><p>Brouillon</p>';
    }
    
    echo '
    <div>
        <p>Chapitre '.$list[$i]->chapterNumber().' : '.$list[$i]->title().'</p>
        <a href="write.php?chapter='.$list[$i]->chapterNumber().'">modifier</a><a href="delete.php?chapter='.$list[$i]->chapterNumber().'">supprimer</a>
    </div>';
}

echo '<p><a href="write.php">Ecrire un nouveau chapitre</a></p>';

if(isset($_GET['a']) && $_GET['a'] == 'created')
{
    echo '<p>Le chapitre a bien été créé</p>';
}
if(isset($_GET['a']) && $_GET['a'] == 'updated')
{
    echo '<p>Le chapitre a bien été mis à jour</p>';
}