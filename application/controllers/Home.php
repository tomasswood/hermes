<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function index()
	{
		$display_type = 'app';
		$header = array('displayType' => $display_type, 'links' => array(
			'<script type="text/javascript" src="' . js_doc_url() . 'Entities.js"></script>'
		));
		$this->load->view('template/header', $header);
		$this->load->view('home');
		$this->load->view('template/footer', array('displayType' => $display_type));
	}

	public function get_properties()
	{
		$this->load->model('entity_model');
		return $this->entity_model->load_properties();
	}
}