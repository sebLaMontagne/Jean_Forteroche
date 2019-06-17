<?php if(isset($_SESSION['data'])) : ?>

<form method="post" action="index.php?action=confirmNewPost">
    <p style="text-align: center;">
        <label for="chapterNumber">Chapitre n° </label>
        <input type="number" name="chapterNumber" min="1" max="65535" id="chapterNumber" value="<?= $_SESSION['data']['chapterNumber'] ?>" required /> : 
        <input id="title" type="text" name="title" placeholder="Titre" value="<?= $_SESSION['data']['title'] ?>" required />
    </p>
    <textarea class="tinyMCE" name="content"><?= $dataContent ?></textarea>

    <?php if($_SESSION['data']['publish']) : ?>
    
    <div class="radios"><input type="radio" id="publish" name="publish" value="1" required checked /><label for="publish">Publier</label></div>
    <div class="radios"><input type="radio" id="draft" name="publish" value="0" required /><label for="draft">Brouillon</label></div>

    <?php else : ?>

    <div class="radios"><input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label></div>
    <div class="radios"><input type="radio" id="draft" name="publish" value="0" required checked /><label for="draft">Brouillon</label></div>

    <?php endif ?>

    <input type="submit" value="sauvegarder" />
</form>

    <?php unset($_SESSION['data']) ?>

<?php else : ?>

<form method="post" action="index.php?action=confirmNewPost">
    <p style="text-align: center;">
        <label for="chapterNumber">Chapitre n° </label>
        <input type="number" name="chapterNumber" min="1" id="chapterNumber" required /> : 
        <input id="title" type="text" name="title" placeholder="Titre" required />
    </p>
    <textarea class="tinyMCE" name="content"></textarea>
    <div class="radios"><input type="radio" id="publish" name="publish" value="1" required /><label for="publish">Publier</label></div>
    <div class="radios"><input type="radio" id="draft" name="publish" value="0" required checked /><label for="draft">Brouillon</label></div>
    <input type="submit" value="sauvegarder" />
</form>

<?php endif ?>

<script src="https://cloud.tinymce.com/5/tinymce.min.js"></script>
<script>tinymce.init({ selector:"textarea", entity_encoding : "raw"});</script>