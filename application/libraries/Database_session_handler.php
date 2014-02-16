<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Database_session_handler
	{
		private $_ci;

		public function __construct()
		{
			$this->_ci =& get_instance();
			$this->_ci->load->database();
		}

		public function _close()
		{
			$this->_ci->db->close();
		}

		function _read($id)
		{
			$query = "SELECT se_data
					  FROM session
					  WHERE se_session = ".$this->_ci->db->escape($id)
			;

			if($result = $this->_ci->db->query($query))
			{
				if($result->num_rows() > 0)
				{
					return $result['se_data'];
				}
			}

			return '';
		}

		function _write($id, $data)
		{
			$query = "REPLACE
                	  INTO session
                	  VALUES (".$this->_ci->db->escape($id).", NOW(), ".$this->_ci->db->escape($data).")"
			;

			return $this->_ci->db->query($query);
		}

		function _destroy($id)
		{
			$query = "DELETE
                	  FROM session
                	  WHERE se_session = ".$this->_ci->db->escape($id)
			;

			return $this->_ci->db->query($query);
		}

		function _clean($max)
		{
			$old = time() - $max;
			$query = "DELETE
                	  FROM session
                	  WHERE se_updated < ".$this->_ci->db->escape($old)
            ;

			return $this->_ci->db->query($query);
		}

		// quick function for killing a users session
		public function killUserSession($username)
		{
			$query = "DELETE
                	  FROM session
                	  WHERE se_data like('%s:10:\"u_username\";s:5:\"" . $this->_ci->db->escape($username) . "%\";%')";

			$this->_ci->db->query($query);
		}
	}