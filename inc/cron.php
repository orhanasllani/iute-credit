<?php

// cron schedule 24h / 3
add_filter('cron_schedules', 'cron_add_thrice');

function cron_add_thrice($schedules)
{

	$schedules['thrice'] = array(
		'interval' => 28800,
		'display' => __('Thrice in day')
	);

	$schedules['hh'] = array(
		'interval' => 1800,
		'display' => __('Half hour')
	);

	return $schedules;
}

// update iute params
function get_iute_params()
{

	global $wpdb;

	require_once 'class-wc-iute-crud.php';
	require_once 'class-wc-iute-api.php';

	$crud = new WC_Gateway_Iute_CRUD();

	$table_name = IUTE_TABLE_PARAMS;
	$iute_class = new WC_Gateway_IuteCredit();
	$iute_api_username = $iute_class->get_option('username');
	$iute_api_password = $iute_class->get_option('password');
	$iute_api_agent_id = $iute_class->get_option('agent_id');
	$iute_params = WC_Gateway_Iute_API::iute_params($iute_api_username, $iute_api_password, $iute_api_agent_id);

	// clear table
	$crud->clear($table_name);

	// update params
	if (!empty($iute_params)) {
		foreach ($iute_params as $param) {
			$data = array(
				'product_id' => $param->customProductId,
				'minAmount' => $param->minAmount,
				'maxAmount' => $param->maxAmount,
				'minPeriod' => $param->minPeriod,
				'maxPeriod' => $param->maxPeriod,
				'lastUpdate' => date("Y-m-d H:i:s")
			);

			$crud->insert($table_name, $data);
		}
	}
}

add_action('iute_params_update', 'get_iute_params');

// update application status
function iute_status_update()
{

	require_once 'class-wc-iute-api.php';

	$orders = get_posts([
		'numberposts' => -1,
		'post_type'   => 'shop_order',
		'post_status' => 'any',
		'meta_query'  => [
			'relation' => 'OR',
			[
				'key'     => '_iute_status',
				'value'   => 'IN_PROCESS',
			],
			[
				'key'     => '_iute_status',
				'value'   => 'APPROVED',
			],
			[
				'key'     => '_iute_status',
				'value'   => 'SIGNED',
			],
		]
	]);

	$iute_class = new WC_Gateway_IuteCredit();
	$iute_api_username = $iute_class->get_option('username');
	$iute_api_password = $iute_class->get_option('password');

	foreach ($orders as $key => $post_order) {
		// update order status
		$ref_nr = get_post_meta($post_order->ID, '_iute_number')[0];



		$status = json_decode(WC_Gateway_Iute_API::iute_status($iute_api_username, $iute_api_password, $ref_nr));

		update_post_meta($post_order->ID, '_iute_status', $status->status);

		$order = new WC_Order($post_order->ID);

		if ($status->status == 'APPROVED') {

			$order->update_status('processing');
		} elseif ($status->status == 'REJECTED' || $status->status == 'WITHDRAW') {

			$order->update_status('failed');
		} elseif ($status->status == 'PAID_OUT') {

			$order->update_status('completed');
		}
	}
}

add_action('iute_status_update', 'iute_status_update');
