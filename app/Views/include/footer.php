<footer>
    <a href="/"><img src="assets/img/BiAway_Logo.png" alt="Logo BiAway"></a>
    <nav>
        <?php if(!isset($_SESSION['user'])){ ?>
        <a href="/loginpage">Connexion / Inscription</a> 
        <?php }else{ ?>
        <a href="#">Mon compte</a>
        <?php } ?> 
        <a href="#">Contact</a>  
        <a href="#">À Propos</a> 
        <?php if(isset($_SESSION['user'])){ ?>
        <a href="/deconnexion">Déconnexion</a>
        <?php } ?>
    </nav>
    <h3>Contactez-nous</h3>
    <form class="contact_form">
        <fieldset>
            <input type="text" placeholder="Nom" name="nom">
            <input type="text" placeholder="Prénom" name="prenom">
            <input type="phone" placeholder="Téléphone" name="telephone">
            <input type="mail" placeholder="Adresse mail" name="mail">
            <textarea name="message" placeholder="Message" cols="30" rows="10"></textarea>
            <button type='submit'>Envoyer</button>
        </fieldset>
        
    </form>
</footer>
</body>
</html>