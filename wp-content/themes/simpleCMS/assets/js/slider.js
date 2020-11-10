function addSlider() {
    $('.slider').slick({
        autoplay: true,
        autoplaySpeed: 4000,
        dots: true,
        arrows: false
    })
}
$(document).ready(function () {
    let initialWidth = $(window).width()
    let needReload = false
    let isExist = false
    let initialTiles = $('.tiles__wrap').html()
    if (initialWidth < 768) {
        $('.tiles__wrap').addClass('slider')
        addSlider()
        isExist = true
        needReload = true
    }
    $(window).resize(function () {
        let width = $(window).width()
        if (width < 768) {
            $('.tiles__wrap').addClass('slider')
            if (!isExist) {
                addSlider()
                isExist = true
                needReload = true
            }
        } else if (width > 768 && needReload) {
            let removedWrap = $('.tiles__wrap')
            removedWrap.removeClass()
            removedWrap.addClass('tiles__wrap content')
            $('.tiles__wrap').empty()
            $('.tiles__wrap').prepend(initialTiles)
            needReload = false
            isExist = false
        }
    })

})