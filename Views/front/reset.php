<?php

try
{
    require_once('../autoloader.php');
    
    $title = 'Réinitialisation du compte';
    
    $content  = '';
    $content .= '<form method="post" action="reset.php">';
    $content .= '<label for="email"></label><input id="email" type="email" name="email" placeholder="Veuillez entrer votre email" required />';
    $content .= '<input type="submit" value="Réinitialiser" />';
    $content .= '</form>';

    if(!empty($_POST))
    { 
        $userManager = new UserManager();
        $user = $userManager->getUserByEmail($_POST['email']);

        if($user == null)
        {
            $content .= '<p>Email inexistant</p>';
        }
        else
        {
            $content .= '<p>Email existant</p>';
            $content .= '<p>Un email contenant un lien de réinitialisation va vous être envoyé à l\'adresse entrée</p>';
          
          	$header = "From: \"Jean Forteroche\"<jean.forteroche@jeanforteroche.fr>\n";
            $header.= "Reply-to: \"Jean Forteroche\"<jean.forteroche@jeanforteroche.fr>\n";
            $header.= "MIME-Version: 1.0\n";
            $header.= "Content-Type: text/html;";

            mail($_POST['email'], 'réinitialisation de compte', '<html><head></head><body><p>Voici votre lien de réinitialisation : <a href="http://jeanforteroche.fr/Views/front/register.php?mail='.$_POST['email'].'">réinitialiser</a></p></body></html>', $header);
        }
    }
    
    include('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}