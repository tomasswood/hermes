<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function index()
	{
		$display_type = 'app';
		$header = array('displayType' => $display_type, 'links' => array(
			'<script type="text/javascript" src="' . js_doc_url() . 'Entities.js"></script>'
		));

		$this->load->model('entity_model');
		$types = $this->entity_model->load_customtypes();

		$this->load->view('template/header', $header);
		$this->load->view('home', array('types' => $types));
		$this->load->view('template/footer', array('displayType' => $display_type));
	}

	public function get_properties($typeid)
	{
		$this->load->model('entity_model');
		return $this->entity_model->load_properties($typeid);
	}

	public function save_properties()
	{
		if($this->input->post())
		{
			$properties = $this->input->post('properties_data', true);
			$this->load->model('entity_model');
			$this->entity_model->save_properties($properties);
		};
	}

	public function get_values($typeid)
	{
		$this->load->model('entity_model');
		return $this->entity_model->load_values($typeid);
	}
}