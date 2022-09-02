<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" id="confirm" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Alert!</h5>
				<button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body delMsg text-center"></div>
			<div class="modal-footer justify-content-center">
				<button type="button" id="delete" class="btn btn-danger bg-gradient waves-effect waves-light btn-label">
					<div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-delete-bin-line label-icon align-middle fs-20 me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            @if(isset($module) && $module == 'blocked-ips') Unblock @else Delete @endif
                        </div>
                    </div>					
				</button>
				<button type="button" class="btn btn-light bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
					<div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            Close
                        </div>
                    </div>
				</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" id="confirmForAll" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Alert!</h5>
				<button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body delMsg text-center"></div>
			<div class="modal-footer justify-content-center">
				<button type="button" id="deleteAll" class="btn btn-danger bg-gradient waves-effect waves-light btn-label">
					<div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-delete-bin-line label-icon align-middle fs-20 me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            @if(isset($module) && $module == 'blocked-ips') Unblock @else Delete @endif
                        </div>
                    </div>					
				</button>
				<button type="button" class="btn btn-light bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
					<div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            Close
                        </div>
                    </div>
				</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" id="confirmForAllBlock" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Alert!</h5>
				<button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body delMsg text-center"></div>
			<div class="modal-footer">
				<button type="button" id="deleteAllBlock" class="btn btn-danger bg-gradient waves-effect waves-light btn-label">
					<div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-delete-bin-line label-icon align-middle fs-20 me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            @if(isset($module) && $module == 'blocked-ips') Unblock @else Delete @endif
                        </div>
                    </div>					
				</button>
				<button type="button" class="btn btn-light bg-gradient waves-effect waves-light btn-label" data-bs-dismiss="modal">
					<div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            Close
                        </div>
                    </div>
				</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div><!-- /.modal -->
