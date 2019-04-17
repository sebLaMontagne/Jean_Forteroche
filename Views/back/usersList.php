<?php

$title = 'Liste des utilisateurs';
require('template.php');

$_SESSION['refresh'] = 1;
unset($_SESSION['refresh']);

if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
{
    header("location:javascript://history.go(-1)");
}
else
{
    $userManager = new UserManager();
    $users = $userManager->getAllUsers();
    
    $display  = '<h3>Liste des utilisateurs</h3>';
    $display .= '<p>Sur cette page, vous pouvez consulter la liste des utilisateurs et leur statut actuel, et éventuellement en bannir ou en gracier</p>';
    
    for($i = 0; $i < count($users); $i++)
    {
        $display .= '<p>'.$users[$i]->name();
        
        if($users[$i]->isBanned())
        { 
            $display .= ' (banni)</p>';
            $display .= '<p><a href="confirmBanUser.php?action=unban&id='.$users[$i]->id().'&redirect=usersList.php">Débannir</a></p>';
        }
        elseif($users[$i]->isAdmin())
        {
            $display .= ' (administrateur)</p>';
        }
        elseif(!$users[$i]->isActivated())
        {
            $display .= ' (usager non activé)</p>';
        }
        else
        {
            $display .= ' (simple usager)</p>';
            $display .= '<p><a href="confirmBanUser.php?action=ban&id='.$users[$i]->id().'&redirect=usersList.php">Bannir</a></p>';
        }
        
        $display .= '<p><a href="commentsList.php?id='.$users[$i]->id().'">Voir tous les commentaires de cet utilisateur</a></p>';
        $display .= '<hr />';
    }
    
    echo $display;
}