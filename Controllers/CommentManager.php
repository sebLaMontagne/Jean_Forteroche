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
        $refinedAnswer['isNew']     = (int) $brutAnswer['comment_isNew'];
        
        return $refinedAnswer;
    }
    
    public function saveComment($postId, $author, $content)
    {
        $q = $this->_db->prepare('
            INSERT INTO comment(
                post_id,
                user_id,
                comment_content,
                comment_date,
                comment_isNew)
            VALUES(
                :postId,
                :userId,
                :content,
                NOW(),
                1)');
        
        $q->bindValue(':postId', htmlspecialchars($postId));
        $q->bindValue(':userId', htmlspecialchars($author));
        $q->bindValue(':content', htmlspecialchars($content));
        
        $q->execute();
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