<?php
add_action('wp_enqueue_scripts', 'get_styles');

function get_styles()
{
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_style('main', get_template_directory_uri().'/assets/styles/style.css');
}
