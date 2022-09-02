{!! Form::open(['method' => 'post', 'id' => 'frmWorkflowApproval']) !!}
{!! Form::hidden('workflow_type', 'approvals') !!}
{!! Form::hidden('activity', 'approvals') !!}
{!! Form::hidden('action', 'approvals') !!}
<div class="form-body">
    <div class="flow_form">
        @if (isset($workflow->varUserId))
            @php $user_selected = explode(',', $workflow->varUserId); @endphp
        @elseif(null !== old('user'))
            @php $user_selected = old('user'); @endphp
        @else
            @php $user_selected = []; @endphp
        @endif
        @php $needs_permissions = (count($user_selected) >= 2 && !in_array('', $user_selected));  @endphp
        <div class="row">
            <div class="col-md-12">
                <div class="form-group ">
                    <div class="user_fill">
                        <span>Start</span>
                    </div>
                </div>
                <div class="arrow_line"><span></span></div>
                <div class="form-group @if ($errors->first('user_roles')) has-error @endif form-md-line-input">
                    <label class="form-label" class="site_name">Select role to create workflow <span aria-required="true" class="required"> * </span></label>
                    <div class="clearfix"></div>
                    <div class="input_box">
                        @php $old_user_roles = old('user_roles') == null ? '' : old('user_roles'); @endphp
                        <select id="user_roles" name="user_roles" data-sort data-order class="form-control bs-select select2 status_select">
                            <option value="">Select Role</option>
                            @foreach ($nonAdminRoles as $role)
                                <option @if (isset($workflow->varUserRoles) && $role->id == $workflow->varUserRoles) selected @endif value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="help-block">
                        {{ $errors->first('user_roles') }}
                    </span>
                </div>
                <div class="moduls_box_approval" id="modulehtml" style="display:none">
                    <div class="arrow_line"><span></span></div>
                    <div class="spacer15"></div>
                    @if (isset($workflow->varUserId))
                        @php $selected = explode(',', $workflow->varUserId); @endphp
                    @elseif(null !== old('user'))
                        @php $selected = old('user'); @endphp
                    @else
                        @php $selected = []; @endphp
                    @endif
                    @php $needs = (count($selected) >= 2 && !in_array('', $selected));   @endphp
                    <div class="row">
                        <div class="col-5">
                            <div class="side_moduls_box">
                                <div class="form-group form-md-line-input">
                                    <label class="form-label">Module List <span class="approval_div">(Approval Needed)</span> <a href="javascript:;" id="approvalid">Select All</a></label>
                                    <div class="select_moduls_box">
                                        <select name="catwise_modules[]" id="undo_redo" class="form-select" size="" multiple="multiple"></select>
                                    </div>
                                </div>
                                <div id="errorToShow"></div>
                                <div class="arrow_line"><span></span></div>
                                <img class="image-need" src="{{ $CDN_PATH . 'resources/image/packages/workflow/workflow1.png' }}" name="Module List (Approval Needed)">
                                <div class="arrow_line"><span></span></div>

                                <div class="form-group form-md-line-input">
                                    <div class="input_box @if ($errors->first('user')) has-error @endif ">
                                        <label class="form-label site_name">Select Admins<span aria-required="true" class="required"> * </span></label>
                                        <select id="user" data-choices name="user[]" class="form-control" multiple>
                                            <option style="width:100% !important;" value="">Select Admin</option>
                                        </select>
                                        <span class="help-block">{{ $errors->first('user') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="moduls_select_buttons clearfix">
                                <button type="button" id="undo_redo_rightAll" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1"><i class="ri-speed-fill"></i></button>
                                <button type="button" id="undo_redo_rightSelected" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1"><i class="ri-arrow-right-s-line"></i></button>
                                <button type="button" id="undo_redo_leftSelected" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1"><i class="ri-arrow-left-s-line"></i></button>
                                <button type="button" id="undo_redo_leftAll" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1"><i class="ri-rewind-fill"></i></button>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="side_moduls_box">
                                <div class="form-group form-md-line-input">
                                    <label class="form-label">Module List <span class="noapproval_div">(No Approval Needed)</span> <a href="javascript:;" id="noapprovalid">Select All</a></label>
                                    <div class="select_moduls_box">
                                        <select name="directApproved[]" id="undo_redo_to" class="form-control" size="" multiple="multiple"></select>
                                    </div>
                                </div>
                                <div class="arrow_line"><span></span></div>
                                <img class="image-need" src="{{ $CDN_PATH . 'resources/image/packages/workflow/workflow2.png' }}" name="Module List (Approval Needed)">
                                <div class="arrow_line"><span></span></div>
                                <div class="form-group form-md-line-input">
                                    <div class="input_box">
                                        <div class="row_inp_rh">
                                            <label class="form-label">Direct Approved</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-12 text-center">
            <button type="submit" name="saveandexit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                    </div>
                    <div class="flex-grow-1">
                        {!! trans('workflow::template.common.saveandexit') !!}
                    </div>
                </div>
            </button>
            <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/workflow') }}">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                    </div>
                    <div class="flex-grow-1">
                        {{ trans('workflow::template.common.cancel') }}
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
{!! Form::close() !!}
