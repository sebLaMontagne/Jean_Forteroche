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
    '<h3>Chapitre '.$selectedPost->chapterNumber().' : '.$selectedPost->Title().'</h3>
     <p>'.$selectedPost->Content().'</p>
     <p>Publié le '.$postDate->format('d/m/Y à H:i:s').' par '.$postAuthor->name().'</p>';
    
    /*
    Changer la gestion des chapitre suivant/précédent pour afficher
    
    if($postManager->isChapterExist($_GET['chapter']-1))
    {
        echo '<a href="http://localhost/P4/Views/front/chapter.php?chapter='.($_GET['chapter']-1).'">Chapitre précédent</a>';
    }
    if($postManager->isChapterExist($_GET['chapter']+1))
    {
        echo '<a href="http://localhost/P4/Views/front/chapter.php?chapter='.($_GET['chapter']+1).'">Chapitre suivant</a>';
    }
    */
    
    echo '<p>Next valid chapter : '.$postManager->getNextChapterNumber($_GET['chapter']).'</p>';
    
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
    
    
    $commentManager = new CommentManager();
    
    if(!empty($_POST['comment']))
    {   
        $commentManager->saveComment($selectedPost->id(), $_SESSION['id'], $_POST['comment']);
    }
    
    $comments = $commentManager->getPostComments($selectedPost);
    for($i=0; $i < count($comments); $i++)
    {
        $commentDate = new DateTime($comments[$i]->date());
        $commentAuthor = $userManager->getUserById($comments[$i]->userId());
        
        echo
        '<div>
            <p>'.$commentAuthor->name().'</p>
            <p>a écrit le '.$commentDate->format('d/m/Y à H:i:s').' :</p>
            <p>'.$comments[$i]->content().'</p>
            <p><a href="leaveAppreciation.php?appreciation=like&id='.$comments[$i]->id().'">Aimer</a>   <a  href="leaveAppreciation.php?appreciation=report&id='.$comments[$i]->id().'">Signaler</a>
         </div>';
        //faire passer l'id du post
    }
}
else
{
    echo '<p>Ce chapitre n\'existe pas</p>';
}