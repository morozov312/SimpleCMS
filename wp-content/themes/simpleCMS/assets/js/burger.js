var burger = document.querySelector('.burger');
var title = document.querySelector('.header__title');
var nav = document.querySelector('.header__nav');
burger.onclick = function () {
    burger.classList.toggle('active-burger');
    title.classList.toggle('hidden-title');
    nav.classList.toggle('mobile-nav');
}