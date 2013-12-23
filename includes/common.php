<?php

	// DatabaseSessionHandler class handles database sessions
	require_once("database-session-handler.class.php");
	// Setup PDO Options
	// Exchange info using UTF-8
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
	try
	{
		// Create & Open DB Connection ($db)
		$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS, $options);
	} catch(PDOException $ex)
	{
		die("Failed to connect to the database.");
	}
	// Setup PDO Attributes
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
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
		$sess = new DatabaseSessionHandler();
		session_set_save_handler(array(&$sess, '_open'),
			array(&$sess, '_close'),
			array(&$sess, '_read'),
			array(&$sess, '_write'),
			array(&$sess, '_destroy'),
			array(&$sess, '_clean'));
		// End session hook
		// start session
	}