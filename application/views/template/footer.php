<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<?php
			if ($displayType == 'app')
			{
				//<!-- Issue collector for Jira -->
				//echo '<script type="text/javascript" src="https://flindle.atlassian.net/s/d41d8cd98f00b204e9800998ecf8427e/en_UKnh8jyk-1988229788/6207/13/1.4.3/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?collectorId=6075ec01"></script>';
			}
			else
			{
				$this->load->view('template/site-footer');
			}
		?>
		<!-- Active Buttons -->
		<script type="text/javascript">
			$(document).ready(function() {
				//Hack so that our Panels can have an active class
				$('.panel-buttons .btn').click(function() {
					$('.panel-buttons > .btn').removeClass('active');
					$(this).addClass('active');
				});
			});
			function changeService(alias)
			{
				// Reload the page with the Business alias as a parameter
				window.location = window.location + '?alias=' + alias;
			}
			//Hack to change our Active Menu item
			function changeActive(id) {
				$("[id^=menu-]").removeClass("active");
				$("#menu-" + id).addClass("active");
			}
		</script>
	</body>
</html>