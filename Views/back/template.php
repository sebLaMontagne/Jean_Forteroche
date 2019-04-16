<?php

session_start();

spl_autoload_register(function($class){
    
    $file = '../../Models/'.$class.'.php';
    if(file_exists($file))
    {
        require_once($file);
    }
    else
    {
        $file = '../../Controllers/'.$class.'.php';
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
        <link rel="stylesheet" href="../../Ressources/css/styles.css" />
        <script src="../../Ressources/js/jQuery.js"></script>
    </head>
    
    <body>
        <h1>Panneau d'administration</h1>
        <h2><?= 'Bienvenue '.$_SESSION['pseudo']; ?></h2>
        <nav>
            <a href="../front/home.php">Retourner sur le site</a>
            <a href="admin.php">Accueil</a>
            <a href="chaptersList.php">Gestion des Chapitres</a>
            <a href="commentsList.php">Gestion des Commentaires</a>
            <a href="usersList.php">Gestion des utilisateurs</a>
        </nav>
    </body>
</html>