<?php if($isChapterExists) : ?>

    <?php if(!isset($_POST['confirmation'])) ?>
    
<p style="text-align: center">Ce chapitre existe déjà. Etes-vous sûr de vouloir l'écraser ?</p>
<form method="post" action="index.php?action=confirmNewPost">
    <div class="radios"><input type="radio" name="confirmation" value="yes" id="yes" /><label for="yes">Oui</label></div>
    <div class="radios"><input type="radio" name="confirmation" value="no" id="no" /><label for="no">Non</label></div>
    <input type="hidden" name="chapterNumber" value="<?= $_POST['chapterNumber'] ?>" />
    <input type="hidden" name="title" value="<?= $_POST['title'] ?>" />
    <input type="hidden" name="content" value="<?= $encodedContent ?>" />
    <input type="hidden" name="publish" value="<?= $_POST['publish'] ?>" />
    <input type="submit" value="Confirmer" />
</form>
            
    <?php endif ?>

<?php endif ?>