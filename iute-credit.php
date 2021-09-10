<?php

/*
Plugin Name: IuteCredit Payment
Plugin URI: 
Description: IuteCredit payment for WooCommerce
Version: 1.0.0
Author: Orhan Asllani
Author URI: mailto:asllaniorhan6@gmail.com
License: MIT
License URI: https://opensource.org/licenses/MIT
Text Domain: iutecredit-payment
Domain Path: /assets/lang
Requires PHP: 5.4
*/

global $wpdb;
define('IUTE_API_URL_DEV', 'http://test.iute.tripledev.ee:7101');
define('IUTE_API_URL', 'https://api.iutecredit.com');
define('PNAME', 'iute-credit');
define('IUTE_TABLE_PARAMS', $wpdb->prefix . "wc_iute_params");

require_once 'inc/class-wc-iute-template.php';
require_once 'inc/class-wc-iute-crud.php';
require_once 'inc/admin.php';
require_once 'inc/front.php';
require_once 'inc/cron.php';
require_once 'inc/scripts-iute.php';

// activation
function iute_activate()
{

	global $wpdb;
	$table_name = IUTE_TABLE_PARAMS;
	$charset_collate = $wpdb->get_charset_collate();

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

		$sql = "CREATE TABLE $table_name (
		`product_id` INT NOT NULL,
		`minAmount` FLOAT NOT NULL,
		`maxAmount` FLOAT NOT NULL,
		`minPeriod` FLOAT NOT NULL,
		`maxPeriod` FLOAT NOT NULL,
		`lastUpdate` TIMESTAMP NOT NULL
		); 
		$charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	// CRON
	wp_clear_scheduled_hook('iute_params_update');
	wp_clear_scheduled_hook('iute_status_update');

	// update loan params three times in day
	wp_schedule_event(time(), 'thrice', 'iute_params_update');

	// update application status 
	wp_schedule_event(time(), 'hh', 'iute_status_update');
}

register_activation_hook(__FILE__, 'iute_activate');

// deactivation
function iute_deactivate()
{
	wp_clear_scheduled_hook('iute_params_update');
	wp_clear_scheduled_hook('iute_status_update');
}

register_deactivation_hook(__FILE__, 'iute_deactivate');

// plugin loading
function iute_load()
{

	global $wpdb;

	class WC_Gateway_IuteCredit extends WC_Payment_Gateway
	{

		public $country;

		function __construct()
		{
			$country = new WC_Countries;
			$this->country = $country->get_base_country();
			$this->id = PNAME;
			$this->has_fields = true;
			$this->order_button_text = __('Apliko', PNAME);
			$this->method_title      = __('IuteCredit', PNAME);
			$this->method_description = __('Iute Credit is the fastest and the most comfortable
				loan provider', PNAME);
			$this->supports = array(
				'products'
			);

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			// Define user set variables.
			$this->title = $this->get_option('title');
			$this->username = $this->get_option('username');
			$this->password = $this->get_option('password');
			$this->agent_id = $this->get_option('agent_id');
			$this->monthly_payment_box  = $this->get_option('monthly_payment_box');
			$this->info_total = $this->get_option('info_total');
			$this->info_commision = $this->get_option('info_commision');
			$this->info_admin_fee = $this->get_option('info_admin_fee');
			$this->info_interest_cost = $this->get_option('info_interest_cost');
			$this->info_apr = $this->get_option('info_apr');
			$this->info_xirr = $this->get_option('info_xirr');
			$this->info_patr = $this->get_option('info_patr');
			$this->info_bank = $this->get_option('info_bank');
			$this->user_agree = $this->get_option('user_agree');
			$this->unique_campaign_cat = $this->get_option('unique_campaign_cat');
			$this->unique_campaign_id = $this->get_option('unique_campaign_id');
			$this->unique_campaign_disc = $this->get_option('unique_campaign_disc');
			$this->email = $this->get_option('email');
			$this->email_admin = $this->get_option('email_admin');
			$this->email_user = $this->get_option('email_user');
			$this->dev_mode = $this->get_option('dev_mode');
			$this->form_fields = include 'inc/settings-iute.php';

			if (!$this->is_valid_for_use()) {
				$this->enabled = 'no';
			};

			add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		}

		public function is_valid_for_use()
		{
			return in_array(
				$this->country,
				apply_filters(
					'woocommerce_iute_supported_countries',
					array('AL', 'MD', 'MK', 'XK', 'BH', 'BG')
				),
				true
			);
		}

		public function admin_options()
		{
			if ($this->is_valid_for_use()) {
				parent::admin_options();
			} else {
				$textGatewayDisabled = esc_html_e('Gateway disabled', 'woocommerce');
				$textIuteNotSupported = esc_html_e('IuteCredit does not support your store location.', PNAME);
				echo "<div class='inline error'><p><strong>{$textGatewayDisabled}</strong>: {$textIuteNotSupported}</p></div>";
			}
		}

		// if order total in avaliable price range
		public function is_available()
		{
			$is_available = true;
			$iute_params = WC_Gateway_Iute_CRUD::get(IUTE_TABLE_PARAMS);
			$total = $this->get_order_total();

			foreach ($iute_params as $param) {

				if ($param->minAmount > $total or $param->maxAmount < $total) {
					$is_available = false;
				}
			}

			return $is_available;
		}

		public function payment_fields()
		{


			if ($this->description) {
				echo wpautop(wp_kses_post($this->description));
			}

			echo '<fieldset id="wc-' . esc_attr($this->id) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';

			// Add this action hook if you want your custom payment gateway to support it
			do_action('woocommerce_credit_card_form_start', $this->id);

			require_once 'inc/class-wc-iute-template.php';

			$data = WC()->customer->data['billing'];

			$data['email'] = WC()->customer->data['email'];


			$newTemplate = new WC_Gateway_Iute_Template;
			$newTemplate->calc_camp(
				WC()->cart->get_cart(),
				$this->get_option('unique_campaign_disc'),
				$this->get_option('unique_campaign_id'),
				$this->get_option('unique_campaign_cat')
			);

			echo $newTemplate->calc(WC_Payment_Gateway::get_order_total(), $data);

			do_action('woocommerce_credit_card_form_end', $this->id);

			echo '<div class="clear"></div></fieldset>';
		}

		public function validate_fields()
		{

			$consent = ($_POST['iute_appl_agree'] == 'on') ? true : false;

			// check required fields
			if (empty(trim($_POST['loan_amount']))) {
				wc_add_notice(__('Please select loan type', PNAME), 'error');
				return false;
			}

			if (empty(trim($_POST['loan_period']))) {
				wc_add_notice(__('Please select loan type', PNAME), 'error');
				return false;
			}

			if (empty(trim($_POST['iute_appl_fname']))) {
				wc_add_notice(__('Application First Name is required', PNAME), 'error');
				return false;
			}

			if (empty(trim($_POST['iute_appl_lname']))) {
				wc_add_notice(__('Application Last Name is required', PNAME), 'error');
				return false;
			}

			if (empty(trim($_POST['iute_appl_email']))) {
				wc_add_notice(__('Application Email is required', PNAME), 'error');
				return false;
			}

			if (empty(trim($_POST['iute_appl_phone']))) {
				wc_add_notice(__('Application Phone is required', PNAME), 'error');
				return false;
			}

			if (empty(trim($_POST['iute_appl_idnp']))) {
				wc_add_notice(__('Application IDNP is required', PNAME), 'error');
				return false;
			}

			if (empty(trim($_POST['iute_appl_bdate']))) {
				wc_add_notice(__('Application Birth Date is required', PNAME), 'error');
				return false;
			}

			if (!$consent) {
				wc_add_notice(__('Please apply user agreement', PNAME), 'error');
				return false;
			}
		}

		public function process_payment($order_id)
		{

			require_once 'inc/class-wc-iute-api.php';

			global $woocommerce;
			$order = new WC_Order($order_id);

			$data = array(
				'agentId' => $this->get_option('agent_id'),
				'productId' => $_POST['iute_appl_pr_id'],
				'amount' => $_POST['loan_amount'],
				'period' => $_POST['loan_period'],
				'firstName' => $_POST['iute_appl_fname'],
				'lastName' => $_POST['iute_appl_lname'],
				'idnp' => $_POST['iute_appl_idnp'],
				'email' => $_POST['iute_appl_email'],
				'code' => $order_id,
				'phoneNr' => $_POST['iute_appl_phone'],
				'birthDate' => $_POST['iute_appl_bdate'],
				'patronymic' => $_POST['iute_appl_patronymic'],
				'country' => $this->country,
				'consent' => ($_POST['iute_appl_agree'] == 'on') ? true : false,
				'loanSigning' => $_POST['iute_appl_sign'],
				'bankAccountNr' => $_POST['iute_appl_bank_nr']
			);

			$req_loan = WC_Gateway_Iute_API::iute_application(
				$this->get_option('username'),
				$this->get_option('password'),
				$data
			);


			if (is_null($req_loan['data'])) {
				if ($req_loan['error']) {
					wc_add_notice('ERROR: IuteCredit [' . $req_loan['error'] . ']', 'error');
					return;
				} else {
					wc_add_notice('ERROR: IuteCredit not responding', 'error');
					return;
				}
			}

			if ($req_loan['data']->responseCode == 'ERROR') {
				wc_add_notice('ERROR: ' . $req_loan['data']->description, 'error');
				return;
			}

			if ($req_loan['data']->responseCode != 'SUCCESS') {
				wc_add_notice('IuteCredit - ' . $req_loan['data']->message, 'error');
				return;
			}

			// DEBUG
			// echo 'message:' . json_encode($req_loan['data']);
			// echo 'info:' . $req_loan['info'];
			// return;

			// Mark as on-hold (we're awaiting the cheque), and set Iute status
			$order->update_status('on-hold', __('Awaiting approving', PNAME));
			$order->update_meta_data('_iute_status', 'IN_PROCESS');

			// Iute loan number
			$order->update_meta_data('_iute_number', $req_loan['data']->refNr);

			// save order
			$order->save();

			// Remove cart
			$woocommerce->cart->empty_cart();

			// Return thankyou redirect
			return array(
				'result' => 'success',
				'redirect' => $this->get_return_url($order)
			);
		}
	}

	// update iute params
	if (empty($wpdb->get_row("SELECT * FROM " . IUTE_TABLE_PARAMS))) {
		do_action('iute_params_update');
	}

	// localization
	load_plugin_textdomain(PNAME, false, dirname(plugin_basename(__FILE__)) . '/languages/');
};

add_action('plugins_loaded', 'iute_load');

function add_iutecredit_class($methods)
{
	$methods[] = new WC_Gateway_IuteCredit;
	return $methods;
}

add_filter('woocommerce_payment_gateways', 'add_iutecredit_class');


function create_custom_order($sku)
{

	global $woocommerce;
	$address = array(
		'first_name' => $_POST['emer'],
		'last_name'  => $_POST['mbiemer'],
		'company'    => 'TBD',
		'email'      => $_POST['email'],
		'phone'      => $_POST['phone'],
		'address_1'  => $_POST['address'],
		'address_2'  => 'To be decided',
		'city'       => $_POST['city'],
		'state'      => 'AL',
		'postcode'   => '1001',
		'country'    => 'AL'
	);
	$note = '';
	$orderStatus = "processing";
	if (!empty($_POST['parapagimi'])) {
		$parapagimValue = $_POST['parapagimi'];
		$note = 'Shuma e parapagimit: ' . $parapagimValue;
		$orderStatus = "prepay";
	} else {
		$note = 'Pa parapagim.';
		$orderStatus = "processing";
	}
	// Now we create the order
	global $product;

	$order = wc_create_order();
	$orderProductSKU = $product->get_sku();
	$orderProductId = wc_get_product_id_by_sku($sku);

	//$orderProductId = get_the_ID();
	// The add_product() function below is located in /plugins/woocommerce/includes/abstracts/abstract_wc_order.php
	$order->add_product(wc_get_product($orderProductId)); // This is an existing SIMPLE product
	$order->set_payment_method('iute-credit');
	$order->set_payment_method_title('Paguaj me Këste');
	$order->set_address($address, 'billing');
	$order->add_order_note($note);
	//
	$order->calculate_totals();
	$order->update_status($orderStatus, 'Imported order', TRUE);
	//wp_die(var_dump($orderProductId, $orderProductSKU));
}
