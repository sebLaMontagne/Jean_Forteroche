<?php

try
{
    $title = 'Création d\'un chapitre';
    require('template.php');

    $_SESSION['refresh'] = 1;
    unset($_SESSION['refresh']);

    if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != '1')
    {
        header("location:javascript://history.go(-1)");
    }
    else
    {
        if(isset($_SESSION['data']))
        {
            echo '
            <form method="post" action="confirmNewPost.php">
                <label for="chapterNumber">Chapitre n°</label><input type="number" name="chapterNumber" min="1" id="chapterNumber" value="'.$_SESSION['data']['chapterNumber'].'" required /> : 
                <input type="text" name="title" placeholder="Titre" value="'.$_SESSION['data']['title'].'" required />
                <textarea name="content">'.$_SESSION['data']['content'].'</textarea>
                <input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label>
                <input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label>
                <input type="submit" value="sauvegarder" />
            </form>';

            unset($_SESSION['data']);
        }
        else
        {
            echo '
            <form method="post" action="confirmNewPost.php">
                <label for="chapterNumber">Chapitre n°</label><input type="number" name="chapterNumber" min="1" id="chapterNumber" placeholder="numéro de chapitre" required /> : 
                <input type="text" name="title" placeholder="Titre" required />
                <textarea name="content"></textarea>
                <input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label>
                <input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label>
                <input type="submit" value="sauvegarder" />
            </form>';
        }

        echo '
        <script src="https://cloud.tinymce.com/5/tinymce.min.js"></script>
        <script>tinymce.init({ selector:"textarea"});</script>';
    }
}
catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage();
}