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
        $refinedAnswer['isPublished']   = (int) $brutAnswer['post_isPublished'];
        
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
    
    public function getAllPublishedPosts()
    {
        $q = $this->_db->query('SELECT * FROM post WHERE post_isPublished = 1 ORDER BY post_chapter_number');
        
        $list = [];
        while($brutAnswer = $q->fetch())
        {
            $refinedAnswer = $this->refineAnswer($brutAnswer);
            $list[] = new Post($refinedAnswer);
        }
        return $list;
    }
    
    public function getPost($chapter)
    {
        $q = $this->_db->prepare('SELECT * FROM post WHERE post_chapter_number = :chapter');
        $q->bindValue(':chapter',htmlspecialchars($chapter));
        $q->execute();

        return new Post($this->refineAnswer($q->fetch()));
    }
    
    public function getPostIDbyChapter($chapter)
    {
        $q = $this->_db->prepare('SELECT post_id FROM post WHERE post_chapter_number = :chapter');
        $q->bindValue(':chapter', htmlspecialchars($chapter));
        $q->execute();
        
        return $q->fetch()[0];
    }
    
    public function isChapterExist($chapter)
    {
        $q = $this->_db->prepare('SELECT * FROM post WHERE post_chapter_number = :chapter');
        $q->bindValue(':chapter',htmlspecialchars($chapter));
        $q->execute();
        
        if($a = $q->fetch())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function savePost($author, $chapter, $title, $content, $publish)
    {
        $q = $this->_db->prepare('
            INSERT INTO post(
                user_id,
                post_title,
                post_chapter_number,
                post_content,
                post_date,
                post_isPublished)
            VALUES(
                :author,
                :title,
                :chapter,
                :content,
                NOW(),
                :publish)');
        
        $q->bindValue(':author', htmlspecialchars($author));
        $q->bindValue(':title', htmlspecialchars($title));
        $q->bindValue(':chapter', htmlspecialchars($chapter));
        $q->bindValue(':content', htmlspecialchars($content));
        $q->bindValue(':publish', htmlspecialchars($publish));
        
        $q->execute();
    }
    
    public function updatePost($id, $chapter, $title, $content, $publish)
    {   
        $q = $this->_db->prepare('
            UPDATE  post
            SET     post_chapter_number     = :chapter,
                    post_title              = :title,
                    post_content            = :content,
                    post_isPublished        = :publish
            WHERE   post_id                 = :id');
        
        $q->bindValue(':chapter', htmlspecialchars($chapter));
        $q->bindValue(':title', htmlspecialchars($title));
        $q->bindValue(':content', htmlspecialchars($content));
        $q->bindValue(':publish', htmlspecialchars($publish));
        $q->bindValue(':id', htmlspecialchars($id));
        
        $q->execute();
        
    }
    
    public function deletePost($chapter)
    {
        $q = $this->_db->prepare('DELETE FROM post WHERE post_chapter_number = :chapter');
        $q->bindValue(':chapter', htmlspecialchars($chapter));
        $q->execute();
    }
}