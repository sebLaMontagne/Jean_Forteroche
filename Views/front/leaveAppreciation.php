<?php
$title = 'gestion des appreciations';
require('template.php');


if(isset($_GET['appreciation']) && isset($_GET['id']))
{
    //Vérifier que l'appreciation n'existe pas déjà avant d'en écrire une autre
    if(($_GET['appreciation'] == 'like' || $_GET['appreciation'] == 'report') && intval($_GET['id']) > 0)
    {
        $appreciationManager = new AppreciationManager();
        $appreciationManager->addAppreciation($_GET['id'], $_SESSION['id'], $_GET['appreciation']);
    }
    
    $commentManager = new CommentManager();
    header('Location:chapter.php?chapter='.$commentManager->getChapterNumberByCommentId($_GET['id']));
}
else
{
    header('Location:home.php');
}