<?php get_header(); ?>
<main class="container">
    <h2 class="lodgement_title"><?= htmlspecialchars($data['lodgement']['title']) ?></h2>
    <section class="lodgement_picture">
        <img src="/public/assets/img/lodgements/<?= $data['lodgement']['picture'] != null ? htmlspecialchars($data['lodgement']['picture']) : "default_lodgment.webp"  ?>" alt="photo du logement">
    </section>
    <section class="lodgement_description">
        <h3>Logement de <?= htmlspecialchars($data['user']['firstname']); ?></h3>
        <div class="count_lodgement"><i class="fa-solid fa-house"></i> <?= htmlspecialchars($data['user']['count_lodgement']); ?> Logements</div>
        <p> <?= nl2br(htmlspecialchars($data['lodgement']['description'])); ?></p>
    </section>
    
    <?php print_r( $data); ?>
</main>


<script>
    
</script>

<?php get_footer(); ?>