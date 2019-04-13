<?php

require_once('Manager.php');

class AppreciationManager extends Manager
{
    public function addAppreciation($commentId, $userId, $appreciationType = 'like')
    {
        if(intval($commentId) > 0)
        {
            if(intval($userId) > 0)
            {
                if($appreciationType == 'like')
                {
                    $q = $this->_db->prepare('
                        INSERT INTO appreciation(
                            comment_id,
                            user_id,
                            appreciation_isLike,
                            appreciation_isReport)
                        VALUES(
                            :comment_id,
                            :user_id,
                            1,
                            0)');
                }
                elseif($appreciationType == 'report')
                {
                    $q = $this->_db->prepare('
                        INSERT INTO appreciation(
                            comment_id,
                            user_id,
                            appreciation_isLike,
                            appreciation_isReport)
                        VALUES(
                            :comment_id,
                            :user_id,
                            0,
                            1)');
                }
                else
                {
                    throw new Exception('The appreciation type must be either "like" or "report"');
                }
                
                $q->bindValue(':comment_id', $commentId);
                $q->bindValue(':user_id', $userId);
                $q->execute();
            }
            else
            {
                throw new Exception('The user id must be a strictly positive integer value');
            }
        }
        else
        {
            throw new Exception('The comment id must be a strictly positive integer value');
        }
    }
    
    public function getCommentAppreciation($commentId, $appreciationType = 'likes')
    {
        if(intval($commentId) > 0)
        {
            if($appreciationType == 'likes')
            {
                $q = $this->_db->prepare('SELECT COUNT(comment_id) FROM appreciation WHERE comment_id = :id AND appreciation_isLike = 1');
            }
            elseif($appreciationType == 'reports')
            {
                $q = $this->_db->prepare('SELECT COUNT(comment_id) FROM appreciation WHERE comment_id = :id AND appreciation_isReport = 1');
            }
            else
            {
                throw new Exception('The appreciation type must be either "likes" or "reports"');
            }
            
            $q->bindValue(':id', $commentId);
            $q->execute();
            return $q->fetch()[0];
        }
        else
        {
            throw new Exception('The comment id must be a strictly positive integer value');
        }
    }
    
    public function isUserAlreadyAppreciated($user, $comment)
    {
        
    }
}