<?php

class Controller
{  
    private $userManager;
    private $postManager;
    private $commentManager;
    private $appreciationManager;
    
    private $frontTemplate = 'views/front/template.php';
    private $backTemplate = 'views/back/template.php';
    
    private function display($view, $parameters = [])
    {
        ob_start();
        extract($parameters);
        require($view);
        return ob_get_clean();
    }
    
    private function forbidAccess()
    {
        $_SESSION['refresh'] = 1;
        unset($_SESSION['refresh']);

        if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
        {
            header("location:javascript://history.go(-1)");
            exit();
        }
    }
    
    public function home()
    {   
        $title = 'Billet simple pour l\'Alaska - Accueil';
        $content = $this->display('views/front/home.php');
        include($this->frontTemplate);
    }
    
    public function notFound()
    {
        $title = 'Page non trouvée';
        $content = $this->display('views/front/notFound.php');       
        include($this->frontTemplate);
    }
    
    public function about()
    {
        $title = 'Billet simple pour l\'Alaska - A propos de l\'auteur';
        $content = $this->display('views/front/about.php');
        include($this->frontTemplate);
    }
    
    public function chapters()
    {   
        $posts = $this->postManager->getAllPublishedPosts();
    
        $title = 'Billet simple pour l\'Alaska - Liste des chapitres';
        $content = $this->display('views/front/chapters.php', compact('posts'));
        include($this->frontTemplate);
    }
    
    public function chapter()
    {
        if(isset($_GET) && !empty($_GET['number']) && $this->postManager->isChapterExist($_GET['number']) && !$this->postManager->isChapterDrafted($_GET['number']))
        {
            //Enregistrement commentaire
            
            $selectedPost = $this->postManager->getPost($_GET['number']);
            $postDate = new DateTime($selectedPost->Date());
            $postContent = $this->postManager->decode($selectedPost->Content());
            $comments = $this->commentManager->getPostComments($selectedPost);
            $isPreviousChapterExists = $this->postManager->isChapterExist($this->postManager->getPreviousChapterNumber($_GET['number']));
            $isNextChapterExists = $this->postManager->isChapterExist($this->postManager->getNextChapterNumber($_GET['number']));
            
            if(!empty($_POST['comment']))
            {
                $this->commentManager->saveComment($selectedPost->id(), $_SESSION['id'], $_POST['comment']);
                header('Location: index.php?action=chapter&number='.$_GET['number']);
                exit();
            }
            
            $title = 'Billet simple pour l\'Alaska - Chapitre '.$selectedPost->chapterNumber(). ' : '.$selectedPost->Title();
            $content = $this->display('views/front/chapter.php', compact('selectedPost','postDate','postContent','comments','isPreviousChapterExists','isNextChapterExists'));
        }
        else
        {
            $this->notFound();
        }
        
        include($this->frontTemplate);
    }
    
    public function contact()
    {
        if(isset($_POST['message']))
        {
            if(isset($_SESSION['pseudo']) && isset($_SESSION['email']))
            {
                $subject = 'Message concernant Billet simple pour l\'Alaska de '.$_SESSION['pseudo'];
                $from = '"'.$_SESSION['pseudo'].'"<'.$_SESSION['email'].'>';
                $reply = "Reply-to: \"".$_SESSION['pseudo']."\"<".$_SESSION['email'].">\n";
            }
            elseif(isset($_POST['name']) && isset($_POST['mail']))
            {
                $subject = 'Message concernant Billet simple pour l\'Alaska de '.$_POST['name'];
                $from = '"'.$_POST['name'].'"<'.$_POST['mail'].'>';
                $reply = "Reply-to: \"".$_POST['name']."\"<".$_POST['mail'].">\n";
            }

            $header = "From: ".$from."\n";
            $header.= "Reply-to:".$reply;
            $header.= "MIME-Version: 1.0\n";
            $header.= "Content-Type: text/plain;";

            mail('seb-roche@orange.fr', $subject, $_POST['message'], $header);
        }
            
        $title = 'Billet simple pour l\'Alaska - Contact';
        $content = $this->display('views/front/contact.php');
        include($this->frontTemplate);
    }
    
    public function login()
    {
        if(!empty($_SESSION['pseudo']))
        {
            header('location: index.php?action=home');
            exit();
        }
        if(!empty($_POST))
        {
            $user = $this->userManager->getUserByLogins($_POST['name'], $_POST['password']);
        }
        $title = 'Billet simple pour l\'Alaska - Connection';
        $content = $this->display('views/front/login.php', compact('user'));
        include($this->frontTemplate);
    }
    
    public function logout()
    {
        if(isset($_POST['logout']) && $_POST['logout'] == 'yes')
        {
            session_destroy();
            header('Location:index.php?action=home');
            exit();
        }
        
        $title = 'Billet simple pour l\'Alaska - Déconnection';
        $content = $this->display('views/front/logout.php');
        include($this->frontTemplate);
    }
    
    public function leaveAppreciation()
    {
        if(isset($_SESSION['isAdmin']))
        {
            if(isset($_GET['type']) && isset($_GET['target']))
            {
                if(($_GET['type'] == 'like' || $_GET['type'] == 'report') && intval($_GET['target']) > 0 && !$this->appreciationManager->isAppreciationExist($_SESSION['id'], $_GET['target']))
                {
                    $this->appreciationManager->addAppreciation($_GET['target'], $_SESSION['id'], $_GET['type']);
                }
                elseif(($_GET['type'] == 'reset' && $this->appreciationManager->isAppreciationExist($_SESSION['id'], $_GET['target'])))
                {
                    $this->appreciationManager->deleteAppreciation($_SESSION['id'], $_GET['target']);
                }

                header('Location:index.php?action=chapter&number='.$this->commentManager->getChapterNumberByCommentId($_GET['target']));
                exit();
            }
            else
            {
                header('Location:index.php?action=home.php');
                exit();
            }
        }
        else
        {
            header("location:javascript://history.go(-1)");
            exit();
        }
    }
    
    public function register()
    {
        if(!empty($_SESSION['pseudo']))
        {
            header('location:index.php?action=home');
            exit();
        }
        
        if(isset($_POST['name']))   {$isUserNameFree = $this->userManager->isUsernameFree($_POST['name']);}
        if(isset($_POST['email']))  {$isEmailFree = $this->userManager->isEmailFree($_POST['email']);}
        if(isset($_GET['mail']))    {$user = $this->userManager->getUserByEmail($_GET['mail']);}
        
        if(isset($_POST['name']) && isset($_POST['email']) && $this->userManager->isUsernameFree($_POST['name']) && $this->userManager->isEmailFree($_POST['email']))
        {
            $this->userManager->addUser($_POST['name'], $_POST['password'], $_POST['email']);
        }
        
        if(isset($user) && isset($_POST['name']) && isset($_POST['password']) && isset($_GET['mail']) && $this->userManager->getUserByEmail($_GET['mail']) != null)
        {
            $this->userManager->updateUserLogins($user, $_POST['name'], $_POST['password']);
        }
        
        $title = 'Billet simple pour l\'Alaska - Inscription';
        $content = $this->display('views/front/register.php',compact('isUserNameFree','isEmailFree','user'));
        include($this->frontTemplate);
    }
    
    public function reset()
    {
        if(isset($_POST['email'])) { $user = $this->userManager->getUserByEmail($_POST['email']); }
        
        if(!empty($user))
        {
            $header = "From: \"Jean Forteroche\"<jean.forteroche@jeanforteroche.fr>\n";
            $header.= "Reply-to: \"Jean Forteroche\"<jean.forteroche@jeanforteroche.fr>\n";
            $header.= "MIME-Version: 1.0\n";
            $header.= "Content-Type: text/html;";

            mail($_POST['email'], 'réinitialisation de compte', '<html><head></head><body><p>Voici votre lien de réinitialisation : <a href="localhost/P4-2/index.php?action=register&mail='.$_POST['email'].'">réinitialiser</a></p></body></html>', $header);
        }
        
        $title = 'Billet simple pour l\'Alaska - Réinitialisation';
        $content = $this->display('views/front/reset.php', compact('user'));
        include($this->frontTemplate);
    }
    
    public function admin()
    {
        $this->forbidAccess();
        
        $publicChaptersCount = $this->postManager->getPublicChaptersCount();
        $draftedChaptersCount = $this->postManager->getDraftChaptersCount();
        $allCommentCount = $this->commentManager->getAllCommentsCount();
        $adminUsersCount = $this->userManager->getAdminUsersCount();
        $normalUsersCount = $this->userManager->getNormalUsersCount();
        $bannedUsersCount = $this->userManager->getBannedUsersCount();
        
        $title = 'Billet simple pour l\'Alaska - Panneau d\'administration';
        $content = $this->display('views/back/admin.php', compact('publicChaptersCount','draftedChaptersCount','allCommentCount','adminUsersCount','normalUsersCount','bannedUsersCount'));
        include($this->backTemplate);
    }
    
    public function backChapters()
    {
        $this->forbidAccess();
        
        $list = $this->postManager->getAllPosts();
        
        $title = 'Billet simple pour l\'Alaska - Gestion des chapitres';
        $content = $this->display('views/back/backChapters.php',compact('list'));
        include($this->backTemplate);
    }
    
    public function newPost()
    {
        $this->forbidAccess();
        
        if(isset($_SESSION['data']))
        {
            $dataContent = $this->postManager->decode($_SESSION['data']['content']);   
        }
        
        $title = 'Billet simple pour l\'Alaska - Nouveau chapitre';
        $content = $this->display('views/back/newPost.php');
        include($this->backTemplate);
    }
    
    public function confirmNewPost()
    {
        $this->forbidAccess();
        
        $isChapterExists = $this->postManager->isChapterExist($_POST['chapterNumber']);
        $encodedContent = $this->postManager->encode($_POST['content']);
        
        if(isset($_POST['confirmation']))
        {
            if($_POST['confirmation'] == 'yes')
            {
                $this->postManager->updatePost($postManager->getPostIDbyChapter($_POST['chapterNumber']), $_POST['title'], $postManager->encode($_POST['content']), $_POST['publish']);
                header('Location: index.php?action=backChapters');
                exit();
            }
            elseif($_POST['confirmation'] == 'no')
            {
                $_SESSION['data']['chapterNumber'] = $_POST['chapterNumber'];
                $_SESSION['data']['title'] = $_POST['title'];
                $_SESSION['data']['content'] = $_POST['content'];
                $_SESSION['data']['publish'] = $_POST['publish'];
                header('location: index.php?action=newPost');
                exit();
            }
        }
        
        if(!$this->postManager->isChapterExist($_POST['chapterNumber']))
        {
            $this->postManager->savePost($_POST['chapterNumber'], $_POST['title'], $this->postManager->encode($_POST['content']), intval($_POST['publish']));
            header('Location: index.php?action=backChapters');
            exit();
        }
        
        $title = 'Billet simple pour l\'Alaska - Confirmation de chapitre';
        $content = $this->display('views/back/confirmNewPost.php',compact('isChapterExists','encodedContent'));
        include($this->backTemplate);
    }
    
    public function updatePost()
    {
        $this->forbidAccess();
        
        if(!empty($_GET['target']) && intval($_GET['target']) > 0 && $this->postManager->isChapterExist($_GET['target']))
        {
            $selectedChapter = $this->postManager->getPost($_GET['target']);
            $chapterContent = $this->postManager->decode($selectedChapter->content());
            
            $title = 'Billet simple pour l\'Alaska - Mise à jour de chapitre';
            $content = $this->display('views/back/updatePost.php', compact('selectedChapter','chapterContent'));
            include($this->backTemplate);
        }
        else
        {
            header('Location:index.php?action=admin');
            exit();
        }
    }
    
    public function confirmUpdatePost()
    {
        $this->forbidAccess();
        
        $this->postManager->updatePost($this->postManager->getPostIDbyChapter($_POST['chapterNumber']), $_POST['title'], $this->postManager->encode($_POST['content']), $_POST['publish']);
        header('Location: index.php?action=backChapters');
        exit();
    }
    
    public function deletePost()
    {
        $this->forbidAccess();
        
        if(!empty($_GET['target']) && $this->postManager->isChapterExist($_GET['target']))
        {
            $isChapterExists = $this->postManager->isChapterExist($_GET['target']);
            
            if(isset($_POST['delete']) && $_POST['delete'] == 'yes')
            {
                $this->postManager->deletePost($_GET['target']);
                header('Location: index.php?action=backChapters');
                exit();
            }

            if(isset($_POST['delete']) && $_POST['delete'] == 'no')
            {
                header('Location: index.php?action=backChapters');
                exit();
            }
            
            $title = 'Billet simple pour l\'Alaska - Suppression de chapitre';
            $content = $this->display('views/back/deletePost.php', compact('isChapterExists'));
            include($this->backTemplate);
        }
        else
        {
            header('Location:index.php?action=admin');
            exit();
        }
    }
    
    public function backComments()
    {
        $this->forbidAccess();
        
        if(empty($_GET['sortedBy']) || ($_GET['sortedBy'] != 'reports' && $_GET['sortedBy'] != 'likes' && $_GET['sortedBy'] != 'date'))
        {
            header('Location: index.php?action=admin');
            exit();
        }
        else
        {
            if(empty($_GET['target']))
            {
                $comments = $this->commentManager->getAllCommentsSortedBy($_GET['sortedBy']);
            }
            elseif(!empty($_GET['target']) && $this->userManager->isUserExist($_GET['target']))
            {
                $comments = $this->commentManager->getAllUserCommentsSortedBy($_GET['target'], $_GET['sortedBy']);
            }
            else
            {
                header('Location: index.php?action=admin');
                exit();
            }
        }
        
        $title = 'Billet simple pour l\'Alaska - Liste des commentaires';
        $content = $this->display('views/back/backComments.php', compact('comments'));
        include($this->backTemplate);
    }
    
    public function filterComments()
    {
        $this->forbidAccess();
        
        if(!empty($_POST['sortedBy']) && empty($_POST['target']))
        {
            if($_POST['sortedBy'] == 'reports')
            {
                header('Location: index.php?action=backComments&sortedBy='.$_POST['sortedBy']);
                exit();
            }
            elseif($_POST['sortedBy'] == 'likes')
            {
                header('Location: index.php?action=backComments&sortedBy='.$_POST['sortedBy']);
                exit();
            }
            elseif($_POST['sortedBy'] == 'date')
            {
                header('Location: index.php?action=backComments&sortedBy='.$_POST['sortedBy']);
                exit();
            }
            else
            {
                header('Location: index.php?action=admin');
                exit();
            }
        }
        elseif(!empty($_POST['sortedBy']) && !empty($_POST['target']) && intval($_POST['target']) > 0)
        {
            if($_POST['sortedBy'] == 'reports')
            {
                header('Location: index.php?action=backComments&sortedBy='.$_POST['sortedBy'].'&target='.$_POST['target']);
                exit();
            }
            elseif($_POST['sortedBy'] == 'likes')
            {
                header('Location: index.php?action=backComments&sortedBy='.$_POST['sortedBy'].'&target='.$_POST['target']);
                exit();
            }
            elseif($_POST['sortedBy'] == 'date')
            {
                header('Location: index.php?action=backComments&sortedBy='.$_POST['sortedBy'].'&target='.$_POST['target']);
                exit();
            }
            else
            {
                header('Location: index.php?action=admin');
                exit();
            }
        }
        else
        {
            header('Location: index.php?action=admin');
            exit();
        }
    }
    
    public function confirmCommentSuppression()
    {   
        $this->forbidAccess();
        
        if(isset($_GET['target']) && intval($_GET['target']) > 0)
        {
            $title = 'Billet simple pour l\'Alaska - Suppression de commentaire';
            $content = $this->display('views/back/confirmCommentSuppression.php');
            include($this->backTemplate);
            
            if(isset($_POST['confirmation']))
            {
                if($_POST['confirmation'] == 'yes')
                {
                    $this->commentManager->deleteComment($_GET['target']);
                    header('Location: index.php?action=backComments&sortedBy='.$_GET['sortedBy']);
                    exit();
                }
                elseif($_POST['confirmation'] == 'no')
                {
                    header('Location: index.php?action=backComments&sortedBy='.$_GET['sortedBy']);
                    exit();
                }
            }
        }
        else
        {
            header('Location: index.php?action=admin');
            exit();
        }
    }
    
    public function confirmBanUser()
    {
        $this->forbidAccess();
        
        if(isset($_GET['method']) && ($_GET['method'] == 'ban' || $_GET['method'] == 'unban') && isset($_GET['target']) && intval($_GET['target']) > 0 && isset($_GET['redirectPage']) && isset($_GET['redirectSortedBy']))
        {
            if($_GET['method'] == 'ban')
            {
                $this->userManager->banUser($_GET['target']);
                header('Location: index.php?action='.$_GET['redirectPage'].'&sortedBy='.$_GET['redirectSortedBy']);
                exit();
            }
            elseif($_GET['method'] == 'unban')
            {
                $this->userManager->unbanUser($_GET['target']);
                header('Location: index.php?action='.$_GET['redirectPage'].'&sortedBy='.$_GET['redirectSortedBy']);
                exit();
            }
        }
        else
        {
            header('Location: index.php?action=admin');
            exit();
        }   
    }
    
    public function backUsers()
    {
        $this->forbidAccess();
        
        if(empty($_GET['sortedBy']) || ($_GET['sortedBy'] != 'all' && $_GET['sortedBy'] != 'admins' && $_GET['sortedBy'] != 'banned' && $_GET['sortedBy'] != 'users'))
        {
            header('Location: index.php?action=admin');
            exit();
        }
        else
        {
            $users = $this->userManager->getAllUsers($_GET['sortedBy']);
        }

        $title = 'Billet simple pour l\'Alaska - Liste des utilisateurs';
        $content = $this->display('views/back/backUsers.php', compact('users'));
        include($this->backTemplate);
    }
    
    public function filterUsers()
    {
        $this->forbidAccess();
        
        if(!empty($_GET['sortedBy']))
        {
            if($_GET['sortedBy'] == 'all')
            {
                header('Location: index.php?action=backUsers&sortedBy='.$_GET['sortedBy']);
                exit();
            }
            elseif($_GET['sortedBy'] == 'admins')
            {
                header('Location: index.php?action=backUsers&sortedBy='.$_GET['sortedBy']);
                exit();
            }
            elseif($_GET['sortedBy'] == 'banned')
            {
                header('Location: index.php?action=backUsers&sortedBy='.$_GET['sortedBy']);
                exit();
            }
            elseif($_GET['sortedBy'] == 'users')
            {
                header('Location: index.php?action=backUsers&sortedBy='.$_GET['sortedBy']);
                exit();
            }
            else
            {
                header('Location:admin');
                exit();
            }
        }
        else
        {
            header('Location:admin');
            exit();
        }
    }
    
    public function __construct()
    {
        $this->userManager = new UserManager();
        $this->postManager = new PostManager();
        $this->commentManager = new CommentManager();
        $this->appreciationManager = new AppreciationManager();
    }
}