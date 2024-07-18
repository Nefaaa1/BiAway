<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico">
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <link rel="stylesheet" href="/public/assets/library/fontawesome/css/all.min.css">
    <title>BiAway</title>
</head>
<body>
<?php if(isset($_SESSION['user']) && $_SESSION['user']['id_role'] == 1){ ?>
<header class="back_header">
    <nav>
        <a href="/backoffice/dashboard"><h1 class="h1_back">BackOffice</h1></a>
        <i class="fa-solid fa-bars menu-burger"></i>
        <div class="menu_header">
            <a href="/backoffice/utilisateurs" class="menu-desktop">Utilisateurs</a>
            <a href="/backoffice/logements" class="menu-desktop">Logements</a>
            <a href="/backoffice/reservations" class="menu-desktop">Réservations</a>
            <a href="/" class="menu-desktop">Retour au site</a>
            <a href="/deconnexion" class="menu-desktop">Déconnexion</a>
        </div>
    </nav>
    <div class="menu-list">
        <ul>
            <li><a href="/backoffice/utilisateurs" class="menu-desktop">BackOffice</a></li>
            <li><a href="/backoffice/utilisateurs" class="menu-desktop">Utilisateurs</a></li>
            <li><a href="/backoffice/logements" class="menu-desktop">Logements</a></li>
            <li><a href="/backoffice/reservations" class="menu-desktop">Réservations</a></li>
            <li><a href="/" class="menu-desktop">Retour au site</a></li>
            <li><a href="/deconnexion" class="menu-desktop">Déconnexion</a></li>
        </ul>
    </div>
</header>
<?php } ?>
<script src="/public/assets/js/menu-burger.js" ></script>