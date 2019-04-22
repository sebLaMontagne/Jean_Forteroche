<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Liste des chapitres';
    
    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {
        $content  = '<p>Ici, vous pouvez écrire ou modifier des chapitres, et les rendre publics ou non</p>';
        $content .= '<p><a href="newPost.php">Ecrire un nouveau chapitre</a></p>';

        $postManager = new PostManager;
        $list = $postManager->getAllPosts();

        for($i = 0; $i < count($list); $i++)
        {
            if($list[$i]->isPublished())
            {
                $content .= '<div><p>Public</p>';
            }
            else
            {
                $content .= '<div><p>Brouillon</p>';
            }
            
            $content .= '<div>';
            $content .= '<p>Chapitre '.$list[$i]->chapterNumber().' : '.$list[$i]->title().'</p>';
            $content .= '<a href="updatePost.php?chapter='.$list[$i]->chapterNumber().'">modifier</a><a href="deletePost.php?chapter='.$list[$i]->chapterNumber().'">supprimer</a>';
            $content .= '</div>';
        }

        $content .= '<p><a href="newPost.php">Ecrire un nouveau chapitre</a></p>';
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}
