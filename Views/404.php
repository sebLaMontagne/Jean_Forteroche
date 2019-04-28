<?php

try
{
    require_once('autoloader.php');
    
    $title = 'Page inexistante';
    
    $content = '<p class="content filler">Cette page n\'exite pas</p>';
    
    require('front/template.php');
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}