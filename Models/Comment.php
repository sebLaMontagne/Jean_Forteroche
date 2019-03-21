<?php

class Comment
{
    private $_id;
    private $_postId;
    private $_userId;
    private $_content;
    private $_date;
    
    //--------------------------------------------------------------------
    // GETTERS
    //--------------------------------------------------------------------
    
    public function id()        { return $this->_id; }
    public function postId()    { return $this->_postId; }
    public function userId()    { return $this->_userId; }
    public function content()   { return $this->_content; }
    public function date()      { return $this->_date; }
    
    //--------------------------------------------------------------------
    // SETTERS
    //--------------------------------------------------------------------
    
    public function setId($id)
    {
        if(is_int($id))
        {
            if($id > 0)
            {
                $this->_id = $id
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
        //Check date validity
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