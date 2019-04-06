<?php

$title = 'Liste des chapitres';
require('template.php');

$postManager = new PostManager;
$list = $postManager->getAllPosts();

for($i = 0; $i < count($list); $i++)
{
    echo '
    <div>
        <p>Chapitre '.$list[$i]->chapterNumber().' : </p>
    </div>';
    
}
var_dump($list);