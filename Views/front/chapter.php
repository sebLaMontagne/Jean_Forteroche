<?php $appreciationManager = new AppreciationManager() ?>

<div style="text-align: center">
    <div class="chapter">
        <h3>Chapitre <?= $selectedPost->chapterNumber() ?> : <?= $selectedPost->Title() ?></h3>
        <hr />
        <div class="chapter-content"><?= $postContent ?></div>
        <hr />
        <p>Publié le <?= $postDate->format('d/m/Y à H:i:s') ?></p>
    </div>

    <p class="chapter-links"> 
        <?php if($isPreviousChapterExists) : ?>
        <a href="index.php?action=chapter&number=<?= $selectedPost->chapterNumber() - 1 ?>">&larr; Précédent</a>
        <?php endif ?>

        <?php if($isNextChapterExists) : ?>
        <a href="index.php?action=chapter&number=<?= $selectedPost->chapterNumber() + 1 ?>"> Suivant &rarr;</a>
        <?php endif ?>
    </p>

    <!--Formulaire commentaire-->

    <?php if(!empty($_SESSION['pseudo'])) : ?>

    <form method="post" action="index.php?action=chapter&number=<?= $selectedPost->chapterNumber() ?>">
        <textarea placeholder="Laissez-nous un commentaire" name="comment"></textarea>
        <input type="submit" value="commenter" />
    </form>

    <?php else : ?>
    
    <p><a class="link-standard" href="index.php?action=register">Inscrivez-vous</a> ou <a class="link-standard" href="index.php?action=login">connectez-vous</a> pour nous laisser un commentaire :)</p>
    
    <?php endif ?>

    <p class="limiter">Commentaires :</p>

    <!--Affichage commentaires-->

    <?php foreach($comments as $comment) :

        $commentDate = new DateTime($comment->date());
        $commentAuthor = $comment->authorName();

        if($commentAuthor != null) : ?>

            <div class="comment">
                <p class="comment-description"><span class="comment-author"><?= $commentAuthor ?></span> <span class="comment-date">a écrit le <?= $commentDate->format('d/m/Y à H:i:s') ?> :</span></p>
                <p class="comment-content"><?= $comment->content() ?></p>

            <?php if(isset($_SESSION['id']) && $comment->userId() != $_SESSION['id'] && !$appreciationManager->isAppreciationExist($_SESSION['id'], $comment->id())) : ?>
            
                <p>
                    <a class="link-standard" href="index.php?action=leaveAppreciation&type=like&target=<?= $comment->id() ?>">Aimer</a>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="link-standard" href="index.php?action=leaveAppreciation&type=report&target=<?= $comment->id() ?>">Signaler</a>
                </p>
            
                <!--Where we stopped-->
                
            <?php elseif(isset($_SESSION['id']) && !$comment->userId() != $_SESSION['id'] && $appreciationManager->isAppreciationExist($_SESSION['id'], $comment->id())) : ?>
            
                <?php if($appreciationManager->AppreciationIsLike($_SESSION['id'], $comment->id())) : ?>
                
                    <p>Vous avez déjà liké ce commentaire</p>
                
                <?php elseif($appreciationManager->AppreciationIsReport($_SESSION['id'], $comment->id())) : ?>
                
                    <p>Vous avez déjà signalé ce commentaire</p>
                
                <?php endif ?>
                
                <p><a class="link-standard" href="index.php?action=leaveAppreciation&type=reset&target=<?=$comment->id() ?>">Retirer votre appréciation</a></p>
            
            <?php elseif(isset($_SESSION['id']) && $_SESSION['id'] == $comment->userId()) : ?>
            
                <p>Vous ne pouvez pas laisser d'appreciation sur vos commentaires</p>
            
            <?php else : ?>
            
                <p><a class="link-standard" href="register">Inscrivez-vous</a> ou <a class="link-standard" href="login">connectez-vous</a> pour laisser une appreciation</p>
            
            <?php endif ?>

        <?php endif ?>

            <!--Affichage likes / reports-->

            <p class="comment-counts">
                <span><?= $comment->likes() ?> likes</span>
                <span><?= $comment->reports() ?> reports</span>
            </p>
        </div>

    <?php endforeach ?>
</div>