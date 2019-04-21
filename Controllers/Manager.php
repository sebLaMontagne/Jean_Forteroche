<?php
abstract class Manager
{
    protected $_db;
    
    private function connectDb()
    { 
        $this->_db = new PDO('mysql:host=db5000055745.hosting-data.io;dbname=dbs50590;', 'dbu151311','Dorian33...', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function __construct()
    {
        $this->connectDb();
    }
}