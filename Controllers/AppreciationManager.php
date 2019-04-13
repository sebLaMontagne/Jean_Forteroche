<?php

require_once('Manager.php');

class AppreciationManager extends Manager
{
    private function refineAnswer($brutAnswer)
    {
        $refinedAnswer['id']        = (int) $brutAnswer['appreciation_id'];
        $refinedAnswer['commentId'] = (int) $brutAnswer['comment_id'];
        $refinedAnswer['userId']    = (int) $brutAnswer['user_id'];
        $refinedAnswer['isLike']    = (int) $brutAnswer['appreciation_isLike'];
        $refinedAnswer['isReport']  = (int) $brutAnswer['appreciation_isReport'];

        return $refinedAnswer;
    } 
    
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
    
    public function getCommentAppreciations(Comment $comment)
    {
        $q = $this->_db->prepare('SELECT * FROM appreciation WHERE comment_id = :comment_id');
        $q->bindValue(':comment_id', $comment->id());
        $q->execute();
        
        $list = [];
        while($brutAnswer = $q->fetch())
        {
            $refinedAnswer = $this->refineAnswer($brutAnswer);
            $list[] = new Appreciation($refinedAnswer);
        }
        return $list;
    }
    
    public function countCommentAppreciations($commentId, $appreciationType = 'likes')
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
    
    public function isAppreciationExist($user, $comment)
    {
        if(intval($user) > 0 && intval($comment) > 0)
        {
            $q = $this->_db->prepare('SELECT user_id FROM appreciation WHERE comment_id = :comment_id AND user_id = :user_id');
            $q->bindValue(':comment_id', $comment);
            $q->bindValue(':user_id', $user);
            $q->execute();
            
            if($q->fetch())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            throw new Exception('The parameters must be integer values');
        }
    }
    
    public function appreciationIsLike($user, $comment)
    {
        if(intval($user) > 0 && intval($comment) > 0)
        {
            $q = $this->_db->prepare('SELECT appreciation_isLike FROM appreciation WHERE comment_id = :comment_id AND user_id = :user_id');
            $q->bindValue(':comment_id', $comment);
            $q->bindValue(':user_id', $user);
            $q->execute();
            
            return $q->fetch()[0];
        }
        else
        {
            throw new Exception('The parameters must be integer values');
        }
    }
    
    public function appreciationIsReport($user, $comment)
    {
        if(intval($user) > 0 && intval($comment) > 0)
        {
            $q = $this->_db->prepare('SELECT appreciation_isReport FROM appreciation WHERE comment_id = :comment_id AND user_id = :user_id');
            $q->bindValue(':comment_id', $comment);
            $q->bindValue(':user_id', $user);
            $q->execute();
            
            return $q->fetch()[0];
        }
        else
        {
            throw new Exception('The parameters must be integer values');
        }
    }
    
    public function deleteAppreciation($user, $comment)
    {
        if(intval($user) > 0 && intval($comment) > 0)
        {
            $q = $this->_db->prepare('DELETE FROM appreciation WHERE comment_id = :comment_id AND user_id = :user_id');
            $q->bindValue(':comment_id', $comment);
            $q->bindValue(':user_id', $user);
            $q->execute();
        }
        else
        {
            throw new Exception('The parameters must be integer values');
        }
    }
    
    public function deleteAppreciationById($id)
    {
        if(intval($id) > 0)
        {
            $q = $this->_db->prepare('DELETE FROM appreciation WHERE appreciation_id = :appreciation_id');
            $q->bindValue(':appreciation_id', $id);
            $q->execute();
        }
        else
        {
            throw new Exception('The parameters must be integer values');
        }
    }
}