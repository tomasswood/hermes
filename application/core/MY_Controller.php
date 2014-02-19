<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class MY_Controller extends CI_Controller {

		function __construct()
		{
			parent::__construct();
			if(ENVIRONMENT == 'development')
			{
				//$this->output->enable_profiler(true);
			}

			$this->load->library(array(
				//'common'
				//'biz',
				//'user'
			));
		}
	}