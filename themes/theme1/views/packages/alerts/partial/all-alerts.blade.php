<style>
    .xdsoft_datetimepicker.xdsoft_noselect.xdsoft_{
        z-index: 100522;
    }
</style>
<div class="ac-modal modal fade bd-example-modal-lg composer-element-popup ckeditor-popup ckbusiness-popup" id="sectionAlertsModuleTemplate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="ac-modal-table">
        <div class="ac-modal-center">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['method' => 'post','id'=>'frmSectionAlertsModuleTemplate']) !!}
                    <input type="hidden" name="editing">
                    <input type="hidden" name="template">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span>×</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel"><b>Alerts</b></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label form_title">Caption <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('section_title', old('section_title'), array('maxlength'=>'160','class' => 'form-control','id'=>'section_title','autocomplete'=>'off')) !!}
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label form_title">Alert Type</label>
                            <select name="alertType" class="form-control bootstrap-select bs-select layout-class" id="alert-template-layout">
                                <option value="">All Alert Type</option>  
                                <option value="1">High</option>
                                <option value="2">Medium</option>
                                <option value="3">Low</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Start Date</label>
                            {!! Form::text('alert_start_date_time', old('alert_start_date_time'), array('maxlength'=>'160','class' => 'form-control','id'=>'alert_start_date_time','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">End Date</label>
                            {!! Form::text('alert_end_date_time', old('alert_end_date_time'), array('maxlength'=>'160','class' => 'form-control','id'=>'alert_end_date_time','autocomplete'=>'off')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Limit (Note: All the alerts will be shown if Limit not entered)</label>
                            {!! Form::number('section_limit', old('section_limit'), array('maxlength'=>'2','class' => 'form-control','id'=>'section_limit','autocomplete'=>'off','min'=>'1')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label form_title">Extra Class</label>
                            {!! Form::text('extra_class', old('extra_class'), array('maxlength'=>'160','class' => 'form-control','id'=>'extra_class','autocomplete'=>'off')) !!}
                        </div>
                        <div class="clearfix"></div>
                        <div class="text-right">
                            <button type="button" class="btn red btn-outline cancel-btn" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-green-drake" id="addSection">Add</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>