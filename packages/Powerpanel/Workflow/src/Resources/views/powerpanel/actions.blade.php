@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/css/packages/workflow/workflow.css' }}" rel="stylesheet" type="text/css" />
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp
@section('content')

<div class="row">
	<div class="col-sm-12">
		@if(Session::has('message'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ Session::get('message') }}
		</div>
		@endif
		@if(Session::has('error'))
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			{{ Session::get('error') }}
		</div>
		@endif
		
        <div class="card">
            <div class="card-body">
                <div class="live-preview" id="general">
					@php $workflow = isset($workflow)?$workflow:null; @endphp

					@if(!isset($workflow->varType))
						<div class="approvals">
							@include('workflow::powerpanel.partials.user-approvals',['workflow'=>$workflow,'adminUsers'=>$adminUsers,'moduleCategory'=>$moduleCategory,'approvalWorkFlows'=>$approvalWorkFlows])
						</div>
					@elseif($workflow->varType == "leads")
						<div class="leads">@include('workflow::powerpanel.partials.leads',['workflow'=>$workflow])</div>
					@elseif($workflow->varType == "approvals")
						<div class="approvals">@include('workflow::powerpanel.partials.user-approvals',['workflow'=>$workflow])</div>
					@endif
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
window.site_url =  '{!! url("/") !!}';
var seoFormId = 'frmWorkflow';
var user_action = "{{ isset($workflow)?'edit':'add' }}";
var moduleAlias = 'workflow';
var categoryAllowed = false;
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<!-- BEGIN CORE PLUGINS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap/js/bootstrap.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js' }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/workflow/workflow-validations.js' }}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
		$('#undo_redo').multiselect();
});
$('#approvalid').click(function() {
    $('#undo_redo option').prop('selected', true);
});
$('#noapprovalid').click(function() {
    $('#undo_redo_to option').prop('selected', true);
});
</script>     
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/workflow/prettify.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/workflow/multiselect.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
@endsection