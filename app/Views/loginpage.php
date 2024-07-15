<?php get_header(); ?>
<main class="container">
    <section>
        <form method="post"  id="form_inscription">
            <fieldset>
               <input type="text" placeholder="Nom" name="lastname">
               <input type="text" placeholder="Prénom" name="firstname">
               <input type="email" placeholder="Mail" name="mail">
               <input type="tel" placeholder="Téléphone" name="phone">
               <input type="password" placeholder="Mot de passe" name="password">
               <button onclick="submitInscription(event)">M'inscrire</button>
            </fieldset>
            <p id="message_inscription"></p>
            <p>Tu possède déjà un compte ? <span class="toggleForm">Connecte toi !</span></p>
        </form>

        <form method="post"  id="form_connexion" class="show">
            <fieldset>
               <input type="email" placeholder="Mail" name="mail">
               <input type="password" placeholder="Mot de passe" name="password">
               <button onclick="submitConnexion(event)">Connexion</button>
            </fieldset>
            <p id="message_connexion"></p>
            <p>Tu n'as pas de compte ? <span class="toggleForm">Inscrit toi !</span></p>
        </form>  
    </section>
</main>


<script>
    var switchForm = document.querySelectorAll(".toggleForm");
    var input_inscription= document.querySelectorAll("#form_inscription input");
    var input_connexion= document.querySelectorAll("#form_connexion input");
    function submitInscription(event){
        event.preventDefault()
        var form = document.getElementById("form_inscription");
        var formData = new FormData(form);
        var alert =  document.getElementById('message_inscription');
        alert.className="";
        input_inscription.forEach(input => {
            input.title = '';
            input.classList.remove('error');
        });
        
        fetch('/inscription', 
        {
            method: "POST",
            body : formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 'success') {
                input_inscription.forEach(input => {
                    input.title = '';
                    input.classList.remove('error');
                });
                alert.classList.add(data.status);
                alert.textContent=data.message;
                setTimeout( () => {
                     window.location.href = '/';
                    }, 1500);
            }else {
                alert.classList.add(data.status);
                alert.textContent=data.message;
                for (const key in data.key) {
                    const value = data.key[key];
                    console.log("#form_inscription input[name="+ key +"]")
                    document.querySelector("#form_inscription input[name="+ key +"]").title = value;
                    document.querySelector("#form_inscription input[name="+ key +"]").classList.add('error');
                }          
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
        var alert =  document.getElementById('message_connexion');
        alert.className="";
        input_connexion.forEach(input => {
            input.title = '';
            input.classList.remove('error');
        });
        
        fetch('/connexion', 
        {
            method: "POST",
            body : formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data)
            if (data.status == 'success') {
                input_connexion.forEach(input => {
                    input.title = '';
                    input.classList.remove('error');
                });
                alert.classList.add(data.status);
                alert.textContent=data.message;
                setTimeout( () => {
                    window.location.href = '/';
                }, 1500);
            }else {
                alert.classList.add(data.status);
                alert.textContent=data.message;
                for (const key in data.key) {
                    const value = data.key[key];
                    console.log("#form_connexion input[name="+ key +"]")
                    document.querySelector("#form_connexion input[name="+ key +"]").title = value;
                    document.querySelector("#form_connexion input[name="+ key +"]").classList.add('error');
                }   
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

<?php get_footer(); ?>