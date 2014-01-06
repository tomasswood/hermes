<?php
	function pageHeader($links = array())
	{
?>
	<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="">
		<title>Hermes</title>
		<!-- Favicon -->
		<link rel="icon" type="image/ico" href="<?php echo Path('images', 'abs') . 'favicon.ico'; ?>">
		<!-- Links & Script -->
		<link rel="stylesheet" href="<?php echo Path('styles', 'rel') . 'style.css'; ?>">
		<script src="<?php echo Path('js', 'abs') . 'jquery-1.10.2.min.js'; ?>"></script>
		<script src="<?php echo Path('js', 'abs') . 'jquery-ui-1.10.3.min.js'; ?>"></script>
		<script src="<?php echo Path('js', 'abs') . 'bootstrap.min.js'; ?>"></script>
	</head>
	<!-- #header -->
	<div id="header">
		<nav class="navbar navbar-main" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<h1>
						<a class="navbar-brand" id="logo"
							href="<?php echo Path('root', 'abs') . 'index.php?flindle=true'; ?>">Pegasus</a>
					</h1>
				</div>
				<div class="collapse navbar-collapse navbar-ex1-collapse"> <?php //TODO: Remove collapse ?>
					<ul class="nav navbar-nav">
						<li>Hello</li>
					</ul>
				</div>
				<!-- End .navbar-collapse -->
			</div><!-- End .container -->
		</nav><!-- End navbar navbar-default -->
	</div><!-- End #header -->
	<body>
<?php
	}