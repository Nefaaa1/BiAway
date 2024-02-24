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
            <li><a href="/loginpage">Connexion / Inscription</a></li>
            <li><a href="#">Contact</a>  </li>
            <li><a href="#">À Propos</a> </li>
        </ul>
    </div>
</header>


<script>
    var menuIcon = document.querySelector('.menu-burger');
    var menuList = document.querySelector('.menu-list');

    menuIcon.addEventListener('click', function () {
        menuList.classList.add('show-menu');
    });

    document.addEventListener('click', function (e) {
        if(!menuList.contains(e.target) && !menuIcon.contains(e.target) && menuList.classList.contains('show-menu')){
            menuList.classList.remove('show-menu');
        }
    });
</script>
