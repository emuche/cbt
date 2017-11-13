<!-- Modal Dialog -->
<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Action Confirmation</h4>
			</div>
			<div class="modal-body">
				<p><?php echo $delete_confirm_message = isset($delete_confirm) ? $delete_confirm : ' ' ;?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-warning" id="confirm">Continue</button>
			</div>
		</div>
	</div>
</div>