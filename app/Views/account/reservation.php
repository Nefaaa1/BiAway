<?php get_header(); ?>
<main class="container">
    <h1><?= htmlspecialchars($data['lodgement']['title']) ?></h1>
    <section>
        <h2>Mes réservations</h2>
        <?php if(count($data['reservation']) >0){ ?>
        <table class="liste">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Mail</th>
                    <th>Téléphone</th>
                    <th>Début</th>
                    <th>Fin</th>
                </tr>
            </thead>
            <tbody >
                <?php foreach ($data['reservation'] as $k=>$v){ ?>
                <tr data-id="<?= $v['id'] ?>">
                    <td data-nom='Nom'><?= htmlspecialchars($v['lastname']) ?></td>
                    <td data-nom='Prénom'><?= htmlspecialchars($v['firstname']) ?></td>
                    <td data-nom='Mail'><?= htmlspecialchars($v['mail']) ?></td>
                    <td data-nom='Téléphone'><?= htmlspecialchars($v['phone']) ?></td>
                    <td data-nom='Début'><?= (new DateTime(htmlspecialchars($v['start'])))->format('d/m/y') ?></td>
                    <td data-nom='Fin'><?= (new DateTime(htmlspecialchars($v['end'])))->format('d/m/y') ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php }else{ ?>
            <p>Vous n'avez pas de demande de réservation.</p>
        <?php } ?>
    </section>
    
</main>
<?php get_footer(); ?>

<script>
   
</script>