<?php get_header(); ?>
<main class="container">
    <p id="reponse_form"></p>
    <h1 class="lodgement_title"><?= htmlspecialchars($data['lodgement']['title']) ?></h1>
    <section class="lodgement_picture">
        <img src="/public/assets/img/lodgements/<?= $data['lodgement']['picture'] != null ? htmlspecialchars($data['lodgement']['picture']) : "default_lodgment.webp"  ?>" alt="photo du logement">
    </section>
    <section class="lodgement_description">
        <h2>Logement de <?= htmlspecialchars($data['user']['firstname']); ?></h2>
        <div class="details_lodgement"><i class="fa-solid fa-house"></i> <?= htmlspecialchars($data['user']['count_lodgement']); ?> Logements</div>
        <div class="details_lodgement"><i class="fa-solid fa-users"></i> <?= htmlspecialchars($data['lodgement']['peoples']); ?> Voyageurs</div>
        <div class="details_lodgement"><i class="fa-solid fa-euro-sign"></i> <?= htmlspecialchars($data['lodgement']['price']); ?> € / nuit</div>
        <p> <?= nl2br(htmlspecialchars($data['lodgement']['description'])); ?></p>
        <iframe
            width="100%"
            height="200"
            frameborder="0"
            scrolling="no"
            marginheight="0"
            marginwidth="0"
            src="https://www.openstreetmap.org/export/embed.html?bbox=<?= (htmlspecialchars($data['longitude'])-0.02) . ',' . (htmlspecialchars($data['latitude'])-0.02) . ',' . (htmlspecialchars($data['longitude'])+0.02) . ',' . (htmlspecialchars($data['latitude'])+0.02); ?>&layer=mapnik"
            >
            
        </iframe>       
    </section>
    <section class="lodgement_reservation">
        <h2>Réservation </h2>
        <?php if(isset($_SESSION['user'])){ ?>
            <?php if(!$data['is_reserved']){ ?>
                <form id="reservation_form">
                    <input type="hidden" name="id_lodgement" value="<?= htmlspecialchars($data['lodgement']['id']) ?>"/>
                        <label>Début
                            <input type="date" placeholder="Début" name="start" />
                        </label>
                        <label>Fin
                            <input type="date" placeholder="Fin" name="end" />
                        </label>
                        <button type='submit' onclick="submitReservation(event)">Envoyer la demande</button>
                </form>
            <?php }else{ ?>
                <p>Votre demande a bien été prise en compte. Le propriétaire vous contactera dans les meilleurs délais.</p>
            <?php } ?>
        <?php }else{ ?>
            <p>Pour réserver un logement : <a href="/loginpage" class="button">Connecte toi !</a></p>
        <?php } ?>
    </section>

</main>


<script>
    function submitReservation(event){
        event.preventDefault()
        var input_form= document.querySelectorAll("#reservation_form input");
        var form = document.querySelector('#reservation_form');
        var formData = new FormData(form);
        var message = document.getElementById('reponse_form');
        input_form.forEach(input => {
            input.title = '';
            input.classList.remove('error');
        });
        message.className = '';
        fetch('/logement/reservation', 
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
                message.innerHTML='Réservation envoyée !';
                message.classList.add('success');
                setTimeout( () => {
                        window.location.reload();;
                    }, 1500);
            }else {
                for (const key in data.key) {
                    const value = data.key[key]
                    document.querySelector("#reservation_form input[name="+ key +"]").title = value;
                    document.querySelector("#reservation_form input[name="+ key +"]").classList.add('error');
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