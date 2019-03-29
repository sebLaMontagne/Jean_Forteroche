<?php

$title = 'Email validé';
require('template.php');

if(isset($_GET) && !empty($_GET['token']))
{
    $userManager = new UserManager();
    $a = $userManager->confirmUser($_GET['token']);
    echo'<p>Votre compte a bien été validé</p>';
    
}
else
{
    echo'<p>Erreur dans votre lien d\'activation</p>';
}
