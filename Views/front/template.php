<?php require_once('../autoloader.php'); ?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Libre+Baskerville" />
        <link rel="stylesheet" href="../../Ressources/css/styles.css" />
        <script src="../../Ressources/js/jQuery.js"></script>
    </head>
    <body>
        <header>
            <h1>Billet simple pour l'Alaska</h1>
            <nav>
                <a href="home">Accueil</a>
                <a href="about">A propos de l'auteur</a>
                <a href="chaptersList">Liste des chapitres</a>
                <a href="contact">Contact</a>
                <div class="account-access">
                    <?php 
                    if(!empty($_SESSION['pseudo']) && !empty($_SESSION['email']) && ($_SESSION['isAdmin'] == '0' || $_SESSION['isAdmin'] == '1') && !empty($_SESSION['id']))
                    {
                        echo '<p>Bienvenue <span class="username">'.$_SESSION['pseudo'].'</span></p>';
                        
                        if($_SESSION['isAdmin'])
                        {
                            echo '<a href="back/admin">Admin</a>';
                        }
                        echo '<a href="logout">Déconnexion</a>';
                    }
                    else
                    {
                        echo '<a href="login">Connection</a>';
                        echo '<a href="register">S\'inscrire</a>';
                    }
                    ?>
                </div>
            </nav>
        </header>
        <?= $content ?>
    </body>
</html>