<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="public/assets/library/fontawesome/css/all.min.css">
    <title>BiAway</title>
</head>
<body>
<header class='header'>
    <nav>
        <a href="/"><img src="public/assets/img/BiAway_Logo.png" alt="Logo BiAway"></a>
        <i class="fa-solid fa-bars menu-burger"></i>
        <?php if(!isset($_SESSION['user'])){ ?>
        <a href="/loginpage" class="menu-desktop">Connexion / Inscription</a>
        <?php }else{ ?>
        <?php if($_SESSION['user']['id_role'] == 1){ ?>
        <a href="/backoffice" class="menu-desktop">Gestion du site</a>
        <?php }} ?>
        <a href="#" class="menu-desktop">Contact</a>  
        <a href="#" class="menu-desktop">À Propos</a> 
        <?php if(isset($_SESSION['user'])){ ?>
        <a href="#" class="menu-desktop"><i class="fa-regular fa-user"></i></a>
        <a href="/deconnexion" class="menu-desktop"><i class="fa-solid fa-right-from-bracket"></i></a>
        <?php } ?>
    </nav>
    <div class="menu-list">
        <ul>
            <li><a href="/"><img src="public/assets/img/BiAway_Logo.png" alt="Logo BiAway"></a></li>
            <?php if(!isset($_SESSION['user'])){ ?>
            <li><a href="/loginpage">Connexion / Inscription</a></li>
            <?php }else{ ?>
            <li><a href="#">Mon compte</a></li>
            <?php } ?>
            <li><a href="#">Contact</a>  </li>
            <li><a href="#">À Propos</a> </li>
            <?php if(isset($_SESSION['user'])){ ?>
            <li><a href="/deconnexion">Déconnexion</a></li>
            <?php } ?>
        </ul>
    </div>
</header>


<script src="public/assets/js/menu-burger.js"></script>
