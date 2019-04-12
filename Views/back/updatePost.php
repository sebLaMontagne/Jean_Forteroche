<?php

$title = 'traitement posts';
include('template.php');

if(!empty($_GET['chapter']) && intval($_GET['chapter']) > 0)
{
    $postManager = new PostManager();
    if($postManager->isChapterExist($_GET['chapter']))
    {
        if(isset($_SESSION['data']))
        {
            echo '
            <form method="post" action="confirmUpdatePost.php">
                <input type="hidden" name="chapterNumber" value="'.$_GET['chapter'].'" />
                Chaptre n°'.$_GET['chapter'].' : <input type="text" name="title" placeholder="Titre" value="'.$_SESSION['data']['title'].'" required />
                <textarea name="content">'.$_SESSION['data']['content'].'</textarea>
                <input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label>
                <input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label>
                <input type="submit" value="sauvegarder" />
            </form>';
            
            unset($_SESSION['data']);
        }
        else
        {
            $selectedChapter = $postManager->getPost($_GET['chapter']);
        
            echo '
            <form method="post" action="confirmUpdatePost.php">
                <input type="hidden" name="chapterNumber" value="'.$_GET['chapter'].'" />
                Chaptre n°'.$_GET['chapter'].' : <input type="text" name="title" placeholder="Titre" value="'.$selectedChapter->title().'" required />
                <textarea name="content">'.$selectedChapter->content().'</textarea>
                <input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label>
                <input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label>
                <input type="submit" value="sauvegarder" />
            </form>';
        }
        
    }
    else
    {
        header('Location:admin.php');
    }
}
else
{
    header('Location:admin.php');
}

?>

<script src="https://cloud.tinymce.com/5/tinymce.min.js"></script>
<script>tinymce.init({ selector:"textarea"});</script>