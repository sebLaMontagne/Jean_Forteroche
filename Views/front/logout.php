<?php

try
{
    require_once('../autoloader.php');
    
    $title = 'Billet simple pour l\'Alaska - Déconnection';
    
    $content  = '';
    $content .= '<p>Etes-vous sûr de vouloir vous déconnecter ?</p>';
    $content .= '<form method="post" action="logout.php">';
    $content .= '<label for="logout-yes">Oui</label><input id="logout-yes" type="radio" name="logout" value="yes" />';
    $content .= '<label for="logout-no">Non</label><input id="logout-no" type="radio" name="logout" value="no" />';
    $content .= '<input type="submit" value="confirmer" />';
    $content .= '</form>';

    if(isset($_POST['logout']) && $_POST['logout'] == 'yes')
    {
        session_destroy();
        header('Location:home.php');
        exit();
    }
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}

?>