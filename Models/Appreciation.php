<?php
class Appreciation
{
    private $_id;
    private $_commentId;
    private $_userId;
    private $_isLike;
    private $_isReport;
    
    //--------------------------------------------------------------------
    // GETTERS
    //--------------------------------------------------------------------
    
    public function id()        { return $this->_id; }
    public function commentId() { return $this->_commentId; }
    public function userId()    { return $this->_userId; }
    public function isLike()    { return $this->_isLike; }
    public function isReport()  { return $this->_isReport; }
    
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
            throw new Exception('The Appreciation_id must be a strictly positive integer value');
        }
    }
    
    public function setCommentId($commentId)
    {
        if(intval($commentId) > 0)
        {
            $this->_commentId = $commentId;
        }
        else
        {
            throw new Exception('The Appreciation_comment_id must be a strictly positive integer value');
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
            throw new Exception('The Appreciation user id must be a strictly positive integer value');
        }
    }
    
    public function setIsLike($isLike)
    {
        if($isLike == 1 || $isLike == 0)
        {
            $this->_isLike = $isLike;
        }
        else
        {
            throw new Exception('The author status must be a boolean value');
        }
    }
    
    public function setIsReport($isReport)
    {
        if($isReport == 1 || $isReport == 0)
        {
            $this->_isReport = $isReport;
        }
        else
        {
            throw new Exception('The author status must be a boolean value');
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
    }
}