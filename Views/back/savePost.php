<?php

$title = 'traitement posts';
include('template.php');

var_dump($_POST);

//vérifier si le chapitre n'existe pas déjà en bdd
//  SI OUI, alors proposer de l'écraser
//enregistrer dans la bdd en tant que brouillon ou publier en fonction de l'état de "save"