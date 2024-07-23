<?php get_backheader(); ?>
<main class="content_back">
    <p class="accueil">Bienvenue sur votre espace de gestion !</p>
    <div class="stat_back">
        <div class="detail_stat">
            <span class="nombre"><?= $data['users'] ?></span>
            <span class="intitule">Utilisateurs</span>
        </div>
        <div class="detail_stat">
            <span class="nombre"><?= $data['lodgements'] ?></span>
            <span class="intitule">Logements</span>
        </div>
        <div class="detail_stat">
            <span class="nombre"><?= $data['reservations'] ?></span>
            <span class="intitule">Réservations</span>
        </div>
    </div>
</main>
<?php get_backfooter(); ?>
