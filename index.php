<?php

try
{
    header('Location:Views/home.php');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}