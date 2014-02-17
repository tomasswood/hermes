<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	echo "<script type='text/javascript' src='" . js_url() . 'sidebar.js' . "'></script>";
?>
<div id="sidebar" class="expanded">
	<div class="sidebar-inner">
		<ul>
			<li>
				<span class="heading">Element Data</span>
				<ul id="element-data" class="sidebar-submenu expanded">
					<li id="selected-id" class="text-center">No element currently selected.</li>
					<li class="value">
						<label>Value</label>
						<textarea id="element-attr-value" class="form-control" type="text"></textarea>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</div>