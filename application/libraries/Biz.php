<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Businesses
	{
		private $_ci;
		public $current = null;
		public $business = array();

		// --------------------------------------------
		// Default Constructor
		// --------------------------------------------
		function __construct($refresh = false)
		{
			$this->_ci =& get_instance();
			$this->_ci->load->database();

			/*
			 * Re-fetch everything from the Database and Sync to Session
			 */
			if($refresh)
			{
				$this->syncSessionFromDB();
				$this->syncCurrent($this->getCurrentID());
			}
			else
			{
				/*
				 * Pull all Business info from SESSION
				 */
				if(isset($_SESSION['businesses']))
				{
					if(isset($_SESSION['businesses']['business']))
						$this->business = $this->populateBusinessesFromRows($_SESSION['businesses']['business']);

					if(isset($_SESSION['businesses']['current']))
						$this->current = $_SESSION['businesses']['current'];
				}
				/*
				 * Businesses data yet to be fetched. Pull from DB.
				 */
				if(User::isOnline() && !isset($_SESSION['businesses']['business'])) // TODO: Tidy this up
				{
					$this->syncSessionFromDB();
				}
			}
		}

		private function syncSessionFromDB()
		{
			// Setup Object
			$this->business = $this->populateBusinessesFromRows($this->fetchAll());
			// Setup SESSION
			$_SESSION['businesses']['business'] = $this->objectsToArray($this->business);
		}

		##################################################################################################
		# CURRENT - SET, GET, FETCH
		##################################################################################################
		public function syncCurrent($input)
		{
			$match = false;
			unset($_SESSION['businesses']['current']);
			if(isset($_SESSION['businesses']) && $input != 'home')
			{
				/*
				 * Iterate over the businesses in SESSION, looking for
				 * a match on s_id. Then duplicate data into current.
				 */
				foreach ($_SESSION['businesses']['business'] as $biz)
				{
					// Look for match on $sid
					if(is_numeric($input))
					{
						// Match found
						if($biz['bu_id'] == $input)
						{
							$_SESSION['businesses']['current'] = $biz; # Update current in SESSION
							$match = true;
							break;
						}
					}
					// Match on alias
					else
					{
						// Match found
						if($biz['bu_alias'] == $input)
						{
							$_SESSION['businesses']['current'] = $biz; # Update current in SESSION
							$match = true;
							break;
						}
					}
				}
			}
			if((!$match || !isset($_SESSION['businesses'])) && $input != 'home')
			{
				// Look for match on $sid
				if(is_numeric($input))
				{
					$_SESSION['businesses']['current'] = Biz::myObjectToArray(Biz::withID($input)); # Update current in SESSION
				}
				// Match on alias
				else
				{
					$_SESSION['businesses']['current'] = Biz::myObjectToArray(Biz::withAlias($input)); # Update current in SESSION
				}
			}
		}

		public static function setCurrent($input)
		{
			$match = false;
			unset($_SESSION['businesses']['current']);
			if(isset($_SESSION['businesses']) && $input != 'home')
			{
				/*
				 * Iterate over the businesses in SESSION, looking for
				 * a match on s_id. Then duplicate data into current.
				 */
				if(isset($_SESSION['businesses']['business']))
				{
					foreach ($_SESSION['businesses']['business'] as $biz)
					{
						// Look for match on $sid
						if(is_numeric($input))
						{
							// Match found
							if($biz['bu_id'] == $input)
							{
								$_SESSION['businesses']['current'] = $biz; # Update current in SESSION
								$match = true;
								break;
							}
						}
						// Match on alias
						else
						{
							// Match found
							if($biz['bu_alias'] == $input)
							{
								$_SESSION['businesses']['current'] = $biz; # Update current in SESSION
								$match = true;
								break;
							}
						}
					}
				}
			}
			if((!$match || !isset($_SESSION['businesses'])) && $input != 'home')
			{
				// Look for match on $sid
				if(is_numeric($input))
				{
					$_SESSION['businesses']['current'] = Biz::myObjectToArray(Biz::withID($input)); # Update current in SESSION
				}
				// Match on alias
				else
				{
					$_SESSION['businesses']['current'] = Biz::myObjectToArray(Biz::withAlias($input)); # Update current in SESSION
				}
			}
			return new Businesses();
		}

		public function getCurrent()
		{
			if (isset($_SESSION['businesses']['current']))
				return $this->current;
			else
				return false;
		}

		public static function fetchCurrent()
		{
			if (isset($_SESSION['businesses']['current']))
			{
				$biz = new Biz();
				$biz->populateBusinessFromRow($_SESSION['businesses']['current']);
				return $biz;
			} else
			{
				return false;
			}
		}

		/*
		 * Change the current business to whatever was input.
		 * Accepts either BUID or Alias.
		 */
		public static function changeCurrentBusiness($input)
		{
			// Set current
			Businesses::setCurrent($input);
			// Update user role/right info if they are logged in
			if(User::isOnline())
			{
				$user = new User();
				$user->updateRoleInfo($user->fetchRoleInfo($user->getUserID(), Businesses::getCurrentID()));
			}
		}

		public function objectsToArray($objs) //zxzx
		{
			$businesses = array();

			foreach($objs as $biz)
			{
				array_push($businesses, $biz->objectToArray($biz));
			}

			return $businesses;
		}

		##################################################################################################
		# FETCH & POPULATE
		##################################################################################################
		/*
		 * Creates an array of businesses
		 * $fromSession should only be set to TRUE when data is passed in
		 * directly from the SESSION.
		 */
		private function populateBusinessesFromRows($rows)
		{
			$businesses = array();

			foreach($rows as $row)
			{
				$biz = new Biz();
				array_push($businesses, $biz->populateBusinessFromRow($row));
			}

			return $businesses;
		}

		public static function fetchBusinesses()
		{
			unset($_SESSION['businesses']);

			$businesses = new self();
			$businesses->populateBusinessesFromRows($businesses->fetchAll());

			return new Businesses();
		}

		public static function fetchAll()
		{
			$_ci =& get_instance();
			$_ci->load->database();

			// Fetch User info
			$query = "
				SELECT business.*, ub_id, bl_id, bl_link
                FROM `userbusiness`
                INNER JOIN " . get_gus_user() . " ON ub_userid = u_id
                INNER JOIN `business` ON ub_businessid = bu_id
                INNER JOIN `businesslogo` ON bu_businesslogoid = bl_id
                WHERE u_id = ?
			";
			$result = $_ci->db->query($query, array(User::getUserID()));
			return $result->result_array();
		}

		/* --------------------------------------------------------------------
			Fetch all of the Logos
		-------------------------------------------------------------------- */
		public static function fetchBusinessLogos()
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT *
				FROM  `businesslogo`
				WHERE  `bl_businessid` IS NULL
					OR  `bl_businessid` = ?
				ORDER BY  `bl_businessid`, bl_id
            ";

			$result = $_ci->db->query($query, array(Businesses::getCurrentID()));
			return $result->result_array();
		}

		/* --------------------------------------------------------------------
			Fetches all staff associated with the currently active Business
		-------------------------------------------------------------------- */
		public static function fetchBusinessStaff()
		{
			$_ci =& get_instance();
			$_ci->load->database();

			// Fetch User Roles & Rights
			$query = "
				SELECT ub_id, u_id, u_username, u_firstname, u_lastname, u_email, r_rolename
				FROM " . get_gus_user() . "
				INNER JOIN userbusiness ON u_id = ub_userid
				INNER JOIN userrole ON r_id = ub_userroleid
				WHERE ub_businessid = ?
            ";

			$result = $_ci->db->query($query, array(Businesses::getCurrentID()));
			return $result->result_array();
		}

		/* --------------------------------------------------------------------
			Fetches all Roles associated with the currently active Business
		-------------------------------------------------------------------- */
		public static function fetchBusinessRoles()
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT      r_id, r_rolename, ur_user, ur_view, ur_edit, ur_reporting, ur_admin, r_businessid
				FROM        userrole
				LEFT JOIN   userright ON ur_id = r_userrightid
				WHERE       r_businessid = ?
							OR r_businessid IS NULL
				ORDER BY    r_businessid
			";

			$result = $_ci->db->query($query, array(Businesses::getCurrentID()));
			return $result->result_array();
		}

		##################################################################################################
		# ACCESSORS - get_()
		# Only pulls SESSION data
		##################################################################################################
		// Returns an array of all the businesses currently in SESSION
		public static function getBusinesses()
		{
			if(isset($_SESSION['businesses']['business']))
				return $_SESSION['businesses']['business'];
			else
				return array();
		}

		public static function getCurrentID()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_id'];
			else
				return false;
		}

		public static function getUserBusinessID()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['ub_id'];
			else
				return false;
		}

		public static function getCurrentName()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_name'];
			else
				return false;
		}

		public static function getCurrentAlias()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_alias'];
			else
				return false;
		}

		public static function getCurrentDescription()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_description'];
			else
				return false;
		}

		public static function getCurrentType()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_type'];
			else
				return false;
		}

		public static function getCurrentEmail()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_email'];
			else
				return false;
		}

		public static function getCurrentPhone()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_phone'];
			else
				return false;
		}

		public static function getCurrentAddress()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_address'];
			else
				return false;
		}

		public static function getCurrentInterval()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_interval'];
			else
				return false;
		}

		public static function getExpire()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_expire'];
			else
				return false;
		}

		public static function getStatus()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_status'];
			else
				return false;
		}

		public static function getTimeOpening()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_time_opening'];
			else
				return false;
		}

		public static function getTimeClosing()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['bu_time_closing'];
			else
				return false;
		}

		// Logo
		// ------------------------------------------------------
		public static function getCurrentLogoID()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['logo']['bl_id'];
			else
				return false;
		}

		public static function getCurrentLogoURL()
		{
			if(isset($_SESSION['businesses']['current']))
				return $_SESSION['businesses']['current']['logo']['bl_link'];
			else
				return "default/flindle-default.jpg";
		}

		// Settings
		// ------------------------------------------------------
		public static function isChoiceful()
		{
			if(isset($_SESSION['businesses']['current']))
			{
				if($_SESSION['businesses']['current']['settings']['bu_specify_choice_enable'])
					return true;
				else
					return false;
			} else {
				return false;
			}
		}

		public static function isApprove()
		{
			if(isset($_SESSION['businesses']['current']))
			{
				if($_SESSION['businesses']['current']['settings']['bu_approve_enable'])
					return true;
				else
					return false;
			} else {
				return false;
			}
		}

		public static function isLockout()
		{
			if(isset($_SESSION['businesses']['current']))
			{
				if($_SESSION['businesses']['current']['settings']['bu_lockout_enable'])
					return true;
				else
					return false;
			} else {
				return false;
			}
		}

		public static function isStage()
		{
			if(isset($_SESSION['businesses']['current']))
			{
				if($_SESSION['businesses']['current']['settings']['bu_stage_enable'])
					return true;
				else
					return false;
			} else {
				return false;
			}
		}

		public static function calcExpiry()
		{
			$orig_expire = self::getExpire();
			$date_format = date_format(date_create($orig_expire), 'g:ia \o\n l jS F Y');
			$expire = strtotime($orig_expire);
			$now = time(); // or your date as well
			$datediff = $expire - $now;
			$dif = floor($datediff/(60*60*24));

			if($dif == 0)
				return 'expires <span id="trial-expiry" data-toggle="tooltip" data-placement="top" title="Expires: ' . $date_format . '">Today</span>.';
			else if($dif == 1)
				return 'expires <span id="trial-expiry" data-toggle="tooltip" data-placement="top" title="Expires: ' . $date_format . '">Tomorrow</span>.';
			else if($dif < 0)
			{
				if(self::getStatus() != 'Expired')
				{
					$biz = Biz::withID(Businesses::getCurrentID());
					$biz->setStatus("Expired");
				}
				return 'has <span id="trial-expiry" data-toggle="tooltip" data-placement="top" title="Expired: ' . $date_format . '">expired</span>.';
			}
			else
				return 'expires in <span id="trial-expiry" data-toggle="tooltip" data-placement="top" title="Expires: ' . $date_format . '">' . $dif . ' days</span>.';
		}
	}


	class Biz
	{
		private $_ci;
		private $id = null;
		private $ubid = null;
		private $name = null;
		private $alias = null;
		private $description = null;
		private $type = null;
		private $email = null;
		private $phone = null;
		private $address = null;
		private $interval = null;
		private $expire = null;
		private $status = null;
		private $timeOpening = null;
		private $timeClosing = null;

		// Logo
		private $logoID = null; # Stored in business table
		private $logoURL = null; # Stored in businesslogo table

		// Settings
		public $settings = array();

		// --------------------------------------------
		// Default Constructor
		// --------------------------------------------
		function __construct()
		{
			$this->_ci =& get_instance();
			$this->_ci->load->database();
		}

		##################################################################################################
		# LOAD / CONSTRUCT
		##################################################################################################
		public static function currentWithID()
		{
			$instance = new self();
			$instance->getAll();

			return $instance;
		}

		public static function withID($buid)
		{
			$instance = new self();
			$instance->loadFromID($buid);

			return $instance;
		}

		public function loadFromID($buid, $uid = null)
		{
			$uid = (empty($uid) ? User::getUserID() : $uid);
			// Fetch User info
			$query = "
				SELECT business.*, bl_id, bl_link, ub_id
                FROM `business`
                INNER JOIN `businesslogo` ON bu_businesslogoid = bl_id
                	AND (bl_businessid = ? OR bl_businessid IS NULL)
                INNER JOIN `userbusiness` ON ub_businessid = bu_id
                	AND ub_userid = ?
                WHERE bu_id = ?
			";

			$result = $this->_ci->db->query($query, array($buid, $uid, $buid));
			$this->populateBusinessFromRow($result->row_array());
		}

		public static function withAlias($alias)
		{
			$instance = new self();
			$instance->loadFromAlias($alias);

			return $instance;
		}

		public function loadFromAlias($alias)
		{
			// Fetch User info
			$query = "
				SELECT business.*, bl_id, bl_link
                FROM `business`
                INNER JOIN `businesslogo` ON bu_businesslogoid = bl_id
                WHERE bu_alias = ?
			";

			$result = $this->_ci->db->query($query, array($alias));
			$this->populateBusinessFromRow($result->row_array());
		}
		##################################################################################################
		# CREATE
		##################################################################################################
		// Create a new Business and assign it to the user specified
		// Returns the BusinessID if successful
		public static function create($uid, $name, $alias, $type, $email, $password)
		{
			require_once(dirname(__FILE__) . '/../libraries/api/stripe/lib/Stripe.php');
			$_ci =& get_instance();
			$_ci->load->database();

			// Ensure that the user has entered a valid business type
			if(!empty($type))
			{
				$testArray = array(
					"Restaurant",
					"Salon",
					"Event",
					"Other"
				);
				// Ensure the form values weren't tampered with
				if(!in_array($type, $testArray))
					pushAlert("Invalid type field entered! <br> Please select a value from the dropdown.", "danger");
			}
			// ----------------------------
			// Ensure alias entered is unique
			// ----------------------------
			if(Biz::checkUniqueAlias($alias))
			{
				$timeOpening = "09:00:00";
				$timeClosing = "17:00:00";
				// Build Insert
				$query = "
					INSERT INTO `business` (
						bu_name,
						bu_alias,
						bu_type,
						bu_time_opening,
						bu_time_closing,
						bu_email,
						bu_expire
					) VALUES (
						?,
						?,
						?,
						?,
						?,
						?,
						NOW() + INTERVAL 1 MONTH
					);
				";
				// Start transaction to rollback as creating a business
				// Is a 2 step process, (1)create the business (2)assign user
				$_ci->db->trans_start();
				// Bind Query Params
				$query_params = array(
					$name,
					$alias,
					$type,
					$timeOpening,
					$timeClosing,
					$email
				);

				$_ci->db->query($query, $query_params);
				// Fetch ID of newly created business & store in session
				$buid = $_ci->db->insert_id();
				if($_ci->db->affected_rows() > 0)
				{
					Stripe::setApiKey(STRIPE_API_SECRET);
					// Create a Customer
					$customer = Stripe_Customer::create(array(
							"email" => $email,
							"description" => "Customer"
						)
					);
					if(empty($customer->id))
						$_ci->db->trans_rollback();
					$query = "
						INSERT INTO " . get_gus_cardlink() . " (
							cl_pegasusid,
							cl_customerid
						) VALUES (
							?,
							?
						)
					";
					$bind = array($buid, $customer->id);
					$_ci->db->query($query, $bind);
					$clid = $_ci->db->insert_id();

					$query = "
						UPDATE `business`
						SET bu_cardlinkid = ?
						WHERE bu_id = ?
					";
					$bind = array($clid, $buid);
					$_ci->db->query($query, $bind);
				}

				if($_ci->db->affected_rows() > 0)
				{
					if(empty($uid))
					{
						$user = User::create($alias, $password, $email, "", "", null);
						if(empty($user))
						{
							$_ci->db->trans_rollback();
							header("Location: " . business_url() . "create/");
							die("Redirecting to Create a Business...");
						}
						else
						{
							$uid = $user->returnUserID();
						}
					}
					// --------------------------------
					//  ASSIGN USER TO NEWLY CREATED BUSINESS
					// --------------------------------
					// Build Insert
					$query = "
						INSERT INTO `userbusiness` (
							ub_userid,
							ub_businessid,
							ub_userroleid
						) VALUES (
							?,
							?,
							?
						);
					";
					// Bind Query Params
					$query_params = array(
						$uid,
						$buid,
						2 //Full admin role
					);
					$_ci->db->query($query, $query_params);

					if($_ci->db->affected_rows() > 0)
					{
						$_ci->db->trans_complete();
						return $buid;
					}
				}
			} else {
				pushAlert("Failed to create new Business. <br> Alias provided is already taken!", "danger");
			}

			return false;
		}

		public static function checkUniqueAlias($alias)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT      1
				FROM        business
				WHERE       bu_alias = ?
			";

			$result = $_ci->db->query($query, array(strtolower($alias)));

			if($result->num_rows() > 0)
				return false;
			else
				return true;
		}
		##################################################################################################
		# MISC
		##################################################################################################
		/*
		 * Populates a single business object from $row input
		 * $fromSession should only be set to TRUE when this function
		 * is being fed data from SESSION
		 */
		public function populateBusinessFromRow($row)
		{
			$this->id = $row['bu_id'];
			$this->ubid = $row['ub_id'];
			$this->name = $row['bu_name'];
			$this->alias = $row['bu_alias'];
			$this->description = $row['bu_description'];
			$this->type = $row['bu_type'];
			$this->email = $row['bu_email'];
			$this->phone = $row['bu_phone'];
			$this->address = $row['bu_address'];
			$this->interval = $row['bu_interval'];
			$this->expire = $row['bu_expire'];
			$this->status = $row['bu_status'];
			$this->timeOpening = $row['bu_time_opening'];
			$this->timeClosing = $row['bu_time_closing'];

			// Check if data was passed from SESSION or DB
			// Data passed from SESSION is formatted into associative arrays(ie: [logo] & [settings])

			// Logo
			if (isset($row['logo']))
			{
				$this->logoID = $row['logo']['bl_id'];
				$this->logoURL = $row['logo']['bl_link'];
			} else {
				$this->logoID = $row['bl_id'];
				$this->logoURL = $row['bl_link'];
			}

			// Settings
			if(isset($row['settings']))
			{
				// Settings
				$this->settings = array(
					"bu_default_availability" => $row['settings']['bu_default_availability'],
					"bu_max_attendees" => $row['settings']['bu_max_attendees'],
					"bu_max_msg" => $row['settings']['bu_max_msg'],
					"bu_max_msg_enable" => $row['settings']['bu_max_msg_enable'],
					"bu_booking_expiry" => $row['settings']['bu_booking_expiry'],
					"bu_booking_expiry_enable" => $row['settings']['bu_booking_expiry_enable'],
					"bu_concurrent_seats" => $row['settings']['bu_concurrent_seats'],
					"bu_concurrent_seats_enable" => $row['settings']['bu_concurrent_seats_enable'],
					"bu_specify_choice_enable" => $row['settings']['bu_specify_choice_enable'],
					"bu_approve_enable" => $row['settings']['bu_approve_enable'],
					"bu_lockout_enable" => $row['settings']['bu_lockout_enable'],
					"bu_stage_enable" => $row['settings']['bu_stage_enable']
				);
			} else {
				$this->settings = array(
					"bu_default_availability" => $row['bu_default_availability'],
					"bu_max_attendees" => $row['bu_max_attendees'],
					"bu_max_msg" => $row['bu_max_msg'],
					"bu_max_msg_enable" => $row['bu_max_msg_enable'],
					"bu_booking_expiry" => $row['bu_booking_expiry'],
					"bu_booking_expiry_enable" => $row['bu_booking_expiry_enable'],
					"bu_concurrent_seats" => $row['bu_concurrent_seats'],
					"bu_concurrent_seats_enable" => $row['bu_concurrent_seats_enable'],
					"bu_specify_choice_enable" => $row['bu_specify_choice_enable'],
					"bu_approve_enable" => $row['bu_approve_enable'],
					"bu_lockout_enable" => $row['bu_lockout_enable'],
					"bu_stage_enable" => $row['bu_stage_enable']
				);
			}

			return $this;
		}

		public function objectToArray($obj)
		{
			return array(
				"bu_id" => $this->id,
				"ub_id" => $this->ubid,
				"bu_name" => $this->name,
				"bu_alias" => $this->alias,
				"bu_description" => $this->description,
				"bu_type" => $this->type,
				"bu_email" => $this->email,
				"bu_phone" => $this->phone,
				"bu_address" => $this->address,
				"bu_interval" => $this->interval,
				"bu_expire" => $this->expire,
				"bu_status" => $this->status,
				"bu_time_opening" => $this->timeOpening,
				"bu_time_closing" => $this->timeClosing,
				"logo" => array(
					"bl_id" => $this->logoID,
					"bl_link" => $this->logoURL
				),
				"settings" => $this->settings
			);
		}

		public static function myObjectToArray($obj)
		{
			return array(
				"bu_id" => $obj->id,
				"ub_id" => $obj->ubid,
				"bu_name" => $obj->name,
				"bu_alias" => $obj->alias,
				"bu_description" => $obj->description,
				"bu_type" => $obj->type,
				"bu_email" => $obj->email,
				"bu_phone" => $obj->phone,
				"bu_address" => $obj->address,
				"bu_interval" => $obj->interval,
				"bu_expire" => $obj->expire,
				"bu_status" => $obj->status,
				"bu_time_opening" => $obj->timeOpening,
				"bu_time_closing" => $obj->timeClosing,
				"logo" => array(
					"bl_id" => $obj->logoID,
					"bl_link" => $obj->logoURL
				),
				"settings" => $obj->settings
			);
		}

		// Detects whether $input is an: ID or Alias
		public function detectInputType($input)
		{
			// ID
			if(is_numeric($input))
			{
				$query = " WHERE bu_id = " . $this->_ci->db->escape($input) . " ";
			}
			// Alias
			else
			{
				$query = " WHERE bu_alias = " . $this->_ci->db->escape($input) . " ";
			}

			return $query;
		}

		// Detects whether $input is an: ID or Alias
		public static function determineInputType($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();
			// ID
			if(is_numeric($input))
			{
				$query = " WHERE bu_id = " . $_ci->db->escape($input) . " ";
			}
			// Alias
			else
			{
				$query = " WHERE bu_alias = " . $_ci->db->escape($input) . " ";
			}

			return $query;
		}


		##################################################################################################
		# ACCESSORS - get_()
		# Only pulls SESSION data
		##################################################################################################
		public function getAll()
		{
			$biz = array(
				'bu_id' => $this->id,
				"ub_id" => $this->ubid,
				'bu_name' => $this->name,
				'bu_alias' => $this->alias,
				'bu_description' => $this->description,
				'bu_type' => $this->type,
				'bu_email' => $this->email,
				'bu_phone' => $this->phone,
				'bu_address' => $this->address,
				'bu_interval' => $this->interval,
				'bu_expire' => $this->expire,
				'bu_status' => $this->status,
				'bu_businesslogoid' => $this->logoID,
				'bl_link' => $this->logoURL
			);
			return $biz;
		}

		public function getID()
		{
			return $this->id;
		}

		public function getUserBusinessID()
		{
			return $this->ubid;
		}

		public function getName()
		{
			return $this->name;
		}

		public function getAlias()
		{
			return $this->alias;
		}

		public function getDescription()
		{
			return $this->description;
		}

		public function getType()
		{
			return $this->type;
		}

		public function getEmail()
		{
			return $this->email;
		}

		public function getPhone()
		{
			return $this->phone;
		}

		public function getAddress()
		{
			return $this->address;
		}

		public function getExpire()
		{
			return $this->expire;
		}

		public function getStatus()
		{
			return $this->status;
		}

		public function getInterval()
		{
			return $this->interval;
		}

		public function getTimeOpening()
		{
			return $this->timeOpening;
		}

		public function getTimeClosing()
		{
			return $this->timeClosing;
		}

		public function getLogoID()
		{
			return $this->logoID;
		}

		public function getLogoURL()
		{
			return $this->logoURL;
		}

		##################################################################################################
		# ACESSORS & fetch_()
		##################################################################################################
		public static function fetchID($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT bu_id
				FROM `business`
			";
			$query .= self::detectInputType($input);

			$result = $_ci->db->query($query);
			return $result->row()->bu_id;
		}

		public static function fetchStripeCustomerID($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT cl_customerid
				FROM `business`
				INNER JOIN " . get_gus_cardlink() . " ON bu_cardlinkid = cl_id
			";
			$query .= self::determineInputType($input);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->cl_customerid;
			else
				return null;
		}

		public static function fetchName($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT bu_name
				FROM `business`
			";
			$query .= self::detectInputType($input);

			$result = $_ci->db->query($query);
			return $result->row()->bu_name;
		}

		public static function fetchAlias($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT bu_alias
				FROM `business`
			";
			$query .= self::detectInputType($input);

			$result = $_ci->db->query($query);
			return $result->row()->bu_alias;
		}

		public static function fetchDescription($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT bu_description
				FROM `business`
			";
			$query .= self::detectInputType($input);

			$result = $_ci->db->query($query);
			return $result->row()->bu_description;
		}

		public static function fetchType($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT bu_type
				FROM `business`
			";
			$query .= self::detectInputType($input);

			$result = $_ci->db->query($query);
			return $result->row()->bu_type;
		}

		public static function fetchCardDetails($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT card.*
				FROM `business`
				INNER JOIN " . get_gus_cardlink() . " ON bu_cardlinkid = cl_id
					AND cl_pegasusid = ?
				INNER JOIN " . get_gus_card() . " ON cl_cardid = cc_id
				WHERE bu_id = ?
			";

			$result = $_ci->db->query($query, array($input, $input));
			if($result->num_rows() > 0)
				return $result->row_array();
			else
				return null;
		}

		public static function fetchInterval($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT bu_interval
				FROM `business`
			";
			$query .= self::detectInputType($input);

			$result = $_ci->db->query($query);
			return $result->row()->bu_interval;
		}

		public static function fetchLogoID($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT bu_businesslogoid
				FROM `business`
			";
			$query .= self::detectInputType($input);

			$result = $_ci->db->query($query);
			return $result->row()->bu_businesslogoid;
		}

		public static function fetchLogoURL($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT bl_link
				FROM `businesslogo`
				INNER JOIN `business` ON bu_businesslogoid = bl_id
			";
			$query .= self::detectInputType($input);

			$result = $_ci->db->query($query);
			return $result->row()->bl_link;
		}

		public static function fetchRoles($buid)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT r_id, r_rolename, r_userrightid, r_businessid, ur_user, ur_view, ur_edit, ur_reporting, ur_admin
				FROM userrole
				LEFT JOIN userright ON ur_id = r_userrightid
				WHERE r_businessid = ?
					OR r_businessid IS NULL
				ORDER BY r_businessid, r_rolename, r_id
				LIMIT 0 , 30
			";

			$result = $_ci->db->query($query, array($buid));
			return $result->result_array();
		}

		##################################################################################################
		# ACESSORS & set_()
		# Sets / Updates info in DB
		##################################################################################################
		public function setName($input)
		{
			$query = "
				UPDATE `business`
				SET bu_name = ?
				WHERE bu_id = ?
			";
			$result = $this->_ci->db->query($query, array($input, $this->id));

			if($this->_ci->db->affected_rows() > 0)
				$this->name = $input;

			return $result;
		}

		public function setAlias($input)
		{
			$query = "
				UPDATE `business`
				SET bu_alias = ?
				WHERE bu_id = ?
			";

			$result = $this->_ci->db->query($query, array($input, $this->id));

			if($this->_ci->db->affected_rows() > 0)
				$this->alias = $input;

			return $result;
		}

		public function setDescription($input)
		{
			$query = "
				UPDATE `business`
				SET bu_description = ?
				WHERE bu_id = ?
			";

			$result = $this->_ci->db->query($query, array($input, $this->id));

			if($this->_ci->db->affected_rows() > 0)
				$this->description = $input;

			return $result;
		}

		public function setType($input)
		{
			$query = "
				UPDATE `business`
				SET bu_type = ?
				WHERE bu_id = ?
			";

			$result = $this->_ci->db->query($query, array($input, $this->id));

			if($this->_ci->db->affected_rows() > 0)
				$this->type = $input;

			return $result;
		}

		public function setInterval($input)
		{
			$query = "
				UPDATE `business`
				SET bu_interval = ?
				WHERE bu_id = ?
			";

			$result = $this->_ci->db->query($query, array($input, $this->id));

			if($this->_ci->db->affected_rows() > 0)
				$this->interval = $input;

			return $result;
		}

		public function setExpire()
		{
			$query = "
				UPDATE `business`
				SET `bu_expire` = DATE_ADD(`bu_expire` , INTERVAL 1 MONTH)
				WHERE bu_id = ?
			";

			$result = $this->_ci->db->query($query, array($this->id));

			if($this->_ci->db->affected_rows() > 0)
				$this->expire = strtotime(date("Y-m-d", strtotime($this->getExpire())) . "+1 month");

			return $result;
		}

		public function setStatus($input)
		{
			$query = "
				UPDATE `business`
				SET `bu_status` = ?
				WHERE bu_id = ?
			";

			$result = $this->_ci->db->query($query, array($input, $this->id));

			if($this->_ci->db->affected_rows() > 0)
				$this->status = $input;

			return $result;
		}

		public function setLogoID($input)
		{
			$query = "
				UPDATE `business`
				SET bu_businesslogoid = ?
				WHERE bu_id = ?
			";

			$result = $this->_ci->db->query($query, array($input, $this->id));

			if($this->_ci->db->affected_rows() > 0)
			{
				$this->logoID = $input;
				// Reload our Businesses
				new Businesses(true);
			}

			return $result;
		}

		public static function setCard($last4, $type, $business)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				DELETE FROM " . get_gus_card() . "
				WHERE cc_id =
				(
					SELECT cl_customerid FROM " . get_gus_cardlink() . "
					WHERE cl_pegasusid = ?
				)
			";
			$_ci->db->query($query, array($business));

			$query = "
				INSERT INTO " . get_gus_card() . "
				(cc_lastfour, cc_type)
				VALUES (?, ?)
			";
			$_ci->db->query($query, array($last4, $type));

			if($_ci->db->affected_rows() > 0)
			{
				$query = "
					UPDATE " . get_gus_cardlink() . "
					SET cl_cardid = ?
					WHERE cl_pegasusid = ?
				";
				$_ci->db->query($query, array($_ci->db->insert_id(), $business));
			}
		}
	}
