<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="left-toolbar">
	<ul class="menu">
		<li id="toolbar-btn-generate">Generate</li>
		<li id="toolbar-btn-element-textbox"><?php $this->load->view('template/element-selector', array("text" => "Textbox")); ?></li>
		<li id="toolbar-btn-element-image"><?php $this->load->view('template/element-selector', array("text" => "Image")); ?></li>
	</ul>
</div>