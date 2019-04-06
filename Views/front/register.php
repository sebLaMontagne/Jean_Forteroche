<?php

$title = 'Billet simple pour l\'Alaska - Accueil';
$content = ''; 
require('template.php');
?>

<form method="post" action="register.php<?php if(isset($_GET['mail'])){echo'?mail='.$_GET['mail'];} ?>" id="register-form">

    <label for="pseudo"></label>
    <input type="text" name="name" id="pseudo" placeholder="Entrez votre pseudonyme" required />

    <div id="username-feedback">
        <p id="username-length">*Le pseudo doit contenir au moins 6 caractères</p>
    </div>

    <?php
    
    if(!isset($_GET['mail']))
    {
        echo '<label for="email"></label>
        <input type="email" name="email" id="email" placeholder="Entrez votre email" required />';
    }

    ?>

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

    <input type="submit" value="<?php if(isset($_GET['mail'])){ echo 'mettre à jour';} else { echo 'confirmer l\'inscription';} ?>" id="confirm" />
</form>

<?php

$userManager = new UserManager();

if(!empty($_POST) && empty($_GET))
{   
    if($userManager->isUsernameFree($_POST['name']))
    {
        echo '<p>Pseudo valide</p>';
    }
    else
    {
        echo '<p>Le pseudo est déjà pris en base de données</p>';
    }
    
    if($userManager->isEmailFree($_POST['email']))
    {
        echo '<p>Email valide</p>';
    }
    else
    {
        echo '<p>L\'email est déjà pris en base de données</p>';
    }
    
    if($userManager->isUsernameFree($_POST['name']) && $userManager->isEmailFree($_POST['email']))
    {
        echo '<p>Inscription valide</p>';
        echo '<p>Un email de confirmation va vous être envoyé</p>';
        $userManager->addUser($_POST['name'], $_POST['password'], $_POST['email']);
    }
    else
    {
        echo '<p>Inscription invalide</p>';
    }
}

if(!empty($_GET) && !empty($_POST) && isset($_GET['mail']))
{
    if(filter_var($_GET['mail'], FILTER_VALIDATE_EMAIL))
    {
        $user = $userManager->getUserByEmail($_GET['mail']);
        
        if($user != null)
        {
            $userManager->updateUserLogins($user, $_POST['name'], $_POST['password']);
            echo 'votre compte a été mis à jour';
        }
        else
        {
            echo '<p>L\'email ne correspond à aucun utilisateur</p>';
        }
    }
}

?>

<script src="../../Ressources/js/register-form.js"></script>