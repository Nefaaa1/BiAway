<footer class="footer">
    <div id="reponse_contact"></div>
    <section class="lien_rapide">
       <a href="/"><img src="/public/assets/img/BiAway_Logo.png" alt="Logo BiAway"></a>
        <nav>
            <?php if(!isset($_SESSION['user'])){ ?>
            <a href="/loginpage">Connexion / Inscription</a> 
            <?php }else{ ?>
            <a href="/moncompte">Mon compte</a>
            <?php } ?> 
            <?php if(isset($_SESSION['user'])){ ?>
            <a href="/deconnexion">Déconnexion</a>
            <?php } ?>
        </nav> 
    </section>
    <section class="contact_site">
        <h3>Contactez-nous</h3>
        <form class="contact_form">
            <fieldset>
                <input type="text" placeholder="Nom" name="nom">
                <input type="text" placeholder="Prénom" name="prenom">
                <input type="tel" placeholder="Téléphone" name="telephone">
                <input type="email" placeholder="Adresse mail" name="mail">
                <textarea name="message" placeholder="Message" cols="30" rows="10"></textarea>
                <button type='submit' onclick="submitContact(event)">Envoyer</button>
            </fieldset>
        </form>
    </section>
</footer>

<script>
    function submitContact(event){
        event.preventDefault()
        var input_form= document.querySelectorAll(".contact_form input");
        var form = document.querySelector('.contact_form');
        var formData = new FormData(form);
        var message = document.getElementById('reponse_contact');
        input_form.forEach(input => {
            input.title = '';
            input.classList.remove('error');
        });
        message.className = '';
        fetch('/contact/send', 
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
                message.innerHTML='Message envoyé avec succés !';
                message.classList.add('success');
                setTimeout( () => {
                    input_form.forEach(input => {
                        input.value = '';
                    });
                    document.querySelector(".contact_form textarea").value = "";
                    }, 1500);
            }else {
                for (const key in data.key) {
                    const value = data.key[key]
                    document.querySelector(".contact_form [name="+ key +"]").title = value;
                    document.querySelector(".contact_form [name="+ key +"]").classList.add('error');
                }
                
                message.innerHTML = data.message;
                message.classList.add('error');
            }
        })
        .catch(error => {
            console.error("Erreur lors de la requête Fetch:", error);
        });
    }

    function verticalScroll() {
        return document.documentElement.scrollHeight > window.innerHeight;
    }

    if (!verticalScroll()) {
        document.querySelector('.footer').classList.add('footer_noscroll');
    } 
</script>
</body>
</html>