<footer class="footer">
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
                <button type='submit'>Envoyer</button>
            </fieldset>
            
        </form>
    </section>
</footer>
</body>
</html>