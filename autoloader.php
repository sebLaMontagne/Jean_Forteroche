<?php

session_start();

spl_autoload_register(function($class)
{
    $file1 = 'models/'.$class.'.php';
    $file2 = 'controllers/'.$class.'.php';
    
    if(file_exists($file1))
    {
        require_once($file1);
    }
    elseif(file_exists($file2))
    {
        require_once($file2);
    }
});