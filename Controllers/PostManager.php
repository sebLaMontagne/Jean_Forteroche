<?php
require_once('Manager.php');

class PostManager extends Manager
{
    private function refineAnswer($brutAnswer)
    {
        $refinedAnswer['id']            = (int) $brutAnswer['post_id'];
        $refinedAnswer['authorId']      = (int) $brutAnswer['user_id'];
        $refinedAnswer['title']         =       $brutAnswer['post_title'];
        $refinedAnswer['chapterNumber'] = (int) $brutAnswer['post_chapter_number'];
        $refinedAnswer['content']       =       $brutAnswer['post_content'];
        $refinedAnswer['date']          =       $brutAnswer['post_date'];
        
        return $refinedAnswer;
    }
    
    public function getAllPosts()
    {
        $q = $this->_db->query('SELECT * FROM post ORDER BY post_chapter_number');
        
        $list = [];
        while($brutAnswer = $q->fetch())
        {
            $refinedAnswer = $this->refineAnswer($brutAnswer);
            $list[] = new Post($refinedAnswer);
        }
        return $list;
    }
    
    public function getPost($id)
    {
        $q = $this->_db->prepare('SELECT * FROM post WHERE post_id = :postId');
        $q->bindValue(':postId',htmlspecialchars($id));
        $q->execute();
        
        return new Post($this->refineAnswer($q->fetch()));
    }
}