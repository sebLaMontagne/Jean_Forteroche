<?php

class Comment
{
    private $_id;
    private $_postId;
    private $_userId;
    private $_content;
    private $_date;
    private $_isNew;
    private $_likes;
    private $_reports;
    
    //--------------------------------------------------------------------
    // GETTERS
    //--------------------------------------------------------------------
    
    public function id()        { return $this->_id; }
    public function postId()    { return $this->_postId; }
    public function userId()    { return $this->_userId; }
    public function content()   { return $this->_content; }
    public function date()      { return $this->_date; }
    public function isNew()     { return $this->_isNew; }
    public function likes()     { return $this->_likes; }
    public function reports()   { return $this->_reports; }
    
    //--------------------------------------------------------------------
    // SETTERS
    //--------------------------------------------------------------------
    
    public function setId($id)
    {
        if(is_int($id))
        {
            if($id > 0)
            {
                $this->_id = $id;
            }
            else
            {
                throw new Exception('The Comment id must be a strictly positive integer value');
            }
        }
        else
        {
            throw new Exception('The Comment id must be an integer value');
        }
    }
    
    public function setPostId($postId)
    {
        if(is_int($postId))
        {
            if($postId > 0)
            {
                $this->_postId = $postId;
            }
            else
            {
                throw new Exception('The Comment post id must be a strictly positive integer value');
            }
        }
        else
        {
            throw new Exception('The Comment post id must be an integer value');
        }
    }
    
    public function setUserId($userId)
    {
        if(is_int($userId))
        {
            if($userId > 0)
            {
                $this->_userId = $userId;
            }
            else
            {
                throw new Exception('The Comment user id must be a strictly positive integer value');
            }
        }
        else
        {
            throw new Exception('The Comment user id must be an integer value');
        }
    }
    
    public function setContent($content)
    {
        if(is_string($content))
        {
            $this->_content = $content;
        }
        else
        {
            throw new Exception('The Comment content must be a string value');
        }
    }
    
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
    
    public function setIsNew($isNew)
    {
        if($isNew == 1 || $isNew == 0)
        {
            $this->_isNew = htmlspecialchars($isNew);
        }
        else
        {
            throw new Exception('The isNew attribute must be a boolean value');
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
        $appreciationManager = new AppreciationManager();
        $this->_likes = (int) $appreciationManager->countCommentAppreciations($this->_id, 'likes');
        $this->_reports = (int) $appreciationManager->countCommentAppreciations($this->_id, 'reports');
    }
}