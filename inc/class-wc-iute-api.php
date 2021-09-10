<?php

class WC_Gateway_Iute_API
{


	// get params
	protected static function iute_req($api, $username, $password, $post_data = '')
	{

		$iute = new WC_Gateway_IuteCredit();

		if ($iute->get_option('dev_mode') == 'yes') {
			$api_url = IUTE_API_URL_DEV;
		} else {
			$api_url = IUTE_API_URL;
		}

		$req = curl_init($api_url . $api);

		curl_setopt($req, CURLOPT_USERPWD, $username . ":" . $password);
		curl_setopt($req, CURLOPT_TIMEOUT, 10);

		if (!empty($post_data)) {
			curl_setopt($req, CURLOPT_POST, 1);
			curl_setopt($req, CURLOPT_POSTFIELDS, $post_data);
		}

		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);

		return $req;
	}

	// agent params
	public static function iute_params($username, $password, $agent_id)
	{

		$api = '/rest/public/e-shop/parameters/multi/1.0/country/' . (new WC_Countries)->get_base_country() . '/agent/' . $agent_id;

		$req = self::iute_req($api, $username, $password);

		if (curl_errno($req)) {
			return 'error';
		}

		$res = curl_exec($req);


		$res_info = curl_getinfo($req);

		return json_decode($res)->loanProductParametersResponseList;
	}

	// application request
	public static function iute_application($username, $password, $data)
	{

		if (empty($data)) {
			return 'Error: no data';
		}

		$api = '/rest/e-shop/application/1.0';

		$req = self::iute_req($api, $username, $password, $data);

		if (curl_errno($req)) {
			return 'error';
		}
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLOPT_HTTP200ALIASES, array(400));

		$res = curl_exec($req);

		$res_info = curl_getinfo($req);
		$http_code = curl_getinfo($req, CURLINFO_HTTP_CODE);

		if ($http_code == 400) {
			$return = array(
				'error' => $res,
				'info' => $res_info
			);
		} else {
			$return = array(
				'data' => json_decode($res),
				'info' => $res_info
			);
		}



		return $return;
	}

	// getLoanStatus
	public static function iute_status($username, $password, $ref_nr)
	{
		$api = '/rest/loan/status/1.0/country/' . (new WC_Countries)->get_base_country() . '/refNr/' . $ref_nr;

		$req = self::iute_req($api, $username, $password);

		if (curl_errno($req)) {
			return 'error';
		}

		$res = curl_exec($req);

		return $res;
	}

	// get each product base loan

	public function iute_base_loan($username, $password, $agent_id, $amount)
	{
		$api = '/rest/public/e-shop/minMonthlyRepayment/1.0/country/' . (new WC_Countries)->get_base_country() . '/agent/' . $agent_id . '/amount/' . $amount;

		$req = self::iute_req($api, $username, $password);

		if (curl_errno($req)) {
			return 'error';
		}

		$res = curl_exec($req);

		return json_decode($res);
	}
};
