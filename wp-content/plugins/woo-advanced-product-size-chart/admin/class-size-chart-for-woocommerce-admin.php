<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Size_Chart_For_Woocommerce
 * @subpackage Size_Chart_For_Woocommerce/admin
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Size_Chart_For_Woocommerce
 * @subpackage Size_Chart_For_Woocommerce/admin
 * @author     Multidots <inquiry@multidots.in>
 */
class Size_Chart_For_Woocommerce_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * The post_type_name of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $post_type_name The current post_type_name of this plugin.
     */
    private  $post_type_name ;
    /**
     * The size chart settings.
     * @var
     */
    public  $size_chart_settings ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @param string $post_type_name The post type name of this plugin.
     *
     * @since    1.0.0
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
     * Get the plugin version.
     * @return string
     */
    public function get_plugin_post_type_name()
    {
        return $this->post_type_name;
    }
    
    /**
     * Register the Style and JavaScript for the admin area.
     * Enqueue style and JavaScript for admin area.
     * Add inline Style and JavaScript for selected pages.
     *
     * @param string $hook_suffix the current page hook suffix.
     *
     * @since    1.0.0
     */
    public function enqueue_styles_scripts_callback( $hook_suffix )
    {
        global  $post, $typenow ;
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
        /**
         * Register, Enqueue and Add inline styles.
         */
        // Register styles.
        wp_register_style(
            $this->get_plugin_dash_name() . '-jquery-editable-style',
            plugin_dir_url( __FILE__ ) . 'css/jquery.edittable.css',
            array(),
            $this->get_plugin_version(),
            'all'
        );
        wp_register_style(
            $this->get_plugin_dash_name() . '-select2-style',
            plugin_dir_url( __FILE__ ) . 'css/select2.css',
            array(),
            $this->get_plugin_version(),
            'all'
        );
        wp_register_style(
            $this->get_plugin_dash_name(),
            plugin_dir_url( __FILE__ ) . 'css/size-chart-for-woocommerce-admin.css',
            array(),
            $this->get_plugin_version(),
            'all'
        );
        wp_register_style(
            $this->get_plugin_dash_name() . '-main-style',
            plugin_dir_url( __FILE__ ) . 'css/style.css',
            array(),
            $this->get_plugin_version(),
            'all'
        );
        // Enqueue styles.
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        wp_enqueue_style( $this->get_plugin_dash_name() . '-jquery-editable-style' );
        wp_enqueue_style( $this->get_plugin_dash_name() . '-select2-style' );
        wp_enqueue_style( $this->get_plugin_dash_name() . '-main-style' );
        wp_enqueue_style( $this->get_plugin_dash_name() );
        // Add inline style.
        wp_add_inline_style( $this->get_plugin_dash_name(), 'body{}' );
        
        if ( isset( $post ) ) {
            $post_status = get_post_meta( $post->ID, 'post_status', true );
            if ( $this->get_plugin_post_type_name() === $post->post_type && isset( $post_status ) && 'default' === $post_status ) {
                // Add inline style.
                wp_add_inline_style( 'wp-jquery-ui-dialog', "#delete-action, .bulkactions, #duplicate-action, #misc-publishing-actions, #minor-publishing-actions{display:none;}" );
            }
        }
        
        /**
         * Register, Enqueue and Add inline JavaScripts.
         */
        // Register scripts.
        wp_register_script(
            $this->get_plugin_dash_name() . '-jquery-editable-js',
            plugin_dir_url( __FILE__ ) . 'js/jquery.edittable.js',
            array( 'jquery' ),
            $this->get_plugin_version(),
            false
        );
        wp_register_script(
            $this->get_plugin_dash_name(),
            plugin_dir_url( __FILE__ ) . 'js/size-chart-for-woocommerce-admin' . $suffix . '.js',
            array( 'jquery', 'wp-color-picker', 'selectWoo' ),
            $this->get_plugin_version(),
            false
        );
        $screen = get_current_screen();
        // Localize script.
        $size_chart_localize_script_args = array(
            'size_chart_admin_url'             => admin_url( 'admin-ajax.php' ),
            'size_chart_nonce'                 => wp_create_nonce( 'size_chart_for_wooocmmerce_nonoce' ),
            'size_chart_post_title_required'   => __( 'Title is required.', 'size-chart-for-woocommerce' ),
            'size_chart_post_type_name'        => __( 'size-chart' ),
            'size_chart_current_screen_id'     => ( isset( $screen->id ) ? $screen->id : '' ),
            'size_chart_get_started_page_slug' => 'size-chart-get-started',
            'size_chart_plugin_dash_name'      => $this->get_plugin_dash_name(),
            'size_chart_plugin_name'           => $this->get_plugin_name(),
        );
        if ( scfw_fs()->is_plan( 'free', true ) ) {
            $size_chart_localize_script_args['size_chart_plugin_menu_url'] = 'edit.php?post_type=' . $this->get_plugin_post_type_name();
        }
        wp_localize_script( $this->get_plugin_dash_name(), 'sizeChartScriptObject', $size_chart_localize_script_args );
        // Enqueue scripts.
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script( $this->get_plugin_dash_name() . '-jquery-editable-js' );
        wp_enqueue_script( $this->get_plugin_dash_name() );
        // Loads the image iframe.
        
        if ( $this->get_plugin_post_type_name() === $typenow ) {
            wp_enqueue_media();
            // Registers and enqueues the required javascript.
            wp_register_script( $this->get_plugin_dash_name() . '-meta-box-image', plugin_dir_url( __FILE__ ) . 'js/images-frame' . $suffix . '.js', array( 'jquery' ) );
            wp_localize_script( $this->get_plugin_dash_name() . '-meta-box-image', 'meta_image', array(
                'title'  => __( 'Upload an Image', 'size-chart-for-woocommerce' ),
                'button' => __( 'Use this image', 'size-chart-for-woocommerce' ),
            ) );
            wp_enqueue_script( $this->get_plugin_dash_name() . '-meta-box-image' );
        }
        
        // Disable the publish button on size chart.
        
        if ( 'post.php' === $hook_suffix ) {
            $post_status = get_post_meta( $post->ID, 'post_status', true );
            if ( $this->get_plugin_post_type_name() === $post->post_type && isset( $post_status ) && 'default' === $post_status ) {
                // Add inline script.
                wp_add_inline_script( $this->get_plugin_dash_name(), "window.onload = function() {jQuery('#title').prop('disabled', true);};" );
            }
        }
        
        
        if ( $this->get_plugin_post_type_name() === $typenow && 'edit.php' === $hook_suffix ) {
            $default_size_chart_ids = size_chart_get_default_post_ids();
            
            if ( isset( $default_size_chart_ids ) && !empty($default_size_chart_ids) && array_filter( $default_size_chart_ids ) ) {
                $default_size_chart_ids_jquery = implode( ',', $default_size_chart_ids );
                // Add inline script.
                wp_add_inline_script( $this->get_plugin_dash_name(), 'window.onload = function() {jQuery.each([ ' . $default_size_chart_ids_jquery . ' ], function( index, value ) {jQuery("input#cb-select-"+value).remove();});};' );
            }
        
        }
    
    }
    
    /**
     * Register a new post type called chart.
     *
     * @since    1.0.0
     */
    public function size_chart_register_post_type_chart_callback()
    {
        $labels = array(
            'name'               => __( 'Size Charts', 'size-chart-for-woocommerce' ),
            'singular_name'      => __( 'Size Charts', 'size-chart-for-woocommerce' ),
            'menu_name'          => __( 'Size Charts', 'size-chart-for-woocommerce' ),
            'name_admin_bar'     => __( 'Size Charts', 'size-chart-for-woocommerce' ),
            'add_new'            => __( 'Add New', 'size-chart-for-woocommerce' ),
            'add_new_item'       => __( 'Add New Size Charts', 'size-chart-for-woocommerce' ),
            'new_item'           => __( 'New Size Charts', 'size-chart-for-woocommerce' ),
            'edit_item'          => __( 'Edit Size Charts', 'size-chart-for-woocommerce' ),
            'view_item'          => __( 'View Size Charts', 'size-chart-for-woocommerce' ),
            'all_items'          => __( 'All Size Charts', 'size-chart-for-woocommerce' ),
            'search_items'       => __( 'Search Size Charts', 'size-chart-for-woocommerce' ),
            'parent_item_colon'  => __( 'Parent Size Charts:', 'size-chart-for-woocommerce' ),
            'not_found'          => __( 'No chart found.', 'size-chart-for-woocommerce' ),
            'not_found_in_trash' => __( 'No charts found in Trash.', 'size-chart-for-woocommerce' ),
        );
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'size-chart-for-woocommerce' ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => plugin_dir_url( __FILE__ ) . 'images/thedotstore-images/menu-icon.png',
            'supports'           => array( 'title', 'editor' ),
        );
        register_post_type( $this->get_plugin_post_type_name(), $args );
    }
    
    /**
     * This function call for welcome screen in size chart plugin and Default size chart create hook.
     */
    public function size_chart_pro_welcome_screen_and_default_posts_callback()
    {
        /**
         * Default Template.
         * Created default size chart posts.
         * Add option for check default size chart.
         */
        $default_size_chart_option = get_option( 'default_size_chart' );
        
        if ( empty($default_size_chart_option) ) {
            $default_size_chart_posts = array(
                'tshirt-shirt'    => __( "Men's T-Shirts & Polo Shirts Size Chart" ),
                'womens-tshirt'   => __( "Women's T-shirt / Tops size chart" ),
                'mens-shirts'     => __( "Men's Shirts Size Chart" ),
                'womens-dress'    => __( "Women's Dress Size Chart " ),
                'jeans-trouser'   => __( "Men's Jeans & Trousers Size Chart" ),
                'womens-jeans'    => __( "Women's Jeans And Jeggings Size Chart" ),
                'mens-waistcoats' => __( "Men's Waistcoats Size Chart" ),
                'women-cloth'     => __( "Women's Cloth size chart" ),
                'men-shoes'       => __( "Men's Shoes Size Chart" ),
                'women-shoes'     => __( "Women's Shoes Size Chart" ),
            );
            // Get current user to assign a post.
            $user_id = get_current_user_id();
            $default_size_chart_posts_ids = array();
            foreach ( $default_size_chart_posts as $default_post_size_chart_key => $default_post_size_chart_value ) {
                $size_chart_content_html = $this->size_chart_cloth_template_html_content( $default_post_size_chart_key );
                $size_chart_post_arg = array(
                    'post_author'  => $user_id,
                    'post_content' => $size_chart_content_html,
                    'post_type'    => $this->get_plugin_post_type_name(),
                    'post_status'  => 'publish',
                    'post_title'   => $default_post_size_chart_value,
                );
                $post_id = wp_insert_post( $size_chart_post_arg );
                $default_size_chart_posts_ids[] = $post_id;
                if ( 0 !== $post_id ) {
                    $this->size_chart_add_post_meta( $post_id, $default_post_size_chart_key );
                }
            }
            update_option( 'default_size_chart', 'default_size_chart_set' );
            size_chart_update_default_post_ids( $default_size_chart_posts_ids );
        }
        
        /**
         * Size chart welcome page.
         */
        // If no activation redirect.
        if ( !get_transient( '_welcome_screen_activation_redirect_size_chart' ) ) {
            return;
        }
        // Delete the redirect transient.
        delete_transient( '_welcome_screen_activation_redirect_size_chart' );
        // If activating from network, or bulk.
        // Sanitize user input.
        $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_STRING );
        if ( is_network_admin() || isset( $activate_multi ) ) {
            return;
        }
        // Redirect to size chart welcome page.
        wp_safe_redirect( admin_url( "admin.php?page=size-chart-get-started" ) );
        exit;
    }
    
    /**
     * Register admin menu and add size chart dashboard page for the plugin.
     * @since      1.0.0
     */
    public function size_chart_pro_welcome_page_screen_and_menu_callback()
    {
        global  $GLOBALS ;
        if ( empty($GLOBALS['admin_page_hooks']['dots_store']) ) {
            add_menu_page(
                __( 'DotStore Plugins', 'size-chart-for-woocommerce' ),
                __( 'DotStore Plugins', 'size-chart-for-woocommerce' ),
                'NULL',
                'dots_store',
                array( $this, 'size_chart_get_started_page' ),
                plugin_dir_url( __FILE__ ) . 'images/thedotstore-images/menu-icon.png',
                25
            );
        }
        add_submenu_page(
            'dots_store',
            __( $this->get_plugin_name() ),
            __( $this->get_plugin_name() ),
            'manage_options',
            'size-chart-get-started',
            array( $this, 'size_chart_get_started_page' )
        );
        add_submenu_page(
            'dots_store',
            __( 'Introduction', 'size-chart-for-woocommerce' ),
            __( 'Introduction', 'size-chart-for-woocommerce' ),
            'manage_options',
            'size-chart-information',
            array( $this, 'size_chart_information_page' )
        );
        add_submenu_page(
            'dots_store',
            __( 'Get Started', 'size-chart-for-woocommerce' ),
            __( 'Get Started', 'size-chart-for-woocommerce' ),
            'manage_options',
            'size-chart-get-started-page',
            array( $this, 'size_chart_get_started_page' )
        );
        $settings = add_submenu_page(
            'edit.php?post_type=size-chart',
            __( 'Settings', 'size-chart-for-woocommerce' ),
            __( 'Settings', 'size-chart-for-woocommerce' ),
            'manage_options',
            'size-chart-setting-page',
            array( $this, 'size_chart_settings_form_callback' )
        );
        add_action( "load-{$settings}", array( $this, 'size_chart_settings_page_callback' ) );
        add_action( "load-{$settings}", array( $this, 'size_chart_settings_page_callback' ) );
    }
    
    public function size_chart_get_started_page()
    {
        $file_dir_path = 'partials/size-chart-get-started-page.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    public function size_chart_information_page()
    {
        $file_dir_path = 'partials/size-chart-information-page.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     * Size chart settings and redirection.
     *
     * @since      1.0.0
     */
    public function size_chart_settings_page_callback()
    {
        // Sanitize user input.
        $size_chart_submit = filter_input( INPUT_POST, 'size_chart_submit', FILTER_SANITIZE_STRING );
        $get_page_name = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
        
        if ( isset( $size_chart_submit ) && isset( $get_page_name ) && 'size-chart-setting-page' === $get_page_name ) {
            $this->size_chart_settings = array();
            // Sanitize user input. (Free)
            $size_chart_tab_label = filter_input( INPUT_POST, 'size-chart-tab-label', FILTER_SANITIZE_STRING );
            $size_chart_popup_label = filter_input( INPUT_POST, 'size-chart-popup-label', FILTER_SANITIZE_STRING );
            $size_chart_sub_title_text = filter_input( INPUT_POST, 'size-chart-sub-title-text', FILTER_SANITIZE_STRING );
            // Assign the user input data in size chart settings object.
            $this->size_chart_settings['size-chart-tab-label'] = $size_chart_tab_label;
            $this->size_chart_settings['size-chart-popup-label'] = $size_chart_popup_label;
            $this->size_chart_settings['size-chart-sub-title-text'] = $size_chart_sub_title_text;
            $size_chart_settings_json_encode = wp_json_encode( $this->size_chart_settings );
            // Update size chart settings option.
            update_option( "size_chart_settings", $size_chart_settings_json_encode );
            ?>
            <div class="updated">
                <p>
                    <strong>
						<?php 
            esc_html_e( 'Settings saved.', 'size-chart-for-woocommerce' );
            ?>
                    </strong>
                </p>
            </div>
			<?php 
        }
    
    }
    
    /**
     * Removed the index.php in submenu page.
     */
    public function welcome_screen_remove_menus_callback()
    {
        remove_submenu_page( 'index.php', 'size-chart-about' );
        remove_submenu_page( 'dots_store', 'size-chart-information' );
        remove_submenu_page( 'dots_store', 'size-chart-get-started-page' );
    }
    
    /**
     * Size chart and product page in save the size chart meta.
     *
     * @param int $post_id The ID of the post being saved.
     *
     * @return bool
     */
    public function size_chart_pro_product_and_size_chart_save_callback( $post_id )
    {
        // If this is an autosave, our form has not been submitted,
        // so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        // Sanitize user input.
        $post_type_name = filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_STRING );
        // Check the user's permissions.
        
        if ( 'product' === $post_type_name || $this->get_plugin_post_type_name() === $post_type_name ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( !current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        
        // Save the meta when the chart post is saved.
        // Sanitize the user input.
        $size_chart_select_nonce = filter_input( INPUT_POST, 'size_chart_select_custom_box', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        // Verify that the nonce is valid.
        
        if ( isset( $size_chart_select_nonce ) && wp_verify_nonce( $size_chart_select_nonce, 'size_chart_select_custom_box' ) ) {
            // Save the size chart in product page.
            $product_chart_id = filter_input( INPUT_POST, 'prod-chart', FILTER_SANITIZE_NUMBER_INT );
            
            if ( isset( $product_chart_id ) && !empty($product_chart_id) ) {
                update_post_meta( $post_id, 'prod-chart', $product_chart_id );
                return true;
            } else {
                delete_post_meta( $post_id, 'prod-chart' );
            }
        
        }
        
        // Save the meta when the chart post is saved.
        // Sanitize the user input.
        $size_chart_inner_nonce = filter_input( INPUT_POST, 'size_chart_inner_custom_box', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( isset( $size_chart_inner_nonce ) && wp_verify_nonce( $size_chart_inner_nonce, 'size_chart_inner_custom_box' ) ) {
            // Sanitize the user input.
            $chart_label = filter_input( INPUT_POST, 'label', FILTER_SANITIZE_STRING );
            $chart_img = filter_input( INPUT_POST, 'primary-chart-image', FILTER_SANITIZE_STRING );
            $chart_position = filter_input( INPUT_POST, 'position', FILTER_SANITIZE_STRING );
            $chart_table = filter_input(
                INPUT_POST,
                'chart-table',
                FILTER_SANITIZE_STRING,
                FILTER_FLAG_NO_ENCODE_QUOTES
            );
            $table_style = filter_input( INPUT_POST, 'table-style', FILTER_SANITIZE_STRING );
            // Sanitize the user input array values.
            $args = array(
                'chart-categories' => array(
                'filter' => FILTER_VALIDATE_INT,
                'flags'  => FILTER_REQUIRE_ARRAY,
            ),
            );
            $post_chart_categories = filter_input_array( INPUT_POST, $args );
            $chart_categories = ( isset( $post_chart_categories['chart-categories'] ) && array_filter( $post_chart_categories['chart-categories'] ) ? $post_chart_categories['chart-categories'] : array() );
            $chart_categories = wp_json_encode( $chart_categories );
            // Save the data in post meta.
            update_post_meta( $post_id, 'label', $chart_label );
            update_post_meta( $post_id, 'primary-chart-image', $chart_img );
            update_post_meta( $post_id, 'position', $chart_position );
            update_post_meta( $post_id, 'chart-categories', $chart_categories );
            update_post_meta( $post_id, 'chart-table', $chart_table );
            update_post_meta( $post_id, 'table-style', $table_style );
        }
        
        // Save the meta when the chart post is saved.
        // Sanitize the user input.
        $size_chart_search_product_nonce = filter_input( INPUT_POST, 'size_chart_search_product_custom_box', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( isset( $size_chart_search_product_nonce ) && wp_verify_nonce( $size_chart_search_product_nonce, 'size_chart_search_product_custom_box' ) ) {
            $args = array(
                'product-item' => array(
                'filter' => FILTER_VALIDATE_INT,
                'flags'  => FILTER_REQUIRE_ARRAY,
            ),
            );
            $product_ids = filter_input_array( INPUT_POST, $args );
            if ( isset( $product_ids['product-item'] ) && !empty($product_ids['product-item']) && array_filter( $product_ids['product-item'] ) ) {
                foreach ( $product_ids['product-item'] as $product_id ) {
                    update_post_meta( $product_id, 'prod-chart', $post_id );
                }
            }
        }
        
        return true;
    }
    
    /**
     * Adds the meta box container.
     *
     * @param string $post_type The post type name.
     *
     * @since    1.0.0
     */
    public function size_chart_add_meta_box_callback( $post_type )
    {
        // Limit meta box to chart post type.
        $post_types_chart = array( $this->get_plugin_post_type_name(), 'product' );
        
        if ( in_array( $post_type, $post_types_chart, true ) ) {
            // Chart setting meta box.
            add_meta_box(
                'chart-settings',
                __( 'Size Chart Settings', 'size-chart-for-woocommerce' ),
                array( $this, 'size_chart_meta_box_content_callback' ),
                $this->get_plugin_post_type_name(),
                'advanced',
                'high'
            );
            // Meta box to select chart in product page.
            add_meta_box(
                'additional-chart',
                __( 'Search/Select Size Chart', 'size-chart-for-woocommerce' ),
                array( $this, 'size_chart_select_chart_callback' ),
                'product',
                'side',
                'default'
            );
            // Meta box to List of assign category.
            add_meta_box(
                'chart-assign-category',
                __( 'Assign Category', 'size-chart-for-woocommerce' ),
                array( $this, 'size_chart_assign_category_callback' ),
                $this->get_plugin_post_type_name(),
                'side',
                'default'
            );
            // Meta box to List of assign Product
            add_meta_box(
                'chart-assign-product',
                __( 'Assign Product', 'size-chart-for-woocommerce' ),
                array( $this, 'size_chart_assign_product_callback' ),
                $this->get_plugin_post_type_name(),
                'side',
                'default'
            );
        }
    
    }
    
    /**
     * Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function size_chart_meta_box_content_callback( $post )
    {
        $file_dir_path = 'partials/size-chart-meta-box-content-form.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     *  Meta Box content to select chart on product page.
     *
     * @param WP_Post $post The post object.
     */
    public function size_chart_select_chart_callback( $post )
    {
        $file_dir_path = 'partials/size-chart-select-chart-form.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     *  Meta Box content to assign category list.
     *
     * @param WP_Post $post The post object.
     */
    public function size_chart_assign_category_callback( $post )
    {
        $file_dir_path = 'partials/size-chart-assign-category.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     *  Meta Box content to assign category list.
     *
     * @param WP_Post $post The post object.
     */
    public function size_chart_assign_product_callback( $post )
    {
        $file_dir_path = 'partials/size-chart-assign-product.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     *  Size chart settings form.
     *
     * @since      1.0.0
     */
    public function size_chart_settings_form_callback()
    {
        $file_dir_path = 'partials/size-chart-settings-form.php';
        if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
            include_once plugin_dir_path( __FILE__ ) . $file_dir_path;
        }
    }
    
    /**
     * Duplicate post create as a draft and redirects then to the edit post screen.
     */
    public function size_chart_duplicate_post_callback()
    {
        // Sanitize user input.
        $get_request_get = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_STRING );
        $size_chart_id = ( isset( $get_request_get ) ? absint( $get_request_get ) : 0 );
        
        if ( isset( $get_request_get ) && !empty($get_request_get) ) {
            $clone_post_id = $this->size_chart_duplicate( $size_chart_id );
            wp_redirect( admin_url( 'post.php?action=edit&post=' . $clone_post_id ) );
            exit;
        } else {
            wp_die( esc_html__( 'could not find post: ' . $size_chart_id ), 'size-chart-for-woocommerce' );
        }
    
    }
    
    /**
     * Creates size chart post preview.
     */
    public function size_chart_preview_post_callback()
    {
        $result = array();
        $result['success'] = 0;
        $result['msg'] = esc_html__( 'Something went wrong.', 'size-chart-for-woocommerce' );
        // Sanitize user input.
        $nonce = filter_input( INPUT_GET, 'security', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        // Verify the nonce.
        
        if ( !isset( $nonce ) || !wp_verify_nonce( $nonce, 'size_chart_for_wooocmmerce_nonoce' ) ) {
            $result['msg'] = esc_html__( 'Security check failed.', 'size-chart-for-woocommerce' );
            echo  wp_json_encode( $result ) ;
            wp_die();
        }
        
        // Sanitize user input.
        $chart_id = filter_input( INPUT_GET, 'chartID', FILTER_SANITIZE_NUMBER_INT );
        
        if ( isset( $chart_id ) && !empty($chart_id) ) {
            $chart_label = size_chart_get_label_by_chart_id( $chart_id );
            
            if ( isset( $chart_label ) && !empty($chart_label) ) {
                $result['css'] = size_chart_get_inline_styles_by_post_id( $chart_id );
                ob_start();
                ?>
                <div class="chart-container" id="size-chart-id-<?php 
                echo  esc_attr( $chart_id ) ;
                ?>">
					<?php 
                $file_dir_path = 'includes/common-files/size-chart-contents.php';
                if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path ) ) {
                    require_once plugin_dir_path( dirname( __FILE__ ) ) . $file_dir_path;
                }
                ?>
                </div>
				<?php 
                $result['html'] = ob_get_clean();
                $result['success'] = 1;
                $result['msg'] = esc_html__( 'Successfully...', 'size-chart-for-woocommerce' );
            } else {
                $result['msg'] = esc_html__( 'No data found...', 'size-chart-for-woocommerce' );
            }
        
        } else {
            $result['msg'] = esc_html__( 'No result found...', 'size-chart-for-woocommerce' );
        }
        
        echo  wp_json_encode( $result ) ;
        wp_die();
    }
    
    /**
     * Size chart preview dialog box html.
     */
    public function size_chart_preview_dialog_box_callback()
    {
        ?>
        <div id="wait">
            <img src="<?php 
        echo  esc_url( plugins_url( 'admin/images/loader.gif', dirname( __FILE__ ) ) ) ;
        ?>" width="64" height="64" alt="<?php 
        esc_attr_e( 'loader', 'size-chart-for-woocommerce' );
        ?>"/>
            <br><?php 
        esc_html_e( 'Loading...', 'size-chart-for-woocommerce' );
        ?>
        </div>

        <div id="md-size-chart-modal" class="md-size-chart-modal">
            <!-- Modal content -->
            <div class="md-size-chart-modal-content">
                <div class="md-size-chart-overlay"></div>
                <div class="md-size-chart-modal-body">
                    <button data-remodal-action="close" id="md-poup" class="remodal-close" aria-label="<?php 
        esc_attr_e( 'Close', 'size-chart-for-woocommerce' );
        ?>"></button>
                </div>
            </div>

        </div>
		<?php 
    }
    
    /**
     * Manage size chart type and action.
     *
     * @param array $columns existing columns array.
     *
     * @return array updated columns array.
     */
    public function size_chart_column_callback( $columns )
    {
        $new_columns = ( is_array( $columns ) ? $columns : array() );
        unset( $new_columns['date'] );
        $new_columns['size-chart-type'] = __( 'Size Chart Type', 'size-chart-for-woocommerce' );
        $new_columns['action'] = __( 'Action', 'size-chart-for-woocommerce' );
        return $new_columns;
    }
    
    /**
     * Manage size chart column.
     *
     * @param string $column column name passing.
     */
    public function size_chart_manage_column_callback( $column )
    {
        global  $post ;
        switch ( $column ) {
            case 'size-chart-type':
                $size_chart_type = get_post_meta( $post->ID, 'post_status', true );
                
                if ( isset( $size_chart_type ) && 'default' === $size_chart_type ) {
                    esc_html_e( 'Default Template', 'size-chart-for-woocommerce' );
                } else {
                    esc_html_e( 'Custom Template', 'size-chart-for-woocommerce' );
                }
                
                break;
            case 'action':
                if ( isset( $post ) && !empty($post) ) {
                    if ( '' !== $post->post_title ) {
                        echo  sprintf(
                            "<a href='%s' class='clone-chart' title='%s' rel='permalink'></a><a id='%s' href='javascript:void(0);' class='preview_chart' title='%s' rel='permalink'></a>",
                            esc_url( wp_nonce_url( add_query_arg( array(
                            'action' => 'size_chart_duplicate_post',
                            'post'   => $post->ID,
                        ), admin_url( 'admin.php' ) ), 'size_chart_duplicate_post_callback' ) ),
                            esc_attr__( 'Clone', 'size-chart-for-woocommerce' ),
                            esc_attr( $post->ID ),
                            esc_attr__( 'Preview', 'size-chart-for-woocommerce' )
                        ) ;
                    }
                }
                break;
        }
    }
    
    /**
     * Delete size chart image.
     */
    public function size_chart_delete_image_callback()
    {
        $result = array();
        $result['success'] = 0;
        $result['msg'] = esc_html__( 'Something went wrong.', 'size-chart-for-woocommerce' );
        // Sanitize user input.
        $nonce = filter_input( INPUT_GET, 'security', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        // Verify nonce.
        
        if ( !isset( $nonce ) || !wp_verify_nonce( $nonce, 'size_chart_for_wooocmmerce_nonoce' ) ) {
            $result['msg'] = esc_html__( 'Security check failed.', 'size-chart-for-woocommerce' );
            echo  wp_json_encode( $result ) ;
            wp_die();
        }
        
        // Sanitize user input.
        $post_id = filter_input( INPUT_GET, 'postID', FILTER_SANITIZE_NUMBER_INT );
        
        if ( isset( $post_id ) && !empty($post_id) ) {
            update_post_meta( $post_id, 'primary-chart-image', '' );
            $result['msg'] = esc_html__( 'image successfully deleted...', 'size-chart-for-woocommerce' );
            $result['url'] = size_chart_default_chart_image();
            $result['success'] = 1;
        }
        
        echo  wp_json_encode( $result ) ;
        wp_die();
    }
    
    /**
     * Welcome overview tab.
     *
     * @param string $heading_name heading name.
     */
    public function size_chart_about_callback( $heading_name )
    {
        ?>
        <div class="changelog">
            <h3><?php 
        echo  esc_html( $heading_name ) ;
        ?></h3>
            <div class="changelog about-integrations">
                <div class="wc-feature feature-section col three-col">
                    <div class="size-chart-whatnew-main">
                        <div class="size-chart-whatn-right">
                            <h3><?php 
        esc_html_e( 'Add product custom size guides to any of your WooCommerce products.', 'size-chart-for-woocommerce' );
        ?></h3>
                            <p><?php 
        esc_html_e( 'Size Chart For WooCommerce plugin opens the possibility to create customize size chart options. With this plugin has ready made size chart template and allows you to create custom Size Charts and apply to specific category and product in your online store.', 'size-chart-for-woocommerce' );
        ?></p>
                            <h3><?php 
        esc_html_e( 'Default size chart template', 'size-chart-for-woocommerce' );
        ?></h3>
                            <p><?php 
        esc_html_e( 'With this plugin have sample default size chart template, So you can direct apply to product or category.', 'size-chart-for-woocommerce' );
        ?></p>
                            <h3><?php 
        esc_html_e( 'Create your own size guide', 'size-chart-for-woocommerce' );
        ?></h3>
                            <p><?php 
        esc_html_e( 'With this Plugin, you are able to customize/ clone the default size chart and create your own size guide for anything you imagine!', 'size-chart-for-woocommerce' );
        ?></p>
                            <h3><?php 
        esc_html_e( 'Comprehensive display', 'size-chart-for-woocommerce' );
        ?></h3>
                            <p><?php 
        esc_html_e( 'Customers will be able to fully understand your product and buy it without making unnecessary questions regarding size.', 'size-chart-for-woocommerce' );
        ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php 
    }
    
    /**
     * Remove the row actions for default size chart.
     *
     * @param array $actions post table row actions array.
     *
     * @return mixed post table row actions.
     */
    public function size_chart_remove_row_actions_callback( $actions )
    {
        global  $post ;
        $post_status = get_post_meta( $post->ID, 'post_status', true );
        
        if ( $this->get_plugin_post_type_name() === $post->post_type && isset( $post_status ) && 'default' === $post_status ) {
            unset( $actions );
            $actions = array();
        }
        
        return apply_filters( 'size_chart_add_row_actions', $actions );
    }
    
    /**
     * Size chart default template.
     */
    public function size_chart_filter_default_template_callback()
    {
        global  $typenow ;
        // Sanitize user input.
        $current = filter_input( INPUT_GET, 'default_template', FILTER_SANITIZE_STRING );
        
        if ( $this->get_plugin_post_type_name() === $typenow ) {
            ?>
            <select name="default_template" id="issue">
                <option value=""><?php 
            esc_html_e( 'Show All Template', 'size-chart-for-woocommerce' );
            ?></option>
                <option value="hide-default" <?php 
            selected( 'hide-default', $current, true );
            ?>><?php 
            esc_html_e( 'Hide Default Template', 'size-chart-for-woocommerce' );
            ?></option>
            </select>
			<?php 
        }
    
    }
    
    /**
     * Set size chart default template.
     *
     * @param string|array $query WP_Query arguments. See WP_Query::__construct()
     */
    public function size_chart_filter_default_template_query_callback( $query )
    {
        global  $pagenow ;
        // Sanitize user input.
        $default_template = filter_input( INPUT_GET, 'default_template', FILTER_SANITIZE_STRING );
        $post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );
        if ( is_admin() && 'edit.php' === $pagenow && isset( $post_type ) && $this->get_plugin_post_type_name() === $post_type && isset( $default_template ) && !empty($default_template) ) {
            set_query_var( 'meta_query', array( array(
                'key'     => 'post_status',
                'value'   => 'default',
                'compare' => 'NOT EXISTS',
            ) ) );
        }
    }
    
    /**
     * Size chart selected chart deleted.
     *
     * @param int $post_id size chart id.
     */
    public function size_chart_selected_chart_delete_callback( $post_id )
    {
        
        if ( isset( $post_id ) && !empty($post_id) && $this->get_plugin_post_type_name() === get_post_type( $post_id ) ) {
            $post_ids_result = $this->get_post_id_by_meta_key_and_value( 'prod-chart', $post_id );
            if ( false !== $post_ids_result ) {
                foreach ( $post_ids_result as $product_id ) {
                    delete_post_meta( $product_id, 'prod-chart', $post_id );
                }
            }
        }
    
    }
    
    /**
     * Get post id from meta key and value.
     *
     * @param string $key post meta kay
     * @param mixed $value post meta value for matching.
     *
     * @return array|bool
     */
    public function get_post_id_by_meta_key_and_value( $key, $value )
    {
        global  $wpdb ;
        $cache_key = 'size_chart_meta_key_and_value_' . $key . '_' . $value;
        $meta_object = wp_cache_get( $cache_key );
        
        if ( false === $meta_object ) {
            $meta_object = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key=%s AND meta_value=%s", $key, $value ) );
            wp_cache_set( $cache_key, $meta_object );
        }
        
        
        if ( isset( $meta_object ) && !empty($meta_object) && is_array( $meta_object ) && array_filter( $meta_object ) ) {
            return $meta_object;
        } else {
            return false;
        }
    
    }
    
    /**
     * Size chart pro review admin notice html.
     *
     * @param string $tag_id html tag id.
     * @param string $tag_class html tag class.
     */
    public function size_chart_pro_review_html_callback( $tag_id = '', $tag_class = '' )
    {
        printf(
            "<p id='%s' class='%s'>%s <strong>%s</strong> %s <a href='%s' target='_blank'> %s </a> %s <a href='%s' target='_blank'>%s</a></p>",
            esc_attr__( $tag_id, 'size-chart-for-woocommerce' ),
            esc_attr__( $tag_class, 'size-chart-for-woocommerce' ),
            esc_html__( 'Hello! If you are happy with the', 'size-chart-for-woocommerce' ),
            esc_html__( 'WooCommerce Advanced Product Size Chart', 'size-chart-for-woocommerce' ),
            esc_html__( ', we would really appreciate if you could give', 'size-chart-for-woocommerce' ),
            esc_url( 'https://www.thedotstore.com/woocommerce-advanced-product-size-charts/' ),
            esc_html__( '★★★★★ rating and post your review on DotStore', 'size-chart-for-woocommerce' ),
            esc_html__( 'or', 'size-chart-for-woocommerce' ),
            esc_url( 'https://codecanyon.net/item/advanced-product-size-chart-for-woocommerce/reviews/17465337' ),
            esc_html__( 'CodeCanyon', 'size-chart-for-woocommerce' )
        );
    }
    
    /**
     * Size chart pro plugin admin review notice.
     */
    public function size_chart_pro_admin_notice_review_callback()
    {
        global  $typenow ;
        $get_post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );
        if ( isset( $get_post_type ) && $get_post_type === 'size-chart' || $this->get_plugin_post_type_name() === $typenow ) {
            include_once plugin_dir_path( __FILE__ ) . 'partials/header/plugin-header.php';
        }
    }
    
    /**
     * Ajax search the size chart form product meta search box in product page.
     */
    public function size_chart_search_chart_callback()
    {
        ob_start();
        check_ajax_referer( 'size_chart_search_nonce', 'security' );
        // Sanitized user input.
        $search_query_parameter = filter_input( INPUT_GET, 'searchQueryParameter', FILTER_SANITIZE_STRING );
        $searched_term = ( isset( $search_query_parameter ) ? (string) wc_clean( wp_unslash( $search_query_parameter ) ) : '' );
        if ( empty($searched_term) ) {
            wp_die( -1 );
        }
        $search_query_parameter = str_replace( "’", "'", $search_query_parameter );
        // Search argument.
        $size_chart_search_args = array(
            'post_type'              => $this->get_plugin_post_type_name(),
            's'                      => html_entity_decode( $search_query_parameter, ENT_QUOTES, 'UTF-8' ),
            'post_status'            => 'publish',
            'orderby'                => 'title',
            'order'                  => 'ASC',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        );
        $size_chart_search_wp_query = new WP_Query( $size_chart_search_args );
        $found_chart = array();
        if ( $size_chart_search_wp_query->have_posts() ) {
            foreach ( $size_chart_search_wp_query->posts as $size_chart_search_chart ) {
                $found_chart[$size_chart_search_chart->ID] = sprintf( esc_html__( '%1$s (#%2$s)', 'size-chart-for-woocommerce' ), $size_chart_search_chart->post_title, $size_chart_search_chart->ID );
            }
        }
        wp_send_json( $found_chart );
    }
    
    /**
     * Size chart product assign ajax callback function.
     */
    public function size_chart_product_assign_callback()
    {
        ob_start();
        check_ajax_referer( 'size-chart-pagination', 'security' );
        // Sanitize user input.
        $page_number = filter_input( INPUT_GET, 'pageNumber', FILTER_SANITIZE_NUMBER_INT );
        $post_id = filter_input( INPUT_GET, 'postID', FILTER_SANITIZE_NUMBER_INT );
        $post_per_page = filter_input( INPUT_GET, 'postPerPage', FILTER_SANITIZE_NUMBER_INT );
        if ( empty($post_id) ) {
            wp_die( -1 );
        }
        $response_array = array(
            'success'        => false,
            'msg'            => __( 'Something went wrong.', 'size-chart-for-woocommerce' ),
            'found_products' => array(),
        );
        // Meta_query argument.
        $meta_query_args = $this->size_chart_product_meta_query_argument( $post_id, $page_number, $post_per_page );
        $wp_posts_query = new WP_Query( $meta_query_args );
        if ( $wp_posts_query->have_posts() ) {
            while ( $wp_posts_query->have_posts() ) {
                $wp_posts_query->the_post();
                $response_array['found_products'][get_the_ID()] = array(
                    'href'  => esc_url( get_edit_post_link( get_the_ID() ) ),
                    'title' => get_the_title( get_the_ID() ),
                );
            }
        }
        
        if ( 0 !== count( $response_array['found_products'] ) ) {
            $response_array['success'] = true;
            $response_array['msg'] = __( 'Response successfully.', 'size-chart-for-woocommerce' );
            $response_array['load_pagination'] = size_chart_pagination_html(
                $wp_posts_query,
                $post_id,
                $post_per_page,
                false
            );
        }
        
        wp_send_json( $response_array );
        wp_die();
    }
    
    /**
     * Size chart product meta query argument created.
     *
     * @param int $size_chart_id pass the size chart id.
     * @param int $page_number pass the current page number.
     * @param int $post_per_page pass the per per post.
     *
     * @return array return the array of argument.
     */
    public function size_chart_product_meta_query_argument( $size_chart_id, $page_number = 1, $post_per_page = 10 )
    {
        // Meta_query argument.
        return array(
            'post_type'              => 'product',
            'order'                  => 'DESC',
            'posts_per_page'         => $post_per_page,
            'paged'                  => $page_number,
            'update_post_term_cache' => false,
            'fields'                 => 'ids',
            'orderby'                => 'meta_value',
            'meta_query'             => array( array(
            'key'   => 'prod-chart',
            'value' => $size_chart_id,
        ) ),
        );
    }
    
    /**
     * Quick search products ajax callback function.
     */
    public function size_chart_quick_search_products_callback()
    {
        ob_start();
        check_ajax_referer( 'size_chart_quick_search_nonoce', 'security' );
        // Sanitize user input.
        $quick_search_parameter = filter_input( INPUT_GET, 'searchQueryParameter', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $quick_search_type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );
        $quick_search_post_type = filter_input( INPUT_GET, 'postType', FILTER_SANITIZE_STRING );
        if ( empty($quick_search_parameter) ) {
            wp_die( -1 );
        }
        if ( 'quick-search-posttype-size-chart' !== $quick_search_type ) {
            wp_die( -1 );
        }
        if ( 'product' !== $quick_search_post_type ) {
            wp_die( -1 );
        }
        $response_array = array(
            'success'        => false,
            'msg'            => __( 'Something went wrong.', 'size-chart-for-woocommerce' ),
            'found_products' => array(),
        );
        $quick_search_parameter = str_replace( "’", "'", $quick_search_parameter );
        $size_chart_search_args = array(
            'post_type'              => 'product',
            's'                      => html_entity_decode( $quick_search_parameter, ENT_QUOTES, 'UTF-8' ),
            'post_status'            => 'publish',
            'orderby'                => 'title',
            'order'                  => 'ASC',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        );
        $size_chart_search_wp_query = new WP_Query( $size_chart_search_args );
        if ( $size_chart_search_wp_query->have_posts() ) {
            foreach ( $size_chart_search_wp_query->posts as $size_chart_search_product ) {
                $response_array['found_products'][$size_chart_search_product->ID] = array(
                    'title' => $size_chart_search_product->post_title,
                    'id'    => $size_chart_search_product->ID,
                );
            }
        }
        
        if ( 0 !== count( $response_array['found_products'] ) ) {
            $response_array['success'] = true;
            $response_array['msg'] = __( 'Response successfully.', 'size-chart-for-woocommerce' );
        } else {
            $response_array['msg'] = __( 'No matching products found.', 'size-chart-for-woocommerce' );
        }
        
        wp_send_json( $response_array );
        wp_die();
    }
    
    /**
     * Duplicate size chart.
     *
     * @param int $size_chart_id existing size chart id.
     *
     * @return int|WP_Error duplicated size chart id or get error.
     */
    public function size_chart_duplicate( $size_chart_id )
    {
        $size_chart_title = get_the_title( $size_chart_id );
        $size_chart_content = size_chart_get_the_content( $size_chart_id );
        $new_size_chart_id = 0;
        
        if ( $this->get_plugin_post_type_name() === get_post_type( $size_chart_id ) ) {
            // Get number for current clone id.
            $clone_count = get_post_meta( $size_chart_id, 'clone-cnt', true );
            
            if ( isset( $clone_count ) && '' !== $clone_count ) {
                $cnt = $clone_count + 1;
            } else {
                $cnt = 0;
            }
            
            update_post_meta( $size_chart_id, 'clone-cnt', $cnt );
            $count_clone = get_post_meta( $size_chart_id, 'clone-cnt', true );
            $current_user = wp_get_current_user();
            $clone_post_author = $current_user->ID;
            $count = ( isset( $count_clone ) && $count_clone !== 0 ? '(' . $count_clone . ')' : '' );
            $size_chart_new_post = array(
                'post_title'   => $size_chart_title . ' - Copy' . $count,
                'post_status'  => 'draft',
                'post_type'    => $this->get_plugin_post_type_name(),
                'post_content' => $size_chart_content,
                'post_author'  => $clone_post_author,
            );
            $new_size_chart_id = wp_insert_post( $size_chart_new_post );
            // Copy post metadata.
            $default_size_chart_meta_data = get_post_meta( $size_chart_id );
            foreach ( $default_size_chart_meta_data as $key => $values ) {
                if ( 'clone-cnt' === $key ) {
                    continue;
                }
                foreach ( $values as $value ) {
                    if ( 'default' === $value ) {
                        continue;
                    }
                    add_post_meta( $new_size_chart_id, $key, $value );
                }
            }
        }
        
        return $new_size_chart_id;
    }
    
    /**
     * Default Chart Content HTML.
     *
     * @param string $template selected template name.
     *
     * @return false|string HTML of the size chart.
     */
    public function size_chart_cloth_template_html_content( $template )
    {
        ob_start();
        ?>
        <div class="chart-container">
            <div class="chart-content">
                <div class="chart-content-list">
					<?php 
        
        if ( 'tshirt-shirt' === $template ) {
            ?>
                        <p><?php 
            esc_html_e( 'To choose the correct size for you, measure your body as follows:', 'size-chart-for-woocommerce' );
            ?></p>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part, place the tape close under the arms and make sure the tape is flat across the back.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'womens-tshirt' === $template ) {
            ?>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure under your arms, around the fullest part of the your chest.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Waist : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around your natural waistline, keeping the tape a bit loose.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'mens-shirts' === $template ) {
            ?>
                        <p><?php 
            esc_html_e( 'To choose the correct size for you, measure your body as follows:', 'size-chart-for-woocommerce' );
            ?></p>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part, place the tape close under the arms and make sure the tape is flat across the back.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'womens-dress' === $template ) {
            ?>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure under your arms, around the fullest part of the your chest.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Waist : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around your natural waistline, keeping the tape a bit loose.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Hips : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part of your body at the top of your leg.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'jeans-trouser' === $template ) {
            ?>
                        <p><?php 
            esc_html_e( 'To choose the correct size for you, measure your body as follows:', 'size-chart-for-woocommerce' );
            ?></p>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Waist : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around natural waistline.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Inside leg : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure from top of inside leg at crotch to ankle bone.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'womens-jeans' === $template ) {
            ?>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Waist : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around your natural waistline,keeping the tape bit loose.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Hips : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part of your body at the top of your leg.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Inseam : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Wearing pants that fit well, measure from the crotch seam to the bottom of the leg.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'mens-waistcoats' === $template ) {
            ?>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part, place the tape close under the arms and make sure the tape is flat across the back.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        } elseif ( 'women-cloth' === $template ) {
            ?>
                        <ul>
                            <li>
                                <strong><?php 
            esc_html_e( 'Chest : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the fullest part of the bust, keeping the tape parallel to the floor.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Waist : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure around the narrowest point, keeping the tape parallel to the floor.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Hip : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Stand with feet together and measure around the fullest point of the hip, keep the tape parallel to the floor.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                            <li>
                                <strong><?php 
            esc_html_e( 'Inseam : ', 'size-chart-for-woocommerce' );
            ?></strong>
								<?php 
            esc_html_e( 'Measure inside length of leg from your crotch to the bottom of ankle.', 'size-chart-for-woocommerce' );
            ?>
                            </li>
                        </ul>
					<?php 
        }
        
        ?>
                </div>
                <div class="chart-content-image">
					<?php 
        
        if ( 'tshirt-shirt' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/mens-tshirts-and-polo-shirts.jpg', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'tshirt-shirt-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'womens-tshirt' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/women-t-shirt-top.png', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'womens-tshirt', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'mens-shirts' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/mens-shirts.jpg', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'mens-shirts', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'womens-dress' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/women-dress-size-chart.png', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'womens-dress-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'jeans-trouser' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/mens-jeans-and-trousers.jpg', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'jeans-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'womens-jeans' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/women-jeans-size-chart.png', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'womens-jeans-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'mens-waistcoats' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/mens-waistcoats.jpg', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'mens-waistcoats', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'women-cloth' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/cloth_size_chart.png', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'cloth-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'men-shoes' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/mens-shoes-size-chart.png', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'mens-shoe-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        } elseif ( 'women-shoes' === $template ) {
            ?>
                        <img class="alignnone size-medium alignright" src="<?php 
            echo  esc_url( plugins_url( 'admin/images/default-chart/women-shoes-size-image.jpg', dirname( __FILE__ ) ) ) ;
            ?>" alt="<?php 
            esc_attr_e( 'womens-shoe-chart', 'size-chart-for-woocommerce' );
            ?>" width="300" height="300"/>
					<?php 
        }
        
        ?>
                </div>
            </div>
        </div>

		<?php 
        return ob_get_clean();
    }
    
    /**
     * Default size chart add post meta.
     *
     * @param int $post_id Size chart id.
     * @param string $template selected template name.
     */
    public function size_chart_add_post_meta( $post_id, $template )
    {
        
        if ( 'tshirt-shirt' === $template ) {
            $label = "T-Shirts & Polo Shirts";
            $chart_table = stripcslashes( '[["TO FIT CHEST SIZE","INCHES","CM"],["XXXS","30-32","76-81"],["XXS","32-34","81-86"],["XS","34-36","86-91"],["S","36-38","91-96"],["M","38-40","96-101"],["L","40-42","101-106"],["XL","42-44","106-111"],["XXL","44-46","111-116"],["XXXL","46-48","116-121"]]' );
        } elseif ( 'womens-tshirt' === $template ) {
            $label = "Women's Sizes";
            $chart_table = stripcslashes( '[["UK SIZE","BUST","BUST","WAIST","WAIST","HIPS","HIPS"],["","INCHES","CM","INCHES","CM","INCHES","CM"],["4","31","78","24","60","33","83.5"],["6","32","80.5","25","62.5","34","86"],["8","33","83","26","65","35","88.5"],["10","35","88","28","70","37","93.5"],["12","37","93","30","75","39","98.5"],["14","39","98","31","80","41","103.5"],["16","41","103","33","85","43","108.5"],["18","44","110.5","36","92.5","46","116"]]' );
        } elseif ( 'mens-shirts' === $template ) {
            $label = "Men's Shirts";
            $chart_table = stripcslashes( '[["TO FIT CHEST SIZE","INCHES","CM","TO FIT NECK SIZE","INCHES","CM"],["XXXS","30-32","76-81","XXXS","14","36"],["XXS","32-34","81-86","XXS","14.5","37.5"],["XS","34-36","86-91","XS","15","38.5"],["S","36-38","91-96","S","15.5","39.5"],["M","38-40","96-101","M","16","41.5"],["L","40-42","101-106","L","17","43.5"],["XL","42-44","106-111","XL","17.5","45.5"],["XXL","44-46","111-116","XXL","18.5","47.5"],["XXXL","46-48","116-121","XXXL","19.5","49.5"]]' );
        } elseif ( 'womens-dress' === $template ) {
            $label = "Women's Dress Sizes";
            $chart_table = stripcslashes( '[["SIZE","NUMERIC SIZE","BUST","WAIST","HIP"],["XXXS","000","30","23","33"],["XXS","00","31.5","24","34"],["XS","0","32.5","25","35"],["XS","2","33.5","26","36"],["S","4","34.5","27","37"],["S","6","35.5","28","38"],["M","8","36.5","29","39"],["M","10","37.5","30","40"],["L","12","39","31.5","41.5"],["L","14","40.5","33","43"],["XL","16","42","34.5","44.5"],["XL","18","44","36","46.5"],["XXL","20","46","37.5","48.5"]]' );
        } elseif ( 'jeans-trouser' === $template ) {
            $label = "Men's Jeans & Trousers Size";
            $chart_table = stripcslashes( '[["TO FIT WAIST SIZE","INCHES","CM"],["26","26","66"],["28","28","71"],["29","29","73.5"],["30","30","76"],["31","31","78.5"],["32","32","81"],["33","33","83.5"],["34","34","86"],["36","36","91"],["38","38","96"],["40","40","101"],["","",""],["TO FIT INSIDE LEG LENGTH","INCHES","CM"],["Short","30","76"],["Regular","32","81"],["Long","34","86"]]' );
        } elseif ( 'womens-jeans' === $template ) {
            $label = "Women's Jeans Sizes";
            $chart_table = stripcslashes( '[["Size","Waist","Hip"],["24","24","35"],["25","25","36"],["26","26","37"],["27","27","38"],["28","28","39"],["29","29","40"],["30","30","41"],["31","31","42"],["32","32","43"],["33","33","44"],["34","34","45"]]' );
        } elseif ( 'mens-waistcoats' === $template ) {
            $label = "Men's Waistcoats";
            $chart_table = stripcslashes( '[["CHEST MEASUREMENT","INCHES","CM"],["32","32","81"],["34","34","86"],["36","36","91"],["38","38","96"],["40","40","101"],["42","42","106"],["44","44","111"],["46","46","116"]]' );
        } elseif ( 'women-cloth' === $template ) {
            $label = "Women's Sizes";
            $chart_table = stripcslashes( '[["UK SIZE","BUST","BUST","WAIST","WAIST","HIPS","HIPS"],["","INCHES","CM","INCHES","CM","INCHES","CM"],["4","31","78","24","60","33","83.5"],["6","32","80.5","25","62.5","34","86"],["8","33","83","26","65","35","88.5"],["10","35","88","28","70","37","93.5"],["12","37","93","30","75","39","98.5"],["14","39","98","31","80","41","103.5"],["16","41","103","33","85","43","108.5"],["18","44","110.5","36","92.5","46","116"]]' );
        } elseif ( 'men-shoes' === $template ) {
            $label = "Men's Shoes Size";
            $chart_table = stripcslashes( '[["US","Euro","UK","Inches","CM"],["6","39","5.5","9.25","23.5"],["6.5","39","6","9.5","24.1"],["7","40","6.5","9.625","24.4"],["7.5","40-41","7","9.75","24.8"],["8","41","7.5","9.9375","25.4"],["8.5","41-42","8","10.125","25.7"],["9","42","8.5","10.25","26"],["9.5","42-43","9","10.4375","26.7"],["10","43","9.5","10.5625","27"],["10.5","43-44","10","10.75","27.3"],["11","44","10.5","10.9375","27.9"],["11.5","44-45","11","11.125","28.3"],["12","45","11.5","11.25","28.6"],["13","46","12.5","11.5625","29.4"],["14","47","13.5","11.875","30.2"],["15","48","14.5","12.1875","31"],["16","49","15.5","12.5","31.8"]]' );
        } elseif ( 'women-shoes' === $template ) {
            $label = "Women's Sizes";
            $chart_table = stripcslashes( '[["US","Euro","UK","Inches","CM"],["4","35","2","8.1875","20.8"],["4.5","35","2.5","8.375","21.3"],["5","35-36","3","8.5","21.6"],["5.5","36","3.5","8.75","22.2"],["6","36-37","4","8.875","22.5"],["6.5","37","4.5","9.0625","23"],["7","37-38","5","9.25","23.5"],["7.5","38","5.5","9.375","23.8"],["8","38-39","6","9.5","24.1"],["8.5","39","6.5","9.6875","24.6"],["9","39-40","7","9.875","25.1"],["9.5","40","7.5","10","25.4"],["10","40-41","8","10.1875","25.9"],["10.5","41","8.5","10.3125","26.2"],["11","41-42","9","10.5","26.7"],["11.5","42","9.5","10.6875","27.1"],["12","42-43","10","10.875","27.6"]]' );
        }
        
        update_post_meta( $post_id, 'label', $label );
        update_post_meta( $post_id, 'position', 'popup' );
        update_post_meta( $post_id, 'post_status', 'default' );
        update_post_meta( $post_id, 'chart-table', $chart_table );
    }
    
    /**
     * Create a menu for plugin.
     *
     * @param null $current_post_type current post type name.
     * @param null $current_page page name.
     */
    public function size_chart_menus( $current_post_type = null, $current_page = null )
    {
        global  $pagenow, $typenow ;
        $size_chart_menus = array(
            'size-chart-list'        => array(
            'menu_title' => __( 'Size Charts', 'size-chart-for-woocommerce' ),
            'menu_slug'  => 'size-chart-list',
            'menu_url'   => esc_url( $this->get_size_chart_menu_url( 'edit.php', array(
            'post_type' => 'size-chart',
        ) ) ),
            'class'      => $this->get_menu_active_class( 'size-chart-edit.php-', array( $current_post_type, $pagenow, $current_page ) ),
        ),
            'size-chart-add-new'     => array(
            'menu_title' => __( 'Add New', 'size-chart-for-woocommerce' ),
            'menu_slug'  => 'size-chart-add-new',
            'menu_url'   => esc_url( $this->get_size_chart_menu_url( 'post-new.php', array(
            'post_type' => 'size-chart',
        ) ) ),
            'class'      => $this->get_menu_active_class( 'size-chart-post-new.php', array( $current_post_type, $pagenow ) ),
        ),
            'size-chart-setting'     => array(
            'menu_title' => __( 'Settings', 'size-chart-for-woocommerce' ),
            'menu_slug'  => 'size-chart-add-new',
            'menu_url'   => esc_url( $this->get_size_chart_menu_url( 'edit.php', array(
            'post_type' => 'size-chart',
            'page'      => 'size-chart-setting-page',
        ) ) ),
            'class'      => $this->get_menu_active_class( 'size-chart-size-chart-setting-page', array( $current_post_type, $current_page ) ),
        ),
            'size-chart-get-started' => array(
            'menu_title' => __( 'About', 'size-chart-for-woocommerce' ),
            'menu_slug'  => 'size-chart-get-started',
            'menu_url'   => esc_url( $this->get_size_chart_menu_url( 'admin.php', array(
            'page' => 'size-chart-get-started',
        ) ) ),
            'class'      => ( 'size-chart-get-started' === $current_page ? $this->get_menu_active_class( 'size-chart-get-started', $current_page ) : $this->get_menu_active_class( 'size-chart-information', $current_page ) ),
            'sub_menu'   => array(
            'size-chart-get-started' => array(
            'menu_title' => __( 'Getting Started', 'size-chart-for-woocommerce' ),
            'menu_slug'  => 'size-chart-get-started',
            'menu_url'   => esc_url( $this->get_size_chart_menu_url( 'admin.php', array(
            'page' => 'size-chart-get-started',
        ) ) ),
            'class'      => $this->get_menu_active_class( 'size-chart-get-started', $current_page ),
        ),
            'size-chart-information' => array(
            'menu_title' => __( 'Quick info', 'size-chart-for-woocommerce' ),
            'menu_slug'  => 'size-chart-information',
            'menu_url'   => esc_url( $this->get_size_chart_menu_url( 'admin.php', array(
            'page' => 'size-chart-information',
        ) ) ),
            'class'      => $this->get_menu_active_class( 'size-chart-information', $current_page ),
        ),
        ),
        ),
            'dotstore'               => array(
            'menu_title' => __( 'Dotstore', 'size-chart-for-woocommerce' ),
            'menu_slug'  => 'dotstore',
            'menu_url'   => 'javascript:void(0)',
            'sub_menu'   => array(
            'woocommerce-plugins' => array(
            'menu_title' => __( 'WooCommerce Plugins', 'size-chart-for-woocommerce' ),
            'menu_slug'  => 'woocommerce-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/go/flatrate-pro-new-interface-woo-plugins' ),
        ),
            'wordpress-plugins'   => array(
            'menu_title' => __( 'Wordpress Plugins', 'size-chart-for-woocommerce' ),
            'menu_slug'  => 'wordpress-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/go/flatrate-pro-new-interface-wp-plugins' ),
        ),
            'contact-support'     => array(
            'menu_title' => __( 'Contact Support', 'size-chart-for-woocommerce' ),
            'menu_slug'  => 'contact-support',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/support/' ),
        ),
        ),
        ),
        );
        if ( 'size-chart-post.php' === $typenow . '-' . $pagenow ) {
            $size_chart_menus['size-chart-add-new'] = array(
                'menu_title' => __( 'Edit', 'size-chart-for-woocommerce' ),
                'menu_slug'  => 'size-chart-edit',
                'menu_url'   => esc_url( $this->get_size_chart_menu_url( 'post.php', array(
                'post'   => get_the_ID(),
                'action' => 'edit',
            ) ) ),
                'class'      => $this->get_menu_active_class( 'size-chart-post.php', array( $typenow, $pagenow ) ),
            );
        }
        ?>
        <div class="dots-menu-main">
            <nav>
                <ul>
					<?php 
        foreach ( $size_chart_menus as $size_chart_menu ) {
            ?>
                        <li>
                            <a class="dotstore_plugin <?php 
            if ( isset( $size_chart_menu['class'] ) && !empty($size_chart_menu['class']) ) {
                echo  esc_attr( $size_chart_menu['class'] ) ;
            }
            ?>" href="<?php 
            echo  esc_url( $size_chart_menu['menu_url'] ) ;
            ?>">
								<?php 
            esc_html_e( $size_chart_menu['menu_title'], 'size-chart-for-woocommerce' );
            ?>
                            </a>
							<?php 
            
            if ( isset( $size_chart_menu['sub_menu'] ) && !empty($size_chart_menu['sub_menu']) ) {
                ?>
                                <ul class="sub-menu">
									<?php 
                foreach ( $size_chart_menu['sub_menu'] as $size_chart_sub_menu ) {
                    ?>
                                        <li>
                                            <a class="dotstore_plugin <?php 
                    if ( isset( $size_chart_menu['class'] ) && !empty($size_chart_menu['class']) ) {
                        echo  esc_attr( $size_chart_sub_menu['class'] ) ;
                    }
                    ?>" href="<?php 
                    echo  esc_url( $size_chart_sub_menu['menu_url'] ) ;
                    ?>">
												<?php 
                    esc_html_e( $size_chart_sub_menu['menu_title'], 'size-chart-for-woocommerce' );
                    ?>
                                            </a>
                                        </li>
									<?php 
                }
                ?>
                                </ul>
							<?php 
            }
            
            ?>
                        </li>
					<?php 
        }
        ?>
                </ul>
            </nav>
        </div>
		<?php 
    }
    
    /**
     * Get the size chart menu url.
     *
     * @param string $url_handler url slug | name.
     * @param array $args_parameter url parameter array.
     *
     * @return string Query parameter url.
     */
    public function get_size_chart_menu_url( $url_handler, $args_parameter )
    {
        return add_query_arg( $args_parameter, admin_url( $url_handler ) );
    }
    
    /**
     * Get the menu active class.
     *
     * @param string $current current menu
     * @param string|array $action get actions.
     *
     * @return string class name.
     */
    public function get_menu_active_class( $current, $action )
    {
        
        if ( is_array( $action ) && array_filter( $action ) ) {
            $action = implode( '-', $action );
            if ( $current === $action ) {
                return 'active';
            }
        } else {
            if ( $current === $action ) {
                return 'active';
            }
        }
        
        return '';
    }

}