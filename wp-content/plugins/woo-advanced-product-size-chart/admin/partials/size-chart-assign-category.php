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
// Use get_post_meta to retrieve an existing value of chart from the database.
$size_cart_cat_id = size_chart_get_categories( $post->ID );
?>
<div id="size-chart-meta-fields">
    <div id="assign-category">
        <div class="assign-item">
            <ul>
				<?php
				$size_cart_term = get_terms( 'product_cat', array() );
				if ( ! empty( $size_cart_cat_id ) ) {
					if ( ! empty( $size_cart_term ) ) {
						foreach ( $size_cart_term as $size_cart_cat ) {
							if ( in_array( $size_cart_cat->term_id, $size_cart_cat_id, true ) ) {
								printf(
									"<li>%s</li>",
									esc_html( $size_cart_cat->name )
								);
							}
						}
					}
				} else {
					esc_html_e( 'No Category Assign', 'size-chart-for-woocommerce' );
				} ?>
            </ul>
        </div>
    </div>
</div>