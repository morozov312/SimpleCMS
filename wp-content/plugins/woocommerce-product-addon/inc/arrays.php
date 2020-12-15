<?php
/**
 * Arrays contining settings/meta detail
 **/
 
if( ! defined('ABSPATH') ) die('Not Allowed.');

function ppom_get_plugin_meta(){

	return array('name'	=> 'PPOM',
				'dir_name'		=> '',
				'shortname'		=> 'nm_personalizedproduct',
				'path'			=> PPOM_PATH,
				'url'			=> PPOM_URL,
				'db_version'	=> 3.12,
				'logo'			=> PPOM_URL . '/images/logo.png',
				'menu_position'	=> 90,
				'ppom_bulkquantity'	=> PPOM_WP_PLUGIN_DIR . '/ppom-addon-bulkquantity/classes/input.bulkquantity.php',
				'ppom_eventcalendar'	=> PPOM_WP_PLUGIN_DIR . '/ppom-addon-eventcalendar/classes/input.eventcalendar.php',
				'ppom_fixedprice'	=> PPOM_WP_PLUGIN_DIR . '/ppom-addon-fixedprice/classes/input.fixedprice.php',
	);
}

// Return cols for inputs
function ppom_get_input_cols() {
	
	$ppom_cols = array(2=>'2 Col',3=>'3 Col', 4=>'4 Col',5=>'5 Col',6=>'6 Col',
				7=>'7 Col',8=>'8 Col',9=>'9 Col',10=>'10 Col',11=>'11 Col',12=>'12 Col');
	
	return apply_filters('ppom_field_cols', $ppom_cols);
}

function ppom_field_visibility_options() {
	
	$visibility_options = array('everyone'	=> __('Everyone'),
								'guests'	=> __('Only Guests'),
								'members'	=> __('Only Members'),
								'roles'		=> __('By Role')
								);
								
	return apply_filters('ppom_field_visibility_options', $visibility_options);
}


function ppom_array_get_regions() {
	
	return array('AFRICA','AMERICA','ANTARCTICA','ASIA','ATLANTIC','AUSTRALIA',
				'EUROPE','INDIAN','PACIFIC');
}

// Get timezone list
function ppom_array_get_timezone_list($selected_regions, $show_time) 
{
	if( $selected_regions == 'All' ) {
	    $regions = array(
	        DateTimeZone::AFRICA,
	        DateTimeZone::AMERICA,
	        DateTimeZone::ANTARCTICA,
	        DateTimeZone::ASIA,
	        DateTimeZone::ATLANTIC,
	        DateTimeZone::AUSTRALIA,
	        DateTimeZone::EUROPE,
	        DateTimeZone::INDIAN,
	        DateTimeZone::PACIFIC,
	    );
	} else {
		$selected_regions = explode(",", $selected_regions);
		$tz_regions = array();
		
		foreach($selected_regions as $region) {
			// var_dump($region);
			switch($region) {
				case 'AFRICA':
					$tz_regions[] = DateTimeZone::AFRICA;
				break;
				case 'AMERICA':
					$tz_regions[] = DateTimeZone::AMERICA;
				break;
				case 'ANTARCTICA':
					$tz_regions[] = DateTimeZone::ANTARCTICA;
				break;
				case 'ASIA':
					$tz_regions[] = DateTimeZone::ASIA;
				break;
				case 'ATLANTIC':
					$tz_regions[] = DateTimeZone::ATLANTIC;
				break;
				case 'AUSTRALIA':
					$tz_regions[] = DateTimeZone::AUSTRALIA;
				break;
				case 'EUROPE':
					$tz_regions[] = DateTimeZone::EUROPE;
				break;
				case 'INDIAN':
					$tz_regions[] = DateTimeZone::INDIAN;
				break;
				case 'PACIFIC':
					$tz_regions[] = DateTimeZone::PACIFIC;
				break;
			}
			
		}
		
		$regions = $tz_regions;
	}
	
	// ppom_pa($regions);

    $timezones = array();
    foreach( $regions as $region )
    {
        $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
    }

    $timezone_offsets = array();
    foreach( $timezones as $timezone )
    {
        $tz = new DateTimeZone($timezone);
        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }

    // sort timezone by timezone name
    ksort($timezone_offsets);

    $timezone_list = array();
    foreach( $timezone_offsets as $timezone => $offset )
    {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate( 'H:i', abs($offset) );

        $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
        
        $t = new DateTimeZone($timezone);
        $c = new DateTime(null, $t);
        $current_time = $c->format('g:i A');

		if( $show_time == 'on' ) {
        	$timezone_list[$timezone] = "(${pretty_offset}) $timezone - $current_time";
		} else {
			$timezone_list[$timezone] = "(${pretty_offset}) $timezone";
		}
    }

    return $timezone_list;
}
// Return Plupload languages
function ppom_get_plupload_languages() {
	
	return array (
								'' => 'Default',
								'ar' => 'AR',
								'az' => 'AZ',
								'bs' => 'BS',
								'cs' => 'CS',
								'cy' => 'CY',
								'da' => 'DA',
								'de' => 'DE',
								'el' => 'EL',
								'en' => 'EN',
								'es' => 'ES',
								'et' => 'ET',
								'fa' => 'FA',
								'fi' => 'FI',
								'fr' => 'FR',
								'he' => 'HE',
								'hr' => 'HR',
								'hu' => 'HU',
								'hy' => 'HY',
								'id' => 'ID',
								'it' => 'IT',
								'ja' => 'JA',
								'ka' => 'KA',
								'kk' => 'KK',
								'km' => 'KM',
								'ko' => 'KO',
								'lt' => 'LT',
								'lv' => 'LV',
								'mn' => 'MN',
								'ms' => 'MS',
								'nl' => 'NL',
								'pl' => 'PL',
								'pt_BR' => 'PT_BR',
								'ro' => 'RO',
								'ru' => 'RU',
								'sk' => 'SK',
								'sq' => 'SQ',
								'sr' => 'SR',
								'sr_RS' => 'SR_RS',
								'sv' => 'SV',
								'th_TH' => 'TH_TH',
								'tr' => 'TR',
								'uk_UA' => 'UK_UA',
								'zh_CN' => 'ZH_CN',
								'zh_TW' => 'ZH_TW',
						);
}

// PPOM Settings
function ppom_array_settings() {
	
	$v18_info_url = 'https://najeebmedia.com/blog/ppom-version-18-0-better-price-manipulation-currency-switcher/';
	$more_price_details = '<a target="_blank" href="'.esc_attr($v18_info_url).'">More Details<a>';
	// ppom_pa(ppom_get_all_editable_roles());
	
	$ppom_settings = array(
       
		array(
			'title' => 'PPOM Labels',
			'type'  => 'title',
			'desc'	=> __('You can add your own Labels for PPOM Like Price Table etc'),
			'id'    => 'ppom_labels_settings',
		),
		
		array(
            'title'		=> __( 'Option Total', 'ppom' ),
            'type'		=> 'text',
            'desc'		=> __( 'Label For Price Table', 'ppom' ),
            'default'	=> __('Option Total', 'ppom'),
            'id'		=> 'ppom_label_option_total',
            'css'   	=> 'min-width:300px;',
			'desc_tip'	=> true,
        ),
        array(
            'title'		=> __( 'Product Price', 'ppom' ),
            'type'		=> 'text',
            'desc'		=> __( 'Label For Price Table', 'ppom' ),
            'default'	=> __('Product Price', 'ppom'),
            'id'		=> 'ppom_label_product_price',
            'css'   	=> 'min-width:300px;',
			'desc_tip'	=> true,
        ),
        array(
            'title'		=> __( 'Total', 'ppom' ),
            'type'		=> 'text',
            'desc'		=> __( 'Label For Price Table', 'ppom' ),
            'default'	=> __('Total', 'ppom'),
            'id'		=> 'ppom_label_total',
            'css'   	=> 'min-width:300px;',
			'desc_tip'	=> true,
        ),
        array(
            'title'		=> __( 'Fixed Fee', 'ppom' ),
            'type'		=> 'text',
            'desc'		=> __( 'Label For Price Table', 'ppom' ),
            'default'	=> __('Fixed Fee', 'ppom'),
            'id'		=> 'ppom_label_fixed_fee',
            'css'   	=> 'min-width:300px;',
			'desc_tip'	=> true,
        ),
        array(
            'title'		=> __( 'Discount Price', 'ppom' ),
            'type'		=> 'text',
            'desc'		=> __( 'Label For Price Table', 'ppom' ),
            'default'	=> __('Discount Price', 'ppom'),
            'id'		=> 'ppom_label_discount_price',
            'css'   	=> 'min-width:300px;',
			'desc_tip'	=> true,
        ),
        array(
            'title'		=> __( 'Total Discount', 'ppom' ),
            'type'		=> 'text',
            'desc'		=> __( 'Label For Price Table', 'ppom' ),
            'default'	=> __('Total Discount', 'ppom'),
            'id'		=> 'ppom_label_total_discount',
            'css'   	=> 'min-width:300px;',
			'desc_tip'	=> true,
        ),
        
        array(
				'title'          => __( 'Disable Bootstrap', 'ppom' ),
				'type'          => 'checkbox',
				'label'         => __( 'Yes', 'ppom' ),
				'default'       => 'no',
				'id'            => 'ppom_disable_bootstrap',
				'desc'          => __( 'Bootstrap JS is being loaded from CDN, it will disable if your site already loading it.', 'ppom' ),
			),
			
		array(
				'title'          => __( 'Disable FontAwesome', 'ppom' ),
				'type'          => 'checkbox',
				'label'         => __( 'Yes', 'ppom' ),
				'default'       => 'no',
				'id'            => 'ppom_disable_fontawesome',
				'desc'          => __( 'FontAwesome are being loaded from CDN, it will disable if your site already loading it.', 'ppom' ),
			),
		array(
				'title'          => __( 'Enable Legacy Price Calculations', 'ppom' ),
				'type'          => 'checkbox',
				'label'         => __( 'Yes', 'ppom' ),
				'default'       => 'no',
				'id'            => 'ppom_legacy_price',
				'desc'          => __( 'Yes. '.$more_price_details, 'ppom' ),
			),
		array(
				'title'         => __('PPOM Permissions', 'ppom' ),
				'type'          => 'ppom_multi_select',
				'label'         => __('Button', 'ppom' ),
				'default'       => 'administrator',
				'placeholder'   =>'choose role',
				'options'		=> ppom_get_all_editable_roles(),
				'id'            => 'ppom_permission_mfields',
				'desc'          => __( 'You can set permissions here so PPOM fields can be managed by different roles', 'ppom' ),
				'desc_tip'      => true,
			),
        
	    array(
			'type' => 'sectionend',
			'id'   => 'ppom_labels_settings',
		),
			
		array(
            'name'     => __( 'Advance Features (PRO)', 'ppom' ),
            'type'     => 'title',
            'desc'     => __('These options will work when PRO version is installed', 'ppom'),
            'id'       => 'ppom_pro_features'
        ),
        
        array(
				'title'          => __( 'Hide Product Price?', 'ppom' ),
				'type'          => 'checkbox',
				'label'         => __( 'Yes', 'ppom' ),
				'default'       => 'no',
				'id'            => 'ppom_hide_product_price',
				'desc'          => __( 'Hides Product core price under price Title (When PPOM Fields attached)', 'ppom' ),
				
			),
		
		array(
				'title'          => __( 'Hide Variable Product Price?', 'ppom' ),
				'type'          => 'checkbox',
				'label'         => __( 'Yes', 'ppom' ),
				'default'       => 'no',
				'id'            => 'ppom_hide_variable_product_price',
				'desc'          => __( 'Hides Variable Product core price under price Title (When PPOM Fields attached)', 'ppom' ),
				
			),
			
			
		array(
				'title'          => __( 'Hide Options Price?', 'ppom' ),
				'type'          => 'checkbox',
				'label'         => __( 'Yes', 'ppom' ),
				'default'       => 'no',
				'id'            => 'ppom_hide_option_price',
				'desc'          => __( 'Hides options price in Selec/Radio/Checkbox/Image display prices with label', 'ppom' ),
				
			),
			
		array(
				'title'          => __( 'Clear Fields after Add to Cart?', 'ppom' ),
				'type'          => 'checkbox',
				'label'         => __( 'Yes', 'ppom' ),
				'default'       => 'no',
				'id'            => 'ppom_hide_clear_fields',
				'desc'          => __( 'Empty all fields on Product page after to cart.', 'ppom' ),
				
			),
			
		array(
				'title'          => __( 'Enable PPOM REST API?', 'ppom' ),
				'type'          => 'checkbox',
				'label'         => __( 'Yes', 'ppom' ),
				'default'       => 'no',
				'id'            => 'ppom_api_enable',
				'desc'          => __( 'Check this option to enable PPOM REST API', 'ppom' ),
			),
			
		array(
			'title'          => __( 'Use optimized Price Table Caculation (BETA)', 'ppom' ),
			'type'          => 'checkbox',
			'label'         => __( 'Yes', 'ppom' ),
			'default'       => 'no',
			'id'            => 'ppom_price_table_v2',
			'desc'          => __( 'A Fast and Optimized script to caculate price on product page in Table.', 'ppom' ),
		),
		
		array(
			'title'          => __( 'Enable New Conditional Logic Script', 'ppom' ),
			'type'          => 'checkbox',
			'label'         => __( 'Yes', 'ppom' ),
			'default'       => 'no',
			'id'            => 'ppom_new_conditions',
			'desc'          => __( 'A faster approach to load conditional fields. Beta version, please report bug in new conditional script.', 'ppom' ),
		),
		
		array(
			'title'          => __( 'Do not send Product Meta to PayPal Invoice', 'ppom' ),
			'type'          => 'checkbox',
			'label'         => __( 'Yes', 'ppom' ),
			'default'       => 'no',
			'id'            => 'ppom_disable_meta_paypal_invoice',
			'desc'          => __( 'Product meta will not be sent to PayPal invoice, only the Item name will be sent to invoice', 'ppom' ),
		),
		
		array(
            'title'		=> __( 'Select Option Label', 'ppom' ),
            'type'		=> 'text',
            'desc'		=> __( 'Label For Price Table', 'ppom' ),
            'default'	=> __('Select Options', 'ppom'),
            'id'		=> 'ppom_label_select_option',
            'css'   	=> 'min-width:300px;',
			'desc_tip'	=> true,
        ),
			
			
		array(
            'title' => __( 'PPOM API Secret Key', 'ppom' ),
            'type' => 'text',
            'desc' => __( 'Enter any characters to create a secret key. This key must be set while requesting to API', 'ppom' ),
            'id'   => 'ppom_rest_secret_key',
            'css'   		=> 'min-width:300px;',
			'desc_tip'		=> true,
        ),
        
        array(
                'title'             => __( 'Delete Un-used images', 'ppom' ),
                'type'              => 'select',
                'label'             => __( 'Button', 'ppom' ),
                'default'           => 'daily',
                'options' => array( 'daily'=>__('Daily','ppom'),
                                    'weekly'=> __('Weekly','ppom'),
                                    'monthly'=> __('Monthly','ppom'),
                                ),
                'id'       => 'ppom_remove_unused_images_schedule',
                'desc'       => __( 'Set duration to uploaded images of abandoned cart. Re-activate plugin to when update this option', 'ppom' ),
                'desc_tip'      => true,
            ),
            
        array(
                'title'             => __( 'Meta Group Overrides', 'ppom' ),
                'type'              => 'select',
                'label'             => __( 'Button', 'ppom' ),
                'default'           => 'default',
                'options' => array( 'default'=>__('Default','ppom'),
                                    'category_override'=> __('Category Overrides Individual Assignment','ppom'),
                                    'individual_override'=> __('Individual Overrides Category Assignment','ppom'),
                                ),
                'id'       => 'ppom_meta_overrides',
                'desc'       => __( 'Leave if default if not sure.', 'ppom' ),
                'desc_tip'      => true,
        ),
        
        array(
                'title'             => __( 'Meta Group Priority', 'ppom' ),
                'type'              => 'select',
                'label'             => __( 'Button', 'ppom' ),
                'default'           => 'default',
                'options' => array( 'category_first'=>__('Category First','ppom'),
                                    'individual_first'=> __('Individual First','ppom'),
                                ),
                'id'       => 'ppom_meta_priority',
                'desc'       => __( 'Leave if default if not sure.', 'ppom' ),
                'desc_tip'      => true,
            ),
            
    	array(
                'title'             => __( 'Price Table Position', 'ppom' ),
                'type'              => 'select',
                'label'             => __( 'Button', 'ppom' ),
                'default'           => 'after',
                'options' => array( 'after'=>__('After PPOM Fields','ppom'),
                                    'before'=> __('Before  PPOM Fields','ppom'),
                                ),
                'id'       => 'ppom_price_table_location',
                'desc'       => __( 'Set the location to render Price Table on Front-end', 'ppom' ),
                'desc_tip'      => true,
            ),
        
        array(
			'type' => 'sectionend',
			'id'   => 'ppom_pro_features',
		),
        
		);
		
	return apply_filters('ppom_settings_data', $ppom_settings);
}

/**
 * PPOM Field Meta Array
 * */
function ppom_array_fields_meta() {
	
	$ppom_meta = array(
						'text'	=> array(	'title' => __ ( 'Text Input', 'ppom' ),
										'type'	=> 'text',
										'desc'	=>__ ( 'HTML Form Text input', 'ppom' ),  
										'icon'	=> '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',
										'meta'	=> array (
											'title' 		=> ppom_array_field_title('text'),
											'data_name' 	=> ppom_array_field_data_name('text'),
											'description'	=> ppom_array_field_description('text'),
											'placeholder'	=> ppom_array_field_placeholder('text'),
											'error_message' => ppom_array_field_error_message('text'),
											'maxlength' 	=> ppom_array_field_maxlength('text'),
											'minlength' 	=> ppom_array_field_minlength('text'),
											'default_value' => ppom_array_field_default_value('text'),
											'class' 		=> ppom_array_field_class('text'),
											'input_mask'	=> ppom_array_field_input_mask('text'),
											'width' 		=> ppom_array_field_width('text'),
											'visibility'	=> ppom_array_field_visibility('text'),
											'visibility_role' => ppom_array_field_visibility_role('text'),
											'required'		=> ppom_array_field_required('text'),
											'desc_tooltip'	=> ppom_array_field_desc_tooltip('text'),
											'logic' 		=> ppom_array_field_logic('text'),
											'conditions'	=> ppom_array_field_conditions('text'),
										),
									),
						'textarea'	=> array(	'title' => __ ( 'Textarea', 'ppom' ),
												'type'	=> 'textarea',
												'desc'	=>__ ( 'HTML Form Textarea input', 'ppom' ),  
												'icon'	=> '<i class="fa fa-file-text-o" aria-hidden="true"></i>',
												'meta'	=> array (
													'title' 		=> ppom_array_field_title('textarea'),
													'data_name' 	=> ppom_array_field_data_name('textarea'),
													'description'	=> ppom_array_field_description('textarea'),
													'placeholder'	=> ppom_array_field_placeholder('textarea'),
													'error_message' => ppom_array_field_error_message('textarea'),
													'max_length' 	=> ppom_array_field_maxlength('textarea'),
													'rows'			=> ppom_array_field_rows('textarea'),
													'default_value' => ppom_array_field_default_value('textarea'),
													'class' 		=> ppom_array_field_class('textarea'),
													'width' 		=> ppom_array_field_width('textarea'),
													'visibility'	=> ppom_array_field_visibility('textarea'),
													'visibility_role' => ppom_array_field_visibility_role('textarea'),
													'required'		=> ppom_array_field_required('textarea'),
													'rich_editor'	=> ppom_array_field_rich_editor('textarea'),
													'desc_tooltip'	=> ppom_array_field_desc_tooltip('textarea'),
													'logic' 		=> ppom_array_field_logic('textarea'),
													'conditions'	=> ppom_array_field_conditions('textarea'),
											),
										),
						'select'	=> array(	'title' => __ ( 'Select', 'ppom' ),
											'type'	=> 'select',
											'desc'	=>__ ( 'HTML Form Select DropDown', 'ppom' ),  
											'icon'	=> '<i class="fa fa-file-text-o" aria-hidden="true"></i>',
											'meta'	=> array (
												'title' 		=> ppom_array_field_title('textarea'),
												'data_name' 	=> ppom_array_field_data_name('textarea'),
												'description'	=> ppom_array_field_description('textarea'),
												'error_message' => ppom_array_field_error_message('textarea'),
												'options' 		=> ppom_array_field_options('textarea'),
												'selected'		=> ppom_array_field_selected('textarea'),
												'first_option'	=> ppom_array_field_first_option('textarea'),
												'class' 		=> ppom_array_field_class('textarea'),
												'width' 		=> ppom_array_field_width('textarea'),
												'visibility'	=> ppom_array_field_visibility('textarea'),
												'visibility_role' => ppom_array_field_visibility_role('textarea'),
												'required'		=> ppom_array_field_required('textarea'),
												'onetime'		=> ppom_array_field_onetime('textarea'),
												'onetime_taxable'=> ppom_array_field_onetime_taxable('textarea'),
												'desc_tooltip'	=> ppom_array_field_desc_tooltip('textarea'),
												'logic' 		=> ppom_array_field_logic('textarea'),
												'conditions'	=> ppom_array_field_conditions('textarea'),
										),
									),
						);
						
						
	return apply_filters('ppom_field_meta_array', $ppom_meta);
}

// PPOM Field Meta Helpers
function ppom_array_field_title($type) {
	
	$title = array (
					'type' => 'text',
					'title' => __ ( 'Title', 'ppom' ),
					'desc' => __ ( 'It will be shown as field label', 'ppom' ) 
					);
			
	return apply_filters('ppom_field_meta_title', $title, $type);
}

function ppom_array_field_data_name($type) {
	
	$data_name = array (
					'type' => 'text',
					'title' => __ ( 'Data name', 'ppom' ),
					'desc' => __ ( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'ppom' ) 
					);
			
	return apply_filters('ppom_field_meta_data_name', $data_name, $type);
}

function ppom_array_field_description($type) {
	
	$description = array (
						'type' => 'textarea',
						'title' => __ ( 'Description', 'ppom' ),
						'desc' => __ ( 'Small description, it will be display near name title.', 'ppom' ) 
					);
			
	return apply_filters('ppom_field_meta_description', $description, $type);
}

function ppom_array_field_placeholder($type) {
	
	$placeholder = array (
						'type' => 'text',
						'title' => __ ( 'Placeholder', 'ppom' ),
						'desc' => __ ( 'Optionally placeholder.', 'ppom' ) 
						);
			
	return apply_filters('ppom_field_meta_placeholder', $placeholder, $type);
}

function ppom_array_field_error_message($type) {
	
	$error_message = array (
						'type' => 'text',
						'title' => __ ( 'Error message', 'ppom' ),
						'desc' => __ ( 'Insert the error message for validation.', 'ppom' ) 
						);
			
	return apply_filters('ppom_field_meta_error_message', $error_message, $type);
}

function ppom_array_field_maxlength($type) {
	
	$maxlength = array (
						'type' => 'text',
						'title' => __ ( 'Max. Length', 'ppom' ),
						'desc' => __ ( 'Max. characters allowed, leave blank for default', 'ppom' )
						);
		
	return apply_filters('ppom_field_meta_maxlength', $maxlength, $type);
}

function ppom_array_field_minlength($type) {
	
	$minlength = array (
						'type' => 'text',
						'title' => __ ( 'Min. Length', 'ppom' ),
						'desc' => __ ( 'Min. characters allowed, leave blank for default', 'ppom' )
						);
			
	return apply_filters('ppom_field_meta_minlength', $minlength, $type);
}

function ppom_array_field_default_value($type) {
	
	$default_value = array (
						'type' => 'text',
						'title' => __ ( 'Set default value', 'ppom' ),
						'desc' => __ ( 'Pre-defined value for text input', 'ppom' )
						);
			
	return apply_filters('ppom_field_meta_default_value', $default_value, $type);
}

function ppom_array_field_class($type) {
	
	$class = array (
					'type' => 'text',
					'title' => __ ( 'Class', 'ppom' ),
					'desc' => __ ( 'Insert an additional class(es) (separateb by comma) for more personalization.', 'ppom' ) 
					);
			
	return apply_filters('ppom_field_meta_class', $class, $type);
}

function ppom_array_field_input_mask($type) {
	
	$input_mask = array (
					'type' => 'text',
					'title' => __ ( 'Input Masking', 'ppom' ),
					'desc' => __ ( 'Click options to see all Masking Options', 'ppom' ),
					'link' => __ ( '<a href="https://github.com/RobinHerbots/Inputmask" target="_blank">Options</a>', 'ppom' ) 
					);
			
	return apply_filters('ppom_field_meta_input_mask', $input_mask, $type);
}

function ppom_array_field_width($type) {
	
	$width = array (
					'type' => 'select',
					'title' => __ ( 'Width', 'ppom' ),
					'desc' => __ ( 'Type field width in % e.g: 50%', "ppom"),
					'options'	=> ppom_get_input_cols(),
					'default'	=> 12,
					);
			
	return apply_filters('ppom_field_meta_width', $width, $type);
}

function ppom_array_field_visibility($type) {
	
	$visibility = array (
					'type' => 'select',
					'title' => __ ( 'Visibility', 'ppom' ),
					'desc' => __ ( 'Set field visibility based on user.', "ppom"),
					'options'	=> ppom_field_visibility_options(),
					'default'	=> 'everyone',
					);
			
	return apply_filters('ppom_field_meta_visibility', $visibility, $type);
}

function ppom_array_field_visibility_role($type) {
	
	$visibility_role = array (
							'type' => 'text',
							'title' => __ ( 'User Roles', 'ppom' ),
							'desc' => __ ( 'Role separated by comma.', "ppom"),
							'hidden' => true,
							);
			
	return apply_filters('ppom_field_meta_visibility_role', $visibility_role, $type);
}

function ppom_array_field_required($type) {
	
	$required = array (
					'type' => 'checkbox',
					'title' => __ ( 'Required', 'ppom' ),
					'desc' => __ ( 'Select this if it must be required.', 'ppom' ) 
					);
			
	return apply_filters('ppom_field_meta_required', $required, $type);
}

function ppom_array_field_desc_tooltip($type) {
	
	$desc_tooltip = array (
							'type' => 'checkbox',
							'title' => __ ( 'Show tooltip (PRO)', 'ppom' ),
							'desc' => __ ( 'Show Description in Tooltip with Help Icon', 'ppom' )
							);
			
	return apply_filters('ppom_field_meta_desc_tooltip', $desc_tooltip, $type);
}

function ppom_array_field_logic($type) {
	
	$logic = array (
					'type' => 'checkbox',
					'title' => __ ( 'Enable Conditions', 'ppom' ),
					'desc' => __ ( 'Tick it to turn conditional logic to work below', 'ppom' )
					);
			
	return apply_filters('ppom_field_meta_logic', $logic, $type);
}

function ppom_array_field_conditions($type) {
	
	$conditions = array (
						'type' => 'html-conditions',
						'title' => __ ( 'Conditions', 'ppom' ),
						'desc' => __ ( 'Tick it to turn conditional logic to work below', 'ppom' )
						);
			
	return apply_filters('ppom_field_meta_conditions', $conditions, $type);
}

function ppom_array_field_rows($type) {
	
	$rows = array (
					'type' => 'text',
					'title' => __ ( 'Rows', "ppom" ),
					'desc' => __ ( 'e.g: 3', "ppom" )
				);
			
	return apply_filters('ppom_field_meta_rows', $rows, $type);
}

function ppom_array_field_rich_editor($type) {
	
	$rich_editor = array (
					'type' => 'checkbox',
					'title' => __ ( 'Rich Editor', "ppom" ),
					'desc' => __ ( 'Enable WordPress rich editor.', "ppom" ),
					'link' => __ ( '<a target="_blank" href="https://codex.wordpress.org/Function_Reference/wp_editor">Editor</a>', 'ppom' ) 
					);
			
	return apply_filters('ppom_field_meta_rich_editor', $rich_editor, $type);
}

function ppom_array_field_options($type) {
	
	$options = array (
					'type' => 'paired',
					'title' => __ ( 'Add options', 'ppom' ),
					'desc' => __ ( 'Type option with price (optionally), Option ID should be unique and without spaces.', 'ppom' )
					);
					
	return apply_filters('ppom_field_meta_options', $options, $type);
}

function ppom_array_field_selected($type) {
	
	$selected = array (
					'type' => 'text',
					'title' => __ ( 'Selected option', 'ppom' ),
					'desc' => __ ( 'Type option name given in (Add Options) tab if you want already selected.', 'ppom' ) 
					);
					
	return apply_filters('ppom_field_meta_selected', $selected, $type);
}

function ppom_array_field_first_option($type) {
	
	$first_option = array (
					'type' => 'text',
					'title' => __ ( 'First option', 'ppom' ),
					'desc' => __ ( 'DropDown First Option e.g Select Value', 'ppom' ) 
					);
					
	return apply_filters('ppom_field_meta_first_option', $first_option, $type);
}

function ppom_array_field_onetime($type) {
	
	$onetime = array (
					'type' => 'checkbox',
					'title' => __ ( 'Fixed Fee', 'ppom' ),
					'desc' => __ ( 'Add one time fee to cart total.', 'ppom' ) 
					);
					
	return apply_filters('ppom_field_meta_onetime', $onetime, $type);
}

function ppom_array_field_onetime_taxable($type) {
	
	$onetime_taxable = array (
							'type' => 'checkbox',
							'title' => __ ( 'Fixed Fee Taxable?', 'ppom' ),
							'desc' => __ ( 'Calculate Tax for Fixed Fee', 'ppom' ) 
							);
					
	return apply_filters('ppom_field_meta_onetime_taxable', $onetime_taxable, $type);
}

function ppom_array_get_js_input_vars( $product, $args = null ) {
	
	$defaults = array (
 		'wc_no_decimal' 		=> wc_get_price_decimals(),
 		'show_price_per_unit'	=> false,
	);
	
	// Parse incoming $args into an array and merge it with $defaults
	$args					= wp_parse_args( $args, $defaults );
	$decimal_palces 		= $args['wc_no_decimal'];
	$show_price_per_unit	= $args['show_price_per_unit'];
	
	$product_id 		= ppom_get_product_id($product);
	$ppom				= new PPOM_Meta( $product_id );
	$ppom_meta_settings = $ppom->ppom_settings;
    $ppom_meta_fields	= $ppom->fields;
	
	if( !empty($ppom_id) ) {
		$ppom_meta_fields	= $ppom->get_fields_by_id($ppom_id);
		$ppom_meta_settings	= $ppom->get_settings_by_id($ppom_id);
	}
	
	$ppom_meta_fields_updated = array();
	foreach ($ppom_meta_fields as $index => $fields_meta) {
		
		$type			= isset($fields_meta['type']) ? $fields_meta['type'] : '';
		$title			= ( isset($fields_meta['title']) ? $fields_meta ['title'] : '');
		$data_name		= ( isset($fields_meta['data_name']) ? $fields_meta ['data_name'] : $title);
		
		$fields_meta['data_name']		= sanitize_key( $data_name );
		$fields_meta['title']			= stripslashes($title);
		
		$fields_meta['field_type'] = apply_filters('ppom_js_fields', $type, $fields_meta);

		// Some field specific settings
		switch( $type ) {
		
			case 'daterange':
				// Check if value is in GET 
				if( !empty($_GET[$data_name]) ) {
					
					$value = $_GET[$data_name];
					$to_dates = explode(' - ', $value);
					$fields_meta['start_date'] = $to_dates[0];
					$fields_meta['end_date'] = $to_dates[0];
				}
	        break;
	        
	        case 'color':
	        	// Check if value is in GET 
				if( !empty($_GET[$data_name]) ) {
					
					$fields_meta['default_color'] = $_GET[$data_name];
				}
			break;
			
			case 'bulkquantity':
					
				$fields_meta['options'] = stripslashes($fields_meta['options']);
				
				// To make bulkquantity option WOOCS ready
				$bulkquantities_options = json_decode($fields_meta['options'], true);
				$bulkquantities_new_options = array();
				foreach($bulkquantities_options as $bq_opt) {
					$bq_array = array();
					foreach($bq_opt as $key => $value) {
						
						if( $key != 'Quantity Range' ) {
							$bq_array[$key] = apply_filters('ppom_option_price', $value);
						} else {
							$bq_array[$key] = $value;
						}
					}
					$bulkquantities_new_options[] = $bq_array;
				}
				
				$fields_meta['options'] = json_encode($bulkquantities_new_options);
			break;
		}
		
		$ppom_meta_fields_updated[] = $fields_meta;
		 
	}
	
	
	$js_vars = array();
	$js_vars['ajaxurl'] 		= admin_url( 'admin-ajax.php', (is_ssl() ? 'https' : 'http') );
	$js_vars['ppom_inputs'] 	= $ppom_meta_fields_updated;
	$js_vars['field_meta'] 		= $ppom_meta_fields_updated;
	$js_vars['ppom_validate_nonce'] = wp_create_nonce( 'ppom_validating_action' );
	$js_vars['wc_thousand_sep']	= wc_get_price_thousand_separator();
	$js_vars['wc_currency_pos']	= get_option( 'woocommerce_currency_pos' );
	$js_vars['wc_decimal_sep']	= get_option('woocommerce_price_decimal_sep');
	$js_vars['wc_no_decimal']	= $decimal_palces;
	$variation_id = '';
	$context		= 'product';
	$js_vars['wc_product_price']= ppom_get_product_price($product, $variation_id, $context);
	$js_vars['wc_product_regular_price']= ppom_get_product_regular_price($product);
	$ppom_label_discount_price = ppom_get_option('ppom_label_discount_price', __( 'Discount Price', 'ppom' ));
	$ppom_label_product_price = ppom_get_option('ppom_label_product_price', __( 'Product Price', 'ppom' ));
	$ppom_label_option_total = ppom_get_option('ppom_label_option_total', __( 'Option Total', 'ppom' ));
	$ppom_label_fixed_fee = ppom_get_option('ppom_label_fixed_fee', __( 'Fixed Fee', 'ppom' ));
	$ppom_label_total_discount = ppom_get_option('ppom_label_total_discount', __( 'Total Discount', 'ppom' ));
	$ppom_label_total = ppom_get_option('ppom_label_total', __( 'Total', 'ppom' ));
	$js_vars['total_discount_label']	= sprintf(__("%s", 'ppom'), $ppom_label_total_discount);
	$js_vars['price_matrix_heading']	= sprintf(__("%s", 'ppom'), $ppom_label_discount_price);
	$js_vars['product_base_label']	= sprintf(__("%s", 'ppom'), $ppom_label_product_price);
	$js_vars['option_total_label']	= sprintf(__("%s", 'ppom'), $ppom_label_option_total);
	$js_vars['fixed_fee_heading']	= sprintf(__("%s", 'ppom'), $ppom_label_fixed_fee);
	$js_vars['total_without_fixed_label']	= sprintf(__("%s", 'ppom'), $ppom_label_total);
	$js_vars['product_quantity_label'] = __("Product Quantity", "ppom");
	$js_vars['product_title'] = sprintf(__("%s", "ppom"), $product->get_title());
	$js_vars['per_unit_label'] = __("unit", "ppom");
	$js_vars['show_price_per_unit'] = $show_price_per_unit;
	$js_vars['text_quantity'] = __("Quantity","ppom");
	$js_vars['show_option_price'] =  $ppom->price_display;
	$js_vars['is_shortcode'] = 'no';
	$js_vars['plugin_url'] = PPOM_URL;
	$js_vars['is_mobile'] = ppom_is_mobile();
	$js_vars['product_id'] = $product_id;
	$js_vars['tax_prefix'] = ppom_tax_label_display();
	
	return apply_filters('ppom_input_vars', $js_vars, $product);
}

/* @since 20.5*/
// Showing Tax prefix
function ppom_tax_label_display() {
	if ( wc_tax_enabled() && 'excl' === get_option( 'woocommerce_tax_display_shop' ) &&  get_option( 'woocommerce_price_display_suffix' ) !== '' ) {
		return sprintf(__("%s", 'ppom'), get_option( 'woocommerce_price_display_suffix' ));
	}
}