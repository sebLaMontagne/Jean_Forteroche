<?php

//faire passer en get l'id de l'utilisateur à ban
//protéger l'accès à la page depuis l'URL
//Ecrire une confirmation de ban
//Dans tous les cas, on revient à la page de gestion des commentaires

//Le statut de ban est un booleen passant à true ou false (pas une suppression de l'utilisateur dans la bdd).
//Faire en sorte sur la page de login d'interdire la connexion à un utilisateur ban

$title = 'Gestion des commentaires';
require('template.php');

$_SESSION['refresh'] = 1;
unset($_SESSION['refresh']);

if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
{
    header("location:javascript://history.go(-1)");
}
else
{
    if(isset($_GET['action']) && ($_GET['action'] == 'ban' || $_GET['action'] == 'unban') && isset($_GET['id']) && intval($_GET['id']) > 0 && isset($_GET['redirect']))
    {
        $userManager = new UserManager();
        
        if($_GET['action'] == 'ban')
        {
            $userManager->banUser($_GET['id']);
            header('Location: '.$_GET['redirect']);
        }
        elseif($_GET['action'] == 'unban')
        {
            $userManager->unbanUser($_GET['id']);
            header('Location: '.$_GET['redirect']);
        }
    }
    else
    {
        header('Location: commentsList.php');
    }
}