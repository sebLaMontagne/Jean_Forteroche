<?php

try
{
    require_once('../autoloader.php');
    
    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);
    
    $title = 'Liste des utilisateurs';

    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
        exit();
    }
    else
    {
        $userManager = new UserManager();

        $content  = '<div class="content" style="text-align: center;">';
        $content .= '<p>Sur cette page, vous pouvez consulter la liste des utilisateurs et leur statut actuel, et éventuellement en bannir ou en gracier.</p>';

        $content .= '<p>Montrer : </p>';
        $content .= '<form action="UsersList.php" method="get">';
        $content .= '<select name="show">';
        $content .= '<option value="all">tous les utilisateurs</option>';
        $content .= '<option value="admins">les administrateurs</option>';
        $content .= '<option value="banned">les bannis</option>';
        $content .= '<option value="users">les simples usagers</option>';
        $content .= '</select>';
        $content .= '<input class="filter-confirm" type="submit" value="Confirmer"/>';
        $content .= '</form>';

        if(empty($_GET['show']) || ($_GET['show'] != 'all' && $_GET['show'] != 'admins' && $_GET['show'] != 'banned' && $_GET['show'] != 'users'))
        {
            header('Location:admin.php');
            exit();
        }
        else
        {
            $users = $userManager->getAllUsers($_GET['show']);
        }

        for($i = 0; $i < count($users); $i++)
        {
            $content .= '<div class="comment">';
            $content .= '<p><span class="comment-author">'.$users[$i]->name().'</span>';

            if($users[$i]->isBanned())
            { 
                $content .= ' (banni)</p>';
                $content .= '<p><a class="link-standard" href="confirmBanUser.php?action=unban&id='.$users[$i]->id().'&redirect=usersList.php?show='.$_GET['show'].'">Débannir</a></p>';
            }
            elseif($users[$i]->isAdmin())
            {
                $content .= ' (administrateur)</p>';
            }
            elseif(!$users[$i]->isActivated())
            {
                $content .= ' (usager non activé)</p>';
            }
            else
            {
                $content .= ' (simple usager)</p>';
                $content .= '<p><a class="link-standard" href="confirmBanUser.php?action=ban&id='.$users[$i]->id().'&redirect=usersList.php?show='.$_GET['show'].'">Bannir</a></p>';
            }

            $content .= '<p><a class="link-standard" href="commentsList.php?id='.$users[$i]->id().'&sortedBy=date">Voir tous les commentaires de cet utilisateur</a></p>';
            $content .= '</div>';
        }
        
        $content .= '</div>';
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}