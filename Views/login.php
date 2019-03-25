<?php

$title = 'Billet simple pour l\'Alaska - Connection';
require('template.php');

?>

<p>Veuillez entrer vos identifiants</p>
<form method="post" action="login.php">
    <label for="login-username"></label><input id="login-username" type="text" name="username" placeholder="Veuillez entrer votre pseudonyme" required />
    <label for="login-password"></label><input id="login-password" type="password" name="userpassword" placeholder="Veuillez entrer votre mot de passe" required />
    <input type="submit" value="Se connecter" />
</form>

<?php 

if(!empty($_POST))
{
    $userManager = new UserManager();
    $userdata = $userManager->getUser($_POST['username'], $_POST['userpassword']);
    
    if($userdata == false)
    {
        echo '<p>Mauvais identifiants</p>';
    }
    else
    {
        $_SESSION['pseudo'] = $userdata['user_name'];
        header('Location:home.php');
    }
    
    var_dump($_POST);
    var_dump($userdata);
}

?>