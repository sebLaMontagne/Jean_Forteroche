<?php

$title = 'Liste des chapitres';
require('template.php');

$_SESSION['refresh'] = 1;
unset($_SESSION['refresh']);

if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
{
    header("location:javascript://history.go(-1)");
}
else
{
    echo '<p><a href="newPost.php">Ecrire un nouveau chapitre</a></p>';

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
            <a href="updatePost.php?chapter='.$list[$i]->chapterNumber().'">modifier</a><a href="deletePost.php?chapter='.$list[$i]->chapterNumber().'">supprimer</a>
        </div>';
    }

    echo '<p><a href="newPost.php">Ecrire un nouveau chapitre</a></p>';
}