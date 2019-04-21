<?php

try
{
    $title = 'Réinitialisation du compte';
    include('template.php');

    ?>

    <form method="post" action="reset.php">
        <label for="email"></label><input id="email" type="email" name="email" placeholder="Veuillez entrer votre email" required />
        <input type="submit" value="Réinitialiser" />
    </form>

    <?php

    if(!empty($_POST))
    { 
        $userManager = new UserManager();
        $user = $userManager->getUserByEmail($_POST['email']);

        if($user == null)
        {
            echo '<p>Email inexistant</p>';
        }
        else
        {
            echo '<p>Email existant</p>';
            echo '<p>Un email contenant un lien de réinitialisation va vous être envoyé à l\'adresse entrée</p>';
          
          	$header = "From: \"Jean Forteroche\"<jean.forteroche@jeanforteroche.fr>\n";
            $header.= "Reply-to: \"Jean Forteroche\"<jean.forteroche@jeanforteroche.fr>\n";
            $header.= "MIME-Version: 1.0\n";
            $header.= "Content-Type: text/html;";

            mail($_POST['email'], 'réinitialisation de compte', '<html><head></head><body><p>Voici votre lien de réinitialisation : <a href="http://jeanforteroche.fr/Views/front/register.php?mail='.$_POST['email'].'">réinitialiser</a></p></body></html>', $header);
        }
    } 
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}

?>