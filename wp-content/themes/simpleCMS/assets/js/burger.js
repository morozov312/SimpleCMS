let burger = document.querySelector('.burger')
let title = document.querySelector('.header__title')
let nav = document.querySelector('.header__nav')
burger.addEventListener('click', () => {
    burger.classList.toggle('active-burger')
    title.classList.toggle('hidden-title')
    nav.classList.toggle('mobile-nav')
})