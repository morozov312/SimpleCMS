<?php 
/*
Plugin Name: Saphali Woocommerce Russian
Plugin URI: http://saphali.com/saphali-woocommerce-plugin-wordpress
Description: Saphali Woocommerce Russian - это бесплатный вордпресс плагин, который добавляет набор дополнений к интернет-магазину на Woocommerce.
Version: 1.8.10
Author: Saphali
Author URI: http://saphali.com/
Text Domain: saphali-woocommerce-lite
Domain Path: /languages
WC requires at least: 1.6.6
WC tested up to: 4.6
*/


/*

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software

 */

/* Add a custom payment class to woocommerce
  ------------------------------------------------------------ */
  define('SAPHALI_LITE_SYMBOL', 1 );
  
  // Подключение валюты и локализации
 define('SAPHALI_PLUGIN_DIR_URL',plugin_dir_url(__FILE__));
 define('SAPHALI_LITE_VERSION', '1.8.10' );
 define('SAPHALI_PLUGIN_DIR_PATH',plugin_dir_path(__FILE__));
 class saphali_lite {
 var $email_order_id;
 var $fieldss;
 var $unuque = array();
 var $locale_tmp;
 var $column_count_saphali;
	function __construct() {
		if ( version_compare( WOOCOMMERCE_VERSION, '2.2.0', '<' ) || version_compare( WOOCOMMERCE_VERSION, '2.5.0', '>' ) )
		add_action('before_woocommerce_init', array($this,'load_plugin_textdomain'), 9);
	else
		add_action('before_woocommerce_init', array($this,'load_plugin_textdomain_th'), 9);
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) )  add_action('admin_menu', array($this,'woocommerce_saphali_admin_menu_s_l'), 9);
		else add_action('admin_menu', array($this,'woocommerce_saphali_admin_menu_s_l'), 10);
		
		add_action( 'woocommerce_thankyou',                     array( $this, 'order_pickup_location' ), 20 );
		add_action( 'woocommerce_view_order',                   array( $this, 'order_pickup_location' ), 20 );
		
		add_action( 'woocommerce_after_template_part',          array( $this, 'email_pickup_location' ), 10, 4 );
		
		// add_action( 'woocommerce_admin_order_totals_after_shipping', array( $this, 'woocommerce_admin_order_totals_after_shipping' ), 1 );
		add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_pending_to_completed_notification',  array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_pending_to_on-hold_notification',    array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_failed_to_processing_notification',  array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_failed_to_completed_notification',   array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_completed_notification',             array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_new_customer_note_notification',                  array( $this, 'store_order_id' ), 1 );
		add_action( 'wp_head', array( $this, 'generator' ) );
		add_filter( 'woocommerce_order_formatted_billing_address',  array($this,'formatted_billing_address') , 10 , 2); 
		add_filter( 'woocommerce_order_formatted_shipping_address',  array($this,'formatted_shipping_address') , 10 , 2); 
		
		if( !( isset($_GET['page']) && $_GET['page'] == 'woocommerce_saphali_s_l' ) ) {
			// Hook in
			add_filter( 'woocommerce_checkout_fields' , array($this,'saphali_custom_override_checkout_fields') );
			add_filter( 'wp' , array($this,'wp') );

			add_filter( 'woocommerce_billing_fields',  array($this,'saphali_custom_billing_fields'), 10, 1 );
			add_filter( 'woocommerce_shipping_fields',  array($this,'saphali_custom_shipping_fields'), 10, 1 );
			add_filter( 'woocommerce_default_address_fields',  array($this,'woocommerce_default_address_fields'), 10, 1 );
			//add_filter( 'woocommerce_get_country_locale',  array($this,'woocommerce_get_country_locale'), 10, 1 );
			add_action('admin_init', array($this,'woocommerce_customer_meta_fields_action'), 20);
			add_action( 'personal_options_update', array($this,'woocommerce_save_customer_meta_fields_saphali') );
			add_action( 'edit_user_profile_update', array($this,'woocommerce_save_customer_meta_fields_saphali') );
			/* add_action( 'woocommerce_admin_order_data_after_billing_address', array($this,'woocommerce_admin_order_data_after_billing_address_s') );
			add_action( 'woocommerce_admin_order_data_after_shipping_address', array($this,'woocommerce_admin_order_data_after_shipping_address_s') ); */
			add_action( 'woocommerce_admin_order_data_after_order_details', array($this,'woocommerce_admin_order_data_after_order_details_s') );
		
		}
		add_filter( 'woocommerce_currencies',  array($this,'add_inr_currency') , 11);
		add_filter( 'woocommerce_currency_symbol',  array($this,'add_inr_currency_symbol') , 1, 2 ); 
		add_action( 'woocommerce_checkout_update_order_meta',   array( $this, 'checkout_update_order_meta' ), 99, 2 );
		$this->column_count_saphali = get_option('column_count_saphali');
		if(!empty($this->column_count_saphali)) {
			global $woocommerce_loop;
			$woocommerce_loop['columns'] = $this->column_count_saphali; 
			add_action("wp_head", array($this,'print_script_columns'), 10, 1);
			add_filter("loop_shop_columns", array($this, 'print_columns'), 10, 1);
			add_filter("woocommerce_output_related_products_args", array($this, 'related_print_columns'), 10, 1);
		}
		if(is_admin()) {
			add_filter( 'woocommerce_admin_billing_fields', array($this,'woocommerce_admin_billing_fields'), 10, 1 );
			add_filter( 'woocommerce_admin_shipping_fields', array($this,'woocommerce_admin_shipping_fields'), 10, 1 );
		}
		add_filter( 'woocommerce_checkout_posted_data', array($this,'woocommerce_checkout_posted_data'), 10, 1 );
		add_action("wp_footer", array($this,'print_script_payment_method') );
		if( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '<' ) )
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'after_checkout_validation' ), 10 );
		else
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'after_checkout_validation' ), 10, 2 );
	}
	
	public function remove_no_valid_filds($key, $value, $errors) {
		if (  version_compare( WOOCOMMERCE_VERSION, '3.0.0', '<' ) ) {
			$is_e = true;
			if( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
				global $woocommerce;
				if(!empty($woocommerce->errors)) {
					foreach($woocommerce->errors as $i => $_e) {
						if( strpos($_e, strtolower($value["rf"]) ) !== false || strpos($_e, $value["rf"]) !== false ) {
							unset($woocommerce->errors[$i]);
						} 
					}
				}
			} else {
				$s = WC()->session;
				$notices = $s->get( 'wc_notices', array() );
				if( isset( $notices['error'] ) ) {
					foreach($notices['error'] as $i => $_e) {
						if( strpos($_e, strtolower($value["rf"]) ) !== false || strpos($_e, $value["rf"]) !== false ) {
							unset($notices['error'][$i]);
						} 
					}
				}
				
				if(empty($notices['error'])) {
					unset($notices['error']);
				}
				$s->set( 'wc_notices', $notices );
			}
			
		} else {
			if( is_wp_error($errors) ) {
				$is_e = true;
				if( isset( $errors->errors["required-field"] ) ) {
					foreach($errors->errors["required-field"] as $i => $_e) {
						if( strpos($_e, strtolower(__($value["rf"], 'woocommerce')) ) !== false || strpos($_e, __($value["rf"], 'woocommerce')) !== false ) {
							unset($errors->errors["required-field"][$i]);
						} 
					}
					
				}
			}
		}
		return $is_e;
	}
public function woocommerce_checkout_posted_data( $data ) {	
		if(! isset($this->fieldss) )
			$this->fieldss = get_option('woocommerce_saphali_filds_filters');
		$fieldss = $this->fieldss;
		$keys = array();
		if( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) && isset($_POST['ship_to_different_address']) || version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) && !isset($_POST['shiptobilling']) ) {
			$fieldss____ = array('billing', 'shipping');
		} else $fieldss____ = array('billing');
		foreach($fieldss____ as  $type) {
			foreach($fieldss[$type] as $key => $value) {
				if(isset($value['payment_method'])) {
					$pm_k_remove = array();
					foreach($value['payment_method'] as $k => $v) {
						if($v === '0') {
							$pm_k_remove[] = $k;
						}
					}
					foreach($pm_k_remove as $k_remove) {
						unset($value['payment_method'][$k_remove]);
					}
				}
				if(isset($value['payment_method']) && !empty($value['payment_method'])) {
					$r = ( isset($value["required"]) && $value["required"] );
					$keys[ $key ] = array( 'pm' => $value['payment_method'], 'r' => $r, 'rf' => $value["label"], 'type' => $type );
				}
				if(isset($value['shipping_method'])) {
					$pm_k_remove = array();
					foreach($value['shipping_method'] as $k => $v) {
						if($v === '0') {
							$pm_k_remove[] = $k;
						}
					}
					foreach($pm_k_remove as $k_remove) {
						unset($value['shipping_method'][$k_remove]);
					}
				}
				if(isset($value['shipping_method']) && !empty($value['shipping_method'])) {
					$r = ( isset($value["required"]) && $value["required"] );
					$keys[ $key ] = array( 'pm' => $value['shipping_method'], 'r' => $r, 'rf' => $value["label"], 'type' => $type );
				}
			}
		}

  		foreach($keys as $key => $value) {
        		$s_m= in_array($_POST['shipping_method'], $value["pm"]) || in_array($_POST['shipping_method'][0], $value["pm"]) || in_array( preg_replace('/\:(.*)$/', '', $_POST['shipping_method'][0]), $value["pm"]);
			if( !( in_array($_POST['payment_method'], $value["pm"]) || $s_m ) ) {
				unset( $data[$key] );
			}
  		}
		return $data;
	}
	public function after_checkout_validation( $data, $errors = array() ) {	
		if(! isset($this->fieldss) )
			$this->fieldss = get_option('woocommerce_saphali_filds_filters');
		$fieldss = $this->fieldss;
		$keys = array();
		if( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) && isset($_POST['ship_to_different_address']) || version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) && !isset($_POST['shiptobilling']) ) {
			$fieldss____ = array('billing', 'shipping');
		} else $fieldss____ = array('billing');
		foreach($fieldss____ as  $type) {
			foreach($fieldss[$type] as $key => $value) {
				if(isset($value['payment_method'])) {
					$pm_k_remove = array();
					foreach($value['payment_method'] as $k => $v) {
						if($v === '0') {
							$pm_k_remove[] = $k;
						}
					}
					foreach($pm_k_remove as $k_remove) {
						unset($value['payment_method'][$k_remove]);
					}
				}
				if(isset($value['payment_method']) && !empty($value['payment_method'])) {
					$r = ( isset($value["required"]) && $value["required"] );
					$keys[ $key ] = array( 'pm' => $value['payment_method'], 'r' => $r, 'rf' => $value["label"], 'type' => $type );
				}
				if(isset($value['shipping_method'])) {
					$pm_k_remove = array();
					foreach($value['shipping_method'] as $k => $v) {
						if($v === '0') {
							$pm_k_remove[] = $k;
						}
					}
					foreach($pm_k_remove as $k_remove) {
						unset($value['shipping_method'][$k_remove]);
					}
				}
				if(isset($value['shipping_method']) && !empty($value['shipping_method'])) {
					$r = ( isset($value["required"]) && $value["required"] );
					$keys[ $key ] = array( 'pm' => $value['shipping_method'], 'r' => $r, 'rf' => $value["label"], 'type' => $type );
				}
			}
		}
		$is_e = false;
		foreach($keys as $key => $value) {
			$s_m = in_array($_POST['shipping_method'], $value["pm"]) || in_array($_POST['shipping_method'][0], $value["pm"]) || in_array( preg_replace('/\:(.*)$/', '', $_POST['shipping_method'][0]), $value["pm"]);
			if( !( in_array($_POST['payment_method'], $value["pm"]) || $s_m ) ) {
				unset( $_POST[$key] );
			}
			if( $value["r"] ) {
				if(in_array($_POST['payment_method'], $value["pm"]) ) {
					if( empty($_POST[$key])) {
						$is_e = $this->remove_no_valid_filds($key, $value, $errors);
						if( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '<' ) ) {
							if( !version_compare( WOOCOMMERCE_VERSION, '2.6.0', '<' ) ) 
							$this->comp_woocomerce_mess_error( sprintf( _x( '%s is a required field.', 'FIELDNAME is a required field.', 'woocommerce' ), '<strong>' . $value["rf"] . '</strong>' ) );
							else 
							$this->comp_woocomerce_mess_error( '<strong>' . $value["rf"] . '</strong> ' . __( 'is a required field.', 'woocommerce' ) );
						} else {
							switch ($value["type"]) {
								case 'shipping' :
									/* translators: %s: field name */
									$field_label = __( 'Shipping %s', 'woocommerce' );
								break;
								case 'billing' :
									/* translators: %s: field name */
									$field_label = __( 'Billing %s', 'woocommerce' );
								break;
							}
							$fl = function_exists('mb_strtolower') ? mb_strtolower(  sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . $value["rf"] . '</strong>' ) ) :  sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . $value["rf"] . '</strong>' );
							$this->comp_woocomerce_mess_error( sprintf( $field_label, $fl ) );
						}
					}
				} else {
					if( empty($_POST[$key])) {
						$is_e = $this->remove_no_valid_filds($key, $value, $errors);
					}
				}
				
				if( $s_m ) {
					if( empty($_POST[$key])) {
						$is_e = $this->remove_no_valid_filds($key, $value, $errors);
						if( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '<' ) ) {
							if( !version_compare( WOOCOMMERCE_VERSION, '2.6.0', '<' ) ) 
							$this->comp_woocomerce_mess_error( sprintf( _x( '%s is a required field.', 'FIELDNAME is a required field.', 'woocommerce' ), '<strong>' . $value["rf"] . '</strong>' ) );
							else 
							$this->comp_woocomerce_mess_error( '<strong>' . $value["rf"] . '</strong> ' . __( 'is a required field.', 'woocommerce' ) );
						} else {
							switch ($value["type"]) {
								case 'shipping' :
									/* translators: %s: field name */
									$field_label = __( 'Shipping %s', 'woocommerce' );
								break;
								case 'billing' :
									/* translators: %s: field name */
									$field_label = __( 'Billing %s', 'woocommerce' );
								break;
							}
							$fl = function_exists('mb_strtolower') ? mb_strtolower(  sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . $value["rf"] . '</strong>' ) ) :  sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . $value["rf"] . '</strong>' );
							$this->comp_woocomerce_mess_error( sprintf( $field_label, $fl ) );
						}
					}
				} else {
					if( empty($_POST[$key])) {
						$is_e = $this->remove_no_valid_filds($key, $value, $errors);
					}
				}
			}
		}
		if($is_e &&  !version_compare( WOOCOMMERCE_VERSION, '3.0.0', '<' ) ) {
			if(empty( $errors->errors["required-field"] ) )
				$errors->remove( 'required-field' );
		}
	}
	function comp_woocomerce_mess_error ($m) {
		if( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
			global $woocommerce;
			$woocommerce->add_error( $m );
		} else {
			wc_add_notice( $m, 'error' );
		}
	}
	function print_script_payment_method() {
		if( !(is_cart() || is_checkout()) ) return;
		if(! isset($this->fieldss) )
			$this->fieldss = get_option('woocommerce_saphali_filds_filters');
		$fieldss = $this->fieldss;
		
		foreach(array('billing', 'shipping') as  $type) {
			if(isset($fieldss[$type]) && is_array($fieldss[$type])) {
				foreach($fieldss[$type] as $key => $value) {
					if(isset($value['payment_method'])) {
						$pm_k_remove = array();
						if(is_array($value['payment_method']))
						foreach($value['payment_method'] as $k => $v) {
							if($v === '0') {
								$pm_k_remove[] = $k;
							}
						}
						foreach($pm_k_remove as $k_remove) {
							unset($value['payment_method'][$k_remove]);
						}
					}
					if(isset($value['payment_method']) && !empty($value['payment_method'])) {
						$keys[ $key ] = $value['payment_method'];
					}
					if(isset($value['shipping_method'])) {
						$pm_k_remove = array();
						if(is_array($value['shipping_method']))
						foreach($value['shipping_method'] as $k => $v) {
							if($v === '0') {
								$pm_k_remove[] = $k;
							}
						}
						foreach($pm_k_remove as $k_remove) {
							unset($value['shipping_method'][$k_remove]);
						}
					}
					if(isset($value['shipping_method']) && !empty($value['shipping_method'])) {
						$skeys[ $key ] = $value['shipping_method'];
					}
				}
			}
		}
		?>
		<script>
		var $keys = <?php if( isset($keys) ) echo json_encode($keys); else echo '[]'; ?>;
		var $skeys = <?php if( isset($skeys) ) echo json_encode($skeys); else echo '[]'; ?>;
		function corect_payment_method_filds () {
			var selected_p_method = jQuery("input[name=\"payment_method\"]:checked").val();
			jQuery.each($keys, function(i,e){	
				e = Object.keys(e).map(function(v){ return e[v]});	
				if( jQuery.inArray( selected_p_method, e ) >= 0 ) {
					if( ! ( jQuery("#billing_platelshik_is_grpl").is(':checked') && ( i == 'billing_gruzopoluch' || i == 'billing_gruzopoluch_okpo') ) )
					jQuery("#" + i + "_field").show('slow');
				} else {
					jQuery("#" + i + "_field").hide('slow');
				}
			});
		}
		function corect_shipping_method_filds () {
			var selected_s_method = typeof jQuery("input.shipping_method:checked, input.shipping_method[type=\"hidden\"], select.shipping_method").val() != 'undefined' ? jQuery("input.shipping_method:checked, input.shipping_method[type=\"hidden\"], select.shipping_method").val().split(":")[0] : '';
			jQuery.each($skeys, function(i,e){	
				e = Object.keys(e).map(function(v){ return e[v]});
				if( jQuery.inArray( selected_s_method, e ) >= 0 || e.filter(function(v){ return selected_s_method.indexOf(v) === 0; }).length > 0 ) {
					jQuery("#" + i + "_field").show('slow');
				} else {
					jQuery("#" + i + "_field").hide('slow');
				}
			});
		}
		jQuery("body").delegate("input[name=\"payment_method\"]", 'click', function(){
			corect_payment_method_filds ();
		});
		jQuery("body").on("payment_method_selected", function(){
			corect_payment_method_filds ();
		});
		jQuery("body").delegate("input.shipping_method", 'click', function(){
			corect_shipping_method_filds ();
		});
		jQuery("body").delegate("select.shipping_method", 'change', function(){
			corect_shipping_method_filds ();
		});
		jQuery('body').bind('updated_checkout', function() {
			corect_payment_method_filds ();
			corect_shipping_method_filds ();
		});
		</script>
		<?php
	}
	function formatted_billing_address($address, $order) {
		$billing_data = $this->woocommerce_get_customer_meta_fields_saphali();
		foreach ( array("billing") as $type )
		{
			if ( isset($billing_data[$type]) && is_array($billing_data[$type]))
			{
				foreach ( $billing_data[$type] as $key => $field ) {
					
					if (isset($field['public']) && $field['public'] ) {
						$id = method_exists($order, 'get_id') ? $order->get_id() : $order->id;
						$address[str_replace($type . '_', '', $key)] = get_post_meta( $id, '_' . $key, true );
						if( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) && !empty($address[str_replace($type . '_', '', $key)]) && ( strpos($key, 'new_fild') !== false) && (isset($this->unuque) && !in_array($key, $this->unuque) ) ) {
							$this->unuque[] = $key;
							echo  '<label><strong>'. $field['label']. ':</strong></label> ' . $address[str_replace($type . '_', '', $key)].'<br />';
						}
					}
				}
			}
		}
		return($address);
	}
	function formatted_shipping_address($address, $order) {
		$billing_data = $this->woocommerce_get_customer_meta_fields_saphali();
		if(is_array($billing_data["order"])) {
			foreach ( $billing_data["order"] as $key => $field ) {
				if (isset($field['show']) && !$field['show'] || $key == 'order_comments') continue;
				$id = method_exists($order, 'get_id') ? $order->get_id() : $order->id;
				$address[ str_replace('order_', '', $key) ] = get_post_meta( $id, '_' . $key, true );
				if( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) && !empty($address[ str_replace('order_', '', $key) ]) && ( strpos($key, 'new_fild') === false) && (isset($this->unuque) && !in_array($key, $this->unuque) ) ) {
					$this->unuque[] = $key;
					echo  '<label><strong>'. $field['label']. ':</strong></label> ' . $address[ str_replace('order_', '', $key) ] . '<br />';
				}
			}
		}
		foreach ( array( "shipping") as $type )
		{
			if ( isset($billing_data[$type]) && is_array($billing_data[$type]))
			{
				foreach ( $billing_data[$type] as $key => $field ) {
					
					if (isset($field['public']) && $field['public'] ) {
						$id = method_exists($order, 'get_id') ? $order->get_id() : $order->id;
						$address[str_replace($type . '_', '', $key)] = get_post_meta( $id, '_' . $key, true );
						if( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) && !empty($address[str_replace($type . '_', '', $key)]) && ( strpos($key, 'new_fild') === false) && (isset($this->unuque) && !in_array($key, $this->unuque) ) ) {
							$this->unuque[] = $key;
							echo  '<label><strong>'. $field['label']. ':</strong></label> ' . $address[str_replace($type . '_', '', $key)].'<br />';						
						}
						
					}
				}
			}
		}
		return($address);
	}
	function woocommerce_admin_billing_fields($billing_fields) {
		if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
			$billing_data = $this->woocommerce_get_customer_meta_fields_saphali();
			if(is_array($billing_data["billing"])) {
				foreach ( $billing_data["billing"] as $key => $field ) {
					$key = str_replace('billing_', '', $key);
					if (isset($field['show']) && !$field['show'] || $key == 'order_comments') continue;
					if( strpos($key, 'new_fild') === false)
					$billing_fields[$key] = array(
						'label' =>  $field['label'],
						'show'	=> false
					);
					else
					$billing_fields[$key] = array(
						'label' =>  $field['label'],
						'show'	=> true
					);
				}
			}
		}
		return $billing_fields;
	}
	function woocommerce_admin_shipping_fields($shipping_fields) {
		if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
			$shipping_data = $this->woocommerce_get_customer_meta_fields_saphali();
			if(is_array($shipping_data["shipping"])) {
				foreach ( $shipping_data["shipping"] as $key => $field ) {
					$key = str_replace('shipping_', '', $key);
					if (isset($field['show']) && !$field['show'] || $key == 'order_comments') continue;
					if( strpos($key, 'new_fild') === false)
					 $shipping_fields[$key] = array(
						'label' =>  $field['label'],
						'show'	=> false
					);
					else
					 $shipping_fields[$key] = array(
						'label' =>  $field['label'],
						'show'	=> true
					);
				}
			}
		}
		return $shipping_fields;
	}
	
	public function wp( ) {
		if(function_exists('wc_edit_address_i18n')){
			global $wp;
			if(isset($wp->query_vars['edit-address']))
			add_filter( 'woocommerce_'.wc_edit_address_i18n( sanitize_key( $wp->query_vars['edit-address'] ), true ) .'_fields',  array($this,'saphali_custom_edit_address_fields'), 10, 1 );
		}
	}
	public function checkout_update_order_meta( $order_id, $posted ) {
		if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
			$billing_data = $this->woocommerce_get_customer_meta_fields_saphali();
			if(is_array($billing_data["order"])) {
				foreach ( $billing_data["order"] as $key => $field ) {
					if (isset($field['show']) && !$field['show'] || $key == 'order_comments') continue;
					if(!empty($_POST[$key]))
						update_post_meta( $order_id, '_' . $key, $_POST[$key] );
				}
			}
			foreach ( array("billing", "shipping") as $type )
			{
				if ( isset($billing_data[$type]) && is_array($billing_data[$type]))
				{
					foreach ( $billing_data[$type] as $key => $field ) {
						
						if (isset($field['public']) && $field['public'] && !empty($_POST[$key])) {
							update_post_meta( $order_id, '_' . $key, $_POST[$key] );
						}
					}
				}
			}
		}
	}
	public function woocommerce_admin_order_totals_after_shipping($id) {
		if( apply_filters( 'woocommerce_currency', get_option('woocommerce_currency') ) == 'RUB' ) {
		?>
	<script type="text/javascript">
	jQuery( function($){
		$('#woocommerce-order-totals').on( 'change', '#_order_tax, #_order_shipping_tax, #_cart_discount, #_order_discount', function() {

			var $this =  $(this);
			var fields = $this.closest('.totals').find('input');
			var total = 0;

			fields.each(function(){
				if ( $(this).val() )
					total = total + parseFloat( $(this).val() );
			});

			var formatted_total = accounting.formatMoney( total, {
				symbol 		: woocommerce_writepanel_params.currency_format_symbol,
				decimal 	: woocommerce_writepanel_params.currency_format_decimal_sep,
				thousand	: woocommerce_writepanel_params.currency_format_thousand_sep,
				precision 	: woocommerce_writepanel_params.currency_format_num_decimals,
				format		: woocommerce_writepanel_params.currency_format
			} );
			$this.closest('.totals_group').find('span.inline_total').html( formatted_total );
			
		} );
		setTimeout(function() {$('span.inline_total').closest('.totals_group').find('input').change();}, 100);
	});
	</script>
		<?php
		}
	}
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'woocommerce',  false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		load_plugin_textdomain( 'saphali-woocommerce-lite',  false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	public function load_plugin_textdomain_th() {
		load_plugin_textdomain( 'saphali-woocommerce-lite',  false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	public function woocommerce_default_address_fields($locale) {
		$fieldss = get_option('woocommerce_saphali_filds_locate');
		if(is_array($fieldss)) {
			foreach($fieldss as $_k => $_v) {
				$_fieldss[$_k] = $_v;
				if(isset($_v['label'])) {
					$_fieldss[$_k]['label'] = __( $_v['label'], 'woocommerce');
				}
				if(isset($_v['order'])) {
					$_fieldss[$_k]["priority"] = $_v['order']+10;
					$_fieldss[$_k]["order"] = $_v['order']+10;
				}
				if(isset($_v['placeholder'])) {
					$_fieldss[$_k]['placeholder'] = __( $_v['placeholder'], 'woocommerce');
				}
			}
			$locale = $_fieldss;			
		}

		return $locale;
	}
	public function woocommerce_get_country_locale($locale) {
		
		return $locale;	
	}
	public function generator() {
		echo "\n\n" . '<!-- Saphali Lite Version -->' . "\n" . '<meta name="generator" content="Saphali Lite ' . esc_attr( SAPHALI_LITE_VERSION ) . '" />' . "\n\n";
	}
	function woocommerce_customer_meta_fields_action() {
		add_action( 'show_user_profile', array($this,'woocommerce_customer_meta_fields_s') );
		add_action( 'edit_user_profile', array($this,'woocommerce_customer_meta_fields_s') );
	}
	function woocommerce_customer_meta_fields_s( $user ) {
		if ( ! current_user_can( 'manage_woocommerce' ) )
			return;

		$show_fields = $this->woocommerce_get_customer_meta_fields_saphali();
		if(!empty($show_fields["billing"])) {
			 $show_field["billing"]['title'] = __('Customer Billing Address', 'woocommerce');
			 $show_field["billing"]['fields'] = $show_fields["billing"];
		}
		if(!empty($show_fields["shipping"])) {
			 $show_field["shipping"]['title'] = __('Customer Shipping Address', 'woocommerce');
			 $show_field["shipping"]['fields'] = $show_fields["shipping"];
		}
		if(is_array($show_field)) {
		$count = 0; echo '<fieldset>';
		foreach( $show_field as $fieldset ) :
		if(!$count) echo '<h2>Дополнительные поля</h2>'; 
		$count++;
			?>
			<h3><?php echo $fieldset['title']; ?></h3>
			<table class="form-table">
				<?php
				foreach( $fieldset['fields'] as $key => $field ) :
					?>
					<tr>
						<th><label for="<?php echo $key; ?>"><?php echo $field['label']; ?></label></th>
						<td>
							<input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo esc_attr( get_user_meta( $user->ID, $key, true ) ); ?>" class="regular-text" /><br/>
							<span class="description"><?php echo $field['description']; ?></span>
						</td>
					</tr>
					<?php
				endforeach;
				?>
			</table>
			<?php
		endforeach; 
		echo '</fieldset>';
		}
	}
	function woocommerce_saphali_admin_menu_s_l() {
		add_submenu_page('woocommerce',  __('Настройки Saphali WC Lite', 'woocommerce'), __('Saphali WC Lite', 'woocommerce') , 'manage_woocommerce', 'woocommerce_saphali_s_l', array($this,'woocommerce_saphali_page_s_l'));
	}
	function add_inr_currency( $currencies ) {
		$currencies['UAH'] = __( 'Ukrainian hryvnia', 'saphali-woocommerce-lite' );
		$currencies['RUR'] = __( 'Russian ruble', 'saphali-woocommerce-lite' );
		if( version_compare( WOOCOMMERCE_VERSION, '2.5.2', '<' ) || SAPHALI_LITE_SYMBOL )
		$currencies['RUB'] = __( 'Russian ruble', 'saphali-woocommerce-lite' );
		$currencies['BYN'] = sprintf(__( 'Belarusian ruble%s', 'saphali-woocommerce-lite' ), __(' (new)', 'saphali-woocommerce-lite'));
		$currencies['BYR'] = sprintf(__( 'Belarusian ruble%s', 'saphali-woocommerce-lite' ), '');
		$currencies['AMD'] = __( 'Armenian dram  (Դրամ)', 'saphali-woocommerce-lite' );
		$currencies['KGS'] = __( 'Киргизский сом', 'saphali-woocommerce-lite' );
		$currencies['KZT'] = __( 'Казахстанский тенге ', 'saphali-woocommerce-lite' );
		$currencies['UZS'] = __( 'Узбекский сум', 'saphali-woocommerce-lite' );
		$currencies['LTL'] = __( 'Lithuanian Litas', 'saphali-woocommerce-lite' );
		return $currencies;
	}
	function add_inr_currency_symbol( $symbol , $currency ) {
		if(empty($currency))
		$currency = get_option( 'woocommerce_currency' );
		if(isset($currency)) {
			if( version_compare( WOOCOMMERCE_VERSION, '2.5.2', '<' ) || SAPHALI_LITE_SYMBOL )
			switch( $currency ) {
				case 'UAH': $symbol = '&#x433;&#x440;&#x43D;.'; break;
				case 'RUB': if( is_admin() ) $symbol = '&#x440;&#x443;&#x431;.'; else $symbol = '<span class=rur >&#x440;<span>&#x443;&#x431;.</span></span>'; break;
				case 'RUR': $symbol = '&#x440;&#x443;&#x431;.'; break;
				case 'BYN': $symbol = '&#x440;&#x443;&#x431;.'; break;
				case 'BYR': $symbol = '&#x440;&#x443;&#x431;.'; break;
				case 'AMD': $symbol = '&#x534;'; break;
				case 'KGS': $symbol = 'сом'; break;
				case 'KZT': $symbol = '&#x20B8;'; break;
				case 'UZS': $symbol = '&#x441;&#x45E;&#x43C;'; break;
				case 'LTL': $symbol = 'lt.'; break;
			}
			else 
			switch( $currency ) {
				case 'UAH': $symbol = '&#x433;&#x440;&#x43D;.'; break;
				case 'RUR': $symbol = '&#x440;&#x443;&#x431;.'; break;
				case 'BYN': $symbol = '&#x440;&#x443;&#x431;.'; break;
				case 'BYR': $symbol = '&#x440;&#x443;&#x431;.'; break;
				case 'AMD': $symbol = '&#x534;'; break;
				case 'KGS': $symbol = 'сом'; break;
				case 'KZT': $symbol = '&#x20B8;'; break;
				case 'UZS': $symbol = '&#x441;&#x45E;&#x43C;'; break;
				case 'LTL': $symbol = 'lt.'; break;
			}
		}
		return $symbol;
	}
	function admin_enqueue_scripts_page_saphali() {
		global $woocommerce;
		$plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
		if( isset($_GET['page']) && $_GET['page'] == 'woocommerce_saphali_s_l' )
		wp_enqueue_script( 'tablednd', $plugin_url. '/js/jquery.tablednd.0.5.js', array('jquery'), $woocommerce->version );
	}
	function woocommerce_saphali_page_s_l () {
		?>
		<div class="wrap woocommerce"><div class="icon32 icon32-woocommerce-reports" id="icon-woocommerce"><br /></div>
			<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			Настройки Saphali WC
			</h2>
			<ul class="subsubsub">
				 <li><a href="admin.php?page=woocommerce_saphali_s_l" <?php if(empty($_GET["tab"])) echo 'class="current"';?> >Управление полями</a> | </li>
				 <li><a href="admin.php?page=woocommerce_saphali_s_l&tab=2" <?php if(!empty($_GET["tab"]) && $_GET["tab"] == 2) echo 'class="current"';?>>Число колонок в каталоге</a> | </li>
				 <li><a href="admin.php?page=woocommerce_saphali_s_l&tab=1" <?php if(!empty($_GET["tab"]) && $_GET["tab"] == 1) echo 'class="current"';?>><span color="red">Дополнительная информация</span></a></li>
			</ul>
			<?php if(empty($_GET["tab"])) {
				$this->locale_tmp = get_locale();
				if( !empty($this->locale_tmp) && $this->locale_tmp != 'en_US') {
					switch_to_locale('en_US');
				}
				global $woocommerce;
				remove_filter( 'woocommerce_checkout_fields' , 'woo_customize_checkout_fields' );
				if ( empty( $woocommerce->checkout ) ) {
					
					if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) { 
						include_once( WP_PLUGIN_DIR . '/' . $woocommerce->template_url. 'classes/class-wc-checkout.php' ); 
					} elseif ( !version_compare( WOOCOMMERCE_VERSION, '2.3', '<' ) ) {
						include_once( WP_PLUGIN_DIR . '/' . str_replace( array('compatability/2.3/', 'compatibility/2.4/'), '', WC()->template_path() ) . 'includes/class-wc-autoloader.php' ); 
						$load = new WC_Autoloader();
						if(!class_exists('WC_Cart')) $load->autoload( 'WC_Cart' );if(!class_exists('WC_Customer')) $load->autoload( 'WC_Customer' );  $load->autoload( 'WC_Checkout' ); if ( !version_compare( WOOCOMMERCE_VERSION, '2.2', '<' ) ) { include_once( WP_PLUGIN_DIR . '/' . str_replace( array('compatability/2.3/', 'compatibility/2.4/'), '', WC()->template_path() ) . 'includes/abstracts/abstract-wc-session.php' ); include_once( WP_PLUGIN_DIR . '/' . str_replace( array('compatability/2.3/', 'compatibility/2.4/'), '', WC()->template_path() ) . 'includes/class-wc-session-handler.php' );  $woocommerce->session =  new WC_Session_Handler();} else {
							 $woocommerce->autoload( 'WC_Session' ); 
							 $woocommerce->autoload( 'WC_Session_Handler' ); 
						}  
					} else { 
						if(!class_exists('WC_Cart')) $woocommerce->autoload( 'WC_Cart' );if(!class_exists('WC_Customer')) $woocommerce->autoload( 'WC_Customer' );  $woocommerce->autoload( 'WC_Checkout' ); if ( !version_compare( WOOCOMMERCE_VERSION, '2.2', '<' ) ) { include_once( WP_PLUGIN_DIR . '/' . str_replace( array('compatability/2.2/','compatability/2.3/', 'compatibility/2.4/'), '', WC()->template_path() ) . 'includes/abstracts/abstract-wc-session.php' ); include_once( WP_PLUGIN_DIR . '/' . str_replace( array('compatability/2.2/','compatability/2.3/', 'compatibility/2.4/'), '', WC()->template_path() ) . 'includes/class-wc-session-handler.php' );  $woocommerce->session =  new WC_Session_Handler();} else {
							 $woocommerce->autoload( 'WC_Session' ); 
							 if ( !version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ))
							 $woocommerce->autoload( 'WC_Session_Handler' ); 
						}
					}
					if(class_exists('WC_Checkout')) {
						if(class_exists('WC_Customer')) $woocommerce->customer =  new WC_Customer();
						if(class_exists('WC_Cart')) $woocommerce->cart =  new WC_Cart();
						$f = new WC_Checkout();
					}
				}
				 else	$f = $woocommerce->checkout; 
				 $global_f_checkout_fields = $f->checkout_fields;

				if( !empty($this->locale_tmp) && $this->locale_tmp != 'en_US') {
					switch_to_locale($this->locale_tmp);
				}
				if($_POST){
					if(@$_POST["reset"] != 'All') {
						// Управление новыми полями
						foreach( array('billing', 'shipping', 'order') as $method)
						if(@is_array($_POST[$method]["new_fild"])) {
							foreach ($_POST[$method]["new_fild"] as $k_nf => $v_nf) {
								if ($k_nf == 'name') continue;
								if (is_array($v_nf))
									foreach ($v_nf as $k_nf_f => $v_nf_f) {
										$new_fild = isset($_POST[$method]["new_fild"]['name'][$k_nf_f]) ? $_POST[$method]["new_fild"]['name'][$k_nf_f] : $new_fild;
										if ($k_nf == 'class') {
											$addFild[$method][$new_fild][$k_nf] = array($v_nf_f);
										} elseif ($k_nf == 'options') {
											foreach ($v_nf_f as $val_v_nf_f) {
												$index = isset($addFild[$method][$new_fild][$k_nf])  ? (sizeof($addFild[$method][$new_fild][$k_nf]) + 1) : 1;
												$addFild[$method][$new_fild][$k_nf]['option-' . $index] = $val_v_nf_f;
											}
										} elseif ($k_nf == 'type') {
											// echo '<pre>';var_dump($v_nf_f);echo '</pre>';
											$addFild[$method][$new_fild][$k_nf] = current($v_nf_f);
										} else $addFild[$method][$new_fild][$k_nf] = $v_nf_f;
									}
							}
					
							unset($new_fild, $_POST[$method]["new_fild"]);
						}
						//END 
						$filds = $global_f_checkout_fields;
						foreach( array('billing', 'shipping', 'order') as $method)
						if(is_array($filds[$method])) {
						if(!isset($addFild[$method]) || isset($addFild[$method]) && !is_array($addFild[$method])) $addFild[$method] = array();
						if(!is_array($_POST[$method])) $_POST[$method] = array();
						$filds[$method] = array_merge($filds[$method] ,  $_POST[$method], $addFild[$method]);

						foreach($filds[$method] as $key_post => $value_post) {
							$filds[$method][$key_post]["label"] = str_replace("'", '&rsquo;', stripcslashes($filds[$method][$key_post]["label"]) );
							$value_post["label"] = str_replace("'", '&rsquo;', stripcslashes($value_post["label"]) );
							if( !isset($global_f_checkout_fields[$method][$key_post]['type']) &&  (isset($filds[$method][$key_post]['type']) && $filds[$method][$key_post]['type'] != 'select' && $filds[$method][$key_post]['type'] != 'radio' && $filds[$method][$key_post]['type'] != 'checkbox' && $filds[$method][$key_post]['type'] != 'textarea' || !isset($filds[$method][$key_post]['type']))  ) unset($filds[$method][$key_post]['type'],  $value_post["type"]);

							
								if(@$filds[$method][$key_post]['public'] != 'on') {
									$filds_new[$method][$filds[$method][$key_post]["order"]][$key_post]["public"] = false;
									$fild_remove_filter[$method][] = $key_post;
								} else {$filds_new[$method][$filds[$method][$key_post]["order"]][$key_post]["public"] = true;}

							
							foreach($value_post as $k_post=> $v_post){
								if( 'on' == $v_post  ) {
									$filds[$method][$key_post][$k_post] = true;
									$value_post[$k_post] = true;
								} elseif(in_array($k_post, array('public','clear','required'))) {  $filds[$method][$key_post][$k_post] = false; $value_post[$k_post] = false; if(!$filds[$method][$key_post][$k_post] && $k_post == 'public') unset($filds[$method][$key_post][$k_post]); }
							}
							$filds_new[$method][$filds[$method][$key_post]["order"]][$key_post] = $value_post;
							
							unset($_POST[$method][$key_post]);
						}

						}
						

						//END Управление публикацией
						$filds_finish["billing"] = $filds_finish["shipping"] = $filds_finish["order"] = array();

						for($i = 0; $i<count($filds_new["billing"]); $i++) {
							if(isset($filds_new["billing"][$i]))
							$filds_finish["billing"] = $filds_finish["billing"] + $filds_new["billing"][$i];
						}
						for($i = 0; $i<count($filds_new["shipping"]); $i++) {
							if(isset($filds_new["shipping"][$i]))
							$filds_finish["shipping"] = $filds_finish["shipping"] + $filds_new["shipping"][$i];
						}
						for($i = 0; $i<count($filds_new["order"]); $i++) {
							if(isset($filds_new["order"][$i]))
							$filds_finish["order"] = $filds_finish["order"] + $filds_new["order"][$i];
						}

						$filds_finish_filter = $filds_finish;
						if(is_array($fild_remove_filter["billing"])) {
							foreach($fild_remove_filter["billing"] as $v_filt){
								unset($filds_finish_filter["billing"][$v_filt]);
							}
						}
						if(isset($fild_remove_filter["shipping"]) && is_array($fild_remove_filter["shipping"])) {
							foreach($fild_remove_filter["shipping"] as $v_filt){
								unset($filds_finish_filter["shipping"][$v_filt]);
							}
						}
						if(isset($fild_remove_filter["order"]) && is_array($fild_remove_filter["order"])) {
							foreach($fild_remove_filter["order"] as $v_filt){
								unset($filds_finish_filter["order"][$v_filt]);
							}
						}
						update_option('woocommerce_saphali_filds',$filds_finish);
						update_option('woocommerce_saphali_filds_filters',$filds_finish_filter);
						foreach($filds_finish_filter['billing'] as $k_f => $v_f) {
							$new_key = str_replace('billing_', '' , $k_f);
							if(in_array($new_key, array('country', 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode' ) ))
							$locate[$new_key] = $v_f;
							elseif(in_array(str_replace('shipping_', '' , $k_f), array('country', 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode' ) )) {
								$locate[$new_key] = $filds_finish_filter['shipping'][$k_f];
							}
						}
						if(!update_option('woocommerce_saphali_filds_locate',$locate))add_option('woocommerce_saphali_filds_locate',$locate);
					} else {
							delete_option('woocommerce_saphali_filds');
							delete_option('woocommerce_saphali_filds_filters'); 
							delete_option('woocommerce_saphali_filds_locate'); 
						}
				}
				add_filter('woocommerce_pre_remove_cart_item_from_session', function() { return true; });
			?>
			<div class="clear"></div>
			<h3 class="nav-tab-wrapper woo-nav-tab-wrapper" style="text-align: center;">Управление полями на странице заказа и на странице профиля</h3>
		 <?php if($_POST && @$_POST["reset"] != 'All') { ?><div class="updated" id="message"><p>Настройки сохранены</p></div><?php } ?>
			<h2 align="center">Реквизиты оплаты</h2>
			<form action="" method="post">
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
			<thead>
				<tr>
					<th width="130px">Название<img class="help_tip" data-tip="Название поля должно быть уни&shy;ка&shy;ль&shy;ным (не должно повторяться)." src="<?php bloginfo('wpurl');?>/wp-content/plugins/woocommerce/assets/images/help.png" /></th>
					<th width="130px">Заголовок</th>
					<th width="130px">Текст в поле</th>
					<th width="35px">Clear<img class="help_tip" data-tip="Указывает на то, что следующее поле за текущим, будет начинаться с новой строки." src="<?php bloginfo('wpurl');?>/wp-content/plugins/woocommerce/assets/images/help.png" /> </th>
					<th width="130px">Класс поля<img class="help_tip" data-tip="<h3 style='margin:0;padding:0'>Задает стиль текущего поля</h3><ul style='text-align: left;'><li><span style='color: #000'>form-row-first</span>&nbsp;&ndash;&nbsp;первый в строке;</li><li><span style='color: #000'>form-row-last</span>&nbsp;&ndash;&nbsp;последний в строке.</li></ul><hr /><span style='color: #000'>ЕСЛИ ОСТАВИТЬ ПУСТЫМ</span>, то поле будет отображаться на всю ширину. Соответственно, в предыдущем поле (которое выше) нужно отметить &laquo;Clear&raquo;." src="<?php bloginfo('wpurl');?>/wp-content/plugins/woocommerce/assets/images/help.png" /></th>
				<th  width="40px">Тип поля</th>
				<th  width="40px">Обя&shy;за&shy;те&shy;ль&shy;ное</th>
				
				<th  width="40px">Опу&shy;бли&shy;ко&shy;вать</th>
				<th  width="120px">Метод оплаты</th>
				<th  width="120px">Метод доставки</th>
				<th width="65px">Удалить/До&shy;ба&shy;вить</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Название</th>
				<th>Заголовок</th>
				<th>Текст в поле</th>
				<th width="35px">Clear<img class="help_tip" data-tip="Указывает на то, что следующее поле за текущим, будет начинаться с новой строки." src="<?php  bloginfo('wpurl');?>/wp-content/plugins/woocommerce/assets/images/help.png" /> </th>
				<th>Класс поля</th>
				<th  width="40px">Тип поля</th>
				<th  width="40px">Обя&shy;за&shy;те&shy;ль&shy;ное</th>

				<th  width="40px">Опу&shy;бли&shy;ко&shy;вать</th>
				<th  width="120px">Метод оплаты</th>
				<th  width="120px">Метод доставки</th>
				<th>Удалить/До&shy;ба&shy;вить</th>
				</tr>
			</tfoot>
			<tbody id="the-list" class="myTable">
				<?php 

				$count = 0;

				$checkout_fields = get_option('woocommerce_saphali_filds');
				
				if( isset($checkout_fields["billing"]) && is_array($checkout_fields["billing"])) $global_f_checkout_fields["billing"] = $checkout_fields["billing"];
				if( isset($f) )
				foreach($global_f_checkout_fields["billing"] as $key => $value) {	
				
					$public = 'public';
					if( !version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
						if( isset( $checkout_fields["billing"][$key][$public] ) ) $value[$public] = $checkout_fields["billing"][$key][$public];
						elseif( isset( $checkout_fields["billing"][$key] ) ) {
							$value[$public] = '';
						}
					}
					if(isset($checkout_fields["billing"][$key]['payment_method'])) {
						$pm_k_remove = array();
						if(is_array($checkout_fields["billing"][$key]['payment_method']))
						foreach($checkout_fields["billing"][$key]['payment_method'] as $k => $v) {
							if($v === '0') {
								$pm_k_remove[] = $k;
							}
						} elseif($checkout_fields["billing"][$key]['payment_method'] === '0') {
							// $pm_k_remove[] = $k;
							unset($checkout_fields["billing"][$key]['payment_method']);
						}

						foreach($pm_k_remove as $k_remove) {
							unset($checkout_fields["billing"][$key]['payment_method'][$k_remove]);
						}
						if( isset( $checkout_fields["billing"][$key] ) ) $value['payment_method'] = $checkout_fields["billing"][$key]['payment_method'];
					}
					if(isset($checkout_fields["billing"][$key]['shipping_method'])) {
						$pm_k_remove = array();
						if(is_array($checkout_fields["billing"][$key]['shipping_method']))
						foreach($checkout_fields["billing"][$key]['shipping_method'] as $k => $v) {
							if($v === '0') {
								$pm_k_remove[] = $k;
							}
						} elseif($checkout_fields["billing"][$key]['shipping_method'] === '0') {
							// $pm_k_remove[] = $k;
							unset($checkout_fields["billing"][$key]['shipping_method']);
						}
						
						foreach($pm_k_remove as $k_remove) {
							unset($checkout_fields["billing"][$key]['shipping_method'][$k_remove]);
						}
						if( isset( $checkout_fields["billing"][$key] ) ) $value['shipping_method'] = $checkout_fields["billing"][$key]['shipping_method'];
					}
					
					if(empty($value[$public]) && 
						(isset($checkout_fields["billing"]) && !is_array($checkout_fields["billing"]) || !isset($checkout_fields["billing"]))) 
						$value[$public] = true;
					?>
					<tr>
						<td> <input  disabled value='<?php echo $key?>' type="text" name="billing[<?php echo $key?>][name]" /></td>
						<td><input value='<?php echo $value['label']?>' type="text" name="billing[<?php echo $key?>][label]" /></td>
					<td<?php if(isset($value['type']) && ($value['type'] == 'select' || $value['type'] == 'radio') ) {
						echo ' class="option-area"';}  ?>><?php if(!isset($value['type']) || isset($value['type']) && $value['type'] != 'select' && $value['type'] != 'radio') { ?><input value='<?php if(isset( $value['placeholder'] )) echo $value['placeholder']; ?>' type="text" name="billing[<?php  echo $key?>][placeholder]" /><?php } else { 
							if( isset($value['options']) && is_array($value['options']) ) {
								foreach($value['options'] as $key_option => $val_option) {?>
								<span><input class="options" type="text" name="billing[<?php echo $key?>][options][<?php echo $key_option; ?>]" value="<?php echo $val_option?>" /> <span class="delete-option" style="cursor:pointer;border:1px solid">Удалить</span></span><br />
								
							<?php } ?>
							<div class="button add_option" rel="<?php echo $key; ?>">Добавить еще</div>
							<?php
							}
					
					} ?></td>
						<td><input <?php if(isset($value['clear']) && $value['clear']) echo 'checked'?>  class="<?php echo isset($value['clear']) ? $value['clear'] : '' ;?>" type="checkbox" name="billing[<?php echo $key?>][clear]" /></td>
						<td><?php  if(isset($value['class']) && is_array($value['class'])) { foreach($value['class'] as $v_class) { ?>
						<input value='<?php echo $v_class;?>' type="text" name="billing[<?php echo $key?>][class][]" /> <?php } } else { ?>
						<input value='' type="text" name="billing[<?php echo $key?>][class][]" /> <?php
						} ?></td>
					<td>
					Select <input <?php  if(isset($value['type']) && $value['type'] == 'select') echo 'checked'?> type="radio" name="billing[<?php  echo $key?>][type]" value="select" /><br />
					Radio <input <?php  if(isset($value['type']) && $value['type'] == 'radio') echo 'checked'?> type="radio" name="billing[<?php  echo $key?>][type]" value="radio" /><br />
					Checkbox <input <?php  if(isset($value['type']) && $value['type'] == 'checkbox') echo 'checked'?> type="radio" name="billing[<?php  echo $key?>][type]" value="checkbox"  /><br />
					Textarea <input <?php  if(isset($value['type']) && $value['type'] == 'textarea') echo 'checked'?> type="radio" name="billing[<?php  echo $key?>][type]" value="textarea"  /><br />
					<?php echo (!isset($value['type']) || $value['type'] == 'select'|| $value['type'] == 'checkbox'|| $value['type'] == 'textarea') ? 'Text' : $value['type']; ?> <input <?php  if(isset($value['type']) && $value['type'] == $value['type'] && !in_array( $value['type'], array('select', 'radio', 'textarea', 'checkbox') ) ) echo 'checked'?> type="radio" name="billing[<?php  echo $key?>][type]" value="<?php if( isset($value['type']) && !in_array( $value['type'], array('select', 'radio', 'textarea', 'checkbox') ) ) echo $value['type']; ?>"  />
					</td>
						<td><input <?php if( isset($value['required'] ) && $value['required']) echo 'checked'?> type="checkbox" name="billing[<?php echo $key?>][required]" /></td>
						<td><input <?php if(isset($value[$public]) && $value[$public]) echo 'checked';?> type="checkbox" name="billing[<?php echo $key?>][<?php echo $public; ?>]" /></td>
						<td>
						<select multiple="multiple" width="120px" name="billing[<?php echo $key?>][payment_method][]">
							<option value="0"<?php if( isset($value['payment_method']) && ( in_array('0', $value['payment_method']) || empty($value['payment_method']) ) || !isset($value['payment_method']) ) echo 'selected';?>>Все</option>
							<?php 
								foreach ( $woocommerce->payment_gateways->payment_gateways() as $gateway ) {
									if ( $gateway->enabled != 'yes' ) continue;
									?><option value="<?php echo $gateway->id; ?>" <?php if(isset($value['payment_method']) && in_array($gateway->id, $value['payment_method']) ) echo 'selected';?>><?php echo $gateway->title; ?></option><?php
								} 
							?>
						</select>
						</td>
						<td>
						<select multiple="multiple" width="120px" name="billing[<?php echo $key?>][shipping_method][]">
							<option value="0"<?php if( isset($value['shipping_method']) && ( in_array('0', $value['shipping_method']) || empty($value['shipping_method']) ) || !isset($value['shipping_method']) ) echo 'selected';?>>Все</option>
							<?php 
								foreach ( $woocommerce->shipping->get_shipping_methods() as $act_id => $shipping ) {
									if ( $shipping->enabled == 'no' ) continue;
									?><option value="<?php echo $act_id; ?>" <?php if(isset($value['shipping_method']) && in_array($act_id, $value['shipping_method']) ) echo 'selected';?>><?php echo $shipping->title ? $shipping->title: $shipping->method_title; ?></option><?php
								} 
							?>
						</select>
						</td>
						<td><input rel="sort_order" id="order_count" type="hidden" name="billing[<?php echo $key?>][order]" value="<?php echo $count?>" />
						<input type="button" class="button" id="billing_delete" value="Удалить -"/></td>
					</tr>
					<?php $count++;
				}
				?>
				<tr  class="nodrop nodrag">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>

						<td></td>
						<td></td>
						
						<td colspan="2"><input type="button" class="button"  id="billing" value="Добавить +"/></td>
				</tr>
			</tbody>
			</table>
				
			<h2 align="center">Реквизиты доставки</h2>
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
			<thead>
				<tr>
					<th width="130px">Название<img class="help_tip" data-tip="Название поля должно быть уни&shy;ка&shy;ль&shy;ным (не должно повторяться)." src="<?php bloginfo('wpurl');?>/wp-content/plugins/woocommerce/assets/images/help.png" /></th>
					<th width="130px">Заголовок</th>
					<th width="130px">Текст в поле</th>
					<th width="35px">Clear<img class="help_tip" data-tip="Указывает на то, что следующее поле за текущим, будет начинаться с новой строки." src="<?php bloginfo('wpurl');?>/wp-content/plugins/woocommerce/assets/images/help.png" /> </th>
					<th width="130px">Класс поля<img class="help_tip" data-tip="<h3 style='margin:0;padding:0'>Задает стиль текущего поля</h3><ul style='text-align: left;'><li><span style='color: #000'>form-row-first</span>&nbsp;&ndash;&nbsp;первый в строке;</li><li><span style='color: #000'>form-row-last</span>&nbsp;&ndash;&nbsp;последний в строке.</li></ul><hr /><span style='color: #000'>ЕСЛИ ОСТАВИТЬ ПУСТЫМ</span>, то поле будет отображаться на всю ширину. Соответственно, в предыдущем поле (которое выше) нужно отметить &laquo;Clear&raquo;." src="<?php bloginfo('wpurl');?>/wp-content/plugins/woocommerce/assets/images/help.png" /></th>
					<th  width="40px">Обя&shy;за&shy;те&shy;ль&shy;ное</th>

					<th  width="40px">Опу&shy;бли&shy;ко&shy;вать</th>
					<th  width="120px">Метод оплаты</th>
					<th  width="120px">Метод доставки</th>
					<th width="65px">Удалить/До&shy;ба&shy;вить</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Название</th>
					<th>Заголовок</th>
					<th>Текст в поле</th>
					<th width="56px">Clear<img class="help_tip" data-tip="Указывает на то, что следующее поле за текущим, будет начинаться с новой строки." src="<?php bloginfo('wpurl');?>/wp-content/plugins/woocommerce/assets/images/help.png" /> </th>
					<th>Класс поля</th>
					<th  width="40px">Обя&shy;за&shy;те&shy;ль&shy;ное</th>

					<th  width="40px">Опу&shy;бли&shy;ко&shy;вать</th>
					<th  width="120px">Метод оплаты</th>
					<th  width="120px">Метод доставки</th>
					<th>Удалить/До&shy;ба&shy;вить</th>
				</tr>
			</tfoot>
			<tbody id="the-list" class="myTable">
				<?php $count = 0; 
				if(isset($checkout_fields["shipping"]) && is_array($checkout_fields["shipping"])) $global_f_checkout_fields["shipping"] = $checkout_fields["shipping"];
				if( isset( $global_f_checkout_fields["shipping"] ) )
				foreach($global_f_checkout_fields["shipping"] as $key => $value) {	
					$public = 'public';
					if( ! version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
						if( isset( $checkout_fields["shipping"][$key] ) && isset( $checkout_fields["shipping"][$key][$public] ) ) 
							$value[$public] = $checkout_fields["shipping"][$key][$public];
					}
					if(isset($checkout_fields["shipping"][$key]['payment_method'])) {
						$pm_k_remove = array();
						if(is_array($checkout_fields["shipping"][$key]['payment_method']))
						foreach($checkout_fields["shipping"][$key]['payment_method'] as $k => $v) {
							if($v === '0') {
								$pm_k_remove[] = $k;
							}
						} elseif($checkout_fields["shipping"][$key]['payment_method']=== '0') {
							unset($checkout_fields["shipping"][$key]['payment_method']);
						}
						
						foreach($pm_k_remove as $k_remove) {
							unset($checkout_fields["shipping"][$key]['payment_method'][$k_remove]);
						}
						if( isset( $checkout_fields["shipping"][$key] ) ) $value['payment_method'] = $checkout_fields["shipping"][$key]['payment_method'];
					}
					if(isset($checkout_fields["shipping"][$key]['shipping_method'])) {
						$pm_k_remove = array();
						if(is_array($checkout_fields["shipping"][$key]['shipping_method']))
						foreach($checkout_fields["shipping"][$key]['shipping_method'] as $k => $v) {
							if($v === '0') {
								$pm_k_remove[] = $k;
							}
						} elseif($checkout_fields["shipping"][$key]['shipping_method'] === '0') {
							unset($checkout_fields["shipping"][$key]['shipping_method']);
						}
						
						foreach($pm_k_remove as $k_remove) {
							unset($checkout_fields["shipping"][$key]['shipping_method'][$k_remove]);
						}
						if( isset( $checkout_fields["shipping"][$key] ) ) $value['shipping_method'] = $checkout_fields["shipping"][$key]['shipping_method'];
					}
				if( empty($value['public']) && (isset($checkout_fields["shipping"]) && !is_array($checkout_fields["shipping"]) || !isset($checkout_fields["shipping"]))  ) $value['public'] = true;
					?>
					<tr>
						<td><input  disabled  value=<?php echo $key?> type="text" name="shipping[<?php echo $key?>][name]" /></td>
						<td><input value='<?php echo isset($value['label']) ? $value['label']: ''; ?>' type="text" name="shipping[<?php echo $key?>][label]" /><input value='<?php echo isset($value['type']) ? $value['type']: '' ?>' type="hidden" name="shipping[<?php echo $key?>][type]" /></td>
						<td><input value='<?php if(isset( $value['placeholder'] )) echo $value['placeholder']; ?>' type="text" name="shipping[<?php echo $key?>][placeholder]" /></td>
						<td><input <?php if(isset($value['clear']) && $value['clear']) echo 'checked'?> class="<?php echo isset($value['clear'])? $value['clear'] : ''; ?>" type="checkbox" name="shipping[<?php echo $key?>][clear]" /></td>
						<td><?php  if( isset($value['class']) && is_array($value['class']) ) { foreach($value['class'] as $v_class) { ?>
						
						<input value='<?php echo $v_class;?>' type="text" name="shipping[<?php echo $key?>][class][]" /> <?php } } else { ?>
						<input value='' type="text" name="shipping[<?php echo $key?>][class][]" /> <?php
						} ?></td>
						<td><input <?php if(isset($value['required']) && $value['required']) echo 'checked'?> type="checkbox" name="shipping[<?php echo $key?>][required]" /></td>
						<td><input <?php if(isset($value['public']) && $value['public']) echo 'checked';?> type="checkbox" name="shipping[<?php echo $key?>][public]" /></td>
						<td>
						<select multiple="multiple" width="120px" name="shipping[<?php echo $key?>][payment_method][]">
							<option value="0" <?php if( isset($value['payment_method']) && ( in_array('0', $value['payment_method']) || empty($value['payment_method']) ) || !isset($value['payment_method']) ) echo 'selected';?>>Все</option>
							<?php 
								foreach ( $woocommerce->payment_gateways->payment_gateways() as $gateway ) {
									if ( $gateway->enabled != 'yes' ) continue;
									?><option value="<?php echo $gateway->id; ?>" <?php if(isset($value['payment_method']) && in_array($gateway->id, $value['payment_method']) ) echo 'selected';?>><?php echo $gateway->title; ?></option><?php
								} 
							?>
						</select>
						</td><td>
						<select multiple="multiple" width="120px" name="shipping[<?php echo $key?>][shipping_method][]">
							<option value="0" <?php if( isset($value['shipping_method']) && ( in_array('0', $value['shipping_method']) || empty($value['shipping_method']) ) || !isset($value['shipping_method']) ) echo 'selected';?>>Все</option>
							<?php 
								foreach ( $woocommerce->shipping->get_shipping_methods() as $act_id => $shipping ) {
									if ( $shipping->enabled == 'no' ) continue;
									?><option value="<?php echo $act_id; ?>" <?php if(isset($value['shipping_method']) && in_array($act_id, $value['shipping_method']) ) echo 'selected';?>><?php echo $shipping->title ? $shipping->title: $shipping->method_title; ?></option><?php
								} 
							?>
						</select>
						</td>
						
						<td><input rel="sort_order"  id="order_count" type="hidden" name="shipping[<?php echo $key?>][order]" value="<?php echo $count?>" /><input type="button" class="button" id="billing_delete" value="Удалить -"/>
							<?php 
							if( isset($value['options']) && is_array($value['options']) ) {
								foreach($value['options'] as  $key_option => $val_option) {?>
								<input class="options" type="hidden" name="shipping[<?php echo $key?>][options][<?php echo $key_option; ?>]" value="<?php echo $val_option?>" />
							<?php }
							} ?>
						</td>
					</tr>
					<?php $count++;
				}
				?>
				<tr  class="nodrop nodrag">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
		
						<td></td>
						<td></td>
						<td></td>
						<td colspan="2"><input type="button" class="button" id="shipping" value="Добавить +"/></td>
				</tr>
			
			</tbody>
			</table>		
		<br />
		<h2 align="center">Дополнительные поля</h2>
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
			<thead>
				<tr>
					<th width="130px">Название<img class="help_tip" data-tip="Название поля должно быть уни&shy;ка&shy;ль&shy;ным (не должно повторяться)." src="<?php bloginfo('wpurl');?>/wp-content/plugins/woocommerce/assets/images/help.png" /></th>
					<th width="130px">Заголовок</th>
					<th width="130px">Текст в поле</th>
					<th width="130px">Класс поля</th>
					<th width="130px">Тип поля</th>
					<th  width="40px">Опу&shy;бли&shy;ко&shy;вать</th>
					<th width="65px">Удалить/До&shy;ба&shy;вить</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Название</th>
					<th>Заголовок</th>
					<th>Текст в поле</th>
					<th>Класс поля</th>
					<th>Тип поля</th>
					<th  width="40px">Опу&shy;бли&shy;ко&shy;вать</th>
					<th>Удалить/До&shy;ба&shy;вить</th>
				</tr>
			</tfoot>
			<tbody id="the-list" class="myTable">
				<?php $count = 0;
				if(isset($checkout_fields["order"]) && is_array($checkout_fields["order"])) $global_f_checkout_fields["order"] = $checkout_fields["order"];
				if(isset($global_f_checkout_fields["order"]) )
				foreach($global_f_checkout_fields["order"] as $key => $value) {
					$public = 'public';
					if( ! version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
						if( isset( $checkout_fields["order"][$key] ) ) $value[$public] = $checkout_fields["order"][$key][$public];
					}
					if(empty($value['public']) && (isset($checkout_fields["order"]) && !is_array($checkout_fields["order"]) || !isset($checkout_fields["order"]))) $value['public'] = true;
					?>
					<tr>
						<td><input disabled value=<?php echo $key?> type="text" name="order[<?php echo $key?>][name]" /></td>
						<td><input value='<?php echo $value['label']?>' type="text" name="order[<?php echo $key?>][label]" /></td>
						<td><input value='<?php echo $value['placeholder']?>' type="text" name="order[<?php echo $key?>][placeholder]" /></td>
						
						<td><?php  if(isset($value['class']) && is_array($value['class'])) { foreach($value['class'] as $v_class) { ?>
						
						<input value='<?php echo $v_class;?>' type="text" name="order[<?php echo $key?>][class][]" /> <?php } } else { ?>
						<input value='' type="text" name="order[<?php echo $key?>][class][]" /> <?php
						} ?></td>
						<td><input value='<?php echo $value['type']?>' type="text" name="order[<?php echo $key?>][type]" /></td>
						<td><input <?php if($value['public']) echo 'checked';?> type="checkbox" name="order[<?php echo $key?>][public]" /></td>
						
						<td><input id="order_count" rel="sort_order" type="hidden" name="order[<?php echo $key?>][order]" value="<?php echo $count?>" /><input type="button" class="button" id="billing_delete" value="Удалить -"/>
							<?php 
							if( isset($value['options']) && is_array($value['options']) ) {
								foreach($value['options'] as $key_option => $val_option) {?>
								<input class="options" type="hidden" name="order[<?php echo $key?>][options][<?php echo $key_option; ?>]" value="<?php echo $val_option?>" />
							<?php }
							} ?>
						</td>
					</tr>
					<?php $count++;
				}
				?>
				<tr  class="nodrop nodrag">
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>

					
					<td><input type="button" class="button" id="order" value="Добавить +"/></td>
				</tr>
			</tbody>
			</table><br />
			<input type="submit" class="button alignleft" value="<?php _e('Save') ?>"/>
			</form>
			<form action="" method="post">
				<input type="hidden" name="reset" value="All"/>
				<input type="submit" class="button alignright" value="Восстановить поля по умолчанию"/>
			</form>
			<style type="text/css">
			#tiptip_content{font-size:11px;color:#fff;padding:4px 8px;background:#a2678c;border-radius:3px;-webkit-border-radius:3px;-moz-border-radius:3px;box-shadow:1px 1px 3px rgba(0,0,0,0.1);-webkit-box-shadow:1px 1px 3px rgba(0,0,0,0.1);-moz-box-shadow:1px 1px 3px rgba(0,0,0,0.1);text-align:center}#tiptip_content code{background:#855c76;padding:1px}#tiptip_arrow,#tiptip_arrow_inner{position:absolute;border-color:transparent;border-style:solid;border-width:6px;height:0;width:0}#tiptip_holder.tip_top #tiptip_arrow_inner{margin-top:-7px;margin-left:-6px;border-top-color:#a2678c}#tiptip_holder.tip_bottom #tiptip_arrow_inner{margin-top:-5px;margin-left:-6px;border-bottom-color:#a2678c}#tiptip_holder.tip_right #tiptip_arrow_inner{margin-top:-6px;margin-left:-5px;border-right-color:#a2678c}#tiptip_holder.tip_left #tiptip_arrow_inner{margin-top:-6px;margin-left:-7px;border-left-color:#a2678c}img.help_tip{vertical-align:middle;margin:0 0 0 3px}#tiptip_holder{display:none;position:absolute;top:0;left:0;z-index:99999}#tiptip_holder.tip_top{padding-bottom:5px}#tiptip_holder.tip_bottom{padding-top:5px}#tiptip_holder.tip_right{padding-left:5px}#tiptip_holder.tip_left{padding-right:5px}#tiptip_content{font-size:11px;color:#fff;padding:4px 8px;background:#a2678c;border-radius:3px;-webkit-border-radius:3px;-moz-border-radius:3px;box-shadow:1px 1px 3px rgba(0,0,0,0.1);-webkit-box-shadow:1px 1px 3px rgba(0,0,0,0.1);-moz-box-shadow:1px 1px 3px rgba(0,0,0,0.1);text-align:center}#tiptip_content code{background:#855c76;padding:1px}#tiptip_arrow,#tiptip_arrow_inner{position:absolute;border-color:transparent;border-style:solid;border-width:6px;height:0;width:0}#tiptip_holder.tip_top #tiptip_arrow_inner{margin-top:-7px;margin-left:-6px;border-top-color:#a2678c}#tiptip_holder.tip_bottom #tiptip_arrow_inner{margin-top:-5px;margin-left:-6px;border-bottom-color:#a2678c}#tiptip_holder.tip_right #tiptip_arrow_inner{margin-top:-6px;margin-left:-5px;border-right-color:#a2678c}#tiptip_holder.tip_left #tiptip_arrow_inner{margin-top:-6px;margin-left:-7px;border-left-color:#a2678c}
			input[disabled="disabled"], input[disabled=""] {
				background:none repeat scroll 0 0 #EAEAEA !important;
				color:#636060 !important;
			}
			#the-list select { width: 120px; }
			</style>
			<script type="text/javascript">
			(function($){$.fn.tipTip=function(options){var defaults={activation:"hover",keepAlive:false,maxWidth:"200px",edgeOffset:3,defaultPosition:"bottom",delay:400,fadeIn:200,fadeOut:200,attribute:"title",content:false,enter:function(){},exit:function(){}};var opts=$.extend(defaults,options);if($("#tiptip_holder").length<=0){var tiptip_holder=$('<div id="tiptip_holder" style="max-width:'+opts.maxWidth+';"></div>');var tiptip_content=$('<div id="tiptip_content"></div>');var tiptip_arrow=$('<div id="tiptip_arrow"></div>');$("body").append(tiptip_holder.html(tiptip_content).prepend(tiptip_arrow.html('<div id="tiptip_arrow_inner"></div>')))}else{var tiptip_holder=$("#tiptip_holder");var tiptip_content=$("#tiptip_content");var tiptip_arrow=$("#tiptip_arrow")}return this.each(function(){var org_elem=$(this);if(opts.content){var org_title=opts.content}else{var org_title=org_elem.attr(opts.attribute)}if(org_title!=""){if(!opts.content){org_elem.removeAttr(opts.attribute)}var timeout=false;if(opts.activation=="hover"){org_elem.hover(function(){active_tiptip()},function(){if(!opts.keepAlive){deactive_tiptip()}});if(opts.keepAlive){tiptip_holder.hover(function(){},function(){deactive_tiptip()})}}else if(opts.activation=="focus"){org_elem.focus(function(){active_tiptip()}).blur(function(){deactive_tiptip()})}else if(opts.activation=="click"){org_elem.click(function(){active_tiptip();return false}).hover(function(){},function(){if(!opts.keepAlive){deactive_tiptip()}});if(opts.keepAlive){tiptip_holder.hover(function(){},function(){deactive_tiptip()})}}function active_tiptip(){opts.enter.call(this);tiptip_content.html(org_title);tiptip_holder.hide().removeAttr("class").css("margin","0");tiptip_arrow.removeAttr("style");var top=parseInt(org_elem.offset()['top']);var left=parseInt(org_elem.offset()['left']);var org_width=parseInt(org_elem.outerWidth());var org_height=parseInt(org_elem.outerHeight());var tip_w=tiptip_holder.outerWidth();var tip_h=tiptip_holder.outerHeight();var w_compare=Math.round((org_width-tip_w)/2);var h_compare=Math.round((org_height-tip_h)/2);var marg_left=Math.round(left+w_compare);var marg_top=Math.round(top+org_height+opts.edgeOffset);var t_class="";var arrow_top="";var arrow_left=Math.round(tip_w-12)/2;if(opts.defaultPosition=="bottom"){t_class="_bottom"}else if(opts.defaultPosition=="top"){t_class="_top"}else if(opts.defaultPosition=="left"){t_class="_left"}else if(opts.defaultPosition=="right"){t_class="_right"}var right_compare=(w_compare+left)<parseInt($(window).scrollLeft());var left_compare=(tip_w+left)>parseInt($(window).width());if((right_compare&&w_compare<0)||(t_class=="_right"&&!left_compare)||(t_class=="_left"&&left<(tip_w+opts.edgeOffset+5))){t_class="_right";arrow_top=Math.round(tip_h-13)/2;arrow_left=-12;marg_left=Math.round(left+org_width+opts.edgeOffset);marg_top=Math.round(top+h_compare)}else if((left_compare&&w_compare<0)||(t_class=="_left"&&!right_compare)){t_class="_left";arrow_top=Math.round(tip_h-13)/2;arrow_left=Math.round(tip_w);marg_left=Math.round(left-(tip_w+opts.edgeOffset+5));marg_top=Math.round(top+h_compare)}var top_compare=(top+org_height+opts.edgeOffset+tip_h+8)>parseInt($(window).height()+$(window).scrollTop());var bottom_compare=((top+org_height)-(opts.edgeOffset+tip_h+8))<0;if(top_compare||(t_class=="_bottom"&&top_compare)||(t_class=="_top"&&!bottom_compare)){if(t_class=="_top"||t_class=="_bottom"){t_class="_top"}else{t_class=t_class+"_top"}arrow_top=tip_h;marg_top=Math.round(top-(tip_h+5+opts.edgeOffset))}else if(bottom_compare|(t_class=="_top"&&bottom_compare)||(t_class=="_bottom"&&!top_compare)){if(t_class=="_top"||t_class=="_bottom"){t_class="_bottom"}else{t_class=t_class+"_bottom"}arrow_top=-12;marg_top=Math.round(top+org_height+opts.edgeOffset)}if(t_class=="_right_top"||t_class=="_left_top"){marg_top=marg_top+5}else if(t_class=="_right_bottom"||t_class=="_left_bottom"){marg_top=marg_top-5}if(t_class=="_left_top"||t_class=="_left_bottom"){marg_left=marg_left+5}tiptip_arrow.css({"margin-left":arrow_left+"px","margin-top":arrow_top+"px"});tiptip_holder.css({"margin-left":marg_left+"px","margin-top":marg_top+"px"}).attr("class","tip"+t_class);if(timeout){clearTimeout(timeout)}timeout=setTimeout(function(){tiptip_holder.stop(true,true).fadeIn(opts.fadeIn)},opts.delay)}function deactive_tiptip(){opts.exit.call(this);if(timeout){clearTimeout(timeout)}tiptip_holder.fadeOut(opts.fadeOut)}}})}})(jQuery);
			jQuery(".tips, .help_tip").tipTip({
				'attribute' : 'data-tip',
				'fadeIn' : 50,
				'fadeOut' : 50,
				'delay' : 200
			});
			jQuery('input[value="billing_booking_delivery_t"]').parent().parent().hide();
		jQuery("body").delegate('.delete-option', 'click',function() {
			jQuery(this).parent().remove();
		});
		jQuery("body").delegate('.button.add_option', 'click',function() {
			jQuery(this).before(' <span><br /><input type="text" class="options" value="" name="billing['+jQuery(this).attr('rel')+'][options][option-'+ (jQuery(this).parent().find('input').length + 1) +']"/><span class="delete-option" style="cursor:pointer;border:1px solid">Удалить</span></span>');
		});
		jQuery("body").delegate('input[type="radio"]', 'click',function() {
			if( jQuery(this).val() == 'select' || jQuery(this).val() == 'radio') {
				if (typeof jQuery(this).closest('tr').find('td:first input') != 'undefined' && jQuery(this).closest('tr').find('td:first input').attr('disabled') == "disabled") {} else {
					var obj = jQuery(this).parent().parent();
					obj.find('td').css('border-bottom', 'none');
					

					var indx = obj.attr('class').split(' ')[0].replace('parrent_td_option','');
					if(!obj.parent().find('tr.tr_td_option' + indx).length) {
						obj.addClass('parrent_td_option'+jQuery('.button.add_option').length);
						var firstInput = obj.find('td:first input');
						if('billing[new_fild][name][]' != firstInput.attr('name') )
						obj.after('<tr style="border-top:0" class="tr_td_option'+jQuery('.button.add_option').length +'" ><td  style="border-top:0;padding-left: 72%;" colspan="9"> <span><input class="options" type="text" value="" name="billing['+firstInput.val()+'][options][option-1]"/><span class="delete-option" style="cursor:pointer;border:1px solid">Удалить</span></span> <div class="button add_option" rel="'+firstInput.val()+'">Добавить еще</div></td></tr>');
						else obj.after('<tr style="border-top:0" class="tr_td_option'+jQuery('.button.add_option').length +'" ><td  style="border-top:0;padding-left: 72%;" colspan="9"> <span><input class="options" type="text" value="" name="billing[new_fild][options][option-1]"/><span class="delete-option" style="cursor:pointer;border:1px solid">Удалить</span></span> <div class="button add_option" rel="new_fild">Добавить еще</div></td></tr>');	
					}
				}
			} else {
				var obj = jQuery(this).parent().parent();
				if(obj.find('td').attr('style') != '') {
					obj.find('td').attr('style', '');
					var text = obj.attr('class');//parrent_td_option
					if (typeof text != 'undefined') {
						text = text.replace(/parrent_td_option/g, '');
						jQuery('tr.tr_td_option' + text).remove();
						obj.attr('class', '');
					}
				}
			}
		});
		
		jQuery("body").delegate('input.options', 'blur', function() {
			var text = jQuery(this).attr('name');
			var class_tr = jQuery(this).closest('tr').attr('class').replace('tr_td_', 'parrent_td_');
			text = text.replace(/\[options\]\[(.*)\]/g, '[options][' + (parseInt(jQuery('.' + class_tr + ' td input#order_count').val(), 10) - jQuery('input[name="billing[new_fild][name][]"]').length) + '][' + jQuery(this).val() + ']');
			jQuery(this).attr('name', text);
		});
		var fild_pm;
			jQuery("body").delegate('.button#billing','click',function() {
				var obj = jQuery(this).parent().parent();
				fild_pm = '<td>\
						<select multiple="multiple" width="120px" name="billing[new_fild][payment_method][]">\
							<option selected value="0">Все</option>\
							<?php 
								foreach ( $woocommerce->payment_gateways->payment_gateways() as $gateway ) {
									if ( $gateway->enabled != 'yes' ) continue;
									?><option value="<?php echo $gateway->id; ?>" <?php if(isset($value['payment_method']) && in_array($gateway->id, $value['payment_method']) ) echo 'selected';?>><?php echo str_replace("'", "\\'", $gateway->title); ?></option><?php
								} 
							?>\
						</select>\
						</td>' + '<td>\
						<select multiple="multiple" width="120px" name="billing[new_fild][shipping_method][]">\
							<option selected value="0">Все</option>\
							<?php 
								foreach ( $woocommerce->shipping->get_shipping_methods() as $act_id => $shipping ) {
									if ( $shipping->enabled == 'no' ) continue;
									?><option value="<?php echo $act_id; ?>" <?php if(isset($value['shipping_method']) && in_array($act_id, $value['shipping_method']) ) echo 'selected';?>><?php $st = $shipping->title ? $shipping->title: $shipping->method_title; echo str_replace("'", "\\'", $st); ?></option><?php
								} 
							?>\
						</select>\
						</td>';
			var counter = obj.parent().find('tr td input#order_count:last').length ? obj.parent().find('tr td input#order_count:last').val() : 0;
			obj.html('<td><input value="billing_new_fild'+(parseInt(counter,10)+1)+'" type="text" name="billing[new_fild][name][]" /></td><td><input value="" type="text" name="billing[new_fild][label][]" /></td><td><input value="" type="text" name="billing[new_fild][placeholder][]" /></td><td><input type="checkbox" name="billing[new_fild][clear][]" /></td><td><input value="" type="text" name="billing[new_fild][class][]" /></td><td>	Select <input type="radio" value="select" name="billing[new_fild][type][][' + (parseInt(counter, 10) + 1) + ']"><br>Radio <input type="radio" value="radio" name="billing[new_fild][type][][' + (parseInt(counter, 10) + 1) + ']"><br>Checkbox <input type="radio" value="checkbox" name="billing[new_fild][type][][' + (parseInt(counter, 10) + 1) + ']"><br>	Textarea <input type="radio" value="textarea" name="billing[new_fild][type][][' + (parseInt(counter, 10) + 1) + ']"><br>	Text <input type="radio" value="" name="billing[new_fild][type][][' + (parseInt(counter, 10) + 1) + ']" checked="checked"></td><td><input checked type="checkbox" name="billing[new_fild][required][]" /></td><td><input checked type="checkbox" name="billing[new_fild][public][]" /></td>' + fild_pm + '<td><input id="order_count" rel="sort_order" type="hidden" name="billing[new_fild][order][]" value="'+(parseInt(counter,10)+1)+'" /><input type="button" class="button" id="billing_delete" value="Удалить -"/></td>');
				obj.removeClass('nodrop nodrag');
				obj.after('<tr  class="nodrop nodrag"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type="button" class="button" id="billing" value="Добавить +"/></td></tr>');
			});
			jQuery("body").delegate('.button#shipping', 'click',function() {
				var obj = jQuery(this).parent().parent();
				fild_pm = '<td>\
						<select multiple="multiple" width="120px" name="shipping[new_fild][payment_method][]">\
							<option selected value="0">Все</option>\
							<?php 
								foreach ( $woocommerce->payment_gateways->payment_gateways() as $gateway ) {
									if ( $gateway->enabled != 'yes' ) continue;
									?><option value="<?php echo $gateway->id; ?>" <?php if(isset($value['payment_method']) && in_array($gateway->id, $value['payment_method']) ) echo 'selected';?>><?php echo str_replace("'", "\\'", $gateway->title); ?></option><?php
								} 
							?>\
						</select>\
						</td>' + '<td>\
						<select multiple="multiple" width="120px" name="shipping[new_fild][shipping_method][]">\
							<option selected value="0">Все</option>\
							<?php 
								foreach ( $woocommerce->shipping->get_shipping_methods() as $act_id => $shipping ) {
									if ( $shipping->enabled == 'no' ) continue;
									?><option value="<?php echo $act_id; ?>" <?php if(isset($value['shipping_method']) && in_array($act_id, $value['shipping_method']) ) echo 'selected';?>><?php $st = $shipping->title ? $shipping->title: $shipping->method_title; echo str_replace("'", "\\'", $st); ?></option><?php
								}  
							?>\
						</select>\
						</td>';
				var counter = obj.parent().find('tr td input#order_count:last').length ? obj.parent().find('tr td input#order_count:last').val() : 0;
				obj.html('<td><input value="shipping_new_fild'+(parseInt(counter,10)+1)+'" type="text" name="shipping[new_fild][name][]" /></td><td><input value="" type="text" name="shipping[new_fild][label][]" /></td><td><input value="" type="text" name="shipping[new_fild][placeholder][]" /></td><td><input type="checkbox" name="shipping[new_fild][clear][]" /></td><td><input value="" type="text" name="shipping[new_fild][class][]" /></td><td><input checked type="checkbox" name="shipping[new_fild][required][]" /></td><td><input checked type="checkbox" name="shipping[new_fild][public][]" /></td>' + fild_pm + '<td><input id="order_count" rel="sort_order" type="hidden" name="shipping[new_fild][order][]" value="'+(parseInt(counter,10)+1)+'" /><input type="button" class="button" id="billing_delete" value="Удалить -"/></td>');
				obj.removeClass('nodrop nodrag');
				obj.after('<tr  class="nodrop nodrag"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type="button" class="button" id="shipping" value="Добавить +"/></td></tr>');
			});
			jQuery("body").delegate('.button#order', 'click',function() {
				var obj = jQuery(this).parent().parent();
				var counter = obj.parent().find('tr td input#order_count:last').length ? obj.parent().find('tr td input#order_count:last').val() : 0;
				obj.html('<td><input value="order_new_fild'+(parseInt(counter,10)+1)+'" type="text" name="order[new_fild][name][]" /></td><td><input value="" type="text" name="order[new_fild][label][]" /></td><td><input value="" type="text" name="order[new_fild][placeholder][]" /></td><td><input value="" type="text" name="order[new_fild][class][]" /></td><td><input checked type="text" name="order[new_fild][type][]" /></td><td><input checked type="checkbox" name="order[new_fild][public][]" /></td><td><input id="order_count" rel="sort_order" type="hidden" name="order[new_fild][order][]" value="'+(parseInt(counter,10)+1)+'" /><input type="button" class="button" id="billing_delete" value="Удалить -"/></td>');
				obj.removeClass('nodrop nodrag');
				obj.after('<tr  class="nodrop nodrag"><td></td><td></td><td></td><td></td><td></td><td></td><td><input type="button" class="button" id="order" value="Добавить +"/></td></tr>');
			});

			jQuery("body").delegate('.button#billing_delete', 'click',function() {
				var obj = jQuery(this).parent().parent();
				var obj_r = obj.parent();
				obj.remove();
				obj_r.find("tr").each(function(i, e){
					jQuery(e).find("td input#order_count").val(i);
				});
			});
			jQuery(document).ready(function() {
				jQuery(".myTable").tableDnD({
					onDragClass: "sorthelper",
					onDrop: function(table, row) {
						var data = new Object();
						data.data = new Object();
						data.key = jQuery(table).find("tr td input").attr("rel");
						jQuery(row).fadeOut("fast").fadeIn("slow");   
					
						jQuery(table).find("tr").each(function(i, e){
							var id = jQuery(e).find("td input#order_count").attr("id");
							data.data[i] = id;
							jQuery(e).find("td input#order_count").val(i);
						});
					}
				});
			});
			</script>
			<?php } elseif( $_GET["tab"] == 1) { ?>
			<div class="clear"></div>
			<h2 class="woo-nav-tab-wrapper">Дополнительная информация</h2>
			<?php include_once (SAPHALI_PLUGIN_DIR_PATH . 'go_pro.php');  } elseif($_GET["tab"] == 2) { ?>
			<div class="clear"></div>
			<h2 class="woo-nav-tab-wrapper">Число колонок в каталоге товаров и в рубриках</h2>
			<?php include_once (SAPHALI_PLUGIN_DIR_PATH . 'count-column.php'); } ?>
		</div>
		<?php
	}
	function woocommerce_get_customer_meta_fields_saph_ed() {
		$show_fields = apply_filters('woocommerce_customer_meta_fields', array(
			'billing' => array(
				'title' => __('Customer Billing Address', 'woocommerce'),
				'fields' => array(
					'billing_first_name' => array(
							'label' => __('First name', 'woocommerce'),
							'description' => ''
						),
					'billing_last_name' => array(
							'label' => __('Last name', 'woocommerce'),
							'description' => ''
						),
					'billing_company' => array(
							'label' => __('Company', 'woocommerce'),
							'description' => ''
						),
					'billing_address_1' => array(
							'label' => __('Address 1', 'woocommerce'),
							'description' => ''
						),
					'billing_address_2' => array(
							'label' => __('Address 2', 'woocommerce'),
							'description' => ''
						),
					'billing_city' => array(
							'label' => __('Town / City', 'woocommerce'),
							'description' => ''
						),
					'billing_postcode' => array(
							'label' => __('Postcode / ZIP', 'woocommerce'),
							'description' => ''
						),
					'billing_state' => array(
							'label' => __('State / County', 'woocommerce'),
							'description' => __('Country or state code', 'woocommerce'),
						),
					'billing_country' => array(
							'label' => __('Country', 'woocommerce'),
							'description' => __('2 letter Country code', 'woocommerce'),
						),
					'billing_phone' => array(
							'label' => __('Telephone', 'woocommerce'),
							'description' => ''
						),
					'billing_email' => array(
							'label' => __('Email', 'woocommerce'),
							'description' => ''
						)
				)
			),
			'shipping' => array(
				'title' => __('Customer Shipping Address', 'woocommerce'),
				'fields' => array(
					'shipping_first_name' => array(
							'label' => __('First name', 'woocommerce'),
							'description' => ''
						),
					'shipping_last_name' => array(
							'label' => __('Last name', 'woocommerce'),
							'description' => ''
						),
					'shipping_company' => array(
							'label' => __('Company', 'woocommerce'),
							'description' => ''
						),
					'shipping_address_1' => array(
							'label' => __('Address 1', 'woocommerce'),
							'description' => ''
						),
					'shipping_address_2' => array(
							'label' => __('Address 2', 'woocommerce'),
							'description' => ''
						),
					'shipping_city' => array(
							'label' => __('City', 'woocommerce'),
							'description' => ''
						),
					'shipping_postcode' => array(
							'label' => __('Postcode', 'woocommerce'),
							'description' => ''
						),
					'shipping_state' => array(
							'label' => __('State/County', 'woocommerce'),
							'description' => __('State/County or state code', 'woocommerce')
						),
					'shipping_country' => array(
							'label' => __('Country', 'woocommerce'),
							'description' => __('2 letter Country code', 'woocommerce')
						)
				)
			)
		));
		return $show_fields;
	}
	function woocommerce_get_customer_meta_fields_saphali() {
		if(! isset($this->fieldss) )
			$this->fieldss = get_option('woocommerce_saphali_filds_filters');
		$fieldss = $this->fieldss;
		$show_fields = $this->woocommerce_get_customer_meta_fields_saph_ed();

		

		if(is_array($fieldss)) {
			if(is_array($fieldss["billing"])) {
				$billing = array();
				foreach($fieldss["billing"] as $key => $value) {
					if(isset($show_fields["billing"]['fields'][$key])) continue;
					
					foreach($value as $k_post=> $v_post){
									if( 'on' == $v_post  ) {
										$value[$k_post] = true;
									} elseif(in_array($k_post, array('public','clear','required'))) {  $value[$k_post] = false; }
					}
					$billing = array_merge( $billing , array ($key => $value));
				}
			}
			if(is_array($fieldss["shipping"])) {
				$shipping = array();
				foreach($fieldss["shipping"] as $key => $value) {
					if(isset($show_fields["shipping"]['fields'][$key])) continue;
					foreach($value as $k_post=> $v_post){
						if( 'on' == $v_post  ) {
							$value[$k_post] = true;
						} elseif(in_array($k_post, array('public','clear','required'))) {  $value[$k_post] = false; }
					}
					$shipping = array_merge( $shipping , array ($key => $value));
				}
			}
			if(is_array($fieldss["order"])) {
				$orders = array();
				foreach($fieldss["order"] as $key => $value) {
					if(isset($show_fields["order"]['fields'][$key])) continue;
					foreach($value as $k_post=> $v_post){
						if( 'on' == $v_post  ) {
							$value[$k_post] = true;
						} elseif(in_array($k_post, array('public','clear','required'))) {  $value[$k_post] = false; }
					}
					$orders = array_merge( $orders , array ($key => $value));
				}
			}
		}

		if(!isset($show_fields['billing']['title'])) {
			$_show_fields['billing']['title'] = $show_fields['billing']['title'];
		}
			
		  if(isset($billing))
		  $_show_fields['billing'] =   $billing;
		  
		if(!isset($show_fields['shipping']['title'])) {
			$_show_fields['shipping']['title'] = $show_fields['shipping']['title'];
		}
			
		  if(isset($shipping))
		  $_show_fields['shipping'] =   $shipping;
		

		if(isset($show_fields['order']) && !(@is_array($show_fields['order']['fields']))) {
			$_show_fields['order']['title'] = 'Дополнительные поля'; 
		}
		if(isset($orders))
		 $_show_fields['order'] =   $orders;
		if (isset($_show_fields)) {
		return $_show_fields;
	}
		
	}
	function woocommerce_save_customer_meta_fields_saphali( $user_id ) {
		if ( ! current_user_can( 'manage_woocommerce' ) )
			return $columns;

		$show_fields = $this->woocommerce_get_customer_meta_fields_saphali();
		if(!empty($show_fields["billing"])) {
			 $save_fields["billing"]['title'] = __('Customer Billing Address', 'woocommerce');
			 $save_fields["billing"]['fields'] = $show_fields["billing"];
		}
		if(!empty($show_fields["shipping"])) {
			 $save_fields["shipping"]['title'] = __('Customer Shipping Address', 'woocommerce');
			 $save_fields["shipping"]['fields'] = $show_fields["shipping"];
		}
		/* if(!empty($show_fields["order"])) {
			 $save_fields["order"]['title'] = __('Дополнительные поля', 'woocommerce');
			 $save_fields["order"]['fields'] = $show_fields["order"];
		} */
		if(isset($save_fields) && is_array($save_fields))
		foreach( $save_fields as $fieldset )
			foreach( $fieldset['fields'] as $key => $field )
				if ( isset( $_POST[ $key ] ) )
					update_user_meta( $user_id, $key, trim( esc_attr( $_POST[ $key ] ) ) );
	}
	function woocommerce_admin_order_data_after_billing_address_s($order) {
		$billing_data = $this->woocommerce_get_customer_meta_fields_saphali();
		
		echo '<div class="address">';
		if(is_array($billing_data["billing"])) {
		foreach ( $billing_data["billing"] as $key => $field ) : if (isset($field['show']) && !$field['show']) continue;

			 $field_name = '_'.$key;
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) 
			$value_fild = @$order->order_custom_fields[$field_name][0];
			elseif ( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) 
			$value_fild = $order->__get( $key );
			else $value_fild = get_post_meta( $order->get_id(), '_' . $key, true );
			if( $value_fild && in_array($field["type"] , array('select', 'radio')) && isset($field["options"][$value_fild]) ) {
				$value_fild = $field["options"][$value_fild];
			}
			if ( $value_fild && !empty($field['label']) ) echo '<p><strong>'.$field['label'].':</strong> '.$value_fild.'</p>';
			
			endforeach;
		}
		echo '</div>';
	}
	function woocommerce_admin_order_data_after_shipping_address_s($order) {
		$billing_data = $this->woocommerce_get_customer_meta_fields_saphali();
		echo '<div class="address">';
		if(is_array($billing_data["shipping"])) {
		foreach ( $billing_data["shipping"] as $key => $field ) : if (isset($field['show']) && !$field['show']) continue;

			 $field_name = '_'.$key;

			if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) 
			$value_fild = @$order->order_custom_fields[$field_name][0];
			elseif ( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) 
			$value_fild = $order->__get( $key );
			else $value_fild = get_post_meta( $order->get_id(), '_' . $key, true );
			if ( $value_fild && !empty($field['label']) ) echo '<p><strong>'.$field['label'].':</strong> '.$value_fild.'</p>';
			
			endforeach;
		}
		echo '</div>';
	}
	function woocommerce_admin_order_data_after_order_details_s($order) {
		$billing_data = $this->woocommerce_get_customer_meta_fields_saphali();
		echo '<div class="address">';
		if(is_array($billing_data["order"])) {
		foreach ( $billing_data["order"] as $key => $field ) : if (isset($field['show']) && !$field['show']) continue;

			 $field_name = '_'.$key;
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) 
			$value_fild = @$order->order_custom_fields[$field_name][0];
			elseif ( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) 
			$value_fild = $order->__get( $key );
			else $value_fild = get_post_meta( $order->get_id(), '_' . $key, true );
			if ( $value_fild && !empty($field['label']) ) 

			echo '<div class="form-field form-field-wide"><label>'. $field['label']. ':</label> ' . $value_fild.'</div>';
			
			endforeach;
		}
		echo '</div>';
		
	}
	function saphali_custom_override_checkout_fields( $fields ) {
		
		if(! isset($this->fieldss) )
			$this->fieldss = get_option('woocommerce_saphali_filds_filters');
		$fieldss = $this->fieldss;
		
		if(isset($fieldss["billing"]["billing_city"]) && isset($fields["billing"]["billing_city"]["type"]) && $fields["billing"]["billing_city"]["type"] == 'select') {
			if(isset($fieldss["billing"]["billing_city"]["order"]))
			$fields["billing"]["billing_city"]["order"] =  $fieldss["billing"]["billing_city"]["order"];
			$fieldss["billing"]["billing_city"] = $fields["billing"]["billing_city"];
		}
		if(is_array($fieldss)) {
			$fields["billing"] = $fieldss["billing"];
			$fields["shipping"] = $fieldss["shipping"];
			$fields["order"] = $fieldss["order"];
		}
		foreach(array("billing", "shipping", "order") as $v)
		foreach($fields[$v] as $key => $value) {
			if(isset($fields[$v][$key]["order"]))
			$fields[$v][$key]["priority"] = $value["order"]+10;
			if(isset($fields[$v][$key]["label"]))
			$fields[$v][$key]["label"] = __($value["label"], 'woocommerce');
			if(isset($fields[$v][$key]["placeholder"]))
			$fields[$v][$key]["placeholder"] = __( __($value["placeholder"], 'saphali-woocommerce-lite'), 'woocommerce');
		}
		 return $fields;
	}
	function saphali_custom_edit_address_fields( $fields ) {
		global $wp;
		if(! isset($this->fieldss) )
			$this->fieldss = get_option('woocommerce_saphali_filds_filters');
		$fieldss = $this->fieldss;
		if(isset($fieldss["billing"]["billing_city"]) && $fields["billing"]["billing_city"]["type"] == 'select') {
			if(isset($fieldss["billing"]["billing_city"]["order"]))
			$fields["billing"]["billing_city"]["order"] =  $fieldss["billing"]["billing_city"]["order"];
			$fieldss["billing"]["billing_city"] = $fields["billing"]["billing_city"];
		}
		$__fields = array();
		if(is_array($fieldss))
 		$_fields = $fieldss["billing"];
		if( isset($_fields) && is_array($_fields) )
		foreach($_fields as $key => $value) {
			if(str_replace( 'billing_','', $key ) != 'email')
			$__fields[wc_edit_address_i18n( sanitize_key( $wp->query_vars['edit-address'] ), true ) . '_' . str_replace( 'billing_','', $key ) ] = $value;
		}
		$_a_ = array_diff($__fields, $fields);
			if(is_array($_a_) && is_array($fields) ) $fields = (array)$fields + (array)$_a_;
		$v = 'billing';
		foreach($fields as $key => $value) {
			if(isset($fields[$v][$key]["order"]))
			$fields[$v][$key]["priority"] = $value["order"]+10;
			if(isset($fields[$key]["label"]))
			$fields[$key]["label"] = __($value["label"], 'woocommerce');
			if(isset($fields[$key]["placeholder"]))
			$fields[$key]["placeholder"] = __($value["placeholder"], 'woocommerce');
		}
		return $fields;
	}
	function saphali_custom_billing_fields( $fields ) {
		
		if(! isset($this->fieldss) )
			$this->fieldss = get_option('woocommerce_saphali_filds_filters');
		$fieldss = $this->fieldss;
		if(isset($fieldss["billing"]["billing_city"]) && isset($fields["billing_city"]["type"]) && $fields["billing_city"]["type"] == 'select') {
			if(isset($fieldss["billing"]["billing_city"]["order"]))
			$fields["billing_city"]["order"] =  $fieldss["billing"]["billing_city"]["order"];
			$fieldss["billing"]["billing_city"] = $fields["billing_city"];
		}
	
		if(is_array($fieldss))
 		$fields = $fieldss["billing"];
		$v = 'billing';
		foreach($fields as $key => $value) {
			if(isset($fields[$v][$key]["order"]))
			$fields[$v][$key]["priority"] = $value["order"]+10;
			elseif(isset($fields[$key]["order"]))
				$fields[$key]["priority"] = $value["order"]+10;
			if(isset($fields[$key]["label"]))
			$fields[$key]["label"] = __($value["label"], 'woocommerce');
			if(isset($fields[$key]["placeholder"]))
			$fields[$key]["placeholder"] = __($value["placeholder"], 'woocommerce');
			
		}
		// var_dump($fields["billing_city"]);
		return $fields;
	}
	function saphali_custom_shipping_fields( $fields ) {
		if(! isset($this->fieldss) )
			$this->fieldss = get_option('woocommerce_saphali_filds_filters');
		$fieldss = $this->fieldss;
		if(is_array($fieldss))
		$fields = $fieldss["shipping"];
		$v = 'shipping';
		foreach($fields as $key => $value) {
			if(isset($fields[$v][$key]["order"]))
			$fields[$v][$key]["priority"] = $value["order"]+10;
			elseif(isset($fields[$key]["order"]))
				$fields[$key]["priority"] = $value["order"]+10;
			if(isset($fields[$key]["label"]))
			$fields[$key]["label"] = __($value["label"], 'woocommerce');
			if(isset($fields[$key]["placeholder"]))
			$fields[$key]["placeholder"] = __($value["placeholder"], 'woocommerce');
		}
		return $fields;
	}
	public function store_order_id( $arg ) {
		if ( is_int( $arg ) ) $this->email_order_id = $arg;
		elseif ( is_array( $arg ) && array_key_exists( 'order_id', $arg ) ) $this->email_order_id = $arg['order_id'];
	}
	public function email_pickup_location( $template_name, $template_path, $located, $args = array() ) {
		global $_shipping_data, $_billing_data;
		if($template_name == 'emails/email-addresses.php' && isset($args["order"]) && is_object($args["order"]) ) {
			$this->email_order_id  = $args["order"]->get_id();
		}
		
		if ( $template_name == 'emails/email-addresses.php' && $this->email_order_id ) {

			$order = new WC_Order( $this->email_order_id );

			$billing_data = $this->woocommerce_get_customer_meta_fields_saphali();
			echo '<div class="address">';

			if(is_array($billing_data["billing"]) && !$_billing_data) {
				foreach ( $billing_data["billing"] as $key => $field ) : if (isset($field['show']) && !$field['show']) continue;
					$field_name = '_'.$key;
					if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) 
					$value_fild = @$order->order_custom_fields[$field_name][0];
					elseif ( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) 
					$value_fild = $order->__get( $key );
					else $value_fild = get_post_meta( $order->get_id(), '_' . $key, true );
					if( $value_fild && in_array($field["type"] , array('select', 'radio')) && isset($field["options"][$value_fild]) ) {
						$value_fild = $field["options"][$value_fild];
					}
					if ( $value_fild && !empty($field['label']) ) 
					echo '<div class="form-field form-field-wide"><label><strong>'. $field['label']. ':</strong></label> ' . $value_fild.'</div>';
				endforeach;
			}
			if(is_array($billing_data["shipping"]) && !$_shipping_data) {
				foreach ( $billing_data["shipping"] as $key => $field ) : if (isset($field['show']) && !$field['show']) continue;
					$field_name = '_'.$key;
					if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) 
					$value_fild = @$order->order_custom_fields[$field_name][0];
					elseif ( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) 
					$value_fild = $order->__get( $key );
					else $value_fild = get_post_meta( $order->get_id(), '_' . $key, true );
					if ( $value_fild  && !empty($field['label'])) 
					echo '<div class="form-field form-field-wide"><label><strong>'. $field['label']. ':</strong></label> ' . $value_fild.'</div>';
				endforeach;
			}
			if(is_array($billing_data["order"])) {
			foreach ( $billing_data["order"] as $key => $field ) : if (isset($field['show']) && !$field['show']) continue;

				 $field_name = '_'.$key;
				if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) 
					$value_fild = @$order->order_custom_fields[$field_name][0];
				elseif ( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) 
					$value_fild = $order->__get( $key );
				else 
					$value_fild = get_post_meta( $order->get_id(), '_' . $key, true );
				if ( $value_fild && !empty($field['label']) ) 

				echo '<div class="form-field form-field-wide"><label><strong>'. $field['label']. ':</strong></label> ' . $value_fild.'</div>';
				
			endforeach;
			}
			echo '</div>';
		}
	}
	/* function formatted_billing_address($address, $order) {
		global $billing_data, $_billing_data;
		if( empty($billing_data) )
			$billing_data = $this->woocommerce_get_customer_meta_fields_saphali();
		if(is_array($billing_data["billing"])) {
			$_billing_data = true;
			$no_fild = array ('_billing_booking_delivery_t', '_billing_booking_delivery');
			foreach ( $billing_data["billing"] as $key => $field ) : if (isset($field['show']) && !$field['show'] ) continue;
				
				$field_name = '_'.$key;
				
				if(in_array($field_name, $no_fild)) continue;
				if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) 
					$value_fild = @$order->order_custom_fields[$field_name][0];
				else
					$value_fild = $order->__get( $key );
				if ( $value_fild  && !empty($field['label'])) 
				echo  '<label><strong>'. $field['label']. ':</strong></label> ' . $value_fild.'<br />';
			endforeach;
		}
		return $address;
	} 
	function formatted_shipping_address($address, $order) {
	global $billing_data, $_shipping_data;
	if( empty($billing_data) )
		$billing_data = $this->woocommerce_get_customer_meta_fields_saphali();
		if(is_array($billing_data["shipping"])) {
			$_shipping_data = true;
			foreach ( $billing_data["shipping"] as $key => $field ) : if (isset($field['show']) && !$field['show']) continue;
				$field_name = '_'.$key;
				if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) 
					$value_fild = @$order->order_custom_fields[$field_name][0];
				else
					$value_fild = $order->__get( $key );
				if ( $value_fild  && !empty($field['label'])) {
					echo  '<label><strong>'. $field['label']. ':</strong></label> ' . $value_fild.'<br />';
					$address[$key] = $value_fild;
				}
			endforeach;
		}
		return $address;
	}*/
	function order_pickup_location($order_id) {
		global $_billing_data, $_shipping_data;
		$order = new WC_Order( $order_id );
		
		if ( is_object($order) ) {

			$billing_data = $this->woocommerce_get_customer_meta_fields_saphali();

			echo '<div class="address">';

			if(is_array($billing_data["billing"]) && !$_billing_data) {
				foreach ( $billing_data["billing"] as $key => $field ) : if (isset($field['show']) && !$field['show']) continue;
					$field_name = '_'.$key;
					if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) 
					$value_fild = @$order->order_custom_fields[$field_name][0];
					elseif ( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) 
					$value_fild = $order->__get( $key );
					else $value_fild = get_post_meta( $order->get_id(), '_' . $key, true );
					if( $value_fild && in_array($field["type"] , array('select', 'radio')) && isset($field["options"][$value_fild]) ) {
						$value_fild = $field["options"][$value_fild];
					}
					if ( $value_fild  && !empty($field['label'])) 
					echo '<div class="form-field form-field-wide"><label><strong>'. $field['label']. ':</strong></label> ' . $value_fild.'</div>';
				endforeach;
			}
			if(is_array($billing_data["shipping"]) && !$_shipping_data) {
				foreach ( $billing_data["shipping"] as $key => $field ) : if (isset($field['show']) && !$field['show']) continue;
					$field_name = '_'.$key;
					if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) 
					$value_fild = @$order->order_custom_fields[$field_name][0];
					elseif ( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) 
					$value_fild = $order->__get( $key );
					else $value_fild = get_post_meta( $order->get_id(), '_' . $key, true );
					if ( $value_fild  && !empty($field['label']) ) 
					echo '<div class="form-field form-field-wide"><label><strong>'. $field['label']. ':</strong></label> ' . $value_fild.'</div>';
				endforeach;
			}
			if(is_array($billing_data["order"]) ) {
				foreach ( $billing_data["order"] as $key => $field ) : if (isset($field['show']) && !$field['show']) continue;
					$field_name = '_'.$key;
					if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) 
					$value_fild = @$order->order_custom_fields[$field_name][0];
					elseif ( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) 
					$value_fild = $order->__get( $key );
					else $value_fild = get_post_meta( $order->get_id(), '_' . $key, true );
					if ( $value_fild && !empty($field['label']) ) 
					echo '<div class="form-field form-field-wide"><label><strong>'. $field['label']. ':</strong></label> ' . $value_fild.'</div>';
				endforeach;
			}
			echo '</div>';
		}
	}
	function print_columns ($columns) {
		return $this->column_count_saphali;
	}
	function related_print_columns ($columns) {
		if( isset($columns['columns']) ) {
			$columns['columns'] = $this->column_count_saphali;
			$columns['posts_per_page'] = $this->column_count_saphali;
		}
		
		return $columns;
	}
	function print_script_columns($woocommerce_loop) {
		global $woocommerce_loop;
		if($woocommerce_loop['columns'] > 0 && $woocommerce_loop['columns'] != 4) {
		?>
		<style type='text/css'>
		.woocommerce ul.products li.product {
			width:<?php if($woocommerce_loop['columns'] <= 3 ) echo floor(100/$woocommerce_loop['columns'] - $woocommerce_loop['columns']); elseif($woocommerce_loop['columns'] > 3 )echo floor(100/$woocommerce_loop['columns'] - 4);?>%;
		}
		</style>
		<?php
		}
	}
 }

add_action('plugins_loaded', 'woocommerce_lang_s_l', 0);
if ( ! function_exists( 'woocommerce_lang_s_l' ) ) {
	function woocommerce_lang_s_l() {
		$lite = new saphali_lite();
		if( is_admin() )
		add_action( 'admin_enqueue_scripts',  array( $lite, 'admin_enqueue_scripts_page_saphali' ) );
	}
}
//END
add_action("wp_head", '_print_script_columns', 10 );
add_action("admin_head", '_print_script_columns', 10);
function _print_script_columns() {
		if(apply_filters( 'woocommerce_currency', get_option('woocommerce_currency') ) != 'RUB' || !(version_compare( WOOCOMMERCE_VERSION, '2.5.2', '<' ) || SAPHALI_LITE_SYMBOL ) ) return;
		?>
	<style type="text/css">
		/* @font-face { font-family: "Rubl Sign"; src: url(<?php echo SAPHALI_PLUGIN_DIR_URL; ?>ruble.eot); } */
		
		@font-face { font-family: "rub-arial-regular"; src: url("<?php echo SAPHALI_PLUGIN_DIR_URL; ?>ruble-simb.woff"), url("<?php echo SAPHALI_PLUGIN_DIR_URL; ?>ruble-simb.ttf");
		}
		span.rur {
			font-family: rub-arial-regular;
			text-transform: uppercase;
		}
		span.rur span { display: none; }

		/* span.rur { font-family: "Rubl Sign"; text-transform: uppercase;}
		span.rur:before {top: 0.06em;left: 0.55em;content: '\2013'; position: relative;} */
	</style>
		<?php
}


register_activation_hook( __FILE__, 'saphali_woo_lite_install' );

function saphali_woo_lite_install() {
	$filds_finish_filter = get_option('woocommerce_saphali_filds_filters');
	if($filds_finish_filter) {
		foreach($filds_finish_filter['billing'] as $k_f => $v_f) {
			$new_key = str_replace('billing_', '' , $k_f);
			if(in_array($new_key, array('country', 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode' ) )) {
				$locate[$new_key] = $v_f;
				if( isset($locate[$new_key]['clear']) && $locate[$new_key]['clear'] == 'on') $locate[$new_key]['clear'] = true;
				if( isset($locate[$new_key]['required']) && $locate[$new_key]['required'] == 'on') $locate[$new_key]['required'] = true;
			} elseif(in_array(str_replace('shipping_', '' , $k_f), array('country', 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode' ) )) {
				$locate[$new_key] = $filds_finish_filter['shipping'][$k_f];
				if( isset($locate[$new_key]['clear']) && $locate[$new_key]['clear'] == 'on') $locate[$new_key]['clear'] = true;
				if( isset($locate[$new_key]['required']) && $locate[$new_key]['required'] == 'on') $locate[$new_key]['required'] = true;
			}
			
		}
		update_option('woocommerce_saphali_filds_locate',$locate);
	}
	//update_option('woocommerce_informal_localisation_type' , 'yes');
	//global $woocommerce;
	//copy( SAPHALI_PLUGIN_DIR_PATH . '/languages/woocommerce-ru_RU.mo', $woocommerce->plugin_path() .'/i18n/languages/informal/woocommerce-ru_RU.mo');
	//copy( SAPHALI_PLUGIN_DIR_PATH . '/languages/woocommerce-ru_RU.po', $woocommerce->plugin_path() .'/i18n/languages/informal/woocommerce-ru_RU.po');
}