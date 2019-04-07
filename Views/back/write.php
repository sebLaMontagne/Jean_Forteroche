<?php

$title = 'Création d\'un chapitre';
require('template.php');

$postManager = new PostManager();
if(isset($_GET['chapter']) && !empty($_GET['chapter']))
{
    if($postManager->isChapterExist($_GET['chapter']))
    {
        echo '<p>Il y a un chapitre</p>';
    }
    else
    {
        header('Location:chaptersList.php');
    }
}
else
{
    echo '
    <form method="post" action="savePost.php">
        <label for="chapterNumber">Chapitre n°</label><input type="number" name="chapterNumber" min="1" id="chapterNumber" required /> : 
        <input type="text" name="title" placeholder="Titre" required />
        <textarea name="content"></textarea>
        <input type="radio" id="publish" name="save" value="publish" required /><label for="publish">Publier</label>
        <input type="radio" id="draft" name="save" value="draft" required /><label for="draft">Brouillon</label>
        <input type="submit" value="sauvegarder" />
    </form>';
}

?>

<script src="https://cloud.tinymce.com/5/tinymce.min.js"></script>
<script>tinymce.init({ selector:"textarea" });</script>