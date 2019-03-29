<?php

$title = 'Billet simple pour l\'Alaska - Connection';
require('template.php');

?>

<p>Veuillez entrer vos identifiants</p>
<form method="post" action="login.php">
    <label for="login-username"></label><input id="login-username" type="text" name="name" placeholder="Veuillez entrer votre pseudonyme" required />
    <label for="login-password"></label><input id="login-password" type="password" name="password" placeholder="Veuillez entrer votre mot de passe" required />
    <input type="submit" value="Se connecter" />
</form>

<?php 

if(!empty($_POST))
{
    $user = new User($_POST);
    $userManager = new UserManager();
    $userdata = $userManager->loginUser($user);

    if($userdata == false)
    {
        echo '<p>Identifiants incorrects</p>';
    }
    elseif($userdata['user_activation'] == 0)
    {
        echo '<p>Ce compte existe mais n\'a pas encore été activé</p>';
    }
    else
    {
        $_SESSION['pseudo'] = $userdata['user_name'];
        header('Location:home.php');
    }
}

?>