<p style="text-align: center">Vous pouvez me contacter en utilisant le formulaire çi-dessous :</p>
<form id="register-form" method="post" action="index.php?action=contact">

<?php if(!isset($_SESSION['pseudo']) && !isset($_SESSION['email'])) : ?>

    <input type="text" name="name" placeholder="Entrez votre nom" required />
    <input type="email" name="mail" placeholder="Entrez votre email" required />
    
<?php endif ?>

    <textarea name="message" placeholder="Entrez votre message" required></textarea>
    <input type="submit" value="envoyer" />
</form>


<?php if(isset($_POST['message'])) : ?>

<p style="text-align: center;">Votre message a été envoyé :)</p>

<?php endif ?>