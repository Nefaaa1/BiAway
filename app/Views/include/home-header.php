<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico">
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="public/assets/library/fontawesome/css/all.min.css">
    <title>BiAway</title>
</head>
<body>
<header class="header home-header">
    <nav>
        <a href="/"><img src="public/assets/img/BiAway_Logo.png" alt="Logo BiAway"></a>
        <i class="fa-solid fa-bars menu-burger"></i>
        <div class="menu_header">
        <?php if(!isset($_SESSION['user'])){ ?>
        <a href="/loginpage" class="menu-desktop">Connexion / Inscription</a>
        <?php }else{ ?>
        <?php if($_SESSION['user']['id_role'] == 1){ ?>
        <a href="/backoffice" class="menu-desktop">Gestion du site</a>
        <?php }} ?>
        <?php if(isset($_SESSION['user'])){ ?>
        <a href="/moncompte" class="menu-desktop">Mon compte</a>
        <a href="/deconnexion" class="menu-desktop"><i class="fa-solid fa-right-from-bracket"></i></a>
        <?php } ?>
        </div>
    </nav>
    <div class="menu-list">
        <ul>
            <li><a href="/"><img src="public/assets/img/BiAway_Logo.png" alt="Logo BiAway"></a></li>
            <?php if(!isset($_SESSION['user'])){ ?>
            <li><a href="/loginpage">Connexion / Inscription</a></li>
            <?php }else{ ?>
            <li><a href="/moncompte">Mon compte</a></li>
            <?php if($_SESSION['user']['id_role'] == 1){ ?>
            <li><a href="/backoffice">Gestion du site</a></li>
            <?php }} ?>
            <?php if(isset($_SESSION['user'])){ ?>
            <li><a href="/deconnexion">Déconnexion</a></li>
            <?php } ?>
        </ul>
    </div>
    <section class="search_lodgement">
        <form id="form_logement">
            <fieldset>
                <legend>Recherche</legend>
                <input type="text" name="recherche" placeholder="Où allez-vous?">
            </fieldset>
            <fieldset>
                <legend>Voyageurs</legend>
                <input type="number" name="peoples" placeholder="">
            </fieldset>
            <button type="submit">Recherche</button>
        </form>
    </section>
</header>


<script src="public/assets/js/menu-burger.js"></script>