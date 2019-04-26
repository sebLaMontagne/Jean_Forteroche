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
        $refinedAnswer['isAdmin']           = (int) $brutAnswer['user_isAdmin'];
        $refinedAnswer['isBanned']          = (int) $brutAnswer['user_isBanned'];
        $refinedAnswer['token']             =       $brutAnswer['user_token'];
        $refinedAnswer['isActivated']       = (int) $brutAnswer['user_activation'];
        $refinedAnswer['tokenExpiration']   =       $brutAnswer['user_token_expiration'];
        
        return $refinedAnswer;
    }
    
    public function addUser($name, $password, $email)
    {
        if(is_string($name) && is_string($password) && filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $q = $this->_db->prepare('
            INSERT INTO user(
                user_name, 
                user_password, 
                user_email,
                user_isAdmin,
                user_token,
                user_activation,
                user_token_expiration,
                user_isBanned) 
            VALUES(
                :userName,
                :userPassword,
                :userEmail,
                0,
                :userToken,
                0,
                CURTIME() + INTERVAL 1 DAY,
                0)');
        
            do{
                $confirmToken = '';
                for($i = 0; $i < 12; $i++)
                {
                    $confirmToken .= mt_rand(0,9);
                }   
            }while(!$this->isTokenFree($confirmToken));


            $q->bindValue(':userName', htmlspecialchars($name));
            $q->bindValue(':userPassword', password_hash($password, PASSWORD_DEFAULT));
            $q->bindValue(':userEmail', htmlspecialchars($email));
            $q->bindValue(':userToken', $confirmToken);

            $q->execute();
          
            $header = "From: \"Jean Forteroche\"<jean.forteroche@jeanforteroche.fr>\n";
            $header.= "Reply-to: \"Jean Forteroche\"<jean.forteroche@jeanforteroche.fr>\n";
            $header.= "MIME-Version: 1.0\n";
            $header.= "Content-Type: text/html;";

            mail($email, 'validation de compte', '<html><head></head><body><p>Voici votre lien d\'activation : <a href="http://jeanforteroche.fr/confirmRegistration-'.$confirmToken.'">activer</a></p></body></html>', $header);
        }
        else
        {
            throw new Exception('Invalid parameters');
        }
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
        if(is_string($name))
        {
            $q = $this->_db->prepare('SELECT user_name FROM user WHERE user_name = :userName');
        
            $q->bindValue(':userName', htmlspecialchars($name));
            $q->execute();

            return !$q->fetch();
        }
        else
        {
            throw new Exception('the username parameter must be a string value');
        }
    }
    
    public function isEmailFree($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $q = $this->_db->prepare('SELECT user_email FROM user WHERE user_email = :userEmail');
        
            $q->bindValue(':userEmail', $email);
            $q->execute();

            return !$q->fetch();
        }
        else
        {
            throw new Exception('Invalid email format');
        }
    }
    
    public function isTokenFree($token)
    {
        if(preg_match('#^[0-9]{12}$#', $token))
        {
            $q = $this->_db->prepare('SELECT user_token FROM user WHERE user_token = :token');
            $q->bindValue(':token', $token);
            $q->execute();
            
            return !$q->fetch();
        }
        else
        {
            throw new Exception('Invalid token format');
        }
    }
    
    public function isUserExist($id)
    {
        if(intval($id) > 0)
        {
            $q = $this->_db->prepare('SELECT COUNT(user_id) FROM user WHERE user_id = :id');
            $q->bindValue(':id', $id);
            $q->execute();
            
            return $q->fetch();
        }
        else
        {
            throw new Exception('The user id must be a stictly positive integer value');
        }
    }
    
    public function renewActivationLink(User $user)
    {
        $q = $this->_db->prepare('UPDATE user SET user_token_expiration = CURTIME() + INTERVAL 1 DAY WHERE user_id = :id');
        $q->bindValue(':token', $user->id());
        $q->execute();
      
      	$header = "From: \"Jean Forteroche\"<jean.forteroche@jeanforteroche.fr>\n";
        $header.= "Reply-to: \"Jean Forteroche\"<jean.forteroche@jeanforteroche.fr>\n";
        $header.= "MIME-Version: 1.0\n";
        $header.= "Content-Type: text/html;\n";

        mail($user->email(), 'validation de compte', '<html><head></head><body><p>Voici votre lien d\'activation : <a href="http://jeanforteroche.fr/confirmRegistration-'.$user->token().'">activer</a></p></body></html>', $header);
    }
    
    public function getUserById($id)
    {
        if(intval($id) > 0)
        {
            $q = $this->_db->prepare('SELECT * FROM user WHERE user_id = :userId');
            $q->bindValue(':userId', $id);
            $q->execute();

            if($a = $q->fetch())
            {
                return new User($this->refineAnswer($a));
            }
        }
        else
        {
            throw new Exception('The id must be a strictly positive integer value');
        }
        
    }
    
    public function getUserByLogins($username, $password)
    {
        if(is_string($username) && is_string($password))
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
        else
        {
            throw new Exception('the parameters must be string values');
        }
    }
    
    public function getUserByEmail($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $q = $this->_db->prepare('SELECT * FROM user WHERE user_email = :email');
            $q->bindValue(':email', htmlspecialchars($email));
            $q->execute();

            if($a = $q->fetch())
            {
                return new User($this->refineAnswer($a));
            }
        }
        else
        {
            'Invalid email format';
        }
    }
    
    public function getUserByToken($token)
    {
        if(preg_match('#^[0-9]{12}$#', $token))
        {
            $q = $this->_db->prepare('SELECT * FROM user WHERE user_token = :token');
            $q->bindValue(':token', htmlspecialchars($token));
            $q->execute();

            if($a = $q->fetch())
            {
                return new User($this->refineAnswer($a));
            }
        }
        else
        {
            throw new Exception('Invalid token parameter');
        }
    }
    
    public function getAllUsers($filter)
    {
        if(is_string($filter))
        {
            if($filter == 'all')
            {
                $q = $this->_db->query('SELECT * FROM user');
            }
            elseif($filter == 'admins')
            {
                $q = $this->_db->query('SELECT * FROM user WHERE user_isAdmin = 1');
            }
            elseif($filter == 'banned')
            {
                $q = $this->_db->query('SELECT * FROM user WHERE user_isBanned = 1');
            }
            elseif($filter == 'users')
            {
                $q = $this->_db->query('SELECT * FROM user WHERE user_isBanned = 0 AND user_isAdmin = 0');
            }
            else
            {
                throw new Exception('Non-supported filter');
            }

            $list = [];
            while($a = $q->fetch())
            {
                $list[] = new User($this->refineAnswer($a));
            }
            return $list;
        }
        else
        {
            throw new Exception('The filter must be a string value');
        }
    }
    
    public function getAllUsersCount()
    {
        return $this->_db->query('SELECT COUNT(user_id) FROM user WHERE user_activation = 1')->fetch()[0];
    }
    
    public function getBannedUsersCount()
    {
        return $this->_db->query('SELECT COUNT(user_id) FROM user WHERE user_activation = 1 AND user_isBanned = 1')->fetch()[0];
    }
    
    public function getAdminUsersCount()
    {
        return $this->_db->query('SELECT COUNT(user_id) FROM user WHERE user_activation = 1 AND user_isAdmin = 1')->fetch()[0];
    }
    
    public function getNormalUsersCount()
    {
        return $this->_db->query('SELECT COUNT(user_id) FROM user WHERE user_activation = 1 AND user_isAdmin = 0 AND user_isBanned = 0')->fetch()[0];
    }
    
    public function updateUserLogins(User $user, $username, $password)
    {
        if(is_string($username) && is_string($password))
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
        else
        {
            throw new Exception('The username and password must be string values');
        }
    }
    
    public function banUser($id)
    {
        if(intval($id) > 0)
        {
            $q = $this->_db->prepare('
            UPDATE  user 
            SET     user_isBanned   = 1
            WHERE   user_id         = :id');
            $q->bindValue(':id', $id);
            $q->execute();
        }
        
    }
    
    public function unbanUser($id)
    {
        if(intval($id) > 0)
        {
            $q = $this->_db->prepare('
            UPDATE  user 
            SET     user_isBanned   = 0
            WHERE   user_id         = :id');
            $q->bindValue(':id', $id);
            $q->execute();
        }
    }
}