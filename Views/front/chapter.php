<?php

$title = 'Billet simple pour l\'Alaska - Accueil';
require('template.php');

$postManager = new PostManager();

if(isset($_GET) && !empty($_GET['chapter']) && $postManager->isChapterExist($_GET['chapter']))
{
    $selectedPost = $postManager->getPost($_GET['chapter']);
    $postDate = new DateTime($selectedPost->Date());
    
    $userManager = new UserManager();
    $postAuthor = $userManager->getUserById($selectedPost->authorId());
    
    echo
    '<h3>Chapitre '.$selectedPost->chapterNumber().' : '.$selectedPost->Title().'</h3>'.$postManager->decode($selectedPost->Content()).
    '<p>Publié le '.$postDate->format('d/m/Y à H:i:s').' par '.$postAuthor->name().'</p>';

    
    if($postManager->isChapterExist($postManager->getPreviousChapterNumber($_GET['chapter'])))
    {
         echo '<p><a href="http://localhost/P4/Views/front/chapter.php?chapter='.$postManager->getPreviousChapterNumber($_GET['chapter']).'">Précédent</a>';
    }
    
    if($postManager->isChapterExist($postManager->getNextChapterNumber($_GET['chapter'])))
    {
         echo '<p><a href="http://localhost/P4/Views/front/chapter.php?chapter='.$postManager->getNextChapterNumber($_GET['chapter']).'">   Suivant</a>';
    }
    
    //Formulaire commentaire
    
    if(!empty($_SESSION['pseudo']))
    {
        echo
        '<form method="post" action="chapter.php?chapter='.$_GET['chapter'].'">
            <textarea placeholder="Laissez-nous un commentaire" name="comment"></textarea>
            <input type="submit" value="commenter" />
         </form>';
    }
    else
    {
        echo '<p><a href="register.php">Inscrivez-vous</a> ou <a href="login.php">connectez-vous</a> pour nous laisser un commentaire :)</p>';
    }
    
    echo '<p>Commentaires :</p>';
    
    //Enregistrement commentaire
    
    $commentManager = new CommentManager();
    
    if(!empty($_POST['comment']))
    {   
        $commentManager->saveComment($selectedPost->id(), $_SESSION['id'], $_POST['comment']);
    }
    
    //Affichage commentaires
    
    $comments = $commentManager->getPostComments($selectedPost);
    
    for($i=0; $i < count($comments); $i++)
    {
        $commentDate = new DateTime($comments[$i]->date());
        $commentAuthor = $userManager->getUserById($comments[$i]->userId());
        
        $display  = '';
        $display .= '<div>';
        $display .=     '<p>'.$commentAuthor->name().'</p>';
        $display .=     '<p>a écrit le '.$commentDate->format('d/m/Y à H:i:s').' :</p>';
        $display .=     '<p>'.$comments[$i]->content().'</p>';
        
        //Donner une option pour reset l'appreciation (met isLike et isReport à 0)
        
        $appreciationManager = new AppreciationManager();
        
        if(isset($_SESSION['id']) && !$commentManager->isUserTheCommentAuthor($_SESSION['id'], $comments[$i]->id()) && !$appreciationManager->isAppreciationExist($_SESSION['id'], $comments[$i]->id()))
        {
            $display .= '<p>';
            $display .=     '<a href="leaveAppreciation.php?appreciation=like&id='.$comments[$i]->id().'">Aimer</a>';
            $display .=     '&emsp;&emsp;';
            $display .=     '<a href="leaveAppreciation.php?appreciation=report&id='.$comments[$i]->id().'">Signaler</a>';
            $display .= '</p>';
        }
        elseif(isset($_SESSION['id']) && !$commentManager->isUserTheCommentAuthor($_SESSION['id'], $comments[$i]->id()) && $appreciationManager->isAppreciationExist($_SESSION['id'], $comments[$i]->id()))
        {
            if($appreciationManager->AppreciationIsLike($_SESSION['id'], $comments[$i]->id()))
            {
                $display .= '<p>Vous avez déjà liké ce commentaire</p>';
            }
            elseif($appreciationManager->AppreciationIsReport($_SESSION['id'], $comments[$i]->id()))
            {
                $display .= '<p>Vous avez déjà report ce commentaire</p>';
            }
            $display .= '<p><a href="leaveAppreciation.php?appreciation=reset&id='.$comments[$i]->id().'">Retirer votre appréciation</a></p>';
        }
        elseif(isset($_SESSION['id']) && $commentManager->isUserTheCommentAuthor($_SESSION['id'], $comments[$i]->id()))
        {
            $display .= '<p>Vous ne pouvez pas laisser d\'appreciation sur vos commentaires</p>';
        }
        else
        {
            $display .= '<p><a href="register.php">Inscrivez-vous</a> ou <a href="login.php">connectez-vous</a> pour laisser une appreciation</p>';
        }
        
        //Affichage likes / reports
        
        $display .= '<p>'.$appreciationManager->countCommentAppreciations($comments[$i]->id(), 'likes').' likes</p>';
        $display .= '<p>'.$appreciationManager->countCommentAppreciations($comments[$i]->id(), 'reports').' reports</p>';
        $display .= '</div>';
        
        echo $display;
    }
}
else
{
    echo '<p>Ce chapitre n\'existe pas</p>';
}