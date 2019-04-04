<?php

$title = 'Billet simple pour l\'Alaska - Accueil';
require('template.php');

$postManager = new PostManager();

if(isset($_GET) && !empty($_GET['chapter']) && $postManager->isChapterExist($_GET['chapter']))
{
    $selectedPost = $postManager->getPost($_GET['chapter']);
    $postDate = new DateTime($selectedPost->Date());
    
    $userManager = new UserManager();
    $postAuthor = $userManager->getUserById($selectedPost->authorId());
    
    echo
    '<h3>Chapitre '.$selectedPost->chapterNumber().' : '.$selectedPost->Title().'</h3>
     <p>'.$selectedPost->Content().'</p>
     <p>Publié le '.$postDate->format('d/m/Y à H:i:s').' par '.$postAuthor->name().'</p>';
    
    if($postManager->isChapterExist($_GET['chapter']-1))
    {
        echo '<a href="http://localhost/P4/Views/chapter.php?chapter='.($_GET['chapter']-1).'">Chapitre précédent</a>';
    }
    if($postManager->isChapterExist($_GET['chapter']+1))
    {
        echo '<a href="http://localhost/P4/Views/chapter.php?chapter='.($_GET['chapter']+1).'">Chapitre suivant</a>';
    }
}
else
{
    echo '<p>Ce chapitre n\'existe pas</p>';
}