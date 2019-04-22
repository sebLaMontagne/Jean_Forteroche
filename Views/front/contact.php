<?php

try
{
    require_once('../autoloader.php');
    
    $title = 'Billet simple pour l\'Alaska - Accueil';
    
    $content  = '';
    $content .= '<p>Vous pouvez me contacter en utilisant le formulaire çi-dessous :</p>';
    $content .= '<form method="post" action="contact.php">';

    if(!isset($_SESSION['pseudo']) && !isset($_SESSION['email']))
    {
        $content .= '<input type="text" name="name" placeholder="Entrez votre nom" required />';
        $content .= '<input type="email" name="mail" placeholder="Entrez votre email" required />';
    }
    
    $content .= '<textarea name="message" placeholder="Entrez votre message" required></textarea>';
    $content .= '<input type="submit" value="envoyer" />';
    $content .= '</form>';

    if(isset($_POST['message']))
    {
        if(isset($_SESSION['pseudo']) && isset($_SESSION['email']))
        {
            $subject = 'Message concernant Billet simple pour l\'Alaska de '.$_SESSION['pseudo'];
            $from = $_SESSION['email'];
        }
        elseif(isset($_POST['name']) && isset($_POST['mail']))
        {
            $subject = 'Message concernant Billet simple pour l\'Alaska de '.$_POST['name'];
            $from = $_POST['mail'];
        }
        
        $header = "From: ".$from."\n";
        $header.= "Reply-to: \"Jean Forteroche\"<jean.forteroche@jeanforteroche.fr>\n";
        $header.= "MIME-Version: 1.0\n";
        $header.= "Content-Type: text/plain;";
        
        mail('seb-roche@orange.fr', $subject, $_POST['message'], $header);
        $content .= '<p>Votre message a été envoyé :)</p>';
    }
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}