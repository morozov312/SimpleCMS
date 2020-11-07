function addSlider() {
    $('.slider').slick({
        autoplay: true,
        autoplaySpeed: 4000,
        dots: true,
        arrows: false
    })
}
$(document).ready(function () {
    let initialWidth = $(window).currWidth()
    let needReload = false
    let isExist = false
    if (initialWidth < 768) {
        $('.tiles__wrap').addClass('slider')
        addSlider()
        isExist = true
        needReload = true
    }
    $(window).resize(function () {
        let currWidth = $(window).currWidth()
        if (currWidth < 768) {
            $('.tiles__wrap').addClass('slider')
            if ($('.tiles__wrap') && !isExist) {
                addSlider()
                isExist = true
                needReload = true
            }
        } else if (currWidth > 768 && needReload) {
            $('.tiles__wrap').removeClass('slider');
            location.reload()
            needReload = false
        }
    })
})
