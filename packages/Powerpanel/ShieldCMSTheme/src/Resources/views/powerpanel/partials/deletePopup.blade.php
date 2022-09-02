<!-- /.modal -->
<div class="new_modal modal fade bs-modal-md" id="confirm" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-md">
				<div class="modal-vertical">
						<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										Alert!
								</div>
								<div class="modal-body delMsg text-center"></div>
								<div class="modal-footer">
										<button type="button" id="delete" class="btn red btn-outline">@if(isset($module) && $module == 'blocked-ips') Unblock @else Delete @endif</button>
										<button type="button" class="btn btn-green-drake" data-dismiss="modal">Close</button>
								</div>
						</div>
						<!-- /.modal-content -->
				</div>
		</div>
		<!-- /.modal-dialog -->
</div>

<div class="new_modal modal fade bs-modal-md" id="confirmForAll" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-md">
				<div class="modal-vertical">
						<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										Alert!
								</div>
								<div class="modal-body delMsg text-center"></div>
								<div class="modal-footer">
										<button type="button" id="deleteAll" class="btn red btn-outline">@if(isset($module) && $module == 'blocked-ips') Unblock @else Delete @endif</button>
										<button type="button" class="btn btn-green-drake" data-dismiss="modal">Close</button>
								</div>
						</div>
						<!-- /.modal-content -->
				</div>
		</div>
		<!-- /.modal-dialog -->
</div>
<div class="new_modal modal fade bs-modal-md" id="confirmForAllBlock" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-md">
				<div class="modal-vertical">
						<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										Alert!
								</div>
								<div class="modal-body delMsg text-center"></div>
								<div class="modal-footer">
										<button type="button" id="deleteAllBlock" class="btn red btn-outline">Block</button>
										<button type="button" class="btn btn-green-drake" data-dismiss="modal">Close</button>
								</div>
						</div>
						<!-- /.modal-content -->
				</div>
		</div>
		<!-- /.modal-dialog -->
</div>
