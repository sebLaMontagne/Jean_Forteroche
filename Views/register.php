<?php

$title = 'Billet simple pour l\'Alaska - Accueil';
$content = ''; 
require('template.php');
?>

<form method="post" action="register.php" id="register-form">

    <label for="pseudo"></label>
    <input type="text" name="name" id="pseudo" placeholder="Entrez votre pseudonyme" required />

    <div id="username-feedback">
        <p id="username-length">*Le pseudo doit contenir au moins 6 caractères</p>
    </div>

    <label for="email"></label>
    <input type="email" name="email" id="email" placeholder="Entrez votre email" required />

    <div class="email-feedback"></div>

    <label for="password"></label>
    <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe" required />
    <label for="password-confirm"></label>
    <input type="password" name="password-confirm" id="password-confirm" placeholder="Confirmez votre mot de passe" required />

    <div class="password-feedback">
        <p id="password-lowercase">*Le mot de passe doit contenir au moins un caractère minuscule</p>
        <p id="password-uppercase">*Le mot de passe doit contenir au moins un caractère majuscule</p>
        <p id="password-number">*Le mot de passe doit contenir au moins un chiffre</p>
        <p id="password-length">*Le mot de passe doit contenir au moins 8 caractères</p>
        <p id="password-repeat">*Les deux mots de passe doivent être identiques</p>
    </div>

    <input type="submit" value="confirmer l'inscription" id="confirm" />
</form>

<?php

if(!empty($_POST))
{
    $user = new User($_POST);
    $userManager = new UserManager();
    
    if($userManager->isUsernameFree($user))
    {
        echo '<p>Pseudo valide</p>';
    }
    else
    {
        echo '<p>Le pseudo est déjà pris en base de données</p>';
    }
    
    if($userManager->isEmailFree($user))
    {
        echo '<p>Email valide</p>';
    }
    else
    {
        echo '<p>L\'email est déjà pris en base de données</p>';
    }
    
    if($userManager->isUsernameFree($user) && $userManager->isEmailFree($user))
    {
        echo '<p>Inscription valide';
        echo '<br />Un email de confirmation va vous être envoyé</p>';
        $userManager->addUser($user);
    }
    else
    {
        echo '<p>Inscription invalide</p>';
    }

    //send a succès view to user
    
}
?>

<script src="../Ressources/js/register-form.js"></script>

