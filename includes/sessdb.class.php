<?php
	class sessdb
	{
		private $db;
		private $hostname;
		private $username;
		private $password;
		private $schema;

		function __construct()
		{
			if(func_num_args() == 0)
			{
				$this->hostname = DB_HOST;
				$this->username = DB_USER;
				$this->password = DB_PASS;
				$this->schema = DB_NAME;
			}
			else
			{
				$params = func_get_args();
				$this->hostname = $params[0];
				$this->username = $params[1];
				$this->password = $params[2];
				$this->schema = $params[3];
			}
		}

		private function open()
		{
			$this->db = mysql_connect($this->hostname, $this->username, $this->password) or die ('Error connecting to mysql');
			mysql_select_db($this->schema, $this->db);
		}

		public function executeQuery($query)
		{
			$this->open();
			$results = mysql_query($query, $this->db) or die ("Error in query: $query. " . mysql_error());

			return $results;
		}

		public function close()
		{
			mysql_close($this->db);
		}
	}

?>