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
    $userManager = new UserManager();
    $user = $userManager->getUserByLogins($_POST['name'], $_POST['password']);
    
    var_dump($_POST);
    var_dump($user);
    
    if($user == null)
    {
        echo '<p>Identifiants incorrects</p>';
    }
    elseif(!$user->isActivated())
    {
        echo '<p>Compte existant mais non activé</p>';
    }
    else
    {
        $_SESSION['pseudo'] = $user->name();
        header('Location: home.php');
    }
}

?>