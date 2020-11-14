<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
global  $scfw_fs ;
$plugin_mode = __( 'Free Version ', 'size-chart-for-woocommerce' );
$plugin_header_button_image_alt = __( 'Upgrade to pro plugin', 'size-chart-for-woocommerce' );
$plugin_header_button_image_url = plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/upgrade.png';
$plugin_header_button_account_url = $scfw_fs->get_upgrade_url();
?>
<div id="dotsstoremain">
    <div class="all-pad">
        <header class="dots-header">
            <div class="dots-logo-main">
                <img src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/Advanced-Product-Size-Charts-for-WooCommerce.png' ) ;
?>" alt="<?php 
esc_attr_e( 'Product Size Charts Plugin for WooCommerce', 'size-chart-for-woocommerce' );
?>">
            </div>
            <div class="dots-header-right">
                <div class="logo-detail">
                    <strong><?php 
echo  esc_html( $this->get_plugin_name() ) ;
?></strong>
                    <span><?php 
echo  esc_html( $plugin_mode ) ;
echo  esc_html( $this->get_plugin_version() ) ;
?></span>
                </div>
                <div class="button-dots">
                    <span class="support_dotstore_image">
                        <a target="_blank" href="<?php 
echo  esc_url( $plugin_header_button_account_url ) ;
?>">
                            <img src="<?php 
echo  esc_url( $plugin_header_button_image_url ) ;
?>" alt="<?php 
esc_attr( $plugin_header_button_image_alt );
?>">
                        </a>
                    </span>
                    <span class="support_dotstore_image">
                        <a target="_blank" href="<?php 
echo  esc_url( "https://www.thedotstore.com/support/" ) ;
?>">
                            <img src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/support.png' ) ;
?>" alt="<?php 
esc_attr_e( 'The DotStore support', 'size-chart-for-woocommerce' );
?>">
                        </a>
                    </span>

                </div>
            </div>
			<?php 
$get_size_chart_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
$get_size_chart_post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );
$this->size_chart_menus( $get_size_chart_post_type, $get_size_chart_page );
?>
        </header>