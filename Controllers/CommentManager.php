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
            $list[] = new Post($refinedAnswer);
        }
        return $list;
    }
}