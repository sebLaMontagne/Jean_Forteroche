<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <link rel="icon" href="Ressources/img/favicon.png" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Libre+Baskerville" />
        <link rel="stylesheet" href="Ressources/css/styles.css" />
        <script src="Ressources/js/jQuery.js"></script>
    </head>
    
    <body>
        <h1>Panneau d'administration</h1>
        <h2><?= 'Bienvenue '.$_SESSION['pseudo']; ?></h2>
        <nav>
            <a href="index.php?action=home">Retourner sur le site</a>
            <a href="index.php?action=admin">Tableau de bord</a>
            <a href="index.php?action=backChapters">Gestion des Chapitres</a>
            <a href="index.php?action=backComments&sortedBy=reports">Gestion des Commentaires</a>
            <a href="index.php?action=backUsers&sortedBy=all">Gestion des utilisateurs</a>
        </nav>
        <div class="content filler"><?= $content ?></div>
    </body>
</html>