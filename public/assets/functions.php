<?php 

function get_header(){
    include  $_SERVER['DOCUMENT_ROOT'].'/app/Views/include/header.php';
}

function get_homeheader(){
    include  $_SERVER['DOCUMENT_ROOT'].'/app/Views/include/home-header.php';
}

function get_backheader(){
    include  $_SERVER['DOCUMENT_ROOT'].'/app/Views/include/back-header.php';
}

function get_backfooter(){
    include   $_SERVER['DOCUMENT_ROOT'].'/app/Views/include/back-footer.php';
}

function get_footer(){
    include   $_SERVER['DOCUMENT_ROOT'].'/app/Views/include/footer.php';
}

function envoyerEmail($to, $subject, $message,$headers) {
    if (!mail($to, $subject, $message, $headers)) {
        throw new Exception("Échec de l'envoi de l'email à $to");
    }
}