<?php

try
{
    $title = 'Liste des chapitres';
    require('template.php');

    $postManager = new PostManager();
    $userManager = new UserManager();

    $posts = $postManager->getAllPublishedPosts();
    $content = '';

    for($i = 0; $i < count($posts); $i++)
    {
        $postDate = new DateTime($posts[$i]->Date());

        echo
        '<a href="chapter.php?chapter='.$posts[$i]->chapterNumber().'">
            <h3>Chapitre '.$posts[$i]->chapterNumber().' : '.$posts[$i]->Title().'</h3>
            <p>Publié le '.$postDate->format('d/m/Y à H:i:s').'</p>
        </a>';
    }
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}