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
        $refinedAnswer['content']       = $brutAnswer['post_content'];
        $refinedAnswer['date']          =       $brutAnswer['post_date'];
        $refinedAnswer['isPublished']   = (int) $brutAnswer['post_isPublished'];
        
        return $refinedAnswer;
    }
    
    public function encode($text)
    {
        //trashes
        $text = preg_replace('@"@','[m]', $text);
        $text = preg_replace('@<p><p>(.+)</p></p>@isU', '[p]$1[/p]', $text);
        $text = preg_replace('@<div><div>(.+)</div></div>@isU', '[div]$1[/div]', $text);
        $text = preg_replace('@<pre><pre>(.+)</pre></pre>@isU', '[pre]$1[/pre]', $text);

        //headings
        $text = preg_replace('@<h1>(.+)</h1>@isU', '[h1]$1[/h1]', $text);
        $text = preg_replace('@<h2>(.+)</h2>@isU', '[h2]$1[/h2]', $text);
        $text = preg_replace('@<h3>(.+)</h3>@isU', '[h3]$1[/h3]', $text);
        $text = preg_replace('@<h4>(.+)</h4>@isU', '[h4]$1[/h4]', $text);
        $text = preg_replace('@<h5>(.+)</h5>@isU', '[h5]$1[/h5]', $text);
        $text = preg_replace('@<h6>(.+)</h6>@isU', '[h6]$1[/h6]', $text);
        //inlines
        $text = preg_replace('@<strong>(.+)</strong>@isU', '[b]$1[/b]', $text);
        $text = preg_replace('@<em>(.+)</em>@isU', '[i]$1[/i]', $text);
        $text = preg_replace('@<span style=\[m\]text-decoration: underline;\[m\]>(.+)</span>@isU', '[u]$1[/u]', $text);
        $text = preg_replace('@<span style=\[m\]text-decoration: line-through;\[m\]>(.+)</span>@isU', '[s]$1[/s]', $text);
        $text = preg_replace('@<sup>(.+)</sup>@isU', '[sup]$1[/sup]', $text);
        $text = preg_replace('@<sub>(.+)</sub>@isU', '[sub]$1[/sub]', $text);
        $text = preg_replace('@<code>(.+)</code>@isU', '[code]$1[/code]', $text);
        //blocks
        $text = preg_replace('@<p>(.+)</p>@isU', '[p]$1[/p]', $text);
        $text = preg_replace('@<blockquote>(.+)</blockquote>@isU', '[quote]$1[/quote]', $text);
        $text = preg_replace('@<div>(.+)</div>@isU', '[div]$1[/div]', $text);
        $text = preg_replace('@<pre>(.+)</pre>@isU', '[pre]$1[/pre]', $text); 

        //styles
        $text = preg_replace('@<p style=\[m\]text-align: (left|center|right|justify);\[m\]>(.+)</p>@isU', '[p $1]$2[/p]', $text);
        $text = preg_replace('@<div style=\[m\]text-align: (left|center|right|justify);\[m\]>(.+)</div>@isU', '[div $1]$2[/div]', $text);
        $text = preg_replace('@<pre style=\[m\]text-align: (left|center|right|justify);\[m\]>(.+)</pre>@isU', '[pre $1]$2[/pre]', $text);

        $text = preg_replace('@<p style=\[m\]padding-left: ([0-9]+)px;\[m\]>(.+)</p>@isU', '[p $1]$2[/p]', $text);
        $text = preg_replace('@<div style=\[m\]padding-left: ([0-9]+)px;\[m\]>(.+)</div>@isU', '[div $1]$2[/div]', $text);
        $text = preg_replace('@<pre style=\[m\]padding-left: ([0-9]+)px;\[m\]>(.+)</pre>@isU', '[pre $1]$2[/pre]', $text);

        $text = preg_replace('@<p style=\[m\]padding-left: ([0-9]+)px; text-align: (left|center|right|justify);\[m\]>(.+)</p>@isU', '[p $1 $2]$3[/p]', $text);
        $text = preg_replace('@<div style=\[m\]padding-left: ([0-9]+)px; text-align: (left|center|right|justify);\[m\]>(.+)</div>@isU', '[div $1 $2]$3[/div]', $text);
        $text = preg_replace('@<pre style=\[m\]padding-left: ([0-9]+)px; text-align: (left|center|right|justify);\[m\]>(.+)</pre>@isU', '[pre $1 $2]$3[/pre]', $text);

        $text = preg_replace('@<p style=\[m\]text-align: (left|center|right|justify); padding-left: ([0-9]+)px;\[m\]>(.+)</p>@isU', '[p $1 $2]$3[/p]', $text);
        $text = preg_replace('@<div style=\[m\]text-align: (left|center|right|justify); padding-left: ([0-9]+)px;\[m\]>(.+)</div>@isU', '[div $1 $2]$3[/div]', $text);
        $text = preg_replace('@<pre style=\[m\]text-align: (left|center|right|justify); padding-left: ([0-9]+)px;\[m\]>(.+)</pre>@isU', '[pre $1 $2]$3[/pre]', $text);
	
	return $text;

        return $text;
    }

    public function decode($text)
    {
        //trashes
        $text = preg_replace('@\[m\]@','"', $text);
        $text = preg_replace('@\[p\]\[p\](.+)\[/p\]\[/p\]@isU', '<p>$1</p>', $text);
        $text = preg_replace('@\[div\]\[div\](.+)\[/div\]\[/div\]@isU', '<div>$1</div>', $text);
        $text = preg_replace('@\[pre\]\[pre\](.+)\[/pre\]\[/pre\]@isU', '<pre>$1</pre>', $text);

        //headings
        $text = preg_replace('@\[h1\](.+)\[/h1\]@isU', '<h1>$1</h1>', $text);
        $text = preg_replace('@\[h2\](.+)\[/h2\]@isU', '<h2>$1</h2>', $text);
        $text = preg_replace('@\[h3\](.+)\[/h3\]@isU', '<h3>$1</h3>', $text);
        $text = preg_replace('@\[h4\](.+)\[/h4\]@isU', '<h4>$1</h4>', $text);
        $text = preg_replace('@\[h5\](.+)\[/h5\]@isU', '<h5>$1</h5>', $text);
        $text = preg_replace('@\[h6\](.+)\[/h6\]@isU', '<h6>$1</h6>', $text);
        //inlines
        $text = preg_replace('@\[b\](.+)\[/b\]@isU', '<strong>$1</strong>', $text);
        $text = preg_replace('@\[i\](.+)\[/i\]@isU', '<em>$1</em>', $text);
        $text = preg_replace('@\[u\](.+)\[/u\]@isU', '<span style="text-decoration: underline;">$1</span>', $text);
        $text = preg_replace('@\[s\](.+)\[/s\]@isU', '<span style="text-decoration: line-through;">$1</span>', $text);
        $text = preg_replace('@\[sup\](.+)\[/sup\]@isU', '<sup>$1</sup>', $text);
        $text = preg_replace('@\[sub\](.+)\[/sub\]@isU', '<sub>$1</sub>', $text);
        $text = preg_replace('@\[code\](.+)\[/code\]@isU', '<code>$1</code>', $text);
        //blocks
        $text = preg_replace('@\[p\](.+)\[/p\]@isU', '<p>$1</p>', $text);
        $text = preg_replace('@\[quote\](.+)\[/quote\]@isU', '<blockquote>$1</blockquote>', $text);
        $text = preg_replace('@\[div\](.+)\[/div\]@isU', '<div>$1</div>', $text);
        $text = preg_replace('@\[pre\](.+)\[/pre\]@isU', '<pre>$1</pre>', $text);

        //styles
        $text = preg_replace('@\[p (left|center|right|justify)\](.+)\[/p\]@isU', '<p style="text-align: $1;">$2</p>', $text);
        $text = preg_replace('@\[div (left|center|right|justify)\](.+)\[/div\]@isU', '<div style="text-align: $1;">$2</div>', $text);
        $text = preg_replace('@\[pre (left|center|right|justify)\](.+)\[/pre\]@isU', '<pre style="text-align: $1;">$2</pre>', $text);

        $text = preg_replace('@\[p ([0-9]+)\](.+)\[/p\]@isU', '<p style="padding-left: $1px;">$2</p>', $text);
        $text = preg_replace('@\[div ([0-9]+)\](.+)\[/div\]@isU', '<div style="padding-left: $1px;">$2</div>', $text);
        $text = preg_replace('@\[pre ([0-9]+)\](.+)\[/pre\]@isU', '<pre style="padding-left: $1px;">$2</pre>', $text);

        $text = preg_replace('@\[p ([0-9]+) (left|center|right|justify)\](.+)\[/p\]@isU', '<p style="padding-left: $1px; text-align: $2;">$3</p>', $text);
        $text = preg_replace('@\[div ([0-9]+) (left|center|right|justify)\](.+)\[/div\]@isU', '<div style="padding-left: $1px; text-align: $2;">$3</div>', $text);
        $text = preg_replace('@\[pre ([0-9]+) (left|center|right|justify)\](.+)\[/pre\]@isU', '<pre style="padding-left: $1px; text-align: $2;">$3</pre>', $text);

        $text = preg_replace('@\[p (left|center|right|justify) ([0-9]+)\](.+)\[/p\]@isU', '<p style="text-align: $1; padding-left: $2px;">$3</p>', $text);
        $text = preg_replace('@\[div (left|center|right|justify) ([0-9]+)\](.+)\[/div\]@isU', '<div style="text-align: $1; padding-left: $2px;">$3</div>', $text);
        $text = preg_replace('@\[pre (left|center|right|justify) ([0-9]+)\](.+)\[/pre\]@isU', '<pre style="text-align: $1; padding-left: $2px;">$3</pre>', $text);

        return $text;
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
    
    public function getChapterByPostId($id)
    {
        $q = $this->_db->prepare('SELECT post_chapter_number FROM post WHERE post_id = :id');
        $q->bindValue(':id', htmlspecialchars($id));
        $q->execute();
        
        return $q->fetch()[0];
    }
    
    public function getNextChapterNumber($chapter)
    {
        if(intval($chapter) > 0)
        {
            $q = $this->_db->prepare('
                SELECT post_chapter_number FROM post 
                WHERE post_chapter_number > :chapter AND post_isPublished = 1
                ORDER BY post_chapter_number ASC');
            $q->bindValue(':chapter', $chapter);
            $q->execute();
            
            return $q->fetch()[0];
        }
    }
    
    public function getPreviousChapterNumber($chapter)
    {
        if(intval($chapter) > 0)
        {
            $q = $this->_db->prepare('
                SELECT post_chapter_number FROM post 
                WHERE post_chapter_number < :chapter AND post_isPublished = 1
                ORDER BY post_chapter_number DESC');
            $q->bindValue(':chapter', $chapter);
            $q->execute();
            
            return $q->fetch()[0];
        }
    }
    
    public function getPublicChaptersCount()
    {
        $q = $this->_db->query('SELECT COUNT(post_id) FROM post WHERE post_isPublished = 1');
        return $q->fetch()[0];
    }
    
    public function getDraftChaptersCount()
    {
        $q = $this->_db->query('SELECT COUNT(post_id) FROM post WHERE post_isPublished = 0');
        return $q->fetch()[0];
    }
    
    public function getAllChaptersCount()
    {
        $q = $this->_db->query('SELECT COUNT(post_id) FROM post');
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
        if(intval($chapter) > 0)
        {   
            $post = $this->getPost($chapter);
            $commentManager = new CommentManager();
            $comments = $commentManager->getPostComments($post);
            
            for($i = 0; $i < count($comments); $i++)
            {
                $commentManager->deleteComment($comments[$i]->id());
            }
            
            $q = $this->_db->prepare('DELETE FROM post WHERE post_chapter_number = :chapter');
            $q->bindValue(':chapter', $chapter);
            $q->execute();
        }
        else
        {
            throw new Exception('The chapter argument must be a strictly positive number');
        }
    }
}