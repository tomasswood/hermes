<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Common
	{
		private $_ci;

		public function __construct()
		{
			$this->_ci =& get_instance();
			// DatabaseSessionHandler class handles database sessions
			$this->_ci->load->library("database_session_handler");
			// Magic Quote Fix for PHP < 5.4
			if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
			{
				function undo_magic_quotes_gpc(&$array)
				{
					foreach($array as &$value)
					{
						if(is_array($value))
						{
							undo_magic_quotes_gpc($value);
						}
						else
						{
							$value = stripslashes($value);
						}
					}
				}

				undo_magic_quotes_gpc($_POST);
				undo_magic_quotes_gpc($_GET);
				undo_magic_quotes_gpc($_COOKIE);
			}
			// Exchange data using UTF-8
			header('Content-Type: text/html; charset=utf-8');
			// Assign directory for serverside storage of sessions data
			//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT']."/tmp/");
			session_start();
			if(empty($_SESSION))
			{
				// Begin session hook
				$sess = new database_session_handler();
				session_set_save_handler(array(&$sess, '_open'),
					array(&$sess, '_close'),
					array(&$sess, '_read'),
					array(&$sess, '_write'),
					array(&$sess, '_destroy'),
					array(&$sess, '_clean'));
				// End session hook
				// start session
			}
		}
	}