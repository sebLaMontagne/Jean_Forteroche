<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Billet simple pour l\'Alaska - Gestion des commentaires';
    

    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {
        $postManager = new PostManager();
        $commentManager = new CommentManager();
        $userManager = new UserManager();

        $content  = '<div class="content filler" style="text-align: center;">';
        $content .= '<p>Ici, vous pouvez supprimer des commentaires ou bannir des utilisateurs pour leur mauvais comportement et filtrer les commentaires en fonction de plusieurs critères</p>';
        
        $content .= '<div style="text-align: center;">';
        $content .= '<p>Trier les commentaires par : </p>';
        $content .= '<form action="filterComment" method="post">';
        $content .= '<select name="sortedBy">';
        $content .= '<option value="date">date</option>';
        $content .= '<option value="likes">nombre de likes</option>';
        $content .= '<option value="reports">nombre de reports</option>';
        $content .= '</select>';
        $content .= '<input class="filter-confirm" type="submit" value="Confirmer"/>';
        $content .= '</form>';
        $content .= '</div>';

        if(empty($_GET['sortedBy']) || ($_GET['sortedBy'] != 'reports' && $_GET['sortedBy'] != 'likes' && $_GET['sortedBy'] != 'date'))
        {
            header('Location:admin');
            exit();
        }
        else
        {
            if(empty($_GET['id']))
            {
                $comments = $commentManager->getAllCommentsSortedBy($_GET['sortedBy']);
            }
            elseif(!empty($_GET['id']) && $userManager->isUserExist($_GET['id']))
            {
                $comments = $commentManager->getAllUserCommentsSortedBy($_GET['id'], $_GET['sortedBy']);
            }
            else
            {
                header('Location:admin');
                exit();
            }
        }

        for($i=0; $i < count($comments); $i++)
        {
            $commentDate = new DateTime($comments[$i]->date());
            $commentAuthor = $userManager->getUserById($comments[$i]->userId());
            
            $content .= '<div class="comment">';

          	if($commentAuthor != null)
            {
              	if($commentAuthor->isBanned())
            	{
                	$content .= '<p class="comment-description"><span class="comment-author">'.$commentAuthor->name().'</span> (Utilisateur banni)';
            	}
            	else
            	{
                	$content .= '<p class="comment-description"><span class="comment-author">'.$commentAuthor->name().'</span>';
            	}
              
              	$content .= ' a écrit le <span class="comment-date">'.$commentDate->format('d/m/Y à H:i:s').'</span>';
            	$content .= ' sur le chapitre '.$postManager->getChapterByPostId($comments[$i]->postId()).' :</p>';
            	$content .= '<hr />';
            	$content .= '<p>'.$comments[$i]->content().'</p>';
            	$content .= '<hr />';
            	$content .= '<p>'.$comments[$i]->likes().' likes '.$comments[$i]->reports().' reports</p>';
            	$content .= '<hr />';
            	$content .= '<p class ="comment-counts">';
            	$content .= '<a class="link-standard" href="confirmCommentSuppression-'.$comments[$i]->id().'-'.$_GET['sortedBy'].'">supprimer le commentaire</a>';

                if($commentAuthor->isBanned())
                {
                    $content .= '<a class="link-standard" href="confirmBanUser-unban-'.$commentAuthor->id().'-commentsList-'.$_GET['sortedBy'].'">Débannir</a>';
                }
                elseif($commentAuthor->isAdmin())
                {

                }
                else
                {
                    $content .= '<a class="link-standard" href="confirmBanUser-ban-'.$commentAuthor->id().'-commentsList-'.$_GET['sortedBy'].'">bannir l\'utilisateur</a>';
                }

                $content .= '</p>';
                $content .= '</div>';
            }    
        }
        
        $content .= '<div>';
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}