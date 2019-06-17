<?php if(isset($_GET['target']) && intval($_GET['target']) > 0) : ?>

    <?php if(!isset($_POST['confirmation'])) : ?>

<p style="text-align: center;">Etes-vous sûr de vouloir supprimer ce commentaire</p>
<form method="post" action="index.php?action=confirmCommentSuppression&target=<?= $_GET['target'] ?>&sortedBy=<?= $_GET['sortedBy'] ?>">
    <div class="radios"><input type="radio" name="confirmation" value="yes" id="confirm-yes" required /><label for="confirm-yes">Oui</label></div>
    <div class="radios"><input type="radio" name="confirmation" value="no" id="confirm-no" required /><label for="confirm-no">Non</label></div>
    <input type="submit" value="valider" />
</form>
            
    <?php endif ?>
<?php endif ?>