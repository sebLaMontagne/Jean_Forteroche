<?php

try
{
    require_once('../autoloader.php');
    
    if(!empty($_POST))
    {

        $userManager = new UserManager();
        $user = $userManager->getUserByLogins($_POST['name'], $_POST['password']);

        if($user == null)
        {
            echo '<p>Identifiants incorrects</p>';
        }
        elseif($user->isBanned())
        {
            echo '<p>Votre compte a été banni. Vous ne pouvez qu\'implorer la clémence de notre maître et seigneur à tous</p>';
        }
        elseif(!$user->isActivated())
        {
            echo '<p>Compte existant mais non activé</p>';
        }
        else
        {
            $_SESSION['pseudo'] = $user->name();
            $_SESSION['email'] = $user->email();
            $_SESSION['isAdmin'] = $user->isAdmin();
            $_SESSION['id'] = $user->id();
            header('Location: home.php');
            exit();
        }
    }
    else
    {
        $title = 'Billet simple pour l\'Alaska - Connection';
        require('template.php');
        
        $content  = '';
        $content .= '<p>Veuillez entrer vos identifiants</p>';
        $content .= '<form method="post" action="login.php">';
        $content .= '<label for="login-username"></label><input id="login-username" type="text" name="name" placeholder="Veuillez entrer votre pseudonyme" required />';
        $content .= '<label for="login-password"></label><input id="login-password" type="password" name="password" placeholder="Veuillez entrer votre mot de passe" required />';
        $content .= '<input type="submit" value="Se connecter" />';
        $content .= '</form>';
        $content .= '<p><a href="reset.php">Avez-vous oublié vos identifiants</a></p>';
        
        echo $content;
    }
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}