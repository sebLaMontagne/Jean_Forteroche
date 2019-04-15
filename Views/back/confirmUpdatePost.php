<?php

$title = 'confirmer la mise à jour';
require('template.php');

$postManager = new PostManager();

if(!isset($_POST['confirmation']))
{
    echo '
        <p>Ce chapitre existe déjà. Etes-vous sûr de vouloir l\'écraser ?</p>
        <form method="post">
            <input type="radio" name="confirmation" value="yes" id="yes" /><label for="yes">Oui</label>
            <input type="radio" name="confirmation" value="no" id="no" /><label for="no">Non</label>
            <input type="hidden" name="chapterNumber" value="'.$_POST['chapterNumber'].'" />
            <input type="hidden" name="title" value="'.$_POST['title'].'" />
            <input type="hidden" name="content" value="'.$postManager->encode($_POST['content']).'" />
            <input type="hidden" name="publish" value="'.$_POST['publish'].'" />
            <input type="submit" value="Confirmer" />
        </form>';
}
elseif($_POST['confirmation'] == 'yes')
{
    $postManager->updatePost($postManager->getPostIDbyChapter($_POST['chapterNumber']), $_POST['chapterNumber'], $_POST['title'], $_POST['content'], $_POST['publish']);
    header('Location:chaptersList.php');
}
elseif($_POST['confirmation'] == 'no')
{
    $_SESSION['data']['title'] = $_POST['title'];
    $_SESSION['data']['content'] = $_POST['content'];
    header('location:updatePost.php?chapter='.$_POST['chapterNumber']);
}

var_dump($_POST);