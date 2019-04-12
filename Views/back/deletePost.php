<?php

$title = 'Suppression d\'un article';
require('template.php');

$postManager = new PostManager();

if(isset($_GET) && !empty($_GET['chapter']) && $postManager->isChapterExist($_GET['chapter']))
{
    echo 
    '<p>Etes-vous sûr de vouloir supprimer cet article ?</p>
     <form method="post" action="deletePost.php?chapter='.$_GET['chapter'].'">
        <label for="delete-yes">Oui</label><input id="delete-yes" type="radio" name="delete" value="yes" />
        <label for="delete-no">Non</label><input id="delete-no" type="radio" name="delete" value="no" />
        <input type="submit" value="confirmer" />
    </form>';

    if(isset($_POST['delete']) && $_POST['delete'] == 'yes')
    {
        $postManager->deletePost($_GET['chapter']);
        header('Location:chaptersList.php');
    }
    
    if(isset($_POST['delete']) && $_POST['delete'] == 'no')
    {
        header('Location:chaptersList.php');
    }
}
else
{
    header('Location:admin.php');
}