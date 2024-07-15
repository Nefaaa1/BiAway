<?php get_header(); ?>
<main class="container">
    <div id="reponse_form"></div>
    <h2 class="">Mon compte</h2>
    <section class="motdepassechange">
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
    <h2 class="">Mes logements</h2>
    <section class="logement_user">
       <a href="moncompte/logement/ajouter" class="button">Ajouter un logement</a>
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
                <tr data-id="<?= $v['id'] ?>">
                    <td data-nom='Titre'><?= $v['title'] ?></td>
                    <td data-nom='Nombre de place'><?= $v['peoples'] ?></td>
                    <td data-nom='Prix'><?= $v['price'] ?>€</td>
                    <td data-nom='Ville'><?= $v['city'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
    
</main>


<script>
    document.addEventListener('DOMContentLoaded', function(){
        var resultats = document.querySelectorAll('.liste tr');
        resultats.forEach(result => {
            result.addEventListener('click',function(e){
                let id= this.getAttribute('data-id');
                window.location.href = `moncompte/logement/modifier/${id}`;
            });
        });
    })

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