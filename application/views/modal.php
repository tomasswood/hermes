<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Modal for Viewing a Booking -->
<div class="modal fade" id="modal-blank" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 id="modal-blank-title" class="modal-title"></h4>
			</div>
			<div id="modal-blank-body" class="modal-body">

			</div>
			<!-- /.modal-body -->
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
	function showModal(title, data)
	{
		$('#modal-blank').modal();
		$('#modal-blank-title').html(title);
		$('#modal-blank-body').html(data);
	}
</script>