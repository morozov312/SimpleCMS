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
    if (is_404()) {
        wp_enqueue_style('404', get_template_directory_uri() . '/assets/styles/404.css');
    } else {
        wp_enqueue_style('style', get_stylesheet_uri());
        wp_enqueue_style('main', get_template_directory_uri() . '/assets/styles/style.css');
    }
}
function get_scripts()
{
    if (is_home()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js');
        wp_enqueue_script('jquery');
        wp_enqueue_script('slick', get_template_directory_uri() . '/assets/js/slick.min.js', '[jquery]', null, true);
        wp_enqueue_script('slider', get_template_directory_uri() . '/assets/js/slider.js', '[jquery]', null, true);
    }
    if (!is_404()) {
        wp_enqueue_script('burger', get_template_directory_uri() . '/assets/js/burger.js', null, null, false);
    }
}
