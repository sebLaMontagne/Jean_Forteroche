<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <link rel="stylesheet" href="../Ressources/css/styles.css" />
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
                    <a href="login.php">Connection</a>
                    <a href="register.php">S'inscrire</a>
                </div>
            </nav>
        </header>
        
        <?= $content ?>
        
    </body>
</html>