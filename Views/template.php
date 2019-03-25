<?php

session_start();

spl_autoload_register(function($class){
    
    $file = '../Models/'.$class.'.php';
    if(file_exists($file))
    {
        require_once($file);
    }
    else
    {
        $file = '../Controllers/'.$class.'.php';
        if(file_exists($file))
        {
            require_once($file);
        }
    }
});

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <link rel="stylesheet" href="../Ressources/css/styles.css" />
        <script src="../Ressources/js/jQuery.js"></script>
    </head>
    <body>
        <header>
            <h1>Billet simple pour l'Alaska</h1>
            <nav>
                <a href="home.php">Accueil</a>
                <a href="about.php">A propos de l'auteur</a>
                <a href="chapters.php">Liste des chapitres</a>
                <a href="contact.php">Contact</a>
                <div class="account-access">
                    <?php 
                    if(!empty($_SESSION['pseudo']))
                    {
                        echo '<p>Bienvenue '.htmlspecialchars($_SESSION['pseudo']).'</p>';
                        echo '<a href="logout.php">Déconnexion</a>';
                    }
                    else
                    {
                        echo '<a href="login.php">Connection</a>';
                        echo '<a href="register.php">S\'inscrire</a>';
                    }
                    ?>   
                </div>
            </nav>
        </header>    
    </body>
</html>