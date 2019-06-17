<p>Ici, vous pouvez supprimer des commentaires ou bannir des utilisateurs pour leur mauvais comportement et filtrer les commentaires en fonction de plusieurs critères</p>
      
<div style="text-align: center;">
    <p>Trier les commentaires par : </p>
    <form action="index.php?action=filterComments" method="post">
        <select name="sortedBy">
            <option value="date">date</option>
            <option value="likes">nombre de likes</option>
            <option value="reports">nombre de reports</option>
        </select>
        
<?php if(isset($_GET['target'])) : ?> 
        <input type="hidden" name="target" value="<?= $_GET['target'] ?>" />
<?php endif ?>
        
        <input class="filter-confirm" type="submit" value="Confirmer"/>
    </form>
</div>


<?php foreach($comments as $comment) :

    $commentDate = new DateTime($comment->date()); ?>

<div class="comment" style="text-align: center;">

    <?php if($comment->AuthorName() != null) : ?>
    
        <?php if($comment->authorIsBanned()) : ?>
        
    <p class="comment-description"><span class="comment-author"><?= $comment->authorName() ?></span> (Utilisateur banni)
        
        <?php else : ?>
        
    <p class="comment-description"><span class="comment-author"><?= $comment->authorName() ?></span>
        
        <?php endif ?>

     a écrit le <span class="comment-date"><?= $commentDate->format('d/m/Y à H:i:s') ?></span>
     sur le chapitre <?= $comment->chapterNumber() ?> :</p>
    <hr />
    <p><?= $comment->content() ?></p>
    <hr />
    <p><?= $comment->likes() ?> likes <?= $comment->reports() ?> reports</p>
    <hr />
    <p class ="comment-counts">
        <a class="link-standard" href="index.php?action=confirmCommentSuppression&target=<?= $comment->id() ?>&sortedBy=<?= $_GET['sortedBy'] ?>">supprimer le commentaire</a>

        <?php if($comment->authorIsBanned()) : ?>
        
        <a class="link-standard" href="index.php?action=confirmBanUser&method=unban&target=<?= $comment->userId() ?>&redirectPage=backComments&redirectSortedBy=<?= $_GET['sortedBy'] ?>">Débannir</a>
        
        <?php elseif($comment->authorIsAdmin()) : ?>
        

        
        <?php else : ?>
        
        <a class="link-standard" href="index.php?action=confirmBanUser&method=ban&target=<?= $comment->userId() ?>&redirectPage=backComments&redirectSortedBy=<?= $_GET['sortedBy'] ?>">bannir l'utilisateur</a>
        
        <?php endif ?>

    </p>
</div>

    <?php endif ?>
<?php endforeach ?>