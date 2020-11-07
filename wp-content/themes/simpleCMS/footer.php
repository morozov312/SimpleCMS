<footer class="footer">
    <div class="content footer__wrap">
        <nav class="footer__nav">
            <ul class="footer__ul">
                <li><a class="inactive-link" href="#" target="_blank">©ICNDY2014</a></li>
                <?php
                wp_nav_menu([
                    'theme_location'  => 'bottom',
                    'container'       => null,
                    'items_wrap' => '%3$s',
                ]); ?>
            </ul>
        </nav>
        <div class="icons"><a class="icons__link" href="https://www.instagram.com/" target="_blank">
                <div class="icons__item">
                    <svg>
                        <use xlink:href="#inst"> </use>
                    </svg>
                </div>
            </a><a class="icons__link" href="https://www.facebook.com/" target="_blank">
                <div class="icons__item">
                    <svg>
                        <use xlink:href="#facebook"></use>
                    </svg>
                </div>
            </a><a class="icons__link" href="https://twitter.com/" target="_blank">
                <div class="icons__item">
                    <svg>
                        <use xlink:href="#twitter"></use>
                    </svg>
                </div>
            </a></div>
    </div>
</footer>
<?php wp_footer() ?>
</body>

</html>