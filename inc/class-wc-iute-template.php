<?php

class WC_Gateway_Iute_Template
{
	public function add_to_cart_before($username, $password, $agent_id, $amount)
	{

		require_once 'class-wc-iute-api.php';

		$iute = new WC_Gateway_Iute_API();

		$base_loan_months = $iute->iute_base_loan($username, $password, $agent_id, $amount);

		$base_loan = $base_loan_months->monthlyRepayments[11]->monthlyRepayment;

		return '<div class="iute-loan"><span>' . __('Kësti Mujor', 'iute-credit') . '</span><span class="iute-loan__price">' . $base_loan . get_woocommerce_currency_symbol() . '</span></div>';
	}

	public function calc($amount = false, $data)
	{

		$options = new WC_Gateway_IuteCredit;

		if (!$amount) {
			return '<span>Error: Cant recive order amount</span>';
		} else {
			ob_start();
			require_once plugin_dir_path(__DIR__) . 'templates/template-calc-checkout.php';
			return ob_get_clean();
		}
	}

	public function add_to_cart_before_single($cost)
	{

		require_once 'class-wc-iute-api.php';

		if (!$cost) {
			return;
		} else {
			ob_start();
			require_once plugin_dir_path(__DIR__) . 'templates/template-calc-single.php';
			return ob_get_clean();
		}
	}

	public function modal()
	{
		global $product;
	
		$priceZ = $product->get_price();
		if ($priceZ > 200000) {
			echo "<script>$('.open-popup').hide();</script>";
		}
		require_once plugin_dir_path(__DIR__) . 'templates/template-modal.php';
	}

	public function calc_camp($products, $disc, $id, $cat)
	{

		if (empty($products)) {
			return;
		}

		$compare = array();

		foreach ($products as $product) {

			array_push($compare, has_term(intval($cat), 'product_cat', $product['product_id']));
		}


		if (in_array(false, $compare)) {

			wp_localize_script(PNAME . '-js', 'iute_compaign', array('hide' => $id));

			wc_clear_notices();
			if (in_array(true, $compare)) {
				wc_add_notice($disc, 'notice');
			}
		}
	}
};
