<?php

	function pageToolbar($type = null)
	{
		buildToolbar();
	}

	function buildToolbar()
	{
		?>

		<div id="toolbar">
			<ul class="menu">
				<li id="toolbar-btn-generate">Generate</li>
				<li id="toolbar-btn-element-textbox">Textbox</li>
				<li>Image</li>
			</ul>
		</div>

	<?php
	}


