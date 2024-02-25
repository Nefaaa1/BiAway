
document.addEventListener('DOMContentLoaded', function () {
    var menuIcon = document.querySelector('.menu-burger');
    var menuList = document.querySelector('.menu-list');

    document.addEventListener('click', function (e) {
        if(!menuList.contains(e.target) && !menuIcon.contains(e.target) && menuList.classList.contains('show-menu')){
            menuList.classList.remove('show-menu');
        }
    });

    document.addEventListener('scroll', function (e) {
        if(menuList.classList.contains('show-menu')){
            menuList.classList.remove('show-menu');
        }
    });
    if (menuIcon && menuList) {
        menuIcon.addEventListener('click', function () {
            menuList.classList.add('show-menu');
        });
    }   
});