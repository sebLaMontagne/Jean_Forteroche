<?php

class Post
{
    private $_id;
    private $_authorId;
    private $_title;
    private $_chapterNumber;
    private $_illustration;
    private $_content;
    private $_date;
    private $_isPublished;
    
    //--------------------------------------------------------------------
    // GETTERS
    //--------------------------------------------------------------------
    
    public function id()            { return $this->_id; }
    public function authorId()      { return $this->_authorId; }
    public function title()         { return $this->_title; }
    public function chapterNumber() { return $this->_chapterNumber; }
    public function illustration()  { return $this->_illustration; }
    public function content()       { return $this->_content; }
    public function date()          { return $this->_date; }
    public function isPublished()   { return $this->_isPublished; }
    
    //--------------------------------------------------------------------
    // SETTERS
    //--------------------------------------------------------------------
    
    public function setId($id)
    {
        if(is_int($id))
        {
            if($id > 0)
            {
                $this->_id = htmlspecialchars($id);
            }
            else
            {
                throw new Exception('The Post id must be a strictly positive integer value');
            }
        }
        else
        {
            throw new Exception('The Post id must be an integer value');
        }
    }
    
    //--------------------------------------------------------------------
    
    public function setAuthorId($authorId)
    {
        if(is_int($authorId))
        {
            if($authorId > 0)
            {
                $this->_authorId = htmlspecialchars($authorId);
            }
            else
            {
                throw new Exception('The Post author id must be a strictly positive integer value');
            }
        }
        else
        {
            throw new Exception('The Post author id must be an integer value');
        }
    }
    
    //--------------------------------------------------------------------
    
    public function setTitle($title)
    {
        if(is_string($title))
        {
            $this->_title = htmlspecialchars($title);
        }
        else
        {
            throw new Exception('The Post title must be a string value');
        }
    }
    
    //--------------------------------------------------------------------
    
    public function setChapterNumber($chapterNumber)
    {
        if(is_int($chapterNumber))
        {
            if($chapterNumber > 0)
            {
                $this->_chapterNumber = htmlspecialchars($chapterNumber);
            }
            else
            {
                throw new Exception('The Post chapter number must be a strictly positive integer value');
            }
        }
        else
        {
            throw new Exception('The Post chapter number must be an integer value');
        }
    }
    
    //--------------------------------------------------------------------
    
    public function setIllustration($illustration)
    {
        if(is_string($illustration))
        {
            if(preg_match("#^/Ressources/img/illustrations/.+(\.jpeg|\.png)$#", $illustration))
            {
                $this->_title = htmlspecialchars($illustration);
            }
        }
        else
        {
            throw new Exception('The Post illustration must be a string value');
        }
    }
    
    //--------------------------------------------------------------------
    
    public function setContent($content)
    {
        if(is_string($content))
        {
            $this->_content = htmlspecialchars($content);
        }
        else
        {
            throw new Exception('The Post illustration must be a string value');
        }
    }
    
    //--------------------------------------------------------------------
    
    public function setDate($date)
    {
        if(is_string($date))
        {
            if(preg_match("#^20[0-9]{2}(-[0-9]{2}){2} ([0-9]{2}:){2}([0-9]){2}$#", $date))
            {
                $this->_date = htmlspecialchars($date);
            }
            else
            {
                throw new Exception('The date don\'t respect the date format');
            }
        }
        else
        {
            throw new Exception('The date must be a string value');
        }
    }
    
    public function setIsPublished($isPublished)
    {
        if($isPublished == 0 || $isPublished == 1)
        {
            $this->_isPublished = $isPublished;
        }
        else
        {
            throw new Exception('The isPublished status must be a boolean value');
        }
    }
    
    //--------------------------------------------------------------------
    // ABOUT CONSTRUCTOR
    //--------------------------------------------------------------------
    
    public function hydrate(array $data)
    {
        foreach($data as $key => $value)
        {
            $method = 'set'.ucfirst($key);
            if(method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }
    
    //--------------------------------------------------------------------
    
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }
}