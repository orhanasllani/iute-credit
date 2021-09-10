<?php

// order custom field (display in admin)
function iute_display_order_data_in_admin($order)
{
	if (get_post_meta($order->id, '_iute_number')) :
?>
		<div class="form-field iute-order">
			<h3><?php _e('IuteCredit loan status', PNAME); ?></h3>
			<?php
			echo '<p><strong>' . __('Loan status', PNAME) . ': </strong>' . get_post_meta($order->id, '_iute_status', true) . '</p>';
			echo '<p><strong>' . __('Loan number', PNAME) . ': </strong>' . get_post_meta($order->id, '_iute_number', true) . '</p>'; ?>
		</div>
<?php
	endif;
}

add_action('woocommerce_admin_order_data_after_order_details', 'iute_display_order_data_in_admin');

// Iute column in order list
function wc_iute_order_column($columns)
{

	$new_columns = array();

	foreach ($columns as $column_name => $column_info) {

		$new_columns[$column_name] = $column_info;

		if ('order_status' === $column_name) {
			$new_columns['order_iute_status'] = __('IuteCredit status', PNAME);
		}
	}

	return $new_columns;
}

add_filter('manage_edit-shop_order_columns', 'wc_iute_order_column', 20);

function wc_iute_order_column_content($column)
{
	global $post;

	if ('order_iute_status' === $column) {

		echo var_dump(get_post_meta($post->ID, '_iute_status'));
	}
}

add_action('manage_shop_order_posts_custom_column', 'wc_iute_order_column_content');

function woo_save()
{

	do_action('iute_params_update');
	die();
}

add_action('wp_ajax_woo_save', 'woo_save');
