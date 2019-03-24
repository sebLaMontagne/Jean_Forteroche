<?php
require_once('Manager.php');

class UserManager extends Manager
{
    public function addUser(User $user)
    {
        $q = $this->_db->prepare('
            INSERT INTO user(
                user_name, 
                user_password, 
                user_email,  
                user_isAuthor, 
                user_isAdmin) 
            VALUES(
                :userName, 
                :userPassword,
                :userEmail,
                :userIsAuthor,
                :userIsAdmin)');
        
        $q->bindValue(':userName', $user->name());
        $q->bindValue(':userPassword', $user->password());
        $q->bindValue(':userEmail', $user->email());
        $q->bindValue(':userIsAuthor', $user->isAuthor());
        $q->bindValue(':userIsAdmin', $user->isAdmin());
        
        $q->execute();
    }
    
    public function getUser($userName, $passWord)
    {
        $q = $this->_db->prepare('SELECT * FROM users WHERE name = :userName AND password = :password');
        
        $q->bindValue(':userName', $userName);
        $q->bindValue(':password', $passWord);
        
        $q->execute();
        return $q->fetch();
    }
    
    public function updateUser($id, $newUserName, $newPassWord)
    {
        $q = $this->_db->prepare('UPDATE users SET name = :userName, password = :password WHERE id = :id');
        
        $q->bindValue(':userName', $newUserName);
        $q->bindValue(':password', $newPassWord);
        $q->bindValue(':id', $id);
        
        $q->execute();
    }
    
    public function deleteUser($id)
    {
        $q = $this->_db->prepare('DELETE FROM users WHERE id = :id');
        $q->bindValue(':id', $id);
        $q->execute();
    }
}