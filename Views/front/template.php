<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <link rel="icon" href="ressources/img/favicon.png" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Libre+Baskerville" />
        <link rel="stylesheet" href="Ressources/css/styles.css" />
        <script src="Ressources/js/jQuery.js"></script>
    </head>
    <body>
        <h1>Billet simple pour l'Alaska</h1>
        <nav>
            <a href="index.php?action=home">Accueil</a>
            <a href="index.php?action=about">A propos de l'auteur</a>
            <a href="index.php?action=chapters">Liste des chapitres</a>
            <a href="index.php?action=contact">Contact</a>

            <div class="account-access">

            <?php if(!empty($_SESSION['pseudo']) && !empty($_SESSION['email']) && ($_SESSION['isAdmin'] == '0' || $_SESSION['isAdmin'] == '1') && !empty($_SESSION['id'])) : ?>

                <p class="welcome">Bienvenue <span class="username"><?= $_SESSION['pseudo'] ?></span></p>

                <?php if($_SESSION['isAdmin']) : ?>

                    <a href="index.php?action=admin">Admin</a>

                    <?php endif; ?>

                    <a href="index.php?action=logout">Déconnexion</a>

                <?php else : ?>

                    <a href="index.php?action=login">Connection</a>
                    <a href="index.php?action=register">S'inscrire</a>

            <?php endif; ?>

            </div>
        </nav>
        <div class="content filler"><?= $content ?></div>
    </body>
</html>