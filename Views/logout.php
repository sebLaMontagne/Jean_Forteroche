<?php

$title = 'Billet simple pour l\'Alaska - Déconnection';
require('template.php');
?>

<p>Etes-vous sûr de vouloir vous déconnecter ?</p>
<form method="post" action="logout.php">
    <label for="logout-yes">Oui</label><input id="logout-yes" type="radio" name="logout" value="yes" />
    <label for="logout-no">Non</label><input id="logout-no" type="radio" name="logout" value="no" />
    <input type="submit" value="confirmer" />
</form>


<?php
if(!empty($_POST))
{
    if($_POST['logout'] == 'yes')
    {
        session_destroy();
        header('Location:home.php');
    }
}

?>