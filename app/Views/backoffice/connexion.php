<?php get_backheader(); ?>
    <main class='back_container'>
        <p id="reponse_form"></p>
        <section class="backoffice_connexion">
            <a href="/"><img src="public/assets/img/BiAway_Logo.png" alt="Logo BiAway"></a>
            <p>Connexion à votre backoffice</p>
            <form method="post"  id="back_connexion">
                <fieldset>
                    <input type="email" placeholder="Mail" name="mail">
                </fieldset>
                <fieldset>
                    <input type="password" placeholder="Mot de passe" name="password">
                </fieldset>
                <button onclick="submitConnexion(event)">Connexion</button>
            </form>  
        </section>
    </main>

    <script>
         var input_connexion= document.querySelectorAll("#back_connexion input");
        function submitConnexion(event){
            event.preventDefault()
            var form = document.getElementById("back_connexion");
            var formData = new FormData(form);
            var alert =  document.getElementById('reponse_form');
            alert.className="";
            input_connexion.forEach(input => {
                input.title = '';
                input.classList.remove('error');
            });
            
            fetch('/connexionback', 
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
                    alert.classList.add('success');
                    alert.textContent=data.message;
                    setTimeout( () => {
                        window.location.href = '/backoffice/dashboard';
                    }, 1500);
                }else {
                    alert.classList.add('error');
                    alert.textContent=data.message;
                    for (const key in data.key) {
                        const value = data.key[key];
                        console.log("#back_connexion input[name="+ key +"]")
                        document.querySelector("#back_connexion input[name="+ key +"]").title = value;
                        document.querySelector("#back_connexion input[name="+ key +"]").classList.add('error');
                    }   
                }
            })
            .catch(error => {
                console.error("Erreur lors de la requête Fetch:", error);
            });
        }
    </script>
<?php get_backfooter(); ?>