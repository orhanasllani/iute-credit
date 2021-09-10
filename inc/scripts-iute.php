<?php

function iute_scripts()
{
	$iute = new WC_Gateway_IuteCredit();

	// css
	wp_enqueue_style(PNAME, 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css');
	//	wp_enqueue_style(PNAME . '-style', plugin_dir_url(__DIR__) . 'assets/dist/css/app.css');

	// js
	wp_deregister_script('jquery');

	wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js');
	wp_enqueue_script('jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js', array('jquery'), '1.0.0',);
	wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js');

	wp_enqueue_script(PNAME . '-js', plugin_dir_url(__DIR__) . 'assets/dist/js/app-min.js', array('jquery'), '1.0.0', true);

	$country = new WC_Countries;
	wp_localize_script(PNAME . '-js', 'iute_params', array(

		'api_url' => IUTE_API_URL,
		'country' => $country->get_base_country(),
		'currency' => get_woocommerce_currency(),
		'agent_id' => $iute->get_option('agent_id'),
		'use_api' => $iute->get_option('use_api'),

	));
}

add_action('wp_enqueue_scripts', 'iute_scripts', 100);

function admin_style()
{
	// admin css
	wp_enqueue_style(PNAME . '-admin-styles', plugin_dir_url(__DIR__) . 'assets/dist/css/iute-admin.css');

	// admin js
	wp_enqueue_script(PNAME . 'admin-script', plugin_dir_url(__DIR__) . 'assets/dist/js/admin.js', array('jquery'), null, true);

	wp_localize_script(PNAME . 'admin-script', 'iute', array(
		'url' => admin_url('admin-ajax.php')
	));
}

add_action('admin_enqueue_scripts', 'admin_style');
