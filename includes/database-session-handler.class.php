<?php
	include_once("sessdb.class.php");
	// includes db handler for sessions

	class DatabaseSessionHandler
	{
		private $db;

		//_open _read _write _destroy and _clean all hook into phps default session handling
		public function _open($save_path, $session_name)
		{
			$this->db = new sessdb();

			return true;
		}

		public function _close()
		{
			$this->db->close();
		}

		function _read($id)
		{
			$id = mysql_real_escape_string($id);
			$query = "SELECT se_data
                FROM session
                WHERE se_session = '$id'";
			if($result = $this->db->executeQuery($query))
			{
				if(mysql_num_rows($result))
				{
					$record = mysql_fetch_assoc($result);

					return $record['se_data'];
				}
			}

			return '';
		}

		function _write($id, $data)
		{
			$id = mysql_real_escape_string($id);
			$data = mysql_real_escape_string($data);
			$query = "REPLACE
                INTO session
                VALUES ('$id', NOW(), '$data')";

			return $this->db->executeQuery($query);
		}

		function _destroy($id)
		{
			$id = mysql_real_escape_string($id);
			$query = "DELETE
                FROM session
                WHERE se_session = '$id'";

			return $this->db->executeQuery($query);
		}

		function _clean($max)
		{
			$old = time() - $max;
			$old = mysql_real_escape_string($old);
			$query = "DELETE
                FROM session
                WHERE se_updated < '$old'";

			return $this->db->executeQuery($query);
		}

		// quick function for killing a users session
		public function killUserSession($username)
		{
			$query = "DELETE
                FROM session 
                WHERE se_data like('%s:10:\"u_username\";s:5:\"" . mysql_real_escape_string($username) . "%\";%')";
			$this->db->executeQuery($query);
		}
	}

?>

