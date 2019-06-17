<form method="post" action="index.php?action=confirmUpdatePost">
    <p style="text-align: center;">
        <input type="hidden" name="chapterNumber" value="<?= $_GET['target'] ?>" />
        Chapitre n° <?= $_GET['target'] ?> : 
        <input id="title" type="text" name="title" placeholder="Titre" value="<?= $selectedChapter->title() ?>" required />
    </p>
    <textarea class="tinyMCE" name="content"><?= $chapterContent ?></textarea>

<?php if($selectedChapter->isPublished()) : ?>

    <div class="radios"><input type="radio" id="publish" name="publish" value="1" required checked /><label for="publish">Publier</label></div>
    <div class="radios"><input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label></div>

<?php else : ?>

    <div class="radios"><input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label></div>
    <div class="radios"><input type="radio" id="draft" name="publish" value="0" required checked /><label for="draft">Brouillon</label></div>

<?php endif ?>

    <input type="submit" value="sauvegarder" />
</form>

<script src="https://cloud.tinymce.com/5/tinymce.min.js"></script>
<script>tinymce.init({ selector:"textarea", entity_encoding : "raw"});</script>