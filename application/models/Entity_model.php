<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Entity_model extends CI_Model {

		function __construct()
		{
			parent::__construct();
			$this->load->database();
		}

		function load_properties()
		{
			//$bsid = Businesses::getUserBusinessID();
			$content = null;
			$status = 0;
			$error = "";

			$query = "
			   SELECT * FROM `customproperty`
			   ORDER BY cp_order
			";
			$result = $this->db->query($query);
			if($result->num_rows() > 0)
			{
				$status = 1;
				$content = $result->result_array();
			}
			$data['content'] = $content;
			$data['status'] = $status;
			$data['error'] = $error;
			echo json_encode($data);
		}
	}
/*
 *  SELECT cv_id, cv_value, cv_custompropertyid FROM customvalue
	LEFT JOIN customproperty ON cv_custompropertyid = cv_id
	GROUP BY cv_link, cv_custompropertyid, cv_value, cv_id
	ORDER BY cp_order
 */