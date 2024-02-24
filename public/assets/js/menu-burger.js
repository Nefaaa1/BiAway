document.addEventListener('DOMContentLoaded', function () {
    var menuIcon = document.getElementById('menuIcon');
    var menuList = document.querySelector('.menu-list');

    menuIcon.addEventListener('click', function () {
        menuList.classList.toggle('show-menu');
    });
});