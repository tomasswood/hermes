<?php

	function pageSidebar($type = null)
	{
		//Put our sidebar javascript into any pages that use the sidebar
		echo "<script type='text/javascript' src='" . Path('js', 'abs') . 'sidebar.js' . "'></script>";
		buildSidebar();
	}

	function buildSidebar()
	{
		?>

		<div id="sidebar" class="expanded">
			<div class="sidebar-inner">
				<ul>

					<li>
						<span class="heading">Element Data</span>
						<ul id="element-data" class="sidebar-submenu">
							<p class="text-center">No element currently selected.</p>
						</ul>
					</li>
				</ul>

			</div>
		</div>

	<?php
	}


