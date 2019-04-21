<?php

try
{
    $title = 'Billet simple pour l\'Alaska - Accueil';
    require('template.php');

    echo '
    <p>Vous pouvez me contacter en utilisant le formulaire çi-dessous :</p>
    <form method="post" action="contact.php">';

    if(!isset($_SESSION['pseudo']) && !isset($_SESSION['email']))
    {
        echo '
        <input type="text" name="name" placeholder="Entrez votre nom" required />
        <input type="email" name="mail" placeholder="Entrez votre email" required />';
    }

    echo'
    <textarea name="message" placeholder="Entrez votre message" required></textarea>
    <input type="submit" value="envoyer" />
    </form>';

    if(isset($_POST['message']))
    {
        $to = 'juniorwebdesign27@gmail.com';
        $message = $_POST['message'];

        if(isset($_SESSION['pseudo']) && isset($_SESSION['email']))
        {
            $subject = 'Message concernant Billet simple pour l\'Alaska de'.$_SESSION['pseudo'];
            $from = $_SESSION['email'];
        }
        elseif(isset($_POST['name']) && isset($_POST['mail']))
        {
            $subject = 'Message concernant Billet simple pour l\'Alaska de'.$_POST['name'];
            $from = $_POST['mail'];
        }

        $headers = 'From: '.$from;
        mail($to, $subject, $message, $headers);
        echo '<p>Votre message a été envoyé :)</p>';
    }
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}