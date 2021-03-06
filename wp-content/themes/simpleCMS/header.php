<!DOCTYPE html>
<html id="html" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICNDY</title>
    <meta name="description" content=" ">
    <meta name="keywords" content=" ">
    <?php wp_head(); ?>
</head>

<body>
    <!--svg-sprites-->
    <svg display="none">
        <symbol id="inst" viewBox="0 0 169.063 169.063">
            <g>
                <path d="M122.406,0H46.654C20.929,0,0,20.93,0,46.655v75.752c0,25.726,20.929,46.655,46.654,46.655h75.752 c25.727,0,46.656-20.93,46.656-46.655V46.655C169.063,20.93,148.133,0,122.406,0z M154.063,122.407 c0,17.455-14.201,31.655-31.656,31.655H46.654C29.2,154.063,15,139.862,15,122.407V46.655C15,29.201,29.2,15,46.654,15h75.752 c17.455,0,31.656,14.201,31.656,31.655V122.407z" />
                <path d="M84.531,40.97c-24.021,0-43.563,19.542-43.563,43.563c0,24.02,19.542,43.561,43.563,43.561s43.563-19.541,43.563-43.561 C128.094,60.512,108.552,40.97,84.531,40.97z M84.531,113.093c-15.749,0-28.563-12.812-28.563-28.561 c0-15.75,12.813-28.563,28.563-28.563s28.563,12.813,28.563,28.563C113.094,100.281,100.28,113.093,84.531,113.093z" />
                <path d="M129.921,28.251c-2.89,0-5.729,1.17-7.77,3.22c-2.051,2.04-3.23,4.88-3.23,7.78c0,2.891,1.18,5.73,3.23,7.78 c2.04,2.04,4.88,3.22,7.77,3.22c2.9,0,5.73-1.18,7.78-3.22c2.05-2.05,3.22-4.89,3.22-7.78c0-2.9-1.17-5.74-3.22-7.78 C135.661,29.421,132.821,28.251,129.921,28.251z" />
            </g>
        </symbol>
    </svg>
    <svg display="none">
        <symbol id="facebook" viewBox="0 0 512 512">
            <g>
                <path d="M288,176v-64c0-17.664,14.336-32,32-32h32V0h-64c-53.024,0-96,42.976-96,96v80h-64v80h64v256h96V256h64l32-80H288z" />
            </g>
        </symbol>
    </svg>
    <svg display="none">
        <symbol id="twitter" viewBox="0 0 512 512">
            <g>
                <path d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016 c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992 c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056 c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152 c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792 c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44 C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568 C480.224,136.96,497.728,118.496,512,97.248z" />
            </g>
        </symbol>
    </svg>
    <!--end of svg-sprites-->
    <header class="header">
        <div class="content header__wrap">
            <div class="header__top">
                <a class="logo" href="<?php echo home_url() ?>"><img class="logo__img" src="<?php echo get_template_directory_uri() . '/assets/images/header/logo.png' ?>" alt="logo"></a>
                <div class="burger header__burger">
                    <span></span>
                </div>
                <nav class="header__nav">
                    <?php
                    wp_nav_menu([
                        'theme_location'  => 'top',
                        'container'       => 'ul',
                        'menu_class'      => 'header__list',
                    ]); ?>
                </nav>
            </div>
            <div class="header__intro">
                <div class="header__title">
                    <h1 class="h1">STÜSSY2014</h1>
                    <p class="subtitle header__subtitle">
                        <a href="<?php echo get_template_directory_uri() . "/shop" ?>">shop now</a>
                    </p>
                </div>
            </div>
            <div class="header__arrow"><img src="<?php echo get_template_directory_uri() . '/assets/images/header/Arrow.png" alt="arrow' ?>"></div>
        </div>
    </header>