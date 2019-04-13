<?php

require_once('Manager.php');

class AppreciationManager extends Manager
{
    public function addAppreciation($commentId, $userId, )
    {
        
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
}