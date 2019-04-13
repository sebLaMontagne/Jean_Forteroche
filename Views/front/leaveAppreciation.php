<?php
$title = 'gestion des appreciations';
require('template.php');


if(isset($_GET['appreciation']) && isset($_GET['id']))
{
    $appreciationManager = new AppreciationManager();
    
    if(($_GET['appreciation'] == 'like' || $_GET['appreciation'] == 'report') && intval($_GET['id']) > 0 && !$appreciationManager->isAppreciationExist($_SESSION['id'], $_GET['id']))
    {
        $appreciationManager->addAppreciation($_GET['id'], $_SESSION['id'], $_GET['appreciation']);
    }
    elseif(($_GET['appreciation'] == 'reset' && $appreciationManager->isAppreciationExist($_SESSION['id'], $_GET['id'])))
    {
        $appreciationManager->deleteAppreciation($_SESSION['id'], $_GET['id']);
    }
    
    $commentManager = new CommentManager();
    header('Location:chapter.php?chapter='.$commentManager->getChapterNumberByCommentId($_GET['id']));
}
else
{
    header('Location:home.php');
}