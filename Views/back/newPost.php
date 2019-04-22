<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Création d\'un chapitre';

    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {
        if(isset($_SESSION['data']))
        {
            $postManager = new PostManager();
            
            $content  = '<form method="post" action="confirmNewPost.php">';
            $content .= '<label for="chapterNumber">Chapitre n°</label><input type="number" name="chapterNumber" min="1" max="65535" id="chapterNumber" value="'.$_SESSION['data']['chapterNumber'].'" required /> : <input type="text" name="title" placeholder="Titre" value="'.$_SESSION['data']['title'].'" required />';
            $content .= '<textarea name="content">'.$postManager->decode($_SESSION['data']['content']).'</textarea>';
            $content .= '<input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label>';
            $content .= '<input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label>';
            $content .= '<input type="submit" value="sauvegarder" />';
            $content .= '</form>';

            unset($_SESSION['data']);
        }
        else
        {
            $content  = '<form method="post" action="confirmNewPost.php">';
            $content .= '<label for="chapterNumber">Chapitre n°</label><input type="number" name="chapterNumber" min="1" id="chapterNumber" placeholder="numéro de chapitre" required /> : <input type="text" name="title" placeholder="Titre" required />';
            $content .= '<textarea name="content"></textarea>';
            $content .= '<input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label>';
            $content .= '<input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label>';
            $content .= '<input type="submit" value="sauvegarder" />';
            $content .= '</form>';
        }
        
        $content .= '<script src="https://cloud.tinymce.com/5/tinymce.min.js"></script>';
        $content .= '<script>tinymce.init({ selector:"textarea", entity_encoding : "raw"});</script>';
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}