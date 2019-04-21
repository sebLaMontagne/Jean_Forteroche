<?php

try
{
    $title = 'Billet simple pour l\'Alaska - A propos de l\'auteur';
    require('template.php');

?>

    <p>Bonjour, je m'appelle Jean Forteroche. Je suis né en France dans la belle ville de Lyon en l'an de grâce 1978.</p>
    <p>Très jeune, je me découvre une passion pour l'écriture qui ne me quittera jamais plus. J'ai écrit de nombreux livres dont le fameux "bOOk of dOOm" dernièrement qui s'est vendu à plus de 100 000 exemplaires.</p>
    <p>Aujourd'hui, je veux tenter une nouvelle façon d'écrire des histoires. C'est la raison de la création de ce blog, où je vous conterai l'histoire de Jean Martin, exilé Parisien cherchant à se créer un nouvelle vie en Alaska et à laisser derrière lui un passé trouble et lourd.</p>
    <p><a href="chapter.php?chapter=1">Lire le premier chapitre</a></p>

<?php
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}
?>