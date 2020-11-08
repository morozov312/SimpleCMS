<?php get_header('short') ?>
<div class="about-content">
    <picture>
        <source srcset="<?php echo get_template_directory_uri() . '/assets/images/about/about.webp' ?>" type="image/webp">
        <source srcset="<?php echo get_template_directory_uri() . '/assets/images/about/about.jpg' ?>" type="image/jpg">
        <img src="<?php echo get_template_directory_uri() . '/assets/images/about/about.jpg' ?>" alt="about">
    </picture>
    
</div>
<?php get_footer() ?>