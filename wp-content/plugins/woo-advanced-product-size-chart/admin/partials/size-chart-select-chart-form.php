<?php
/**
 * Provide a admin area form view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    size-chart-for-woocommerce
 * @subpackage size-chart-for-woocommerce/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $wp_query;
// Add an nonce field so we can check for it later.
wp_nonce_field( 'size_chart_select_custom_box', 'size_chart_select_custom_box' );

// Use get_post_meta to retrieve an existing value of chart from the database.
$chart_id = size_chart_get_product_chart_id( $post->ID );
?>

<div id="size-chart-meta-fields">
    <div id="field">
        <div class="field-item">
            <label for="prod-chart"></label>
            <select name="prod-chart" id="prod-chart" data-allow_clear="true" data-placeholder="<?php esc_attr_e( 'Type the size chart name', 'size-chart-for-woocommerce' ); ?>" data-minimum_input_length="3" data-nonce="<?php echo esc_attr( wp_create_nonce( 'size_chart_search_nonce' ) ); ?>">
                <option value=""><?php esc_html_e( 'Select Chart', 'size-chart-for-woocommerce' ); ?></option>
				<?php
				if ( isset( $chart_id ) && ! empty( $chart_id ) ) {
					printf(
						"<option value='%s' selected>%s</option>",
						esc_attr__( $chart_id ),
						esc_html( get_the_title( $chart_id ) )
					);
				}
				?>
            </select>
        </div>
    </div>
</div>
