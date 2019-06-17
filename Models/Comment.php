<?php

class Comment
{
    private $_id;
    private $_postId;
    private $_chapterNumber;
    private $_userId;
    private $_authorName;
    private $_authorIsBanned;
    private $_authorIsAdmin;
    private $_content;
    private $_date;
    private $_likes;
    private $_reports;
    
    //--------------------------------------------------------------------
    // GETTERS
    //--------------------------------------------------------------------
    
    public function id()            { return $this->_id; }
    public function postId()        { return $this->_postId; }
    public function chapterNumber() { return $this->_chapterNumber; }
    public function userId()        { return $this->_userId; }
    public function authorName()    { return $this->_authorName; }
    public function authorIsBanned(){ return $this->_authorIsBanned; }
    public function authorIsAdmin() { return $this->_authorIsAdmin; }
    public function content()       { return $this->_content; }
    public function date()          { return $this->_date; }
    public function likes()         { return $this->_likes; }
    public function reports()       { return $this->_reports; }
    
    //--------------------------------------------------------------------
    // SETTERS
    //--------------------------------------------------------------------
    
    public function setId($id)
    {
        if(intval($id) > 0)
        {
            $this->_id = $id;
        }
        else
        {
            throw new Exception('The Comment id must be a strictly positive integer value');
        }
    }
    
    public function setPostId($postId)
    {
            if(intval($postId) > 0)
            {
                $this->_postId = $postId;
            }
            else
            {
                throw new Exception('The Comment post id must be a strictly positive integer value');
            }
    }
    
    public function setUserId($userId)
    {
            if(intval($userId) > 0)
            {
                $this->_userId = $userId;
            }
            else
            {
                throw new Exception('The Comment user id must be a strictly positive integer value');
            }
    }
    
    public function setContent($content)
    {
        if(is_string($content))
        {
            $this->_content = htmlspecialchars($content);
        }
        else
        {
            throw new Exception('The Comment content must be a string value');
        }
    }
    
    public function setDate($date)
    {
        if(preg_match("#^20[0-9]{2}(-[0-9]{2}){2} ([0-9]{2}:){2}([0-9]){2}$#", $date))
        {
            $this->_date = $date;
        }
        else
        {
            throw new Exception('The date don\'t respect the date format');
        }
    }
    
    public function setAuthorName($authorName)
    {
        if(is_string($authorName))
        {
            $this->_authorName = htmlspecialchars($authorName);
        }
        else
        {
            throw new Exception('The author name must be a string value');
        }
    }
    
    public function setChapterNumber($chapterNumber)
    {
        if(intval($chapterNumber) > 0)
        {
            $this->_chapterNumber = $chapterNumber;
        }
        else
        {
            throw new Exception('The chapterNumber must be a strictly positive integer value');
        }
    }
    
    public function setAuthorIsBanned($authorIsBanned)
    {   
        if($authorIsBanned == true || $authorIsBanned == false)
        {
            $this->_authorIsBanned = $authorIsBanned;
        }
        else
        {
            throw new Exception('The authorIsBanned attribute must be a boolean value');
        }
    }
    
    public function setAuthorIsAdmin($authorIsAdmin)
    {
        if($authorIsAdmin == 1 || $authorIsAdmin == 0)
        {
            $this->_authorIsAdmin = $authorIsAdmin;
        }
        else
        {
            throw new Exception('The authorIsAdmin attribute must be a boolean value');
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
    
    public function __construct(array $data)
    {
        $this->hydrate($data);
        $appreciationManager = new AppreciationManager();
        $this->_likes = (int) $appreciationManager->countCommentAppreciations($this->_id, 'likes');
        $this->_reports = (int) $appreciationManager->countCommentAppreciations($this->_id, 'reports');
    }
}