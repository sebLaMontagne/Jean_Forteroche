<?php for($i = 0; $i < count($posts); $i++) : 
    $postDate = new DateTime($posts[$i]->Date()); ?>

    <a href="index.php?action=chapter&number=<?= $posts[$i]->chapterNumber()?>">
        <div class="chapter-icon">
            <h3>Chapitre <?= $posts[$i]->chapterNumber() ?> : <?= $posts[$i]->Title()?></h3>
            <p>Publié le <?= $postDate->format('d/m/Y à H:i:s') ?></p>
        </div>
    </a>

<?php endfor; ?>