<?php
try
{
    require_once('../autoloader.php');
    
    $title = 'Billet simple pour l\'Alaska - Accueil';
    $content = ''; 
    
    if(!empty($_GET['mail']))
    {
        $content .= '<form method="post" action="register.php?mail='.$_GET['mail'].'" id="register-form">';
    }
    else
    {
        $content = '<form method="post" action="register.php" id="register-form">';
    }
    
    $content .= '<label for="pseudo"></label><input type="text" name="name" id="pseudo" placeholder="Entrez votre pseudonyme" required />';
    $content .= '<div id="username-feedback">';
    $content .= '<p id="username-length">*Le pseudo doit contenir au moins 6 caractères</p>';
    $content .= '</div>';

    if(empty($_GET['mail']))
    {
        $content .= '<label for="email"></label><input type="email" name="email" id="email" placeholder="Entrez votre email" required />';
    }
    
    $content .= '<label for="password"></label><input type="password" name="password" id="password" placeholder="Entrez votre mot de passe" required />';
    $content .= '<label for="password-confirm"></label><input type="password" name="password-confirm" id="password-confirm" placeholder="Confirmez votre mot de passe" required />';
    
    $content .= '<div class="password-feedback">';
    $content .= '<p id="password-lowercase">*Le mot de passe doit contenir au moins un caractère minuscule</p>';
    $content .= '<p id="password-uppercase">*Le mot de passe doit contenir au moins un caractère majuscule</p>';
    $content .= '<p id="password-number">*Le mot de passe doit contenir au moins un chiffre</p>';
    $content .= '<p id="password-length">*Le mot de passe doit contenir au moins 8 caractères</p>';
    $content .= '<p id="password-repeat">*Les deux mots de passe doivent être identiques</p>';
    $content .= '</div>';
    
    if(!empty($_GET['mail']))
    {
        $content .= '<input type="submit" value="mettre à jour" id="confirm" />';
    }
    else
    {
        $content .= '<input type="submit" value="confirmer l\'inscription" id="confirm" />';
    }
    
    $content .= '</form>';

    $userManager = new UserManager();

    if(!empty($_POST) && empty($_GET))
    {   
        if($userManager->isUsernameFree($_POST['name']))
        {
            $content .= '<p>Pseudo valide</p>';
        }
        else
        {
            $content .= '<p>Le pseudo est déjà pris en base de données</p>';
        }

        if($userManager->isEmailFree($_POST['email']))
        {
            $content .= '<p>Email valide</p>';
        }
        else
        {
            $content .= '<p>L\'email est déjà pris en base de données</p>';
        }

        if($userManager->isUsernameFree($_POST['name']) && $userManager->isEmailFree($_POST['email']))
        {
            $content .= '<p>Inscription valide</p>';
            $content .= '<p>Un email de confirmation va vous être envoyé</p>';
            $userManager->addUser($_POST['name'], $_POST['password'], $_POST['email']);
        }
        else
        {
            $content .= '<p>Inscription invalide</p>';
        }
    }

    if(!empty($_GET) && !empty($_POST) && isset($_GET['mail']))
    {
        if(filter_var($_GET['mail'], FILTER_VALIDATE_EMAIL))
        {
            $user = $userManager->getUserByEmail($_GET['mail']);

            if($user != null)
            {
                $userManager->updateUserLogins($user, $_POST['name'], $_POST['password']);
                $content .= 'votre compte a été mis à jour';
            }
            else
            {
                $content .= '<p>L\'email ne correspond à aucun utilisateur</p>';
            }
        }
    }

    $content .= '<script src="../../Ressources/js/register-form.js"></script>';
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}