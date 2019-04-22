<?php

try
{
    require_once('../autoloader.php');
    
    $title = 'Billet simple pour l\'Alaska - Accueil';
    
    $content  = '';
    $content .= '<p>Qu\'est ce que billet simple pour l\'Alaska ?</p>';
    $content .= '<p>Billet simple pour l\'alaska est un roman publié en ligne et dans lequel votre serviteur vous présentera les aventures de Jean Martin de manière épisodique.</p>';
    $content .= '<div>';
    $content .= '<a href="chapter.php?chapter=1">Lire le premier chapitre</a>';
    $content .= '&nbsp;&nbsp;&nbsp;&nbsp;';
    $content .= '<a href="about.php">Découvrir votre serviteur</a>';
    $content .= '</div>';
    
    require('template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}