<?php

try
{
    $title = 'Validation de compte';
    require('template.php');

    if(!empty($_GET['token']) && preg_match('#^([0-9]){12}$#',$_GET['token']))
    {
        $userManager = new UserManager();
        $user = $userManager->getUserByToken($_GET['token']);

        if($user->tokenExpiration() > time())
        {   
            if($user->isActivated() == 0)
            {
                $userManager->confirmAccount($user);
                echo'<p>Votre compte a été activé avec succès</p>';
            }
            else
            {
                echo'<p>Votre compte a déjà été activé</p>';
            }
        }
        else
        {
            echo'<p>Lien d\'activation expiré</p>';
            $userManager->renewActivationLink($user);
            echo'<p>Un nouveau lien d\'activation a été envoyé sur votre adresse mail</p>';
        }
    }
    else
    {
        echo '<p>Lien d\'activation invalide</p>';
    }
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}