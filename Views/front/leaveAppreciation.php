<?php
$title = 'gestion des appreciations';
require('template.php');

//passer en plus l'id du post

if(isset($_GET['appreciation']))
{
    echo 'Le paramètre est passé';
    
    if($_GET['appreciation'] == 'like')
    {
        echo 'L\'appreciation est un like';
    }
    elseif($_GET['appreciation'] == 'report')
    {
        echo 'L\'appreciation est un report';
    }
}