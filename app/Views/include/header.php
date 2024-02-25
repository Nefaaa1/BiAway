<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/library/fontawesome/css/all.min.css">
    <title>BiAway</title>
</head>
<body>
<header>
<nav>
        <a href="/"><img src="assets/img/BiAway_Logo.png" alt="Logo BiAway"></a>
        <i class="fa-solid fa-bars menu-burger"></i>
    </nav>
    <div class="menu-list">
        <ul>
            <li><a href="/"><img src="assets/img/BiAway_Logo.png" alt="Logo BiAway"></a></li>
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


<script src="./assets/js/menu-burger.js"></script>
