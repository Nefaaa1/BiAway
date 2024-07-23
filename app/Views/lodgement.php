<?php get_header(); ?>
<main class="container">
    <p id="reponse_form"></p>
    <h1 class="lodgement_title"><?= htmlspecialchars($data['lodgement']['title']) ?></h1>
    <img class="lodgement_picture" src="/public/assets/img/lodgements/<?= $data['lodgement']['picture'] != null ? htmlspecialchars($data['lodgement']['picture']) : "default_lodgment.webp"  ?>" alt="photo du logement">
    <section class="lodgement_description">
        <div class="grid1">
            <img src="/public/assets/img/users/<?= $data['user']['picture'] != null ? htmlspecialchars($data['user']['picture']) : "default_user.png"  ?>" alt="photo du propriétaire du logement">
            <div class="all_details">
                <span class="details_lodgement"><i class="fa-solid fa-house"></i> <?= htmlspecialchars($data['user']['count_lodgement']); ?> Logements</span>
                <span class="details_lodgement"><i class="fa-solid fa-users"></i> <?= htmlspecialchars($data['lodgement']['peoples']); ?> Voyageurs</span>
                <span class="details_lodgement"><i class="fa-solid fa-euro-sign"></i> <?= htmlspecialchars($data['lodgement']['price']); ?> € / nuit</span>
            </div> 
        </div>
        <div class="grid2">
            <h2>Logement de <?= htmlspecialchars($data['user']['firstname']); ?></h2>
            <p> <?= $data['lodgement']['description'] != null ? nl2br(htmlspecialchars($data['lodgement']['description'])) : "" ?></p>
        </div>
        <div class="grid3">
            <iframe
                height="200"
                src="https://www.openstreetmap.org/export/embed.html?bbox=<?= (htmlspecialchars($data['longitude'])-0.02) . ',' . (htmlspecialchars($data['latitude'])-0.02) . ',' . (htmlspecialchars($data['longitude'])+0.02) . ',' . (htmlspecialchars($data['latitude'])+0.02); ?>&layer=mapnik"
                >
            </iframe>       
        </div>
       
       
        
    </section>
    <section class="lodgement_reservation">
        <h2>Réservation </h2>
        <?php if(isset($_SESSION['user'])){ ?>
            <?php if(htmlspecialchars($data['lodgement']['id_user']) ==$_SESSION['user']['id'] ){ ?>
                <p> Ce logement vous appartient.</p>
            <?php }else{ ?>
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
            <?php } ?>
        <?php }else{ ?>
            <p>Pour réserver un logement : </p>
            <p><a href="/loginpage" class="button">Connecte toi !</a></p>
            
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