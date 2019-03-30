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
    
    public function addUser(User $user)
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
        
        $q->bindValue(':userName', $user->name());
        $q->bindValue(':userPassword', $user->password());
        $q->bindValue(':userEmail', $user->email());
        $q->bindValue(':userIsAuthor', $user->isAuthor());
        $q->bindValue(':userIsAdmin', $user->isAdmin());
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
    
    public function isUsernameFree(User $user)
    {    
        $q = $this->_db->prepare('SELECT COUNT(user_name) FROM user WHERE user_name = :userName');
        
        $q->bindValue(':userName', $user->name());
        $q->execute();
        $r = $q->fetch()[0];
        
        if($r == 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function isEmailFree(User $user)
    {    
        $q = $this->_db->prepare('SELECT COUNT(user_email) FROM user WHERE user_email = :userEmail');
        
        $q->bindValue(':userEmail', $user->email());
        $q->execute();
        
        $r = $q->fetch()[0];
        if($r == 0)
        {
            return true;
        }
        else
        {
            return false;
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
        $q = $this->_db->prepare('
            SELECT * FROM user 
            WHERE user_name = :userName AND 
                  user_password = :password');
        
        $q->bindValue(':userName', htmlspecialchars($username));
        $q->bindValue(':password', htmlspecialchars($password));
        $q->execute();
        
        if($a = $q->fetch())
        {
            return new User($this->refineAnswer($a));
        }
    }
}