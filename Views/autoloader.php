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