<?php get_header(); ?>
<main>
    <h1>LOGIN PAGE</h1>

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
    </form>

    <form method="post"  id="form_connexion">
        <fieldset>
            <label> <input type="mail" placeholder="Mail" name="mail"></label>
        </fieldset>
        <fieldset>
            <label> <input type="password" placeholder="Mot de passe" name="password"></label>
        </fieldset>
        <button onclick="submitConnexion(event)">Connexion</button>
    </form>
</main>
<?php get_footer(); ?>

<script>
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
            console.log(data); 
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
            console.log(data); 
        })
        .catch(error => {
            console.error("Erreur lors de la requête Fetch:", error);
        });
    }
</script>