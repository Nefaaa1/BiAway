<?php get_header(); ?>
<main>
    <section>
        <form method="post"  id="form_inscription">
            <fieldset>
                <label> <input type="text" placeholder="Nom" name="lastname"></label>
            </fieldset>
            <fieldset>
                <label> <input type="text" placeholder="Prénom" name="firstname"></label>
            </fieldset>
            <fieldset>
                <label> <input type="mail" placeholder="Mail" name="mail"></label>
            </fieldset>
            <fieldset>
                <label> <input type="password" placeholder="Mot de passe" name="password"></label>
            </fieldset>
            <button onclick="submitInscription(event)">M'inscrire</button>
            <p>Tu possède déjà un compte ? <span class="toggleForm">Connecte toi !</span></p>
        </form>

        <form method="post"  id="form_connexion" class="show">
            <fieldset>
                <label> <input type="mail" placeholder="Mail" name="mail"></label>
            </fieldset>
            <fieldset>
                <label> <input type="password" placeholder="Mot de passe" name="password"></label>
            </fieldset>
            <button onclick="submitConnexion(event)">Connexion</button>
            <p>Tu n'as pas de compte ? <span class="toggleForm">Inscrit toi !</span></p>
        </form>  
    </section>
</main>
<?php get_footer(); ?>

<script>
    var switchForm = document.querySelectorAll(".toggleForm");

    function submitInscription(event){
        event.preventDefault()
        var form = document.getElementById("form_inscription");
        var formData = new FormData(form);
        fetch('/inscription', 
        {
            method: "POST",
            body : formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 'success') {

            }else {
                console.error(data.message)
            }
        })
        .catch(error => {
            console.error("Erreur lors de la requête Fetch:", error);
        });
    }

    function submitConnexion(event){
        event.preventDefault()
        var form = document.getElementById("form_connexion");
        var formData = new FormData(form);
        fetch('/connexion', 
        {
            method: "POST",
            body : formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 'success') {
               window.location.href = '/'
            }else {
                console.error(data.message)
            }
        })
        .catch(error => {
            console.error("Erreur lors de la requête Fetch:", error);
        });
    }

    switchForm.forEach(function(element) {
        element.addEventListener('click', function () {
            var connexion = document.getElementById("form_inscription");
            var inscription = document.getElementById("form_connexion");
            connexion.classList.toggle('show');
            inscription.classList.toggle('show');
        });
    });
</script>