<?php get_header(); ?>
<main class="container">
    <div id="reponse_form"></div>
    <h1 class="">Mon compte</h1>
    <section class="motdepassechange">
        <h2 class="">Changement de mot de passe</h2>
        <form method="POST">
            <fieldset>
                <label for="old_password">Mot de passe actuel :</label>
                <input type="password" name="old_password" id="old_password">    
            </fieldset>
            <fieldset>
                <label for="password">Nouveau mot de passe :</label>
                <input type="password" name="password" id="password">
            </fieldset>
            
            <button onclick="submitPassword(event)" >Changer mon mot de passe</button>
        </form>
    </section>
    
    <section class="logement_user">
        <h2 class="">Mes logements</h2>
        <a href="moncompte/logement/ajouter" class="button">Ajouter un logement</a>
        <?php if(count($data['logements']) > 0){ ?>
        <table class="liste">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Nombre de place</th>
                    <th>Prix</th>
                    <th>Ville</th>
                </tr>
            </thead>
            <tbody >
                <?php foreach ($data['logements'] as $k=>$v){ ?>
                <tr>
                    <td data-nom='Titre'><?= htmlspecialchars($v['title']) ?></td>
                    <td data-nom='Nombre de place'><?= htmlspecialchars($v['peoples']) ?></td>
                    <td data-nom='Prix'><?= htmlspecialchars($v['price']) ?>€</td>
                    <td data-nom='Ville'><?= htmlspecialchars($v['city']) ?></td>
                    <td data-nom='Action'>
                        <a href="moncompte/logement/reservation/<?= $v['id'] ?>" class="button tooltip" data-tooltip="Mes réservations"><i class="fa-solid fa-calendar"></i></a>
                        <a href="moncompte/logement/modifier/<?= $v['id'] ?>" class="button"><i class="fa-solid fa-pen"></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else{ ?>
            <p>Vous n'avez aucun logement.</p>
        <?php } ?>
    </section>
    <section>
        <h2 class="">Mes demandes de reservations</h2>
        <?php if(count($data['reservations']) > 0){ ?>
            <table class="liste">
            <thead>
                <tr>
                    <th>Date de la demande</th>
                    <th>Début</th>
                    <th>Fin</th>
                </tr>
            </thead>
            <tbody >
                <?php foreach ($data['reservations'] as $k=>$v){ ?>
                <tr>
                    <td data-nom='Date de la demande'><?= (new DateTime(htmlspecialchars($v['creation'])))->format('d/m/y') ?></td>
                    <td data-nom='Début'><?= (new DateTime(htmlspecialchars($v['start'])))->format('d/m/y') ?></td>
                    <td data-nom='Fin'><?= (new DateTime(htmlspecialchars($v['end'])))->format('d/m/y') ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else{ ?>
            <p>Vous n'avez pas fait de demande de réservation </p>
        <?php } ?>
    </section>
</main>


<script>
    function submitPassword(event){
        event.preventDefault()
        var input_form= document.querySelectorAll(".motdepassechange form input");
        var form = document.querySelector('.motdepassechange form');
        var formData = new FormData(form);
        var message = document.getElementById('reponse_form');
        input_form.forEach(input => {
            input.title = '';
            input.classList.remove('error');
        });
        message.className = '';
        fetch('/moncompte/change_password', 
        {
            method: "POST",
            body : formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 'success') {
                input_form.forEach(input => {
                    input.title = '';
                    input.classList.remove('error');
                });
                message.innerHTML='Mot de passe modifier avec succés !';
                message.classList.add('success');
                setTimeout( () => {
                     window.location.href = '/moncompte';
                    }, 1500);
            }else {
                for (const key in data.key) {
                    const value = data.key[key]
                    document.querySelector(".motdepassechange form input[name="+ key +"]").title = value;
                    document.querySelector(".motdepassechange form input[name="+ key +"]").classList.add('error');
                }
                
                message.innerHTML = data.message;
                message.classList.add('error');
            }
        })
        .catch(error => {
            console.error("Erreur lors de la requête Fetch:", error);
        });
    }
</script>

<?php get_footer(); ?>