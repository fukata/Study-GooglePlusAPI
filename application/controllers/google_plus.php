<?php

class Google_plus extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('google_plus_api');
	}

	function index() {
		$this->load->view('google_plus/index');
	}

	function authorize() {
		$this->google_plus_api->authorize();
	}

	/**
	 * callback excample: 
	 * http://g.fukata.org/oauth2callback?code=4/vs-zOWyVIto35N835lLbXg6O9vcD
	 */
	function callback() {
		$data = new stdClass();
		$user_id = '113705111024427296123'; // Your User ID
		$data->code = $this->input->get('code');
		$data->tokens = $this->google_plus_api->access_token($data->code);
		$data->user = $this->google_plus_api->people_userid($user_id, $data->tokens->access_token);
		$this->load->view('google_plus/callback', $data);
	}

}
