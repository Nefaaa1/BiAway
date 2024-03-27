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
<?php if(isset($_SESSION['admin'])){ ?>
<header class="back_header">
    <nav>
        <a href="/backoffice/dashboard">Accueil</a>
        <a href="/backoffice/utilisateurs">Utilisateurs</a>
        <a href="/backoffice/logements">Logement</a>
        <a href="#">Avis</a>
        <a href="/backoffice/deconnexion">Déconnexion</a>
    </nav>
</header>
<?php } ?>
