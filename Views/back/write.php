<?php

$title = 'Création d\'un chapitre';
require('template.php');

$postManager = new PostManager();
if(isset($_GET['chapter']) && !empty($_GET['chapter']))
{
    if($postManager->isChapterExist($_GET['chapter']))
    {
        $selectedPost = $postManager->getPost($_GET['chapter']);
        echo '
        <form method="post" action="savePost.php">
            <label for="chapterNumber">Chapitre n°</label><input type="number" name="chapterNumber" min="1" id="chapterNumber" value="'.$selectedPost->chapterNumber().'" required /> : 
            <input type="text" name="title" placeholder="Titre" value="'.$selectedPost->title().'" required />
            <textarea name="content">'.$selectedPost->content().'</textarea>
            <input type="radio" id="publish" name="publish" value="publish" required /><label for="publish">Publier</label>
            <input type="radio" id="draft" name="publish" value="draft" required /><label for="draft">Brouillon</label>
            <input type="hidden" name="id" value="'.$selectedPost->id().'" />
            <input type="submit" value="sauvegarder" />
        </form>';
    }
    else
    {
        header('Location:chaptersList.php');
    }
}
elseif(isset($_SESSION['data']))
{
    $chapterNumber = $_SESSION['data']['chapterNumber'];
    $title = $_SESSION['data']['title'];
    $content = $_SESSION['data']['content'];
    $id = $_SESSION['data']['id'];
    unset($_SESSION['data']);
    
    echo '
    <form method="post" action="savePost.php">
        <label for="chapterNumber">Chapitre n°</label><input type="number" name="chapterNumber" min="1" id="chapterNumber" value="'.$chapterNumber.'" required /> : 
        <input type="text" name="title" placeholder="Titre" value="'.$title.'" required />
        <textarea name="content">'.$content.'</textarea>
        <input type="radio" id="publish" name="publish" value="publish" required /><label for="publish">Publier</label>
        <input type="radio" id="draft" name="publish" value="draft" required /><label for="draft">Brouillon</label>
        <input type="hidden" name="id" value="'.$id.'" />
        <input type="submit" value="sauvegarder" />
    </form>';
    
}
else
{
    echo '
    <form method="post" action="savePost.php">
        <label for="chapterNumber">Chapitre n°</label><input type="number" name="chapterNumber" min="1" id="chapterNumber" required /> : 
        <input type="text" name="title" placeholder="Titre" required />
        <textarea name="content"></textarea>
        <input type="radio" id="publish" name="publish" value="publish" required /><label for="publish">Publier</label>
        <input type="radio" id="draft" name="publish" value="draft" required /><label for="draft">Brouillon</label>
        <input type="submit" value="sauvegarder" />
    </form>';
}

var_dump($_POST);
var_dump($_SESSION);

?>

<script src="https://cloud.tinymce.com/5/tinymce.min.js"></script>
<script>tinymce.init({ selector:"textarea" });</script>