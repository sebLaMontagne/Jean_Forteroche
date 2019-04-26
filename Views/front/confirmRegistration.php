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
        
        if($user != null && $user->isActivated() == 1)
        {
            $content .= '<p>Votre compte a déjà été activé</p>';
        }
        elseif($user != null && $user->isActivated() == 0)
        {
            if($user->tokenExpiration() > time())
            {
                $userManager->confirmAccount($user);
                $content .= '<p>Votre compte a été activé avec succès</p>';
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
            header('Location:home');
            exit();
        }
    }
    else
    {
        header('Location:home');
        exit();
    }
    
    $content .= '</div>';
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}