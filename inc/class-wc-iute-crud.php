<?php

class WC_Gateway_Iute_CRUD
{

	// clear table
	public function clear($table)
	{
		global $wpdb;

		$wpdb->query("TRUNCATE TABLE $table");
	}

	// insert
	public function insert($table, $data)
	{
		global $wpdb;

		$wpdb->insert(
			$table,
			$data
		);
	}

	// get
	public static function get($table)
	{
		global $wpdb;

		return $wpdb->get_results("SELECT * FROM $table");
	}
}
