<div style="text-align: center;">
    <p>Ici, vous pouvez écrire ou modifier des chapitres, et les rendre publics ou non</p>
    <p><a class="link-standard" href="index.php?action=newPost">Ecrire un nouveau chapitre</a></p>

<?php foreach($list as $item) : ?>

    <div class="comment">
        
    <?php if($item->isPublished()) : ?>
    
        <p class="comment-description">(Chapitre Public)</p>
    
    <?php else : ?>
    
        <p class="comment-description">(Brouillon)</p>
    
    <?php endif ?>

        <hr />
        <p>Chapitre <?= $item->chapterNumber() ?> : <?= $item->title() ?></p>
        <hr />
        <p class="comment-counts">
            <a class="link-standard" href="index.php?action=updatePost&target=<?= $item->chapterNumber() ?>">modifier</a>
            <a class="link-standard" href="index.php?action=deletePost&target=<?= $item->chapterNumber() ?>">supprimer</a>
        </p>
    </div>

<?php endforeach ?>

    <p><a class="link-standard" href="index.php?action=newPost">Ecrire un nouveau chapitre</a></p>
</div>