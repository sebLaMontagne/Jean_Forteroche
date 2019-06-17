<form method="post" action="index.php?action=reset">
    <label for="email"></label><input id="email" type="email" name="email" placeholder="Veuillez entrer votre email" required />
    <input type="submit" value="Réinitialiser" />
</form>

<?php if(!empty($_POST)) : ?>

    <?php if($user == null) : ?>
    
        <p>Email inexistant</p>
    
    <?php else : ?>
    
        <p>Email existant</p>
        <p>Un email contenant un lien de réinitialisation va vous être envoyé à l'adresse entrée</p>
            
    <?php endif ?>
<?php endif ?>