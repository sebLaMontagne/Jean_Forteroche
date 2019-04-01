<?php
require_once('Manager.php');

class UserManager extends Manager
{
    private function refineAnswer($brutAnswer)
    {      
        $refinedAnswer['id']            = (int) $brutAnswer['user_id'];
        $refinedAnswer['name']          =       $brutAnswer['user_name'];
        $refinedAnswer['password']      =       $brutAnswer['user_password'];
        $refinedAnswer['email']         =       $brutAnswer['user_email'];
        $refinedAnswer['isAuthor']      = (int) $brutAnswer['user_isAuthor'];
        $refinedAnswer['isAdmin']       = (int) $brutAnswer['user_isAdmin'];
        $refinedAnswer['token']         =       $brutAnswer['user_token'];
        $refinedAnswer['isActivated']   = (int) $brutAnswer['user_activation'];
        
        return $refinedAnswer;
    }
    
    public function addUser($name, $password, $email)
    { 
        $q = $this->_db->prepare('
            INSERT INTO user(
                user_name, 
                user_password, 
                user_email,  
                user_isAuthor, 
                user_isAdmin,
                user_token,
                user_activation) 
            VALUES(
                :userName, 
                :userPassword,
                :userEmail,
                :userIsAuthor,
                :userIsAdmin,
                :userToken,
                :userActivation)');
        
        $confirmToken = '';
        $tokenLength = 12;
        
        for($i = 0; $i < $tokenLength; $i++)
        {
            $confirmToken .= mt_rand(0,9);
        }
        
        $q->bindValue(':userName', htmlspecialchars($name));
        $q->bindValue(':userPassword', password_hash(htmlspecialchars($password), PASSWORD_DEFAULT));
        $q->bindValue(':userEmail', htmlspecialchars($email));
        $q->bindValue(':userIsAuthor', 0);
        $q->bindValue(':userIsAdmin', 0);
        $q->bindValue(':userToken', $confirmToken);
        $q->bindValue(':userActivation', 0);
        
        $q->execute();
        
        $to = 'juniorwebdesign27@gmail.com;';  
        $subject = "Confirmation d'inscription";  
        $message = "Voici votre lien d'activation : \n";
        $message.= 'https://billetsimplepourlalaska.000webhostapp.com/Views/confirmRegistration.php?token='.$confirmToken;
        $from = "us-imm-node1a.000webhost.io";
        $headers = "From: $from";
        
        mail($to,$subject,$message,$headers);
    }
    
    public function confirmUser($token)
    {
        $q = $this->_db->prepare('UPDATE user SET user_activation = 1 WHERE user_token = :userToken');
        $q->bindValue(':userToken', htmlspecialchars($token));
        $q->execute();
    }
    
    public function isUsernameFree($name)
    {    
        $q = $this->_db->prepare('SELECT user_name FROM user WHERE user_name = :userName');
        
        $q->bindValue(':userName', htmlspecialchars($name));
        $q->execute();
        
        if($r = $q->fetch())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function isEmailFree($email)
    {    
        $q = $this->_db->prepare('SELECT user_email FROM user WHERE user_email = :userEmail');
        
        $q->bindValue(':userEmail', htmlspecialchars($email));
        $q->execute();
        
        if($r = $q->fetch())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function getUserById($id)
    {
        $q = $this->_db->prepare('SELECT * FROM user WHERE user_id = :userId');
        $q->bindValue(':userId', htmlspecialchars($id));
        $q->execute();
        
        if($a = $q->fetch())
        {
            return new User($this->refineAnswer($a));
        }
    }
    
    public function getUserByLogins($username, $password)
    {
        $q = $this->_db->prepare('SELECT * FROM user WHERE user_name = :userName');
        
        $q->bindValue(':userName', htmlspecialchars($username));
        $q->execute();
        
        if($a = $q->fetch())
        {
            if(password_verify($password, $a['user_password']))
            {
                return new User($this->refineAnswer($a));
            }
        }
    }
    
    public function getUserByEmail($email)
    {
        $q = $this->_db->prepare('SELECT * FROM user WHERE user_email = :email');
        $q->bindValue(':email', htmlspecialchars($email));
        $q->execute();
        
        if($a = $q->fetch())
        {
            return new User($this->refineAnswer($a));
        }
    }
    
    public function updateUser(User $user, $username, $password)
    {
        $q = $this->_db->prepare('
            UPDATE  user 
            SET     user_name       = :username,
                    user_password   = :password
            WHERE   user_id         = :id');
        
        $q->bindValue(':username', htmlspecialchars($username));
        $q->bindValue(':password', password_hash(htmlspecialchars($password), PASSWORD_DEFAULT));
        $q->bindValue(':id', $user->id());
        $q->execute();
    }
}