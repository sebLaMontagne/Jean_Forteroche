<?php

try
{
    require_once('../autoloader.php');
    
    $title = 'Validation de compte';
    $content  = '<div class="content filler">';
    
    if(!empty($_GET['token']) && preg_match('#^([0-9]){12}$#',$_GET['token']))
    {
        $userManager = new UserManager();
        $user = $userManager->getUserByToken($_GET['token']);

        if($user->tokenExpiration() > time())
        {   
            if($user->isActivated() == 0)
            {
                $userManager->confirmAccount($user);
                $content .= '<p>Votre compte a été activé avec succès</p>';
            }
            else
            {
                $content .= '<p>Votre compte a déjà été activé</p>';
            }
        }
        else
        {
            $content .= '<p>Lien d\'activation expiré</p>';
            $userManager->renewActivationLink($user);
            $content .= '<p>Un nouveau lien d\'activation a été envoyé sur votre adresse mail</p>';
        }
    }
    else
    {
        $content .= '<p>Lien d\'activation invalide</p>';
    }
    
    $content .= '</div>';
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}