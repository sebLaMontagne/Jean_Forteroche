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
    
    $display  = '<h3>Liste des utilisateurs</h3>';
    $display .= '<p>Sur cette page, vous pouvez consulter la liste des utilisateurs et leur statut actuel, et éventuellement en bannir ou en gracier</p>';
    
    $sorter  = '<p>Montrer : </p>';
    $sorter .= '<form action="UsersList.php" method="get">';
    $sorter .= '<select name="show">';
    $sorter .= '<option value="all">tous les utilisateurs</option>';
    $sorter .= '<option value="admins">les administrateurs</option>';
    $sorter .= '<option value="banned">les bannis</option>';
    $sorter .= '<option value="users">les simples usagers</option>';
    $sorter .= '</select>';
    $sorter .= '<input type="submit" value="Confirmer"/>';
    $sorter .= '</form>';
    
    echo $sorter;
    
    if(empty($_GET['show']) || ($_GET['show'] != 'all' && $_GET['show'] != 'admins' && $_GET['show'] != 'banned' && $_GET['show'] != 'users'))
    {
        header('Location:admin.php');
    }
    else
    {
        $users = $userManager->getAllUsers($_GET['show']);
    }
    
    for($i = 0; $i < count($users); $i++)
    {
        $display .= '<p>'.$users[$i]->name();
        
        if($users[$i]->isBanned())
        { 
            $display .= ' (banni)</p>';
            $display .= '<p><a href="confirmBanUser.php?action=unban&id='.$users[$i]->id().'&redirect=usersList.php?show='.$_GET['show'].'">Débannir</a></p>';
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
            $display .= '<p><a href="confirmBanUser.php?action=ban&id='.$users[$i]->id().'&redirect=usersList.php?show='.$_GET['show'].'">Bannir</a></p>';
        }
        
        $display .= '<p><a href="commentsList.php?id='.$users[$i]->id().'&sortedBy=date">Voir tous les commentaires de cet utilisateur</a></p>';
        $display .= '<hr />';
    }
    
    echo $display;
}