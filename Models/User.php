<?php

class User
{
    private $_id;
    private $_name;
    private $_password;
    private $_email;
    private $_isAuthor;
    private $_isAdmin;
    private $_token;
    private $_isActivated;
    
    //--------------------------------------------------------------------
    // GETTERS
    //--------------------------------------------------------------------
    
    public function id()            { return $this->_id; }
    public function name()          { return $this->_name; }
    public function password()      { return $this->_password; }
    public function email()         { return $this->_email; }
    public function isAuthor()      { return $this->_isAuthor; }
    public function isAdmin()       { return $this->_isAdmin; }
    public function token()         { return $this->_token; }
    public function isActivated()   { return $this->_isActivated; }
    
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
                throw new Exception('The User id must be must be a strictly positive integer value');
            }
        }
        else
        {
            throw new Exception('The User id must be an integer value');
        }
    }
    
    //--------------------------------------------------------------------
    
    public function setName($name)
    {
        if(is_string($name))
        {
            if(strlen($name) > 5)
            {
                $this->_name = htmlspecialchars($name);
            }
            else
            {
                throw new Exception('The User name must have at least 6 characters');
            }
            
        }
        else
        {
            throw new Exception('The User name must be a string value');
        }
    }
    
    //--------------------------------------------------------------------
    
    public function setPassword($password)
    {
        if(is_string($password))
        {
            if(!preg_match("#[a-z]#", $password))
            {
                throw new Exception('The User password must have at least 1 lowercase character');
            }
            elseif(!preg_match("#[A-Z]#", $password))
            {
                throw new Exception('The User password must have at least 1 uppercase character');
            }
            elseif(!preg_match("#[0-9]#", $password))
            {
                throw new Exception('The User password must have at least 1 number');
            }
            elseif(strlen($password) < 8)
            {
                throw new Exception('The User password must have at least 8 characters');
            }
            else
            {
                $this->_password = htmlspecialchars($password);
            }  
        }
        else
        {
            throw new Exception('The User password must be a string value');
        }
    }
    
    //--------------------------------------------------------------------
    
    public function setEmail($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->_email = htmlspecialchars($email);
        }
        else
        {
            throw new Exception('The User e-mail don\'t respect the requirements');
        }
    }
    
    public function setToken($token)
    {
        if(preg_match('#^[0-9]{12}$#', $token))
        {
            $this->_token = htmlspecialchars($token);
        }
        else
        {
            throw new Exception('the token don\'t respect the right format');
        }
    }
    
    public function setIsAuthor($isAuthor)
    {
        if($isAuthor == 1 || $isAuthor == 0)
        {
            $this->_isAuthor = htmlspecialchars($isAuthor);
        }
        else
        {
            throw new Exception('The author status must be a boolean value');
        }
    }
    
    public function setIsAdmin($isAdmin)
    {
        if($isAdmin == 1 || $isAdmin == 0)
        {
            $this->_isAdmin = htmlspecialchars($isAdmin);
        }
        else
        {
            throw new Exception('The admin status must be a boolean value');
        }
    }
    
    public function setIsActivated($isActivated)
    {
        if($isActivated == 1 || $isActivated == 0)
        {
            $this->_isActivated = htmlspecialchars($isActivated);
        }
        else
        {
            throw new Exception('The activation status must be a boolean value');
        }
    }
    
    //--------------------------------------------------------------------
    // ABOUT CONSTRUCTOR
    //--------------------------------------------------------------------
    
    private function hydrate(array $data)
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