<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="">
	<title>Hermes</title>
	<!-- Favicon -->
	<link rel="icon" type="image/ico" href="<?php echo images_url() . 'favicon.ico'; ?>">
	<!-- Links & Script -->
	<link rel="stylesheet" href="<?php echo styles_url() . 'style.css'; ?>">
	<script src="<?php echo js_libs_url() . 'jquery/jquery-1.10.2.min.js'; ?>"></script>
	<script src="<?php echo js_libs_url() . 'jquery/jquery-ui-1.10.3.min.js'; ?>"></script>
	<script src="<?php echo js_libs_url() . 'bootstrap/bootstrap.min.js'; ?>"></script>
	<script src="http://tinymce.cachefly.net/4.0/tinymce.min.js"></script>
	<?php
		// Include array of script/links/etc passed in via the pages pageHeader call
		foreach($links as $l)
			echo "\n\t\t" . $l;

		if(ENVIRONMENT == 'production')
		{
			include_once($_SERVER['DOCUMENT_ROOT'] . "/assets/includes/ga.php");
		}
	?>
</head>
<body>
<!-- #header -->
<div id="header">
	<nav class="navbar navbar-main" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<h1>
					<a class="navbar-brand" id="logo" href="<?php echo base_url(); ?>">Flindle</a>
				</h1>
			</div>
			<div class="collapse navbar-collapse navbar-ex1-collapse"> <?php //TODO: Remove collapse ?>
				<ul class="nav navbar-nav">
				</ul>
			</div>
			<!-- End .navbar-collapse -->
		</div><!-- End .container -->
	</nav><!-- End navbar navbar-default -->
</div><!-- End #header -->