<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function is_active($page)
	{
		$a = explode('/',$_SERVER['REQUEST_URI']) ;
		return $a[count($a)-2] == $page ? 'active' : '';
	}

	function asset_url(){
		return base_url().'assets/';
	}

	function js_url(){
		return asset_url().'js/';
	}

	function js_libs_url(){
		return js_url().'libs/';
	}

	function js_pages_url(){
		return js_url().'pages/';
	}

	function js_doc_url(){
		return js_url().'doc/';
	}

	function includes_url(){
		return asset_url().'includes/';
	}

	function images_url(){
		return asset_url().'images/';
	}

	function business_logos_url(){
		return images_url().'business-logos/';
	}

	function user_logos_url(){
		return images_url().'user-logos/';
	}

	function styles_url(){
		return asset_url().'styles/';
	}

	function save_url() {
		return asset_url().'saved/';
	}

	function admin_url(){
		return base_url().'admin/';
	}

	function reporting_url(){
		return base_url().'reporting/';
	}

	function user_url(){
		return base_url().'users/';
	}

	function business_url(){
		return base_url().'business/';
	}

	function booking_url(){
		return base_url().'bookings/';
	}

	function payments_url(){
		return base_url().'payments/';
	}

	function get_gus()
	{
		$_ci =& get_instance();
		$_ci->load->database();
		return $_ci->db->gus;
	}

	function get_gus_user()
	{
		$_ci =& get_instance();
		$_ci->load->database();
		return $_ci->db->gus . ".user";
	}

	function get_gus_cardlink()
	{
		$_ci =& get_instance();
		$_ci->load->database();
		return $_ci->db->gus . ".cardlink";
	}

	function get_gus_card()
	{
		$_ci =& get_instance();
		$_ci->load->database();
		return $_ci->db->gus . ".card";
	}

	function dynamicBindSingle($query, $b)
	{
		// Bind Query Params dynamically
		$query_bind = substr_count($query, "?");
		$bind = array();
		for($i = 0; $i < $query_bind; $i++)
		{
			array_push($bind, $b);
		}
		return $bind;
	}

	function unixTimestampToDateTime($timestamp)
	{
		$date = new DateTime();
		$date->setTimestamp($timestamp);
		return $date;
	}