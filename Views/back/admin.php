<p>Bienvenue sur le panneau d\'administration du site. Depuis cette section, vous pouvez rédiger des chapitres, gérer les commentaires et les utilisateurs</p>
<p style="text-align: center;">Il y a actuellement : </p>
<div class="container">
    <div>
        <a href="chaptersList"><img src="Ressources/img/chapters.png" /></a>
        <ul>
            <li><?= $publicChaptersCount ?> chapitres publics </li>
            <li><?= $draftedChaptersCount ?> brouillons </li>
        </ul>
    </div>
    <div>
        <a href="commentsList-reports"><img src="Ressources/img/comments.png" /></a>
        <ul>
            <li><?= $allCommentCount ?> commentaires</li>
        </ul>
    </div>
    <div>
        <a href="usersList-all"><img src="Ressources/img/users.png" /></a>
        <ul>
            <li><?= $adminUsersCount ?> administrateurs</li>
            <li><?= $normalUsersCount ?> utilisateurs</li>
            <li><?= $bannedUsersCount ?> bannis</li>
        </ul>
    </div>
</div>