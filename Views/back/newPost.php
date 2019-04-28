<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Billet simple pour l\'Alaska - Création d\'un chapitre';

    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {
        $content = '<div class="content filler">';
        
        if(isset($_SESSION['data']))
        {
            $postManager = new PostManager();
            
            $content .= '<form method="post" action="confirmNewPost">';
            $content .= '<p style="text-align: center;"><label for="chapterNumber">Chapitre n° </label><input type="number" name="chapterNumber" min="1" max="65535" id="chapterNumber" value="'.$_SESSION['data']['chapterNumber'].'" required /> : <input id="title" type="text" name="title" placeholder="Titre" value="'.$_SESSION['data']['title'].'" required /></p>';
            $content .= '<textarea name="content">'.$postManager->decode($_SESSION['data']['content']).'</textarea>';
            $content .= '<div class="radios"><input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label></div>';
            $content .= '<div class="radios"><input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label></div>';
            $content .= '<input type="submit" value="sauvegarder" />';
            $content .= '</form>';

            unset($_SESSION['data']);
        }
        else
        {
            $content .= '<form method="post" action="confirmNewPost">';
            $content .= '<p style="text-align: center;"><label for="chapterNumber">Chapitre n° </label><input type="number" name="chapterNumber" min="1" id="chapterNumber" required /> : <input id="title" type="text" name="title" placeholder="Titre" required /></p>';
            $content .= '<textarea name="content"></textarea>';
            $content .= '<div class="radios"><input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label></div>';
            $content .= '<div class="radios"><input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label></div>';
            $content .= '<input type="submit" value="sauvegarder" />';
            $content .= '</form>';
        }
        
        $content .= '</div>';
        $content .= '<script src="https://cloud.tinymce.com/5/tinymce.min.js"></script>';
        $content .= '<script>tinymce.init({ selector:"textarea", entity_encoding : "raw"});</script>';
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}