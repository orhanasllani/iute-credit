<?php

// before add to cart button
function iute_before_add_to_card()
{
	$product = wc_get_product(get_the_ID());

	$iute_templates = new WC_Gateway_Iute_Template();

	echo $iute_templates->add_to_cart_before_single($product->get_price());
};

function iute_before_button_loop($add_to_cart_html)
{
	$product = wc_get_product(get_the_ID());

	$iute_params = new WC_Gateway_IuteCredit();

	$username = $iute_params->get_option('username');
	$password = $iute_params->get_option('password');
	$agent_id = $iute_params->get_option('agent_id');

	$iute_templates = new WC_Gateway_Iute_Template();

	$base_loan_template = $iute_templates->add_to_cart_before(
		$username,
		$password,
		$agent_id,
		$product->get_price()
	);

	// $base_loan_template = 200;

	return $base_loan_template . $add_to_cart_html;
};


function iuteModal()
{
	$iute_templates = new WC_Gateway_Iute_Template();
	$iute_templates->modal();
}

function bli_me_keste_button()
{

	echo "<a href='" . get_permalink() . "/?bli_me_keste=true' class='button bli_me_keste'>Bli messss këste</a>";
}

function iuteButton()
{
	global $product;
	echo '<span class="sku" style="display:none;" >' . $product->get_sku() . '</span>';
	echo '<button type="button" class="btn btn-danger btn-lg open-popup" data-bs-toggle="modal" data-bs-target="#iute-modal">Bli me keste</button>';
}


add_action('init', function () {
	$options = new WC_Gateway_IuteCredit;

	//if ($options->get_option('monthly_payment_box') == 'yes') {
	// add_action('woocommerce_after_add_to_cart_button', 'iute_before_add_to_card', 1);
	add_action('woocommerce_after_add_to_cart_button', 'iute_before_button_loop', 10, 3);
	//add_action('woocommerce_after_shop_loop_item', 'bli_me_keste_button', 8);
	add_action('woocommerce_after_add_to_cart_form', 'iuteButton', 1);
	add_action('woocommerce_after_add_to_cart_form', 'iuteModal');

	//}
}, 10, 0);
