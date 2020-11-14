<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    size-chart-for-woocommerce
 * @subpackage size-chart-for-woocommerce/public/includes
 * @author     Multidots
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$chart_label = size_chart_get_label_by_chart_id( $chart_id );
$chart_table = size_chart_get_chart_table_by_chart_id( $chart_id );
if ( isset( $chart_label ) && ! empty( $chart_label ) ) {
	printf( '<h3>%s</h3>', esc_html( $chart_label ) );
}

$post_data = get_post( $chart_id );
if ( $post_data->post_content ) {
	$content = apply_filters( 'the_content', $post_data->post_content );
	printf( '<div class="chart-content"><span><b>%s</b></span>%s</div>',
		esc_html( size_chart_get_sub_title_text() ),
		wp_kses_post( $content )
	);
} else {
	printf( '<div class="chart-content"><span><b>%s</b></span></div>',
		esc_html( size_chart_get_sub_title_text() )
	);
}
$chart_image_id = size_chart_get_primary_chart_image_id( $chart_id );
if ( $chart_image_id ) {
	$chart_image_url = wp_get_attachment_url( $chart_image_id );
	printf(
		'<div class="chart-image"><img src="%s" alt="%s" title="%s"/></div>',
		esc_url( $chart_image_url ),
		esc_attr( $post_data->post_title ),
		esc_attr( $chart_label )
	);
}

if ( isset( $chart_table ) && array_filter( $chart_table ) ) {
    if( false !== is_size_chart_table_empty($chart_table) ) {
	    ?>
        <div class="chart-table">
		    <?php
		    echo wp_kses_post( size_chart_get_chart_table( $chart_table ) );
            ?>
        </div>
	    <?php
    }
}
?>
