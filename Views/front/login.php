<?php

if(!empty($_POST)) :
    if($user == null) : ?>
    
        <p>Identifiants incorrects</p>

    <?php elseif($user->isBanned()) : ?> 
    
        <p>Votre compte a été banni. Vous ne pouvez qu\'implorer la clémence de notre maître et seigneur à tous</p>

    <?php elseif(!$user->isActivated()) : ?>
    
        <p>Compte existant mais non activé</p>
    
    <?php else :
    
        $_SESSION['pseudo'] = $user->name();
        $_SESSION['email'] = $user->email();
        $_SESSION['isAdmin'] = $user->isAdmin();
        $_SESSION['id'] = $user->id();
        header('Location: index.php?action=home');
        exit();

    endif;

else : ?>

    <div class="content filler">
        <p style="text-align: center">Veuillez entrer vos identifiants</p>
        <form id="register-form" method="post" action="index.php?action=login">
            <label for="login-username"></label><input id="login-username" type="text" name="name" placeholder="Veuillez entrer votre pseudonyme" required />
            <label for="login-password"></label><input id="login-password" type="password" name="password" placeholder="Veuillez entrer votre mot de passe" required />
            <input type="submit" value="Se connecter" />
        </form>
        <p style="text-align: center"><a class="link-standard" href="index.php?action=reset">Avez-vous oublié vos identifiants ?</a></p>
    </div>

<?php endif ?>