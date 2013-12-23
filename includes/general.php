<?php

	/* ##################################################################################################

	#

	#   GENERAL

	#   -> Non specific code required for various functionality

	#

	################################################################################################## */
// ====================================================================
//  ROUTING / DIR / PATHS
// ====================================================================
	/* --------------------------------------------------------------------

		Route handling for absolute and relative paths

	-------------------------------------------------------------------- */
	function Path($subdir, $url)
	{
		$path = null;
		if($url == "rel")
		{
			$path = SITE_DIR . "/" . $subdir . "/";
			// Handle Root path
			if($subdir == "root" or $subdir == "")
			{
				$path = SITE_DIR . "/";
			}

			// Return path
			return $path;
		}
		if($url == "home")
		{
			$path = $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . "/" . $subdir . "/";
			// Handle Root path
			if($subdir == "root" or $subdir == "")
			{
				$path = $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . "/";
			}

			// Return path
			return $path;
		}
		if($url == "abs")
		{
			//$path = $subdir."/";
			$path = SITE_FULL . $subdir . "/";
			// Handle Root path
			if($subdir == "root" or $subdir == "")
			{
				$path = SITE_FULL;
			}

			// Return path
			return $path;
		}

		return $path;
	}