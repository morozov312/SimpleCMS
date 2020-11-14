function addSlider() {
    jQuery('.slider').slick({
        autoplay: true,
        autoplaySpeed: 4000,
        dots: true,
        arrows: false
    })
}
jQuery(document).ready(function () {
    let initialWidth = jQuery(window).width()
    let needReload = false
    let isExist = false
    let initialTiles = jQuery('.tiles__wrap').html()
    if (initialWidth < 768) {
        jQuery('.tiles__wrap').addClass('slider')
        addSlider()
        isExist = true
        needReload = true
    }
    jQuery(window).resize(function () {
        let width = jQuery(window).width()
        if (width < 768) {
            jQuery('.tiles__wrap').addClass('slider')
            if (!isExist) {
                addSlider()
                isExist = true
                needReload = true
            }
        } else if (width > 768 && needReload) {
            let removedWrap = jQuery('.tiles__wrap')
            removedWrap.removeClass()
            removedWrap.addClass('tiles__wrap content')
            jQuery('.tiles__wrap').empty()
            jQuery('.tiles__wrap').prepend(initialTiles)
            needReload = false
            isExist = false
        }
    })

})