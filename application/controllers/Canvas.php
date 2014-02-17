<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Canvas extends MY_Controller {

	public function index()
	{
		$display_type = 'app';
		$header = array('displayType' => $display_type, 'links' => array(
			'<script type="text/javascript" src="' . js_url() . 'page.js"></script>',
			'<script type="text/javascript" src="' . js_doc_url() . 'Page.js"></script>',
			'<script type="text/javascript" src="' . js_doc_url() . 'Sidebar.js"></script>'
		));
		$this->load->view('template/header', $header);
		$this->load->view('canvas');
		//$this->load->view('template/editor-toolbar');
		$this->load->view('template/left-toolbar');
		$this->load->view('template/sidebar');
		$this->load->view('template/footer', array('displayType' => $display_type));
	}
}