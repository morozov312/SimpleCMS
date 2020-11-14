<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Size_Chart_For_Woocommerce
 * @subpackage Size_Chart_For_Woocommerce/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Size_Chart_For_Woocommerce
 * @subpackage Size_Chart_For_Woocommerce/includes
 * @author     Multidots <inquiry@multidots.in>
 */
class Size_Chart_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) && ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
			wp_die(
				sprintf( "<strong>%s</strong> %s <strong>%s</strong> <a href='%s'>%s</a>",
					esc_html__( 'Size Chart for WooCommerce', 'size-chart-for-woocommerce' ),
					esc_html__( 'Plugin requires', 'size-chart-for-woocommerce' ),
					esc_html__( 'WooCommerce', 'size-chart-for-woocommerce' ),
					esc_url( get_admin_url( null, 'plugins.php' ) ),
					esc_html__( 'Plugins page', 'size-chart-for-woocommerce' )
				)
			);
		} else {
			set_transient( '_welcome_screen_activation_redirect_size_chart', true, 30 );
		}
	}

}
