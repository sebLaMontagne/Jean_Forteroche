<?php

$title = 'Billet simple pour l\'Alaska - Accueil';
$content = '<p>Bienvenue sur register</p>';

require('template.php');

?>

<div class="form">
    <form method="post" action="register.php">
        
        <label for="pseudo"></label>
        <input type="text" name="pseudo" id="pseudo" placeholder="Entrez votre pseudonyme" required />
        
        <p class="username-validity"></p>
        
        <label for="password"></label>
        <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe" required />
        
        <br />
        
        <label for="password-confirm"></label>
        <input type="password" name="password-confirm" id="password-confirm" placeholder="Confirmez votre mot de passe" required />
        
        <p class="password-validity"></p>
        
        <input type="submit" value="confirmer l'inscription" />
    </form>
</div>
<script src="../Ressources/js/password.js"></script>
