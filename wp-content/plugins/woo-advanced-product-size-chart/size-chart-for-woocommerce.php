<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.multidots.com/
 * @since             1.0.0
 * @package           Size_Chart_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name: Product Size Charts Plugin for WooCommerce
 * Plugin URI:        https://www.thedotstore.com/woocommerce-advanced-product-size-charts/
 * Description:       Add product size charts with default template or custom size chart to any of your WooCommerce products.
 * Version:           2.0.5
 * Author:            theDotstore
 * Author URI:        https://www.thedotstore.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       size-chart-for-woocommerce
 * WC tested up to:   4.5
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( function_exists( 'scfw_fs' ) ) {
    scfw_fs()->set_basename( false, __FILE__ );
    return;
}


if ( !function_exists( 'scfw_fs' ) ) {
    /**
     * Freemius init.
     *
     * @return Freemius
     * @throws Freemius_Exception
     */
    function scfw_fs()
    {
        global  $scfw_fs ;
        
        if ( !isset( $scfw_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $scfw_fs = fs_dynamic_init( array(
                'id'               => '3495',
                'slug'             => 'size-chart-get-started',
                'type'             => 'plugin',
                'public_key'       => 'pk_921eefb3cf0a9c96d9d187aa72ad1',
                'is_premium'       => false,
                'has_addons'       => false,
                'has_paid_plans'   => true,
                'is_org_compliant' => false,
                'menu'             => array(
                'slug'       => 'size-chart-get-started',
                'first-path' => 'admin.php?page=size-chart-get-started',
                'contact'    => false,
                'support'    => false,
            ),
                'is_live'          => true,
            ) );
        }
        
        return $scfw_fs;
    }
    
    scfw_fs();
    do_action( 'scfw_fs_loaded' );
    scfw_fs()->get_upgrade_url();
    scfw_fs()->add_action( 'after_uninstall', 'scfw_fs_uninstall_cleanup' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-size-chart-for-woocommerce-activator.php
 */
function activate_size_chart_for_woocommerce()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-size-chart-for-woocommerce-activator.php';
    Size_Chart_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-size-chart-for-woocommerce-deactivator.php
 */
function deactivate_size_chart_for_woocommerce()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-size-chart-for-woocommerce-deactivator.php';
    Size_Chart_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_size_chart_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_size_chart_for_woocommerce' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-size-chart-for-woocommerce.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_size_chart_for_woocommerce()
{
    $plugin_post_type_name = esc_attr__( 'size-chart', 'size-chart-for-woocommerce' );
    $plugin_name = esc_attr__( 'Product Size Charts Plugin for WooCommerce', 'size-chart-for-woocommerce' );
    $plugin_version = esc_attr__( '2.0.5', 'size-chart-for-woocommerce' );
    $plugin = new Size_Chart_For_Woocommerce( $plugin_name, $plugin_version, $plugin_post_type_name );
    $plugin->run();
}

/**
 * Check Initialize plugin in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
function size_chart_initialize_plugin()
{
    $wc_active = in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true );
    
    if ( current_user_can( 'activate_plugins' ) && $wc_active !== true || $wc_active !== true ) {
        add_action( 'admin_notices', 'size_chart_plugin_admin_notice' );
    } else {
        run_size_chart_for_woocommerce();
    }

}

add_action( 'plugins_loaded', 'size_chart_initialize_plugin' );
/**
 * Show admin notice in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
function size_chart_plugin_admin_notice()
{
    $size_chart_plugin = esc_html__( 'Size Chart for WooCommerce', 'size-chart-for-woocommerce' );
    $wc_plugin = esc_html__( 'WooCommerce', 'size-chart-for-woocommerce' );
    ?>
    <div class="error">
        <p>
			<?php 
    echo  sprintf( esc_html__( '%1$s requires %2$s to be installed & activated!', 'woocommerce-product-attachment' ), '<strong>' . esc_html( $size_chart_plugin ) . '</strong>', '<a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '" target="_blank"><strong>' . esc_html( $wc_plugin ) . '</strong></a>' ) ;
    ?>
        </p>
    </div>
	<?php 
}
