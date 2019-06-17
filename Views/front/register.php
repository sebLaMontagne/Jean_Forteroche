<!--Formulaire-->

<?php if(!empty($_POST['name']) && !empty($_POST['email']) && $isUserNameFree && $isEmailFree) : ?>

<form style="display: none">

<?php elseif(!empty($_GET['mail'])) : ?>

<form method="post" action="index.php?action=register&mail=<?= $_GET['mail'] ?>" id="register-form">

<?php elseif(empty($_GET['mail'])) : ?>

<form method="post" action="index.php?action=register" id="register-form">

<?php endif ?>

    <label for="pseudo"></label><input type="text" name="name" id="pseudo" placeholder="Entrez votre pseudonyme" required />
    <div id="username-feedback">
        <p id="username-length">*Le pseudo doit contenir au moins 6 caractères</p>
    </div>

<?php if(empty($_GET['mail'])) : ?>

    <label for="email"></label><input type="email" name="email" id="email" placeholder="Entrez votre email" required />
    
<?php endif ?>

    <label for="password"></label><input type="password" name="password" id="password" placeholder="Entrez votre mot de passe" required />
    <label for="password-confirm"></label><input type="password" name="password-confirm" id="password-confirm" placeholder="Confirmez votre mot de passe" required />

    <div class="password-feedback">
        <p id="password-lowercase">*Le mot de passe doit contenir au moins un caractère minuscule</p>
        <p id="password-uppercase">*Le mot de passe doit contenir au moins un caractère majuscule</p>
        <p id="password-number">*Le mot de passe doit contenir au moins un chiffre</p>
        <p id="password-length">*Le mot de passe doit contenir au moins 8 caractères</p>
        <p id="password-repeat">*Les deux mots de passe doivent être identiques</p>
    </div>

<?php if(!empty($_GET['mail'])) : ?>

    <input type="submit" value="mettre à jour" id="confirm" />

<?php else : ?>

    <input type="submit" value="confirmer l'inscription" id="confirm" />
    
<?php endif ?>

</form>

<!--Inscription feedback-->

<?php if(!empty($_POST) && empty($_GET['mail'])) : ?> 

<div style="text-align: center">
    
    <?php if($isUserNameFree) : ?>                  <p>Pseudo valide</p>                                                            <?php else : ?>     <p>Le pseudo est déjà pris en base de données</p>   <?php endif ?>
    <?php if($isEmailFree) : ?>                     <p>Email valide</p>                                                             <?php else : ?>     <p>L'email est déjà pris en base de données</p>     <?php endif ?>
    <?php if($isUserNameFree && $isEmailFree) : ?>  <p>Inscription valide</p><p>Un email de confirmation va vous être envoyé</p>    <?php else : ?>     <p>Inscription invalide</p>                         <?php endif ?>
    
<?php endif ?>

<?php if(!empty($_GET) && !empty($_POST) && isset($_GET['mail'])) : ?>
    
    <?php if(filter_var($_GET['mail'], FILTER_VALIDATE_EMAIL)) : ?>

        <?php if($user != null) : ?>
        
    <p>votre compte a été mis à jour</p>
        
        <?php else : ?>
        
    <p>L'email ne correspond à aucun utilisateur</p>
    
        <?php endif ?>
    <?php endif ?>
<?php endif ?>

</div>
<script src="Ressources/js/register-form.js"></script>