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
    
    $display = '<h2>Liste des utilisateurs</h2>';
    
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
            $display .= '<p><a href="confirmBanUser.php?action=ban&id='.$users[$i]->id().'&redirect=usersList.php">Bannir</a></p>';
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
    }
    
    echo $display;
    
    var_dump($users);
}