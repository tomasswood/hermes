<?php
	require_once('includes/includes.php');
	pageHeader();
?>
	<script type="text/javascript" src="<?php echo  Path("js", "abs") ?>page.js"></script>
	<script type="text/javascript" src="<?php echo  Path("doc", "abs") ?>Page.js"></script>
	<script type="text/javascript" src="<?php echo  Path("doc", "abs") ?>Sidebar.js"></script>

    <?php  editorToolbar() ; ?>
    <?php  leftToolbar() ; ?>

	<div id="main">
		<div id="page-container">
			<div id="page-1" class="page"></div>
		</div>

	</div>

	<?php  pageSidebar() ; ?>


<?php pageFooter(); ?>