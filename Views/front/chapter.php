<?php

try
{
    require_once('../autoloader.php');
    
    $title = 'Billet simple pour l\'Alaska - Accueil';
    $content = '<div class="content" style="text-align: center;">';

    $postManager = new PostManager();
    $userManager = new UserManager();
    $commentManager = new CommentManager();
    $appreciationManager = new AppreciationManager();

    if(isset($_GET) && !empty($_GET['chapter']) && $postManager->isChapterExist($_GET['chapter']))
    {
        $selectedPost = $postManager->getPost($_GET['chapter']);
        $postDate = new DateTime($selectedPost->Date());
        $comments = $commentManager->getPostComments($selectedPost);

        $content .= '<div class="chapter">';
        $content .= '<h3>Chapitre '.$selectedPost->chapterNumber().' : '.$selectedPost->Title().'</h3>';
        $content .= '<hr />';
        $content .= '<div class="chapter-content">'.$postManager->decode($selectedPost->Content()).'</div>';
        $content .= '<hr />';
        $content .= '<p>Publié le '.$postDate->format('d/m/Y à H:i:s').'</p>';
        $content .= '</div>';
        
        $content .= '<p class="chapter-links">';
        if($postManager->isChapterExist($postManager->getPreviousChapterNumber($_GET['chapter'])))
        {
            $content .= '<a href="http://localhost/P4/Views/front/chapter.php?chapter='.$postManager->getPreviousChapterNumber($_GET['chapter']).'">&larr; Précédent</a>';
        }

        if($postManager->isChapterExist($postManager->getNextChapterNumber($_GET['chapter'])))
        {
            $content .= '<a href="http://localhost/P4/Views/front/chapter.php?chapter='.$postManager->getNextChapterNumber($_GET['chapter']).'">   Suivant &rarr;</a>';
        }
        $content .= '</p>';

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
            $content .= '<p><a class="link-standard" href="register.php">Inscrivez-vous</a> ou <a href="login.php">connectez-vous</a> pour nous laisser un commentaire :)</p>';
        }

        $content .= '<p class="limiter">Commentaires :</p>';

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

            $content .= '<div class="comment">';
            $content .= '<p class="comment-description"><span class="comment-author">'.$commentAuthor->name().'</span> <span class="comment-date">a écrit le '.$commentDate->format('d/m/Y à H:i:s').' :</span></p>'; 
            $content .= '<p class="comment-content">'.$comments[$i]->content().'</p>';

            if(isset($_SESSION['id']) && !$commentManager->isUserTheCommentAuthor($_SESSION['id'], $comments[$i]->id()) && !$appreciationManager->isAppreciationExist($_SESSION['id'], $comments[$i]->id()))
            {
                $content .= '<p>';
                $content .= '<a class="link-standard" href="leaveAppreciation.php?appreciation=like&id='.$comments[$i]->id().'">Aimer</a>';
                $content .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                $content .= '<a class="link-standard" href="leaveAppreciation.php?appreciation=report&id='.$comments[$i]->id().'">Signaler</a>';
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
                    $content .= '<p>Vous avez déjà signalé ce commentaire</p>';
                }
                $content .= '<p><a class="link-standard" href="leaveAppreciation.php?appreciation=reset&id='.$comments[$i]->id().'">Retirer votre appréciation</a></p>';
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
            
            $content .= '<p class="comment-counts">';
            $content .= '<span>'.$comments[$i]->likes().' likes</span>';
            $content .= '<span>'.$comments[$i]->reports().' reports</span>';
            $content .= '</p>';
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