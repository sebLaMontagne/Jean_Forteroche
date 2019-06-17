<?php if(!empty($_GET['target']) && $isChapterExists) : ?>

<p style="text-align: center;">Etes-vous sûr de vouloir supprimer cet article ?</p>
<form method="post" action="index.php?action=deletePost&target=<?= $_GET['target'] ?>">
    <div class="radios"><label for="delete-yes">Oui</label><input id="delete-yes" type="radio" name="delete" value="yes" /></div>
    <div class="radios"><label for="delete-no">Non</label><input id="delete-no" type="radio" name="delete" value="no" /></div>
    <input type="submit" value="confirmer" />
</form>

<?php endif ?>