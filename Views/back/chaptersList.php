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
        $content  = '<div class="content" style="text-align: center;">';
        $content .= '<p>Ici, vous pouvez écrire ou modifier des chapitres, et les rendre publics ou non</p>';
        $content .= '<p><a class="link-standard" href="newPost.php">Ecrire un nouveau chapitre</a></p>';

        $postManager = new PostManager;
        $list = $postManager->getAllPosts();

        for($i = 0; $i < count($list); $i++)
        {
            $content .= '<div class="comment">';
            $content .= '<p class="comment-description">';
            
            if($list[$i]->isPublished())
            {
                $content .= '(Chapitre Public)</p>';
            }
            else
            {
                $content .= '(Brouillon)</p>';
            }
            
            $content .= '<hr />';
            $content .= '<p>Chapitre '.$list[$i]->chapterNumber().' : '.$list[$i]->title().'</p>';
            $content .= '<hr />';
            $content .= '<p class="comment-counts">';
            $content .= '<a class="link-standard" href="updatePost.php?chapter='.$list[$i]->chapterNumber().'">modifier</a><a class="link-standard" href="deletePost.php?chapter='.$list[$i]->chapterNumber().'">supprimer</a>';
            $content .= '</p>';
            $content .= '</div>';
        }

        $content .= '<p><a class="link-standard" href="newPost.php">Ecrire un nouveau chapitre</a></p>';
        $content .= '</div>';
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}