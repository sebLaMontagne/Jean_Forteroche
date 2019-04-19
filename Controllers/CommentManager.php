<?php
require_once('Manager.php');

class CommentManager extends Manager
{
    private function refineAnswer($brutAnswer)
    {
        $refinedAnswer['id']        = (int) $brutAnswer['comment_id'];
        $refinedAnswer['postId']    = (int) $brutAnswer['post_id'];
        $refinedAnswer['userId']    = (int) $brutAnswer['user_id'];
        $refinedAnswer['content']   =       $brutAnswer['comment_content'];
        $refinedAnswer['date']      =       $brutAnswer['comment_date'];
        
        return $refinedAnswer;
    }
    
    public function saveComment($postId, $author, $content)
    {
        if(intval($postId > 0) && intval($postId > 0) && is_string($content))
        {
            $q = $this->_db->prepare('
            INSERT INTO comment(
                post_id,
                user_id,
                comment_content,
                comment_date)
            VALUES(
                :postId,
                :userId,
                :content,
                NOW())');
        
            $q->bindValue(':postId', $postId);
            $q->bindValue(':userId', $author);
            $q->bindValue(':content', htmlspecialchars($content));

            $q->execute();
        }
        else
        {
            throw new Exception('The parameters doesn\'t respect the good format');
        }
    }
    
    public function getComment($id)
    {
        if(intval($id) > 0)
        {
            $q = $this->_db->prepare('SELECT * FROM comment WHERE comment_id = :id');
            $q->bindValue(':id', $id);
            $q->execute();

            return new Comment($this->refineAnswer($q->fetch()));
        }
        else
        {
            throw new Exception('The id must be a stricty positive integer value');
        }
    }
    
    public function getPostComments(Post $post)
    {
        $q = $this->_db->prepare('SELECT * FROM comment WHERE post_id = :postId');
        $q->bindValue(':postId', $post->id());
        $q->execute();
        
        $list = [];
        while($brutAnswer = $q->fetch())
        {
            $refinedAnswer = $this->refineAnswer($brutAnswer);
            $list[] = new Comment($refinedAnswer);
        }
        return $list;
    }
    
    public function getAllCommentsCount()
    {
        return $this->_db->query('SELECT COUNT(comment_id) FROM comment')->fetch()[0];
    }
    
    public function getAllCommentsSortedBy($filter)
    {
        if(is_string($filter))
        {
            $q = $this->_db->query('SELECT * FROM comment');
        
            $list = [];
            while($brutAnswer = $q->fetch())
            {
                $refinedAnswer = $this->refineAnswer($brutAnswer);
                $list[] = new Comment($refinedAnswer);
            }

            if($filter == 'reports')
            {
                function comparator($object1, $object2) { return $object1->reports() < $object2->reports(); }
            }
            elseif($filter == 'likes')
            {
                function comparator($object1, $object2) { return $object1->likes() < $object2->likes(); }
            }
            elseif($filter == 'date')
            {
                function comparator($object1, $object2) { return $object1->date() < $object2->date(); }
            }
            else
            {
                throw new Exception('This filter does not exist');
            }
            usort($list, "comparator");
            return $list;
        }
        else
        {
            throw new Exception('The filter must be a string value');
        }
    }
    
    public function getAllUserCommentsSortedBy($id, $filter)
    {
        if(intval($id > 0) && is_string($filter))
        {
            $q = $this->_db->prepare('SELECT * FROM comment WHERE user_id = :id');
            $q->bindValue(':id', $id);
            $q->execute();
        
            $list = [];
            while($brutAnswer = $q->fetch())
            {
                $refinedAnswer = $this->refineAnswer($brutAnswer);
                $list[] = new Comment($refinedAnswer);
            }

            if($filter == 'reports')
            {
                function comparator($object1, $object2) { return $object1->reports() < $object2->reports(); }
            }
            elseif($filter == 'likes')
            {
                function comparator($object1, $object2) { return $object1->likes() < $object2->likes(); }
            }
            elseif($filter == 'date')
            {
                function comparator($object1, $object2) { return $object1->date() < $object2->date(); }
            }
            else
            {
                throw new Exception('This filter does not exist');
            }
            usort($list, "comparator");
            return $list;
        }
        else
        {
            throw new Exception('The parameters does not respect the good format');
        }
    }
    
    public function getChapterNumberByCommentId($id)
    {
        if(intval($id) > 0)
        {
            $q = $this->_db->prepare('SELECT post_id FROM comment WHERE comment_id = :id');
            $q->bindValue(':id', $id);
            $q->execute();
            
            $postManager = new PostManager();
            return $postManager->getChapterByPostId($q->fetch()[0]);
        }
        else
        {
            throw new Exception('the id must be a strictly positive integer value');
        }
    }
    
    public function isUserTheCommentAuthor($user, $comment)
    {
        if(intval($user) > 0 && intval($comment) > 0)
        {
            $q = $this->_db->prepare('SELECT user_id FROM comment WHERE comment_id = :comment_id AND user_id = :user_id');
            $q->bindValue(':user_id', $user);
            $q->bindValue(':comment_id', $comment);
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
            throw new Exception('the parameters must be strictly positive integer values');
        }
    }
    
    public function deleteComment($id)
    {
        if(intval($id) > 0)
        {   
            $comment = $this->getComment($id);
            $appreciationManager = new AppreciationManager();
            $appreciations = $appreciationManager->getCommentAppreciations($comment);
            
            for($i = 0; $i < count($appreciations); $i++)
            {
                $appreciationManager->deleteAppreciationById($appreciations[$i]->id());
            }
            
            $q = $this->_db->prepare('DELETE FROM comment WHERE comment_id = :comment_id');
            $q->bindValue(':comment_id', $id);
            $q->execute();
        }
        else
        {
            throw new Exception('The chapter argument must be a strictly positive number');
        }
    }
    
    public function deletePostCommentsById($id)
    {
        if(intval($id) > 0)
        {
            $q = $this->_db->prepare('DELETE FROM comment WHERE post_id = :id');
            $q->bindValue(':id', $id);
            $q->execute();
        }
        else
        {
            throw new Exception('the id must be a strictly positive integer value');
        }
    }
}