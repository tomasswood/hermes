<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class User
	{
		private $_ci;
		private $id = null;
		private $facebookid = null;
		private $username = null;
		private $firstname = null;
		private $lastname = null;
		private $date_registered = null;

		private $password = null;
		private $email = null;
		private $status = null;
		private $authkey = null;

		// Logo
		private $logoID = null; # Stored in user table
		private $logoURL = null; # Stored in userlogo table

		// Roles & Rights
		private $roleID = null;
		private $roleName = null;
		private $rightUser = 0;
		private $rightView = 0;
		private $rightEdit = 0;
		private $rightReporting = 0;
		private $rightAdmin = 0;

		// --------------------------------------------
		// Default Constructor
		// --------------------------------------------
		function __construct()
		{
			$this->_ci =& get_instance();
			$this->_ci->load->database();
			/*
			 * Pull user information from session.
			 * If they are logged in we get a User object with the current users data(stored in SESSION).
			 * If they are NOT logged in we get an empty/null User object
			 */
			if(isset($_SESSION['userdata']))
			{
				$this->id = $_SESSION['userdata']['u_id'];
				$this->facebookid = $_SESSION['userdata']['u_facebookid'];
				$this->firstname = $_SESSION['userdata']['u_firstname'];
				$this->lastname = $_SESSION['userdata']['u_lastname'];
				$this->username = $_SESSION['userdata']['u_username'];
				$this->email = $_SESSION['userdata']['u_email'];
				$this->authkey = $_SESSION['userdata']['u_authkey'];
				// Logo
				$this->logoID = $_SESSION['userdata']['logo']['u_userlogoid'];
				$this->logoURL = $_SESSION['userdata']['logo']['ul_link'];
				// Roles
				if(isset($_SESSION['userdata']['role']))
				{
					$this->roleID = $_SESSION['userdata']['role']['r_id'];
					$this->roleName = $_SESSION['userdata']['role']['r_rolename'];
				}
				// Rights
				if(isset($_SESSION['userdata']['right']))
				{
					$this->rightUser = $_SESSION['userdata']['right']['ur_user'];
					$this->rightView = $_SESSION['userdata']['right']['ur_view'];
					$this->rightEdit = $_SESSION['userdata']['right']['ur_edit'];
					$this->rightReporting = $_SESSION['userdata']['right']['ur_reporting'];
					$this->rightAdmin = $_SESSION['userdata']['right']['ur_admin'];
				}
			}
		}

		// --------------------------------------------
		// Load User with specified u_id
		// --------------------------------------------
		// Used to load a user where the user_id is specified
		public static function withID($id)
		{
			$instance = new self();
			$instance->loadFromID($id);

			return $instance;
		}

		public function loadFromID($id)
		{
			// Fetch User info
			$query = "
				SELECT *
				FROM " . get_gus_user() . "
				INNER JOIN userlogo
	        		ON u_userlogoid = ul_id
				WHERE u_id = ?"
			;
			$result = $this->_ci->db->query($query, array($id));
			$this->populateUserObjectFromRow($result->row_array());
		}

		// --------------------------------------------
		// Load User with specified u_username
		// --------------------------------------------
		// Used to load a user where the user_id is specified
		public static function withUsername($username)
		{
			$instance = new self();
			$instance->loadFromUsername($username);

			return $instance;
		}

		public function loadFromUsername($username)
		{
			// Fetch User info
			$query = "
				SELECT *
				FROM " . get_gus_user() . "
				INNER JOIN userlogo
	        		ON u_userlogoid = ul_id
				WHERE u_username = ?"
			;
			$result = $this->_ci->db->query($query, array($username));
			$this->populateUserObjectFromRow($result->row_array());
		}

		// --------------------------------------------
		// Load User with specified u_username
		// --------------------------------------------
		// Used to load a user where the user_id is specified
		public static function withEmail($email)
		{
			$instance = new self();
			$instance->loadFromEmail($email);

			return $instance;
		}

		public function loadFromEmail($email)
		{
			// Fetch User info
			$query = "
				SELECT *
				FROM " . get_gus_user() . "
				INNER JOIN userlogo
	        		ON u_userlogoid = ul_id
				WHERE u_email = ?"
			;
			$result = $this->_ci->db->query($query, array($email));
			$this->populateUserObjectFromRow($result->row_array());
		}

		// --------------------------------------------
		// Load User with specified u_facebookid
		// --------------------------------------------
		// Used to load a user where the facebook_id is specified
		public static function withFacebook($fbid)
		{
			$instance = new self();
			return $instance->loadFromFacebookID($fbid);
		}

		public function loadFromFacebookID($fbid)
		{
			// Fetch User info
			$query = "
				SELECT *
				FROM " . get_gus_user() . "
				INNER JOIN userlogo
	        		ON u_userlogoid = ul_id
				WHERE u_facebookid = ?"
			;
			$result = $this->_ci->db->query($query, array($fbid));
			if($result->num_rows() > 0)
			{
				$this->populateUserObjectFromRow($result->row_array());
				return true;
			}
			else
			{
				return false;
			}
		}

		##################################################################################################
		# MISCELLANEOUS
		##################################################################################################
		public static function isOnline()
		{
			if(isset($_SESSION['userdata']))
				return true;
			else
				return false;
		}


		// Populates the user object from a DB fetch
		private function populateUserObjectFromRow($row)
		{
			$ul_link = empty($row['ul_link']) ? null : $row['ul_link'];
			// Setup Object attributes
			$this->id = $row['u_id'];
			$this->facebookid = $row['u_facebookid'];
			$this->firstname = $row['u_firstname'];
			$this->lastname = $row['u_lastname'];
			$this->username = $row['u_username'];
			$this->password = $row['u_password'];
			$this->email = $row['u_email'];
			$this->status = $row['u_status'];
			$this->authkey = $row['u_authkey'];
			$this->date_registered = $row['u_registered'];
			// Logo
			$this->logoID = $row['u_userlogoid'];
			$this->logoURL = $ul_link;
			// ---------------------------------
			// Setup SESSION['user']
			// ---------------------------------
			unset($row['u_password']);
			$_SESSION['userdata']['u_id'] = $row['u_id'];
			$_SESSION['userdata']['u_facebookid'] = $row['u_facebookid'];
			$_SESSION['userdata']['u_firstname'] = $row['u_firstname'];
			$_SESSION['userdata']['u_lastname'] = $row['u_lastname'];
			$_SESSION['userdata']['u_username'] = $row['u_username'];
			$_SESSION['userdata']['u_email'] = $row['u_email'];
			$_SESSION['userdata']['u_status'] = $row['u_status'];
			$_SESSION['userdata']['u_authkey'] = $row['u_authkey'];
			$_SESSION['userdata']['u_registered'] = $row['u_registered'];
			// Logo
			unset($_SESSION['userdata']['logo']);
			$_SESSION['userdata']['logo'] = array(
				"u_userlogoid" => $row['u_userlogoid'],
				"ul_link" => $ul_link
			);
		}

		// Detects whether $input is an: ID , email, or username
		public function detectInputType($input)
		{
			// ID
			if(is_numeric($input))
			{
				$query = " WHERE u_id = " . $this->_ci->db->escape($input) . " ";
			}
			// Email
			else if(strpos($input, '@'))
			{
				$query = " WHERE u_email = " . $this->_ci->db->escape($input) . " ";
			}
			// Username
			else
			{
				$query = " WHERE u_username = " . $this->_ci->db->escape($input) . " ";
			}

			return $query;
		}

		// Detects whether $input is an: ID , email, or username
		public static function determineInputType($input)
		{
			$_ci =& get_instance();
			$_ci->load->database();
			// ID
			if(is_numeric($input))
			{
				$query = " WHERE u_id = " . $_ci->db->escape($input) . " ";
			}
			// Email
			else if(strpos($input, '@'))
			{
				$query = " WHERE u_email = " . $_ci->db->escape($input) . " ";
			}
			// Username
			else
			{
				$query = " WHERE u_username = " . $_ci->db->escape($input) . " ";
			}

			return $query;
		}

		##################################################################################################
		# CREATE USER
		##################################################################################################
		public static function create($username, $password, $email, $firstname, $lastname, $facebookid = "NULL", $staff = false, $buid = null)
		{
			require_once(dirname(__FILE__) . '/../libraries/api/stripe/lib/Stripe.php');
			$instance = new self();
			$_ci =& get_instance();
			$_ci->load->database();
			// ------------------------------
			// Check if username is already registered
			// ------------------------------
			// Build Query
			$result = User::fetchUsername($username); //If username is already registered
			// If username exists
			if(!empty($result))
			{
				// Push Alert & Return
				pushAlert("The username provided is already registered.", "danger");
				return false;
			}
			// ------------------------------
			// Check if email is already registered
			// ------------------------------
			$result = User::fetchEmail($email); //If email is already registered
			// If Email exists
			if(!empty($result))
			{
				pushAlert("The email address provided is already registered.", "danger");
				return false;
			}
			$_ci->db->trans_begin();
			// ------------------------------
			// Insert user
			// ------------------------------
			$query = "
				INSERT INTO " . get_gus_user() . " (
					u_facebookid,
					u_username,
					u_password,
					u_email,
					u_firstname,
					u_lastname,
					u_authkey,
					u_status
				) VALUES (
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?
				)
			";
			// Hash password - BCRYPT
			$password = password_hash($password, PASSWORD_BCRYPT);
			// Create an authkey, for use in email activation
			$authkey = substr(md5(rand()), 0, 10);
			$status = ($staff ? "Active" : "Inactive");

			$bind = array(
				$facebookid,
				$username,
				$password,
				$email,
				$firstname,
				$lastname,
				$authkey,
				$status
			);

			$_ci->db->query($query, $bind);
			$uid = $_ci->db->insert_id();

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
						cl_userid,
						cl_customerid
					) VALUES (
						?,
						?
					)
				";
				$bind = array($uid, $customer->id);
				$_ci->db->query($query, $bind);
				$clid = $_ci->db->insert_id();

				$query = "
						UPDATE " . get_gus_user() . "
						SET u_cardlinkid = ?
						WHERE u_id = ?
					";
				$bind = array($clid, $uid);
				$_ci->db->query($query, $bind);
			}

			if($staff)
			{
				if($_ci->db->affected_rows() > 0)
				{
					if(empty($buid))
						$buid = Businesses::getCurrentID();
					//Relating user to business in userbusiness table
					$query = "
						INSERT INTO userbusiness (
							ub_userid,
							ub_businessid,
							ub_userroleid
						) VALUES (
							?,
							?,
							?
						)
					";
					$bind = array(
						':uid' => $uid,
						':buid' => $buid,
						':urid' => 1
					);
					$_ci->db->query($query, $bind);

					if($_ci->db->affected_rows() > 0)
					{
						$_ci->db->trans_commit();
						return true;
					}
					else
					{
						$_ci->db->trans_rollback();
						return false;
					}
				}
			}
			else
			{
				// Fetch the newly inserted user info & populate instance with inserted data
				if($_ci->db->affected_rows() > 0)
				{
					$_ci->db->trans_commit();
					$query = "
						SELECT *
						FROM " . get_gus_user() . "
						WHERE u_id = ?"
					;

					$result = $_ci->db->query($query, array($uid));
					$instance->populateUserObjectFromRow($result->row_array());
				}
				/*
				 * If a user was NOT created return false.
				 * If a user WAS created, return the user object
				 */
				if(empty($instance->id))
				{
					return false;
				}
				else
				{
					return $instance;
				}
			}
		}

		##################################################################################################
		# AUTHENTICATE USER
		##################################################################################################
		public static function authenticate($username, $password)
		{
			if(password_verify($password, User::fetchPassword($username)))
				return true;
			else
				return false;
		}

		public static function login($username, $password)
		{
			// Login SUCCESS
			if(User::authenticate($username, $password))
			{
				// Fetch User object
				$user = new self();

				// Email
				if(strpos($username, '@'))
					$user->loadFromEmail($username); # Populate object created above
				else
					$user->loadFromUsername($username); # Populate object created above

				// Check account STATUS
				if($user->getStatus() == "Active")
				{
					/*
					 * User object values have already been populated &
					 * also stored in session when loadFromUsername()
					 * was called. Hence we can skip this and just include
					 * Rights and Roles.
					 */
					// Populate Businesses session as we need it set to pull role info(even though it will be null)
					new Businesses();
					// Fetch Role & Right Info
					#if(Businesses::fetchCurrent())
					#{
						$user->updateRoleInfo($user->fetchRoleInfo($user->getUserID(), Businesses::getCurrentName()));
						$user->updateLastLogin($user->getUserID());
					#}
					/*
					 * Return the User object (even though everything
					 * is already in SESSION) for use from one liners.
					 */

					return $user;
				}
				// Account inactive
				else if($user->getStatus() == "Inactive")
				{
					// Push Alert & Return
					pushAlert("User account has not been activated! <br> <a href='" . user_url() . "activate'>Resend activation email.</a>", "danger");
					return false;
				}
				else
				{
					// Push Alert & Return
					pushAlert("User account has been disabled. Please contact support to resolve this issue.<br><a href='mailto:support@flindle.com'>support@flindle.com</a>", "danger");
					return false;
				}
			}
			else
			{
				// Push Alert & Return
				pushAlert("The information you provided was incorrect.", "danger");
				return false;
			}
		}

		public static function loginWithFacebook($facebookid)
		{
				// Fetch User object
				$user = new self();

				// Email
				$success = $user->loadFromFacebookID($facebookid); # Populate object created above
				if($success)
				{
					// Check account STATUS
					if($user->getStatus() == "Active")
					{
						/*
						 * User object values have already been populated &
						 * also stored in session when loadFromUsername()
						 * was called. Hence we can skip this and just include
						 * Rights and Roles.
						 */
						// Populate Businesses session as we need it set to pull role info(even though it will be null)
						$businesses = new Businesses();
						// Fetch Role & Right Info
						#if(Businesses::fetchCurrent())
						#{
						$user->updateRoleInfo($user->fetchRoleInfo($user->getUserID(), Businesses::getCurrentName()));
						$user->updateLastLogin($user->getUserID());
						#}
						/*
						 * Return the User object (even though everything
						 * is already in SESSION) for use from one liners.
						 */

						return true;
					}
					// Account inactive
					else if($user->getStatus() == "Inactive")
					{
						// Push Alert & Return
						pushAlert("User account has not been activated! <br> <a href='" . user_url() . "activate'>Resend activation email.</a>", "danger");
						return false;
					}
					else
					{
						// Push Alert & Return
						pushAlert("User account has been disabled. Please contact support to resolve this issue.<br><a href='mailto:support@flindle.com'>support@flindle.com</a>", "danger");
						return false;
					}
				}
				else
				{
					// Push Alert & Return
					pushAlert("We are unable to log you in with Facebook at this time.", "danger");
					return false;
				}
		}

		/*
		 * Updates the users role info in both the object and the SESSION
		 */
		public function updateRoleInfo($row)
		{
			if(!empty($row))
			{
				// -----------------------
				// OBJECT
				// -----------------------
				$this->roleID = $row['r_id'];
				$this->roleName = $row['r_rolename'];
				$this->rightUser = $row['ur_user'];
				$this->rightView = $row['ur_view'];
				$this->rightEdit = $row['ur_edit'];
				$this->rightReporting = $row['ur_reporting'];
				$this->rightAdmin = $row['ur_admin'];
				// -----------------------
				// SESSION
				// -----------------------
				// Roles
				$_SESSION['userdata']['role'] = array(
					'r_id' => $row['r_id'],
					'r_rolename' => $row['r_rolename']
				);
				// Rights
				$_SESSION['userdata']['right'] = array (
					'ur_user' => $row['ur_user'],
					'ur_view' => $row['ur_view'],
					'ur_edit' => $row['ur_edit'],
					'ur_reporting' => $row['ur_reporting'],
					'ur_admin' => $row['ur_admin']
				);
			}
			// Set everything to false
			else {
				// -----------------------
				// OBJECT
				// -----------------------
				$this->roleID = null;
				$this->roleName = null;
				$this->rightUser = false;
				$this->rightView = false;
				$this->rightEdit = false;
				$this->rightReporting = false;
				$this->rightAdmin = false;
				// -----------------------
				// SESSION
				// -----------------------
				// Roles
				$_SESSION['userdata']['role'] = array(
					'r_id' => null,
					'r_rolename' => null
				);
				// Rights
				$_SESSION['userdata']['right'] = array (
					'ur_user' => false,
					'ur_view' => false,
					'ur_edit' => false,
					'ur_reporting' => false,
					'ur_admin' => false
				);
			}
		}

		public function updateLastLogin($user = null)
		{
			/*
			 * Indicates whether this call is being run on/by a user
			 * that is currently online/logged in.
			 * This will return false if the $user param was specified.
			 */
			$online = false;

			// Work around for not being able to pass func calls to default params
			if(empty($user))
			{
				$online = true;
				$user = (int)$this->getUserID();
			}

			$query = "
				UPDATE " . get_gus_user() . "
				SET u_lastlogin = NOW()
			";
			$query .= $this->detectInputType($user);

			$this->_ci->db->query($query);
		}

		##################################################################################################
		# ACESSORS & MUTATORS --> GET ONLY
		# ONLY ACCESSES SESSION DATA, SEE fetch METHODS FOR DB INFO
		##################################################################################################
		public function returnUserID()
		{
			return $this->id;
		}

		public static function getUserID()
		{
			$id = 0;
			if(!empty($_SESSION['userdata']['u_id']))
			{
				$id = $_SESSION['userdata']['u_id'];
			}

			return $id;
		}

		public static function getFacebookID()
		{
			$id = null;
			if(!empty($_SESSION['userdata']['u_facebookid']))
			{
				$id = $_SESSION['userdata']['u_facebookid'];
			}

			return $id;
		}

		public static function getUsername()
		{
			$username = 0;
			if(!empty($_SESSION['userdata']['u_username']))
			{
				$username = $_SESSION['userdata']['u_username'];
			}

			return $username;
		}

		public static function getFirstName()
		{
			$fname = "";
			if(!empty($_SESSION['userdata']['u_firstname']))
			{
				$fname = $_SESSION['userdata']['u_firstname'];
			}

			return $fname;
		}

		public static function getLastName()
		{
			$lname = "";
			if(!empty($_SESSION['userdata']['u_lastname']))
			{
				$lname = $_SESSION['userdata']['u_lastname'];
			}

			return $lname;
		}

		public static function getPassword()
		{
			$pass = "";
			if(!empty($_SESSION['userdata']['u_password']))
			{
				$pass = $_SESSION['userdata']['u_password'];
			}

			return $pass;
		}

		public static function getEmail()
		{
			$email = "";
			if(!empty($_SESSION['userdata']['u_email']))
			{
				$email = $_SESSION['userdata']['u_email'];
			}

			return $email;
		}

		public static function getStatus()
		{
			$status = "";
			if(!empty($_SESSION['userdata']['u_status']))
			{
				$status = $_SESSION['userdata']['u_status'];
			}

			return $status;
		}

		public static function getAuthkey()
		{
			$authkey = "";
			if(!empty($_SESSION['userdata']['u_authkey']))
			{
				$authkey = $_SESSION['userdata']['u_authkey'];
			}

			return $authkey;
		}

		// Logo
		// ------------------------------------------------------
		public static function getLogoID()
		{
			$logoID = "";
			if(!empty($_SESSION['userdata']['logo']['u_userlogoid']))
			{
				$logoID = $_SESSION['userdata']['logo']['u_userlogoid'];
			}

			return $logoID;
		}

		public static function getLogoURL()
		{
			$logoURL = "default/blue.png";
			if(!empty($_SESSION['userdata']['logo']['ul_link']))
			{
				$logoURL = $_SESSION['userdata']['logo']['ul_link'];
			}

			return $logoURL;
		}

		// Roles
		// ------------------------------------------------------
		public static function getRoleID()
		{
			$roleID = "";
			if(!empty($_SESSION['userdata']['role']['r_id']))
			{
				$roleID = $_SESSION['userdata']['role']['r_id'];
			}

			return $roleID;
		}

		public static function getRolename()
		{
			$roleName = "";
			if(!empty($_SESSION['userdata']['role']['r_rolename']))
			{
				$roleName = $_SESSION['userdata']['role']['r_rolename'];
			}

			return $roleName;
		}

		// Rights
		// ------------------------------------------------------
		public static function rightUser()
		{
			$roleUser = false;
			if(!empty($_SESSION['userdata']['right']['ur_user']))
			{
				$roleUser = $_SESSION['userdata']['right']['ur_user'];
			}

			return $roleUser;
		}

		public static function rightView()
		{
			$roleView = false;
			if(!empty($_SESSION['userdata']['right']['ur_view']))
			{
				$roleView = $_SESSION['userdata']['right']['ur_view'];
			}

			return $roleView;
		}

		public static function rightEdit()
		{
			$roleEdit = false;
			if(!empty($_SESSION['userdata']['right']['ur_edit']))
			{
				$roleEdit = $_SESSION['userdata']['right']['ur_edit'];
			}

			return $roleEdit;
		}

		public static function rightReporting()
		{
			$roleReporting = false;
			if(!empty($_SESSION['userdata']['right']['ur_reporting']))
			{
				$roleReporting = $_SESSION['userdata']['right']['ur_reporting'];
			}

			return $roleReporting;
		}

		public static function rightAdmin()
		{
			$roleAdmin = false;
			if(isset($_SESSION['userdata']))
			{
				if($_SESSION['userdata']['u_username'] === SUPER_ADMIN) // Flindle admin account
					return true;
				else if(!empty($_SESSION['userdata']['right']['ur_admin']))
				{
					$roleAdmin = $_SESSION['userdata']['right']['ur_admin'];
				}
			}

			return $roleAdmin;
		}

		##################################################################################################
		# ACESSORS & MUTATORS --> FETCH ONLY
		# ONLY ACCESSES DB DATA, SEE get METHODS FOR SESSION INFO
		##################################################################################################
		public static function fetchAll($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT *
				FROM " . get_gus_user() . "
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			return $result->row_array();
		}

		public static function fetchID($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT u_id
				FROM " . get_gus_user() . "
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->u_id;
			else
				return null;
		}

		public static function fetchStripeCustomerID($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT cl_customerid
				FROM " . get_gus_user() . "
				INNER JOIN " . get_gus_cardlink() . " ON u_cardlinkid = cl_id
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->cl_customerid;
			else
				return null;
		}

		public static function fetchUsername($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT u_username
				FROM " . get_gus_user() . "
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->u_username;
			else
				return null;
		}

		public static function fetchFirstName($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT u_firstname
				FROM " . get_gus_user() . "
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->u_firstname;
			else
				return null;
		}

		public static function fetchLastName($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT u_lastname
				FROM " . get_gus_user() . "
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->u_lastname;
			else
				return null;
		}

		public static function fetchPassword($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT u_password
				FROM " . get_gus_user() . "
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->u_password;
			else
				return null;
		}

		public static function fetchFacebookID($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT u_facebookid
				FROM " . get_gus_user() . "
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->u_facebookid;
			else
				return null;
		}

		public static function fetchEmail($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT u_email
				FROM " . get_gus_user() . "
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->u_email;
			else
				return null;
		}

		public static function fetchStatus($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT u_status
				FROM " . get_gus_user() . "
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->u_status;
			else
				return null;
		}

		public static function fetchAuthkey($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT u_authkey
				FROM " . get_gus_user() . "
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->u_authkey;
			else
				return null;
		}

		public static function fetchCardDetails($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT card.*
				FROM " . get_gus_user() . "
				INNER JOIN " . get_gus_cardlink() . " ON u_cardlinkid = cl_id
					AND cl_userid = ?
				INNER JOIN " . get_gus_card() . " ON cl_cardid = cc_id
				WHERE u_id = ?
			";

			$result = $_ci->db->query($query, array($user, $user));
			if($result->num_rows() > 0)
				return $result->row_array();
			else
				return null;
		}

		// User Business
		// ------------------------------------------------------
		public static function fetchUserBusinessID($business, $user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$bind = array($business, $user);
			$query = "
				SELECT ub_id
				FROM userbusiness
				WHERE ub_businessid = ?
					AND ub_userid = ?
			";

			$result = $_ci->db->query($query, $bind);
			if($result->num_rows() > 0)
				return $result->row()->ub_id;
			else
				return null;
		}

		// Logo
		// ------------------------------------------------------
		public static function fetchLogoID($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT u_userlogoid
				FROM " . get_gus_user() . "
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->u_userlogoid;
			else
				return null;
		}

		public static function fetchLogoURL($user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT ul_link
				FROM " . get_gus_user() . "
				INNER JOIN userlogo
	        		ON u_userlogoid = ul_id
			";
			$query .= self::determineInputType($user);

			$result = $_ci->db->query($query);
			if($result->num_rows() > 0)
				return $result->row()->ul_link;
			else
				return null;
		}

		public static function fetchUserLogos()
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT *
				FROM  `userlogo`
				WHERE  `ul_userid` IS NULL
					OR  `ul_userid` = ?
				ORDER BY  `ul_userid`, ul_id

            ";

			$result = $_ci->db->query($query, array(User::getUserID()));
			return $result->result_array();
		}

		// Roles
		// ------------------------------------------------------
		public function fetchRoleInfo($uid, $buid)
		{
			// Fetch User Roles & Rights
			$query = "
                SELECT r.*, ur.*
				FROM `userbusiness`
				INNER JOIN userrole AS r ON ub_userroleid = r_id
				INNER JOIN userright AS ur ON r_userrightid = ur_id
				AND ub_businessid = ?
					AND ub_userid = ?
			";

			$result = $this->_ci->db->query($query, array($buid, $uid));
			return $result->row_array();
		}

		public static function fetchRoleID($uid, $buid)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT r_id
				FROM `userrole`
				INNER JOIN `userbusiness` ON ub_userroleid = r_id
					AND ub_businessid = ?
					AND ub_userid = ?
			";

			$result = $_ci->db->query($query, array($buid, $uid));
			return $result->row()->r_id;
		}

		public static function fetchRolename($uid, $buid)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT r_rolename
				FROM `userrole`
				INNER JOIN `userbusiness` ON ub_userroleid = r_id
					AND ub_businessid = ?
					AND ub_userid = ?
			";

			$result = $_ci->db->query($query, array($buid, $uid));
			return $result->row()->r_rolename;
		}

		// Roles
		// ------------------------------------------------------
		public static function hasRightUser($uid, $buid)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT ur_user
				FROM `userright`
				INNER JOIN `userrole` ON ur_id = r_userrightid
				INNER JOIN `userbusiness` ON r_id = ub_userroleid
					AND ub_businessid = ?
					AND ub_userid = ?
			";

			$result = $_ci->db->query($query, array($buid, $uid));
			return $result->row()->ur_user;
		}

		public static function hasRightView($uid, $buid)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT ur_view
				FROM `userright`
				INNER JOIN `userrole` ON ur_id = r_userrightid
				INNER JOIN `userbusiness` ON r_id = ub_userroleid
					AND ub_businessid = ?
					AND ub_userid = ?
			";

			$result = $_ci->db->query($query, array($buid, $uid));
			return $result->row()->ur_view;
		}

		public static function hasRightEdit($uid, $buid)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT ur_edit
				FROM `userright`
				INNER JOIN `userrole` ON ur_id = r_userrightid
				INNER JOIN `userbusiness` ON r_id = ub_userroleid
					AND ub_businessid = ?
					AND ub_userid = ?
			";

			$result = $_ci->db->query($query, array($buid, $uid));
			return $result->row()->ur_edit;
		}

		public static function hasRightReporting($uid, $buid)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT ur_reporting
				FROM `userright`
				INNER JOIN `userrole` ON ur_id = r_userrightid
				INNER JOIN `userbusiness` ON r_id = ub_userroleid
					AND ub_businessid = :buid
					AND ub_userid = :uid
			";

			$result = $_ci->db->query($query, array($buid, $uid));
			return $result->row()->ur_reporting;
		}

		public static function hasRightAdmin($uid, $buid)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				SELECT ur_admin
				FROM `userright`
				INNER JOIN `userrole` ON ur_id = r_userrightid
				INNER JOIN `userbusiness` ON r_id = ub_userroleid
				AND ub_businessid = :buid
					AND ub_userid = :uid
			";

			$result = $_ci->db->query($query, array($buid, $uid));
			return $result->row()->ur_admin;
		}


		##################################################################################################
		# ACESSORS & MUTATORS --> SET ONLY
		# SETS / UPDATES user INFO IN DB (not in session)
		##################################################################################################
		public static function setUsername($input, $user = null)
		{
			/*
			 * Indicates whether this call is being run on/by a user
			 * that is currently online/logged in.
			 * This will return false if the $user param was specified.
			 */
			$online = false;

			// Work around for not being able to pass func calls to default params
			if(empty($user))
			{
				$online = true;
				$user = (int)User::getUserID();
			}

			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				UPDATE " . get_gus_user() . "
				SET u_username = ?
			";
			$query .= self::determineInputType($user);
			$_ci->db->query($query, array($input));

			// If Email exists
			if($_ci->db->affected_rows() > 0)
			{
				// Modifying user that is already logged in
				if($online)
				{
					$_SESSION['userdata']['u_username'] = $input;
				}

				$result = new self();
			}

			return $result;
		}

		public static function setFacebookID($input, $user = null)
		{
			/*
			 * Indicates whether this call is being run on/by a user
			 * that is currently online/logged in.
			 * This will return false if the $user param was specified.
			 */
			$online = false;

			// Work around for not being able to pass func calls to default params
			if(empty($user))
			{
				$online = true;
				$user = (int)User::getUserID();
			}

			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				UPDATE " . get_gus_user() . "
				SET u_facebookid = ?
			";
			$query .= self::determineInputType($user);
			$_ci->db->query($query, array($input));

			// If Email exists
			if($_ci->db->affected_rows() > 0)
			{
				// Modifying user that is already logged in
				if($online)
				{
					$_SESSION['userdata']['u_facebookid'] = $input;
				}

				$result = new self();
			}

			return $result;
		}

		public static function setFirstname($input, $user = null)
		{
			/*
			 * Indicates whether this call is being run on/by a user
			 * that is currently online/logged in.
			 * This will return false if the $user param was specified.
			 */
			$online = false;

			// Work around for not being able to pass func calls to default params
			if(empty($user))
			{
				$online = true;
				$user = (int)User::getUserID();
			}

			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				UPDATE " . get_gus_user() . "
				SET u_firstname = ?
			";
			$query .= self::determineInputType($user);
			$_ci->db->query($query, array($input));

			// If Email exists
			if($_ci->db->affected_rows() > 0)
			{
				// Modifying user that is already logged in
				if($online)
				{
					$_SESSION['userdata']['u_firstname'] = $input;
				}

				$result = new self();
			}

			return $result;
		}

		public static function setLastname($input, $user = null)
		{
			/*
			 * Indicates whether this call is being run on/by a user
			 * that is currently online/logged in.
			 * This will return false if the $user param was specified.
			 */
			$online = false;

			// Work around for not being able to pass func calls to default params
			if(empty($user))
			{
				$online = true;
				$user = (int)User::getUserID();
			}

			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				UPDATE " . get_gus_user() . "
				SET u_lastname = ?
			";
			$query .= self::determineInputType($user);
			$_ci->db->query($query, array($input));

			// If Email exists
			if($_ci->db->affected_rows() > 0)
			{
				// Modifying user that is already logged in
				if($online)
				{
					$_SESSION['userdata']['u_lastname'] = $input;
				}

				$result = new self();
			}

			return $result;
		}

		public static function setPassword($input, $user = null)
		{
			/*
			 * Indicates whether this call is being run on/by a user
			 * that is currently online/logged in.
			 * This will return false if the $user param was specified.
			 */
			$online = false;

			// Work around for not being able to pass func calls to default params
			if(empty($user))
			{
				$online = true;
				$user = (int)User::getUserID();
			}

			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				UPDATE " . get_gus_user() . "
				SET u_password = ?
			";
			$query .= self::determineInputType($user);
			$_ci->db->query($query, array($input));

			// If Email exists
			if($_ci->db->affected_rows() > 0)
			{
				$result = new self();
			}

			return $result;
		}

		public static function setEmail($input, $user = null)
		{
			/*
			 * Indicates whether this call is being run on/by a user
			 * that is currently online/logged in.
			 * This will return false if the $user param was specified.
			 */
			$online = false;

			// Work around for not being able to pass func calls to default params
			if(empty($user))
			{
				$online = true;
				$user = (int)User::getUserID();
			}

			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				UPDATE " . get_gus_user() . "
				SET u_email = ?
			";
			$query .= self::determineInputType($user);
			$_ci->db->query($query, array($input));

			// If Email exists
			if($_ci->db->affected_rows() > 0)
			{
				// Modifying user that is already logged in
				if($online)
				{
					$_SESSION['userdata']['u_email'] = $input;
				}

				$result = new self();
			}

			return $result;
		}

		public static function setStatus($input, $user = null)
		{
			/*
			 * Indicates whether this call is being run on/by a user
			 * that is currently online/logged in.
			 * This will return false if the $user param was specified.
			 */
			$online = false;

			// Work around for not being able to pass func calls to default params
			if(empty($user))
			{
				$online = true;
				$user = (int)User::getUserID();
			}

			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				UPDATE " . get_gus_user() . "
				SET u_status = ?
			";
			$query .= self::determineInputType($user);
			$_ci->db->query($query, array($input));

			// If Email exists
			if($_ci->db->affected_rows() > 0)
			{
				// Modifying user that is already logged in
				if($online)
				{
					$_SESSION['userdata']['u_status'] = $input;
				}

				$result = new self();
			}

			return $result;
		}

		public static function setAuthkey($input, $user = null)
		{
			/*
			 * Indicates whether this call is being run on/by a user
			 * that is currently online/logged in.
			 * This will return false if the $user param was specified.
			 */
			$online = false;

			// Work around for not being able to pass func calls to default params
			if(empty($user))
			{
				$online = true;
				$user = (int)User::getUserID();
			}

			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				UPDATE " . get_gus_user() . "
				SET u_authkey = ?
			";
			$query .= self::determineInputType($user);
			$_ci->db->query($query, array($input));

			// If Email exists
			if($_ci->db->affected_rows() > 0)
			{
				// Modifying user that is already logged in
				if($online)
				{
					$_SESSION['userdata']['u_authkey'] = $input;
				}

				$result = new self();
			}

			return $result;
		}

		public static function setCard($last4, $type, $user)
		{
			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				DELETE FROM " . get_gus_card() . "
				WHERE cc_id =
				(
					SELECT cl_customerid FROM " . get_gus_cardlink() . "
					WHERE cl_userid = ?
				)
			";
			$_ci->db->query($query, array($user));

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
					WHERE cl_userid = ?
				";
				$_ci->db->query($query, array($_ci->db->insert_id(), $user));
			}
		}

		// Logo
		// ------------------------------------------------------

		public static function setLogoID($input, $user = null)
		{
			/*
			 * Indicates whether this call is being run on/by a user
			 * that is currently online/logged in.
			 * This will return false if the $user param was specified.
			 */
			$online = false;

			// Work around for not being able to pass func calls to default params
			if(empty($user))
			{
				$online = true;
				$user = (int)User::getUserID();
			}

			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				UPDATE " . get_gus_user() . "
				SET u_userlogoid = ?
			";
			$query .= self::determineInputType($user);
			$_ci->db->query($query, array($input));

			// If Email exists
			if($_ci->db->affected_rows() > 0)
			{
				// Modifying user that is already logged in
				if($online)
				{
					$_SESSION['userdata']['logo']['u_userlogoid'] = $input;
					$_SESSION['userdata']['logo']['ul_link'] = User::fetchLogoURL($user);
				}

				$result = new self();
			}

			return $result;
		}

		// Role
		// ------------------------------------------------------

		public static function setRole($rid, $buid, $uid = null)
		{
			// Work around for not being able to pass func calls to default params
			if(empty($uid))
				$uid = (int)User::getUserID();

			$_ci =& get_instance();
			$_ci->load->database();

			$query = "
				UPDATE `userbusiness`
				SET ub_userroleid = ?
				WHERE ub_userid = ?
					AND ub_businessid = ?
			";
			$result = $_ci->db->query($query, array($rid, $uid, $buid));

			return $result;
		}

	}
