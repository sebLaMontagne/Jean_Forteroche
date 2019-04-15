<?php
require_once('Manager.php');

class UserManager extends Manager
{
    private function refineAnswer($brutAnswer)
    {      
        $refinedAnswer['id']                = (int) $brutAnswer['user_id'];
        $refinedAnswer['name']              =       $brutAnswer['user_name'];
        $refinedAnswer['password']          =       $brutAnswer['user_password'];
        $refinedAnswer['email']             =       $brutAnswer['user_email'];
        $refinedAnswer['isAuthor']          = (int) $brutAnswer['user_isAuthor'];
        $refinedAnswer['isAdmin']           = (int) $brutAnswer['user_isAdmin'];
        $refinedAnswer['isBanned']          = (int) $brutAnswer['user_isBanned'];
        $refinedAnswer['token']             =       $brutAnswer['user_token'];
        $refinedAnswer['isActivated']       = (int) $brutAnswer['user_activation'];
        $refinedAnswer['tokenExpiration']   =       $brutAnswer['user_token_expiration'];
        
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
                user_activation,
                user_token_expiration) 
            VALUES(
                :userName,
                :userPassword,
                :userEmail,
                :userIsAuthor,
                :userIsAdmin,
                :userToken,
                :userActivation,
                CURTIME() + INTERVAL 1 DAY)');
        
        do{
            $confirmToken = '';
            for($i = 0; $i < 12; $i++)
            {
                $confirmToken .= mt_rand(0,9);
            }   
        }while(!$this->isTokenFree(confirmToken));

        
        $q->bindValue(':userName', htmlspecialchars($name));
        $q->bindValue(':userPassword', password_hash($password, PASSWORD_DEFAULT));
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
    
    public function confirmAccount(User $user)
    {
        $q = $this->_db->prepare('
            UPDATE  user 
            SET     user_activation = 1
            WHERE   user_id         = :id');
        
        $q->bindValue(':id', $user->id());      
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

        return !$q->fetch();
    }
    
    public function isTokenFree($token)
    {
        if(preg_match('#^[0-9]{12}$#', $token))
        {
            $q = $this->_db->prepare('SELECT user_token FROM user WHERE user_token = :token');
            $q->bindValue(':token', $token);
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
        else
        {
            throw new Exception('The token is in an invalid format');
        }
    }
    
    public function renewActivationLink(User $user)
    {
        $q = $this->_db->prepare('UPDATE user SET user_token_expiration = CURTIME() + INTERVAL 1 DAY WHERE user_id = :id');
        $q->bindValue(':token', htmlspecialchars($user->id()));
        $q->execute();
        
        $to = 'juniorwebdesign27@gmail.com;';
        $subject = "Confirmation d'inscription";
        $message = "Voici votre lien d'activation : \n";
        $message.= 'https://billetsimplepourlalaska.000webhostapp.com/Views/confirmRegistration.php?token='.$user->token();
        $from = "us-imm-node1a.000webhost.io";
        $headers = "From: $from";
        
        mail($to,$subject,$message,$headers);
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
    
    public function getUserByToken($token)
    {
        $q = $this->_db->prepare('SELECT * FROM user WHERE user_token = :token');
        $q->bindValue(':token', htmlspecialchars($token));
        $q->execute();
        
        if($a = $q->fetch())
        {
            return new User($this->refineAnswer($a));
        }
    }
    
    public function updateUserLogins(User $user, $username, $password)
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