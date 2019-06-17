<div style="text-align: center">
    <p>Sur cette page, vous pouvez consulter la liste des utilisateurs et leur statut actuel, et éventuellement en bannir ou en gracier.</p>

    <p>Montrer : </p>
    <form action="index.php?action=filterUsers" method="get">
        <input type="hidden" name="action" value="<?= $_GET['action'] ?>" />
        <select name="sortedBy">
            <option value="all">tous les utilisateurs</option>
            <option value="admins">les administrateurs</option>
            <option value="banned">les bannis</option>
            <option value="users">les simples usagers</option>
        </select>
        <input class="filter-confirm" type="submit" value="Confirmer"/>
    </form>

<?php foreach($users as $user) : ?>
    
    <div class="comment">
        <p><span class="comment-author"><?= $user->name() ?></span>

    <?php if($user->isBanned()) : ?>

         (banni)</p>
        <p><a class="link-standard" href="index.php?action=confirmBanUser&method=unban&target=<?= $user->id() ?>&redirectPage=backUsers&redirectSortedBy=<?= $_GET['sortedBy'] ?>">Débannir</a></p>
    
    <?php elseif($user->isAdmin()) : ?>
    
        (administrateur)</p>
    
    <?php elseif(!$user->isActivated()) : ?>
    
        (usager non activé)</p>
    
    <?php else : ?>
    
        (simple usager)</p>
        <p><a class="link-standard" href="index.php?action=confirmBanUser&method=ban&target=<?= $user->id() ?>&redirectPage=backUsers&redirectSortedBy=<?= $_GET['sortedBy'] ?>">Bannir</a></p>

    <?php endif ?>

        <p><a class="link-standard" href="index.php?action=backComments&sortedBy=date&target=<?= $user->id() ?>">Voir tous les commentaires de cet utilisateur</a></p>
    </div>

<?php endforeach ?>
</div>