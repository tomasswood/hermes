<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Entity_model extends CI_Model {

		function __construct()
		{
			parent::__construct();
			$this->load->database();
		}

		function load_properties()
		{
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
				// Build Query to Insert/Update Property
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

		function load_values()
		{
			$content = null;
			$status = 0;
			$error = "";

			$query = "
				SELECT cv_id, COALESCE(cv_value, '') AS cv_value, cp_id, cl_id
				FROM customproperty
				LEFT JOIN customvalue ON cv_custompropertyid = cp_id
				INNER JOIN customlink ON cv_linkid = cl_id
				GROUP BY cv_linkid, cp_id, cv_value, cv_id
				ORDER BY cl_timestamp, cl_id, cp_order
			";
			$result = $this->db->query($query);
			if($result->num_rows() > 0)
			{
				$status = 1;
				$values_list = $result->result_array();
			}

			$content = array(); // Data to return
			$entity = array(); // The current entity row we are building
			$link = 0; // What unique link we are on
			// Loop through all of the values we have
			foreach($values_list as $v)
			{
				// Check if we are on a new set of values
				if($link != $v['cl_id'] && $link != 0)
				{
					array_push($content, $entity); // Push our entity values onto our data to return
					$entity = array(); // Empty our entity values array
				}

				// Push the Property ID and the Value onto our entity
				array_push($entity, array(
					'cp_id' => $v['cp_id'],
					'value' => $v['cv_value']
				));

				$link = $v['cl_id']; // Set the link we were just on
			}
			// Check if our Final entity isn't null
			if(!empty($entity))
				array_push($content, $entity);

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