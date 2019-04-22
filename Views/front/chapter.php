<?php

try
{
    require_once('../autoloader.php');
    
    $title = 'Billet simple pour l\'Alaska - Accueil';
    $content = '';

    $postManager = new PostManager();
    $userManager = new UserManager();
    $commentManager = new CommentManager();
    $appreciationManager = new AppreciationManager();

    if(isset($_GET) && !empty($_GET['chapter']) && $postManager->isChapterExist($_GET['chapter']))
    {
        $selectedPost = $postManager->getPost($_GET['chapter']);
        $postDate = new DateTime($selectedPost->Date());
        $comments = $commentManager->getPostComments($selectedPost);

        $content .= '<h3>Chapitre '.$selectedPost->chapterNumber().' : '.$selectedPost->Title().'</h3>'.$postManager->decode($selectedPost->Content()).
        '<p>Publié le '.$postDate->format('d/m/Y à H:i:s').'</p>';

        if($postManager->isChapterExist($postManager->getPreviousChapterNumber($_GET['chapter'])))
        {
            $content .= '<p><a href="http://localhost/P4/Views/front/chapter.php?chapter='.$postManager->getPreviousChapterNumber($_GET['chapter']).'">Précédent</a>';
        }

        if($postManager->isChapterExist($postManager->getNextChapterNumber($_GET['chapter'])))
        {
            $content .= '<p><a href="http://localhost/P4/Views/front/chapter.php?chapter='.$postManager->getNextChapterNumber($_GET['chapter']).'">   Suivant</a>';
        }

        //Formulaire commentaire

        if(!empty($_SESSION['pseudo']))
        {
            $content .= '<form method="post" action="chapter.php?chapter='.$_GET['chapter'].'">';
            $content .= '<textarea placeholder="Laissez-nous un commentaire" name="comment"></textarea>';
            $content .= '<input type="submit" value="commenter" />';
            $content .= '</form>';
        }
        else
        {
            $content .= '<p><a href="register.php">Inscrivez-vous</a> ou <a href="login.php">connectez-vous</a> pour nous laisser un commentaire :)</p>';
        }

        $content .= '<p>Commentaires :</p>';

        //Enregistrement commentaire

        if(!empty($_POST['comment']))
        {   
            $commentManager->saveComment($selectedPost->id(), $_SESSION['id'], $_POST['comment']);
        }

        //Affichage commentaires

        for($i=0; $i < count($comments); $i++)
        {
            $commentDate = new DateTime($comments[$i]->date());
            $commentAuthor = $userManager->getUserById($comments[$i]->userId());

            $content .= '<div>';
            $content .= '<p>'.$commentAuthor->name().'</p>';
            $content .= '<p>a écrit le '.$commentDate->format('d/m/Y à H:i:s').' :</p>';
            $content .= '<p>'.$comments[$i]->content().'</p>';

            if(isset($_SESSION['id']) && !$commentManager->isUserTheCommentAuthor($_SESSION['id'], $comments[$i]->id()) && !$appreciationManager->isAppreciationExist($_SESSION['id'], $comments[$i]->id()))
            {
                $content .= '<p>';
                $content .= '<a href="leaveAppreciation.php?appreciation=like&id='.$comments[$i]->id().'">Aimer</a>';
                $content .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                $content .= '<a href="leaveAppreciation.php?appreciation=report&id='.$comments[$i]->id().'">Signaler</a>';
                $content .= '</p>';
            }
            elseif(isset($_SESSION['id']) && !$commentManager->isUserTheCommentAuthor($_SESSION['id'], $comments[$i]->id()) && $appreciationManager->isAppreciationExist($_SESSION['id'], $comments[$i]->id()))
            {
                if($appreciationManager->AppreciationIsLike($_SESSION['id'], $comments[$i]->id()))
                {
                    $content .= '<p>Vous avez déjà liké ce commentaire</p>';
                }
                elseif($appreciationManager->AppreciationIsReport($_SESSION['id'], $comments[$i]->id()))
                {
                    $content .= '<p>Vous avez déjà report ce commentaire</p>';
                }
                $content .= '<p><a href="leaveAppreciation.php?appreciation=reset&id='.$comments[$i]->id().'">Retirer votre appréciation</a></p>';
            }
            elseif(isset($_SESSION['id']) && $commentManager->isUserTheCommentAuthor($_SESSION['id'], $comments[$i]->id()))
            {
                $content .= '<p>Vous ne pouvez pas laisser d\'appreciation sur vos commentaires</p>';
            }
            else
            {
                $content .= '<p><a href="register.php">Inscrivez-vous</a> ou <a href="login.php">connectez-vous</a> pour laisser une appreciation</p>';
            }

            //Affichage likes / reports

            $content .= '<p>'.$comments[$i]->likes().' likes</p>';
            $content .= '<p>'.$comments[$i]->reports().' reports</p>';
            $content .= '</div>';
        }
    }
    else
    {
        $content .= '<p>Ce chapitre n\'existe pas</p>';
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}