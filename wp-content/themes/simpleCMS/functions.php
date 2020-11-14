<?php
add_action('wp_enqueue_scripts', 'get_styles');
add_action('wp_footer', 'get_scripts');
add_action('after_setup_theme', function () {
    register_nav_menus([
        'top' => 'Меню в шапке',
        'bottom' => 'Меню в подвале'
    ]);
});
function get_styles()
{
    wp_enqueue_style('style', get_stylesheet_uri());
    if (is_404()) {
        wp_enqueue_style('404', get_template_directory_uri() . '/assets/styles/404.css');
    } else {
        wp_enqueue_style('main', get_template_directory_uri() . '/assets/styles/style.css');
    }
    if (is_page([19, 'About - simpleCMS'])) {
        wp_enqueue_style('about us', get_template_directory_uri() . '/assets/styles/about.css');
    }
}
function get_scripts()
{
    if (is_page(['ICNDY', 58])) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/d3js/6.2.0/d3.min.js');
        wp_enqueue_script('jquery');
        wp_enqueue_script('slick', get_template_directory_uri() . '/assets/js/slick.min.js', '[jquery]', null, true);
        wp_enqueue_script('slider', get_template_directory_uri() . '/assets/js/slider.js', '[jquery]', null, true);
    }
    if (!is_404()) {
        wp_enqueue_script('burger', get_template_directory_uri() . '/assets/js/burger.js', null, null, false);
    }
}
function mytheme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}

add_action('after_setup_theme', 'mytheme_add_woocommerce_support');
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

add_filter("loop_shop_per_page", function ($cols) {

    return 8;
}, 20);
