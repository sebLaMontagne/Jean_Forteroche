<?php

try
{
    header('Location:Views/Front/home.php');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}