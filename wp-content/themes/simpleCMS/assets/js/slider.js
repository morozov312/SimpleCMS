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
    if (initialWidth < 768) {
        $('.tiles__wrap').addClass('slider')
        addSlider()
        isExist = true
        needReload = true
    }
    if ($('.tiles__wrap').lenght != 0) {
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
                $('.tiles__wrap').removeClass('slider');
                location.reload()
                needReload = false
            }
        })
    }
})