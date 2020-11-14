<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Size_Chart_For_Woocommerce
 * @subpackage Size_Chart_For_Woocommerce/public
 * @link       http://www.multidots.com/
 * @since      1.0.0
 */
/**
 * If this file is called directly, abort.
 */
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Size_Chart_For_Woocommerce
 * @subpackage Size_Chart_For_Woocommerce/public
 * @author     Multidots <inquiry@multidots.in>
 */
class Size_Chart_For_Woocommerce_Public
{
    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string $version The current version of this plugin.
     */
    private  $post_type_name ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @param string $post_type_name The post type name of this plugin.
     *
     * @since 1.0.0
     */
    public function __construct( $plugin_name, $version, $post_type_name )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->post_type_name = $post_type_name;
    }
    
    /**
     * Get the plugin name.
     * @return string
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
    
    /**
     * Plugin dash name.
     * @return string
     */
    public function get_plugin_dash_name()
    {
        return sanitize_title_with_dashes( $this->get_plugin_name() );
    }
    
    /**
     * Get the plugin version.
     * @return string
     */
    public function get_plugin_version()
    {
        return $this->version;
    }
    
    /**
     * Register the Style and JavaScript for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function enqueue_styles_scripts_callback()
    {
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Size_Chart_For_Woocommerce_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Size_Chart_For_Woocommerce_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        if ( is_single() && 'product' == get_post_type() ) {
            // Register styles.
            wp_register_style(
                $this->get_plugin_dash_name(),
                plugin_dir_url( __FILE__ ) . 'css/size-chart-for-woocommerce-public.css',
                array(),
                $this->version,
                'all'
            );
            wp_register_style(
                $this->get_plugin_dash_name() . '-jquery-modal-default-theme',
                plugin_dir_url( __FILE__ ) . 'css/remodal-default-theme.css',
                array(),
                $this->version,
                'all'
            );
            // Enqueue styles.
            wp_enqueue_style( $this->get_plugin_dash_name() );
            wp_enqueue_style( $this->get_plugin_dash_name() . '-jquery-modal-default-theme' );
            $inline_style_varibale = $this->get_inline_style_for_size_chart();
            if ( false !== $inline_style_varibale ) {
                wp_add_inline_style( $this->get_plugin_dash_name(), $inline_style_varibale );
            }
            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Size_Chart_For_Woocommerce_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Size_Chart_For_Woocommerce_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
            wp_register_script(
                $this->get_plugin_dash_name(),
                plugin_dir_url( __FILE__ ) . 'js/size-chart-for-woocommerce-public' . $suffix . '.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script( $this->get_plugin_dash_name() );
        }
    
    }
    
    /**
     * Size chart product custom tab.
     *
     * @param array $tabs current tabs array.
     *
     * @return array return a array of tabs.
     * @since 1.0.0
     *
     */
    public function size_chart_custom_product_tab_callback( $tabs )
    {
        global  $post ;
        $product_size_chart_id = size_chart_get_product_chart_id( $post->ID );
        
        if ( isset( $product_size_chart_id ) && !empty($product_size_chart_id) && 'publish' === get_post_status( $product_size_chart_id ) ) {
            $size_chart_id = $product_size_chart_id;
        } else {
            $size_chart_id = $this->size_chart_id_by_category( $post->ID );
        }
        
        if ( !$size_chart_id ) {
            return $tabs;
        }
        $chart_label = size_chart_get_label_by_chart_id( $size_chart_id );
        $chart_position = size_chart_get_position_by_chart_id( $size_chart_id );
        
        if ( 'tab' === $chart_position ) {
            $size_chart_tab_label = size_chart_get_tab_label();
            
            if ( isset( $size_chart_tab_label ) && !empty($size_chart_tab_label) ) {
                $tab_label = size_chart_get_tab_label();
            } else {
                $tab_label = $chart_label;
            }
            
            $tabs['custom_tab'] = array(
                'title'    => __( $tab_label, 'size-chart-for-woocommerce' ),
                'priority' => 50,
                'callback' => array( $this, 'size_chart_custom_product_tab_content_callback' ),
            );
            return $tabs;
        }
        
        return $tabs;
    }
    
    /**
     * Check popup button position.
     *
     * @since 1.0.0
     */
    public function size_chart_popup_button_position_callback()
    {
        $filter_hook = apply_filters( 'add_hook_custom_size_chart_position', 'woocommerce_single_product_summary' );
        add_action( $filter_hook, array( $this, 'size_chart_popup_button_callback' ), 11 );
    }
    
    /**
     * Size chart new tab content.
     *
     * @since 1.0.0
     */
    public function size_chart_custom_product_tab_content_callback()
    {
        global  $post ;
        $prod_id = size_chart_get_product_chart_id( $post->ID );
        
        if ( isset( $prod_id ) && !empty($prod_id) && '' !== get_post_status( $prod_id ) && 'publish' === get_post_status( $prod_id ) ) {
            $chart_id = $prod_id;
        } else {
            $chart_id = $this->size_chart_id_by_category( $post->ID );
        }
        
        
        if ( $chart_id ) {
            $file_dir_path = 'includes/common-files/size-chart-contents.php';
            if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path ) ) {
                include_once plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path;
            }
        }
    
    }
    
    /**
     * Hook to display chart button.
     *
     * @since 1.0.0
     */
    public function size_chart_popup_button_callback()
    {
        global  $post ;
        $prod_id = size_chart_get_product_chart_id( $post->ID );
        
        if ( isset( $prod_id ) && !empty($prod_id) && '' !== get_post_status( $prod_id ) && 'publish' === get_post_status( $prod_id ) ) {
            $chart_id = $prod_id;
        } else {
            $chart_id = $this->size_chart_id_by_category( $post->ID );
        }
        
        $chart_label = size_chart_get_label_by_chart_id( $chart_id );
        $chart_position = size_chart_get_position_by_chart_id( $chart_id );
        
        if ( 0 !== $chart_id && 'popup' === $chart_position ) {
            $size_chart_popup_label = size_chart_get_popup_label();
            
            if ( isset( $size_chart_popup_label ) && !empty($size_chart_popup_label) ) {
                $popup_label = $size_chart_popup_label;
            } else {
                $popup_label = $chart_label;
            }
            
            $size_chart_get_button_class = '';
            ?>
            <div class="button-wrapper">
                <a class="<?php 
            echo  esc_attr( $size_chart_get_button_class ) ;
            ?>" href="javascript:void(0);" id="chart-button">
					<?php 
            echo  esc_html( $popup_label ) ;
            ?>
                </a>
            </div>
            <div id="md-size-chart-modal" class="md-size-chart-modal">
                <div class="md-size-chart-modal-content">
                    <div class="md-size-chart-overlay"></div>
                    <div class="md-size-chart-modal-body">
                        <button data-remodal-action="close" id="md-poup" class="remodal-close" aria-label="<?php 
            esc_attr_e( 'Close', 'size-chart-for-woocommerce' );
            ?>"></button>
                        <div class="chart-container">
							<?php 
            $file_dir_path = 'includes/common-files/size-chart-contents.php';
            if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path ) ) {
                include_once plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path;
            }
            ?>
                        </div>
                    </div>
                </div>

            </div>
			<?php 
        }
    
    }
    
    /**
     * BN code added.
     *
     * @param array $paypal_args pass the order object.
     *
     * @return mixed return a array with adding new argument.
     */
    public function paypal_bn_code_filter_callback( $paypal_args )
    {
        $paypal_args['bn'] = 'Multidots_SP';
        return $paypal_args;
    }
    
    /**
     * Check if product belongs to a category.
     *
     * @param int $product_id product id.
     *
     * @return bool|int|mixed return size chart id if size chart id found.
     * @since 1.0.0
     */
    public function size_chart_id_by_category( $product_id )
    {
        $size_chart_id = 0;
        $product_terms = wc_get_product_term_ids( $product_id, 'product_cat' );
        
        if ( isset( $product_terms ) && !empty($product_terms) && (is_array( $product_terms ) && array_filter( $product_terms )) ) {
            $cache_key = 'size_chart_categories_with_product_categories_' . implode( "_", $product_terms );
            $size_chart_id = wp_cache_get( $cache_key );
            
            if ( false === $size_chart_id ) {
                $size_chart_args = array(
                    'posts_per_page'         => 1,
                    'order'                  => 'DESC',
                    'post_type'              => 'size-chart',
                    'post_status'            => 'publish',
                    'no_found_rows'          => true,
                    'update_post_term_cache' => false,
                    'fields'                 => 'ids',
                );
                if ( count( $product_terms ) > 1 ) {
                    $size_chart_args['meta_query']['relation'] = 'OR';
                }
                foreach ( $product_terms as $product_term ) {
                    $size_chart_args['meta_query'][] = array(
                        'key'     => 'chart-categories',
                        'value'   => "[[:<:]]{$product_term}[[:>:]]",
                        'compare' => 'RLIKE',
                    );
                }
                $size_chart_category_query = new WP_Query( $size_chart_args );
                if ( isset( $size_chart_category_query ) && !empty($size_chart_category_query) && $size_chart_category_query->have_posts() ) {
                    foreach ( $size_chart_category_query->posts as $chart_array_id ) {
                        $size_chart_id = $chart_array_id;
                    }
                }
                wp_cache_set( $cache_key, $size_chart_id );
            }
        
        }
        
        return $size_chart_id;
    }
    
    /**
     * Create and get the inline style.
     *
     * @return bool|string Inline style string.
     */
    public function get_inline_style_for_size_chart()
    {
        global  $post ;
        
        if ( isset( $post ) && !empty($post) ) {
            $prod_id = size_chart_get_product_chart_id( $post->ID );
            
            if ( isset( $prod_id ) && !empty($prod_id) ) {
                $chart_id = $prod_id;
            } else {
                $chart_id = $this->size_chart_id_by_category( $post->ID );
            }
            
            return size_chart_get_inline_styles_by_post_id( $chart_id );
        }
        
        return false;
    }

}