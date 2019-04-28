<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Billet simple pour l\'Alaska - Validation d\'un chapitre';
    
    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {
        $postManager = new PostManager();
        if($postManager->isChapterExist($_POST['chapterNumber']))
        {
            if(!isset($_POST['confirmation']))
            {
                $content  = '<div class="content filler">';
                $content .= '<p style="text-align: center;">Ce chapitre existe déjà. Etes-vous sûr de vouloir l\'écraser ?</p>';
                $content .= '<form method="post" action="confirmNewPost">';
                $content .= '<div class="radios"><input type="radio" name="confirmation" value="yes" id="yes" /><label for="yes">Oui</label></div>';
                $content .= '<div class="radios"><input type="radio" name="confirmation" value="no" id="no" /><label for="no">Non</label></div>';
                $content .= '<input type="hidden" name="chapterNumber" value="'.$_POST['chapterNumber'].'" />';
                $content .= '<input type="hidden" name="title" value="'.$_POST['title'].'" />';
                $content .= '<input type="hidden" name="content" value="'.$postManager->encode($_POST['content']).'" />';
                $content .= '<input type="hidden" name="publish" value="'.$_POST['publish'].'" />';
                $content .= '<input type="submit" value="Confirmer" />';
                $content .= '</form>';
                
                include('template.php');
            }
            elseif($_POST['confirmation'] == 'yes')
            {
                $postManager->updatePost($postManager->getPostIDbyChapter($_POST['chapterNumber']), $_POST['title'], $postManager->encode($_POST['content']), $_POST['publish']);
                header('Location:chaptersList');
                exit();
            }
            elseif($_POST['confirmation'] == 'no')
            {
                $_SESSION['data']['chapterNumber'] = $_POST['chapterNumber'];
                $_SESSION['data']['title'] = $_POST['title'];
                $_SESSION['data']['content'] = $_POST['content'];
                header('location:newPost');
                exit();
            }
        }
        else
        {
            $postManager->savePost($_POST['chapterNumber'], $_POST['title'], $postManager->encode($_POST['content']), intval($_POST['publish']));
            header('Location:chaptersList');
            exit();
        }
    }
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}