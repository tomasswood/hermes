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

		function save_properties($data)
		{
			if(!empty($data['del_properties']))
				$this->remove_properties($data['del_properties']);
			if(!empty($data['properties']))
			{

				$bind = array();
				foreach($data['properties'] as $rowValues) {
					array_push($bind, $rowValues['id'], $rowValues['name'], $rowValues['header'], $rowValues['order']);
				}
				// Build Query to Insert User
				$query = "
				   INSERT INTO `customproperty` (cp_id, cp_name, cp_header, cp_order) VALUES
				";
				for($i = 0; $i < count($data['properties']); $i++)
				{
					$query .= '(?,?,?,?),';
				}
				$query = rtrim($query, ",");
				$query .= " ON DUPLICATE KEY UPDATE cp_name = VALUES(cp_name), cp_header = VALUES(cp_header), cp_order = VALUES(cp_order) ";
				$this->db->query($query, $bind);
				if($this->db->affected_rows() > 0)
					echo true;
				else
					echo false;
			}
		}

		function remove_properties($delete)
		{
			$query = "
				DELETE FROM `customproperty`
				WHERE cp_id IN (
			";
			for($i = 0; $i < count($delete); $i++)
			{
				$query .= '?,';
			}
			$query = rtrim($query, ",");
			$query .= ")";

			$this->db->query($query, $delete);
			if($this->db->affected_rows() > 0)
				echo true;
			else
				echo false;
		}
	}
/*
 *  SELECT cv_id, cv_value, cv_custompropertyid FROM customvalue
	LEFT JOIN customproperty ON cv_custompropertyid = cv_id
	GROUP BY cv_link, cv_custompropertyid, cv_value, cv_id
	ORDER BY cp_order
 */