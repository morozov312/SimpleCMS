let burger = document.querySelector('.burger')
let title = document.querySelector('.header__title')
let nav = document.querySelector('.header__nav')
let shortHeader = document.querySelector('.short-header')
burger.addEventListener('click', () => {
    burger.classList.toggle('active-burger')
    if (title) title.classList.toggle('hidden-title')
    nav.classList.toggle('mobile-nav')
    if (shortHeader) shortHeader.classList.toggle('tall-header')
})