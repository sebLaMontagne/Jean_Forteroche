<?php

$title = 'traitement posts';
include('template.php');

$postManager = new PostManager();
if($postManager->isChapterExist(htmlspecialchars($_POST['chapterNumber'])))
{
    if(!isset($_POST['save']))
    {
        echo 
        '<p>Ce chapitre existe déjà en base de données. Etes-vous sûr de vouloir le remplacer ?</p> 
         <form method="post" action="savePost.php">
             <input type="radio" name="save" value="yes" id="save" /><label for="save">Oui</label>
             <input type="radio" name="save" value="no" id="nosave" /><label for="nosave">Non</label>
             <input type="hidden" name="chapterNumber" value="'.$_POST['chapterNumber'].'" />
             <input type="hidden" name="title" value="'.$_POST['title'].'" />
             <input type="hidden" name="content" value="'.$_POST['content'].'" />
             <input type="hidden" name="publish" value="'.$_POST['publish'].'" />
             <input type="hidden" name="id" value="'.$_POST['id'].'" />
             
             <input type="submit" value="Confirmer" />
         </form>';
    }
    
    if(isset($_POST['save']) && $_POST['save'] == 'yes')
    {
        echo '<p>On update le chapitre en base de données</p>';
        
        if($_POST['publish'] == 'publish')
        {
            $postManager->updatePost($_POST['id'], $_POST['chapterNumber'], $_POST['title'], $_POST['content'], 1);
            header('Location: chaptersList.php?a=updated');
        }
        elseif($_POST['publish'] == 'draft')
        {
            $postManager->updatePost($_POST['id'], $_POST['chapterNumber'], $_POST['title'], $_POST['content'], 0);
            header('Location: chaptersList.php?a=updated');
        }
        else
        {
            echo '<p>Erreur lors de la mise à jour du chapitre</p>';
        }
        
    }
    
    if(isset($_POST['save']) && $_POST['save'] == 'no')
    {
        //faire transiter les infos du formulaire avec la session et renvoyer à l'éditeur
        $_SESSION['data']['chapterNumber'] = $_POST['chapterNumber'];
        $_SESSION['data']['title'] = $_POST['title'];
        $_SESSION['data']['content'] = $_POST['content'];
        $_SESSION['data']['id'] = $_POST['id'];
        header('Location:write.php');
    }
}
else
{
    if($_POST['publish'] == 'publish')
    {
        $postManager->savePost($_SESSION['id'], $_POST['chapterNumber'], $_POST['title'], $_POST['content'], 1);
        header('Location: chaptersList.php?a=created');
    }
    elseif($_POST['publish'] == 'draft')
    {
        $postManager->savePost($_SESSION['id'], $_POST['chapterNumber'], $_POST['title'], $_POST['content'], 0);
        header('Location: chaptersList.php?a=created');
    }
    else
    {
        echo '<p>Erreur lors de l\'enregistrement du nouveau chapitre</p>';
    }
}

var_dump($_POST);
var_dump($_GET);