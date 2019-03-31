<?php

$title = 'Réinitialisation du compte';
include('template.php');

?>

<form method="post" action="reset.php">
    <label for="email"></label><input id="email" type="email" name="email" placeholder="Veuillez entrer votre email" required />
    <input type="submit" value="Réinitialiser" />
</form>

<?php

if(!empty($_POST))
{ 
    $userManager = new UserManager();
    $user = $userManager->getUserByEmail($_POST['email']);
    
    if($user == null)
    {
        echo '<p>Email inexistant</p>';
    }
    else
    {
        echo '<p>Email existant</p>';
        echo '<p>Un email contenant un lien de réinitialisation va vous être envoyé à l\'adresse entrée</p>';
        
        $to = 'juniorwebdesign27@gmail.com;';  
        $subject = "Réinitialisation d'inscription";  
        $message = "Voici votre lien de réinitialisation : \n";
        $message.= 'https://billetsimplepourlalaska.000webhostapp.com/Views/Register.php?mail='.$_POST['email'];
        $from = "us-imm-node1a.000webhost.io";
        $headers = "From: $from";
        
        mail($to,$subject,$message,$headers);
    }
}

?>