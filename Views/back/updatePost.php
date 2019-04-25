<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'traitement posts';

    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {   
        if(!empty($_GET['chapter']) && intval($_GET['chapter']) > 0)
        {
            $postManager = new PostManager();
            if($postManager->isChapterExist($_GET['chapter']))
            {
                $content = '<div class="content filler">';
                
                if(isset($_SESSION['data']))
                {
                    $postManager = new PostManager();
                    
                    $content .= '<form method="post" action="confirmUpdatePost">';
                    $content .= '<p style="text-align: center;"><input type="hidden" name="chapterNumber" value="'.$_GET['chapter'].'" />';
                    $content .= 'Chapitre n°'.$_GET['chapter'].' : <input id="title" type="text" name="title" placeholder="Titre" value="'.$_SESSION['data']['title'].'" required /></p>';
                    $content .= '<textarea name="content">'.$postManager->decode($_SESSION['data']['content']).'</textarea>';
                    $content .= '<div class="radios"><input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label></div>';
                    $content .= '<div class="radios"><input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label></div>';
                    $content .= '<input type="submit" value="sauvegarder" />';
                    $content .= '</form>';

                    unset($_SESSION['data']);
                }
                else
                {
                    $selectedChapter = $postManager->getPost($_GET['chapter']);
                    
                    $content .= '<form method="post" action="confirmUpdatePost">';
                    $content .= '<p style="text-align: center;"><input type="hidden" name="chapterNumber" value="'.$_GET['chapter'].'" />';
                    $content .= 'Chapitre n°'.$_GET['chapter'].' : <input id="title" type="text" name="title" placeholder="Titre" value="'.$selectedChapter->title().'" required /></p>';
                    $content .= '<textarea name="content">'.$postManager->decode($selectedChapter->content()).'</textarea>';
                    $content .= '<div class="radios"><input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label></div>';
                    $content .= '<div class="radios"><input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label></div>';
                    $content .= '<input type="submit" value="sauvegarder" />';
                    $content .= '</form>';
                }
            }
            else
            {
                header('Location:admin');
                exit();
            }
        }
        else
        {
            header('Location:admin');
            exit();
        }
        
        $content .= '</div>';
        $content .= '<script src="https://cloud.tinymce.com/5/tinymce.min.js"></script>';
        $content .= '<script>tinymce.init({ selector:"textarea", entity_encoding : "raw"});</script>';
    }
    
    include('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}