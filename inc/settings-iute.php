<?php
/**
 * Settings for IuteCredit Gateway.
 *
 * @package WooCommerce/Classes/Payment
 */

defined( 'ABSPATH' ) || exit;

foreach (get_terms('product_cat') as $value) {
	$unique_campaign[$value->term_id] = $value->name;
}

if (!empty($unique_campaign)) {
	$unique_campaign = array('disable' => __( 'Disable', PNAME )) + $unique_campaign;
} else {
	$unique_campaign = array('disable' => __( 'Product category not found', PNAME ));
}

return array(
	'enabled' => array(
		'title'   => __( 'Enable IuteCredit', PNAME ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable/Disable', PNAME ),
		'default' => 'no',
	),
	'title' => array(
		'title'       => __( 'Title', PNAME ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', PNAME ),
		'default'     => __( 'IuteCredit', PNAME ),
		'desc_tip'    => true,
	),
	'username' => array(
		'title'       => __( 'Iute auth: Username', PNAME ),
		'type'        => 'text',
		'desc_tip'    => true,
	),
	'password' => array(
		'title'       => __( 'Iute auth: Password', PNAME ),
		'type'        => 'text',
		'desc_tip'    => true,
	),
	'agent_id' => array(
		'title'       => __( 'Iute auth: Agent ID', PNAME ),
		'type'        => 'number',
		'desc_tip'    => true,
	),
	'monthly_payment_box' => array(
		'title'       => __( 'Monthly Payment box', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'type'        => 'checkbox',
		'description' => __( 'Displaying "Monthly Payment box" before product "Add to cart" form', PNAME ),
		'desc_tip'    => true,
	),
	'info_total' => array(
		'title'       => __( 'Displaying "Total"', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'type'        => 'checkbox',
		'description' => __( 'Displaying "Total" info in loan summary', PNAME ),
		'desc_tip'    => true,
	),
	'info_commision' => array(
		'title'       => __( 'Displaying "Commision"', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'type'        => 'checkbox',
		'description' => __( 'Displaying "Commision" info in loan summary', PNAME ),
		'desc_tip'    => true,
	),
	'info_admin_fee' => array(
		'title'       => __( 'Displaying "Admin fee"', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'type'        => 'checkbox',
		'description' => __( 'Displaying "Admin fee" info in loan summary', PNAME ),
		'desc_tip'    => true,
	),
	'info_interest_cost' => array(
		'title'       => __( 'Displaying "Interest cost"', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'type'        => 'checkbox',
		'description' => __( 'Displaying "Interest cost" info in loan summary', PNAME ),
		'desc_tip'    => true,
	),
	'info_apr' => array(
		'title'       => __( 'Displaying "APR %"', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'type'        => 'checkbox',
		'description' => __( 'Displaying "APR %" info in loan summary', PNAME ),
		'desc_tip'    => true,
	),
	'info_xirr' => array(
		'title'       => __( 'Displaying "XIRR %"', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'type'        => 'checkbox',
		'description' => __( 'Displaying "XIRR %" info in loan summary', PNAME ),
		'desc_tip'    => true,
	),
	'info_patr' => array(
		'title'       => __( 'Displaying "Patronymic"', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'type'        => 'checkbox',
		'description' => __( 'Displaying "Patronymic" field in loan form', PNAME ),
		'desc_tip'    => true,
	),
	'info_bank' => array(
		'title'       => __( 'Displaying "Bank account number"', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'type'        => 'checkbox',
		'description' => __( 'Displaying "Bank account number" field in loan form', PNAME ),
		'desc_tip'    => true,
	),
    'use_api' => array(
        'title'       => __( 'Use api for calculation', PNAME ),
        'label' 	  => __( 'Enable/Disable', PNAME ),
        'type'        => 'checkbox',
        'description' => __( 'Use api for calculation Monthly payment', PNAME ),
        'desc_tip'    => true,
    ),
	'user_agree' => array(
		'title'       => __( 'User agreement text', PNAME ),
		'type'        => 'text'
	),
	'unique_campaign_cat' => array(
		'title'       => __( 'Unique Campaign Category', PNAME ),
		'type'        => 'select',
		'description' => __( 'Unique Campaign', PNAME ),
		'desc_tip'    => true,
		'options'	  => $unique_campaign
	),
	'unique_campaign_id' => array(
		'title'       => __( 'Unique Campaign ID', PNAME ),
		'type'        => 'number',
		'description' => __( 'Unique Campaign', PNAME ),
		'desc_tip'    => true
	),
	'unique_campaign_disc' => array(
		'title'       => __( 'Unique Campaign Disclaimer ', PNAME ),
		'type'        => 'textarea',
		'description' => __( 'Unique Campaign', PNAME ),
		'desc_tip'    => true
	),
	'email' => array(
		'title'       => __( 'Email notification', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'type'        => 'checkbox'
	),
	'email_admin' => array(
		'title'       => __( 'Email: Admin address', PNAME ),
		'type'        => 'text',
		'default'	  => get_option('admin_email')
	),
	'email_user' => array(
		'title'       => __( 'Email: notify user', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'type'        => 'checkbox'
	),
	'dev_mode' => array(
		'title'       => __( 'Developer mode', PNAME ),
		'label' 	  => __( 'Enable/Disable', PNAME ),
		'description' => __( 'Use test servers' ),
		'type'        => 'checkbox'
	),

);