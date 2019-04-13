<?php

$title = 'test';
include('template.php');

$postManager = new PostManager();
var_dump($postManager->deletePost(1));