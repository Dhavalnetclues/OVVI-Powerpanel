<div class="new_modal modal fade" style="display: none" id="modalForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-vertical">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Quick Edit</h4>
                </div>
                <!-- Modal Body -->
                <div class="modal-body form_pattern">
                    {!! Form::open(['method' => 'post','class'=>'QuickEditForm','id'=>'QuickEditForm']) !!}
                    {!! Form::hidden('id','',array('id' => 'id')) !!}
                    {!! Form::hidden('quickedit','',array('id' => 'quickedit')) !!}
                    <div class="form-group">
                        <label for="name">Name <span aria-required="true" class="required"> * </span></label>
                        {!! Form::text('name',  old('name') , array('id' => 'name', 'class' => 'form-control', 'placeholder'=>'Enter your name')) !!}
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form_title">Search Ranking</label>
                            <a href="javascript:;" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('template.common.SearchEntityTools') }}" title="{{ trans('template.common.SearchEntityTools') }}"><i class="fa fa-question"></i></a>
                            <div class="wrapper search_rank">
                                <label for="yes_radio" id="yes-lbl">High</label><input type="radio" value="1" name="search_rank" id="yes_radio">
                                <label for="maybe_radio" id="maybe-lbl">Medium</label><input type="radio" value="2" name="search_rank" id="maybe_radio">
                                <label for="no_radio" id="no-lbl">Low</label><input type="radio" value="3" name="search_rank" id="no_radio">
                                <div class="toggle"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-md-line-input">
                                <label class="control-label form_title">{{ trans('template.common.startDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                <div class="input-group date form_meridian_datetime" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                    <span class="input-group-btn date_default">
                                        <button class="btn date-set fromButton" type="button">
                                            <i class="ri-calendar-line"></i>
                                        </button>
                                    </span>
                                    {!! Form::text('start_date_time', isset($TableName->dtDateTime)?date('Y-m-d H:i',strtotime($TableName->dtDateTime)):date('Y-m-d H:i'), array('class' => 'form-control','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-md-line-input">
                                <div class="input-group date  form_meridian_datetime expirydate" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                    <label class="control-label form_title" >{{ trans('template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                    <div class="pos_cal">
                                        <span class="input-group-btn date_default">
                                            <button class="btn date-set toButton" type="button">
                                                <i class="ri-calendar-line"></i>
                                            </button>
                                        </span>
                                        {!! Form::text('end_date_time', isset($TableName->dtEndDateTime)?date('Y-m-d H:i',strtotime($TableName->dtEndDateTime)):date('Y-m-d H:i'), array('class' => 'form-control','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> '','data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                    </div>
                                </div>
                                <label class="expdatelabel">
                                    <a id="noexpiry" name="noexpiry" href="javascript:void(0);">
                                        <b class="expiry_lbl"></b>
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-green-drake" id="quick_submit" value="saveandexit">Submit</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>