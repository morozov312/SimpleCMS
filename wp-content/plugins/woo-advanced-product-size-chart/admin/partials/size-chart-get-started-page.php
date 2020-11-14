<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$file_dir_path = 'header/plugin-header.php';
if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
	require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
}
?>

    <div class="thedotstore-main-table res-cl">
        <h2><?php esc_html_e( 'Thanks For Installing Product Size Charts Plugin for WooCommerce Plugin', 'size-chart-for-woocommerce' ); ?></h2>
        <table class="table-outer">
            <tbody>
            <tr>
                <td class="fr-2">
                    <p class="block gettingstarted"><strong><?php esc_html_e( 'Getting Started', 'size-chart-for-woocommerce' ); ?> </strong></p>
                    <p class="block textgetting">
						<?php esc_html_e( 'Product Size Charts Plugin for WooCommerce allows you to assign ready-to-use default size chart templates to the product or Create Custom Size Chart for any of your WooCommerce products. You can also clone existing size chart templates and create your own size charts and assign them to a category or specific products.', 'size-chart-for-woocommerce' ); ?>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'You can edit any of the size charts available in the plugin, preview or clone them.', 'size-chart-for-woocommerce' ); ?>
                        <span class="gettingstarted">
                            <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'images/thedotstore-images/screenshots/Getting_Started_01.png' ); ?>" alt="<?php esc_attr_e( 'Getting_Started_01', 'size-chart-for-woocommerce' ); ?>">
                        </span>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'For each size chart, you can add label, chart image, categories for which you want the chart to appear, chart position (modal popup/additional tab on product page) and table style.', 'size-chart-for-woocommerce' ); ?>
                        <span class="gettingstarted">
                            <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'images/thedotstore-images/screenshots/Getting_Started_02.png' ); ?>" alt="<?php esc_attr_e( 'Getting_Started_02', 'size-chart-for-woocommerce' ); ?>">
                        </span>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'For each size chart, you can create your custom chart table (with as many rows and columns you would like to include)', 'size-chart-for-woocommerce' ); ?>
                        <span class="gettingstarted">
                            <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'images/thedotstore-images/screenshots/Getting_Started_03.png' ); ?>" alt="<?php esc_attr_e( 'Getting_Started_03', 'size-chart-for-woocommerce' ); ?>">
                        </span>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'Plugin settings offers the option to change the label of size chart tab and modal popup, which is displayed in product page.)', 'size-chart-for-woocommerce' ); ?>
                        <span class="gettingstarted">
                            <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'images/thedotstore-images/screenshots/Getting_Started_04.png' ); ?>" alt="<?php esc_attr_e( 'Getting_Started_04', 'size-chart-for-woocommerce' ); ?>">
                        </span>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
<?php
$file_dir_path = 'header/plugin-sidebar.php';
if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
	require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
}