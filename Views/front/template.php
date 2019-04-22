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
                <a href="home.php">Accueil</a>
                <a href="about.php">A propos de l'auteur</a>
                <a href="chaptersList.php">Liste des chapitres</a>
                <a href="contact.php">Contact</a>
                <div class="account-access">
                    <?php 
                    if(!empty($_SESSION['pseudo']) && !empty($_SESSION['email']) && ($_SESSION['isAdmin'] == '0' || $_SESSION['isAdmin'] == '1') && !empty($_SESSION['id']))
                    {
                        echo '<p>Bienvenue <span class="username">'.$_SESSION['pseudo'].'</span></p>';
                        
                        if($_SESSION['isAdmin'])
                        {
                            echo '<a href="../back/admin.php?user='.$_SESSION['id'].'">Admin</a>';
                        }
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
        <?= $content ?>
    </body>
</html>