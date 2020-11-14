<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="dotstore_plugin_sidebar">

    <?php 
$review_url = '';
$plugin_at = '';
$review_url = esc_url( 'https://wordpress.org/plugins/woo-advanced-product-size-chart/#reviews' );
$plugin_at = 'WP.org';
?>
    <div class="dotstore-important-link">
        <div class="image_box">
            <img src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/rate-us.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Rate us', 'size-chart-for-woocommerce' );
?> ">
        </div>
        <div class="content_box">
            <h3><?php 
esc_html_e( 'Like This Plugin?', 'size-chart-for-woocommerce' );
?></h3>
            <p><?php 
esc_html_e( 'Your Review is very important to us as it helps us to grow more.', 'size-chart-for-woocommerce' );
?></p>
            <a class="btn_style" href="<?php 
echo  $review_url ;
?>" target="_blank"><?php 
esc_html_e( 'Review Us on ', 'size-chart-for-woocommerce' );
echo  $plugin_at ;
?></a>
        </div>
    </div>
    <div class="dotstore-important-link">
        <h2>
            <span class="dotstore-important-link-title">
                <?php 
esc_html_e( 'Important link', 'size-chart-for-woocommerce' );
?>
            </span>
        </h2>
        <div class="video-detail important-link">
            <ul>
                <li>
                    <img src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/check-mark.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Plugin documentation', 'size-chart-for-woocommerce' );
?>">
                    <a href="<?php 
echo  esc_url( 'https://www.thedotstore.com/docs/plugin/woocommerce-advanced-product-size-charts/' ) ;
?>" target="_blank"><?php 
esc_html_e( 'Plugin documentation', 'size-chart-for-woocommerce' );
?></a>
                </li>
                <li>
                    <img src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/check-mark.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Support platform', 'size-chart-for-woocommerce' );
?>">
                    <a href="<?php 
echo  esc_url( 'https://www.thedotstore.com/support/' ) ;
?>" target="_blank"><?php 
esc_html_e( 'Support platform', 'size-chart-for-woocommerce' );
?></a>
                </li>
                <li>
                    <img src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/check-mark.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Support platform', 'size-chart-for-woocommerce' );
?>">
                    <a href="<?php 
echo  esc_url( 'https://www.thedotstore.com/suggest-a-feature/' ) ;
?>" target="_blank"><?php 
esc_html_e( 'Suggest A Feature', 'size-chart-for-woocommerce' );
?></a>
                </li>
                <li>
                    <img src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/check-mark.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Change log', 'size-chart-for-woocommerce' );
?>">
                    <a href="<?php 
echo  esc_url( 'https://www.thedotstore.com/woocommerce-advanced-product-size-charts/#tab-change-log' ) ;
?>" target="_blank"><?php 
esc_html_e( 'Change log', 'size-chart-for-woocommerce' );
?></a>
                </li>
            </ul>
        </div>
    </div>
    <!-- html for popular plugin !-->

    <div class="dotstore-important-link">
        <h2>
            <span class="dotstore-important-link-title">
                <?php 
esc_html_e( 'Our Popular plugins', 'size-chart-for-woocommerce' );
?>
            </span>
        </h2>
        <div class="video-detail important-link">
            <ul>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/Advanced-Flat-Rate-Shipping-Method.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Advanced Flat Rate Shipping Method', 'size-chart-for-woocommerce' );
?>">
                    <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/advanced-flat-rate-shipping-method-for-woocommerce" ) ;
?>">
						<?php 
esc_html_e( 'Advanced Flat Rate Shipping Method', 'size-chart-for-woocommerce' );
?>
                    </a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/Conditional-Product-Fees-For-WooCommerce-Checkout.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Conditional Product Fees For WooCommerce Checkout', 'size-chart-for-woocommerce' );
?>">
                    <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-conditional-product-fees-checkout/" ) ;
?>">
						<?php 
esc_html_e( 'Conditional Product Fees For WooCommerce Checkout', 'size-chart-for-woocommerce' );
?>
                    </a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/Advance-Menu-Manager-For-WordPress.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Advance Menu Manager For WordPress', 'size-chart-for-woocommerce' );
?>">
                    <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/advance-menu-manager-wordpress/" ) ;
?>">
						<?php 
esc_html_e( 'Advance Menu Manager For WordPress', 'size-chart-for-woocommerce' );
?>
                    </a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/Enhanced-Ecommerce-Google-Analytics-For-WooCommerce.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Enhanced Ecommerce Google Analytics for WooCommerce', 'size-chart-for-woocommerce' );
?>">
                    <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking" ) ;
?>">
						<?php 
esc_html_e( 'Enhanced Ecommerce Google Analytics for WooCommerce', 'size-chart-for-woocommerce' );
?>
                    </a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/popular-plugins/WooCommerce-Blocker-Prevent-Fake-Orders.png' ) ;
?>" alt="<?php 
esc_attr_e( 'WooCommerce Blocker – Prevent Fake Orders', 'size-chart-for-woocommerce' );
?>">
                    <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/product/woocommerce-blocker-lite-prevent-fake-orders-blacklist-fraud-customers/" ) ;
?>">
						<?php 
esc_html_e( 'WooCommerce Blocker – Prevent Fake Orders', 'size-chart-for-woocommerce' );
?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="view-button">
            <a class="view_button_dotstore" href="<?php 
echo  esc_url( "http://www.thedotstore.com/plugins/" ) ;
?>"  target="_blank"><?php 
esc_html_e( 'View All', 'size-chart-for-woocommerce' );
?></a>
        </div>
    </div>
</div>
</div>
</body>
</html>
