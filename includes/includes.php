<?php

	/* ##################################################################################################

	#

	#   INCLUDES

	#   -> Chain include file

	#

	################################################################################################## */
	// not sure if we need this, will test
	ob_start(); //Turn on output buffering
	// ====================================================================
	//  GENERAL - REQUIRED
	// ====================================================================
	require_once(dirname(__FILE__) . '/settings.php');
	require_once(dirname(__FILE__) . '/common.php');
	// ====================================================================
	//  ESSENTIALS / CORE
	// ====================================================================
	require_once(dirname(__FILE__) . '/general.php');
	// ====================================================================
	//  TEMPLATES
	// ====================================================================
	require_once(dirname(__FILE__) . '/../template/header.php');
	require_once(dirname(__FILE__) . '/../template/footer.php');
	require_once(dirname(__FILE__) . '/../template/toolbar.php');
	require_once(dirname(__FILE__) . '/../template/sidebar.php');
	// ====================================================================
	//  NEW - WILL REPLACE ESSENTIALS / CORE SECTION
	// ====================================================================
	//require_once(dirname(__FILE__) . '/new-inc/user.php');