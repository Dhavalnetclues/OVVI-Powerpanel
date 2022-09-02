<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalForm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header mb-2">
                <h5 class="modal-title" id="myModalLabel">New message</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {!! Form::open(['method' => 'post','class'=>'QuickEditForm','id'=>'QuickEditForm']) !!}
            {!! Form::hidden('id','',array('id' => 'id')) !!}
            {!! Form::hidden('quickedit','',array('id' => 'quickedit')) !!}
            <div class="modal-body form_pattern">
                <div class="row">
                    <div class="col-12">
                        <div class="cm-floating">
                            <label for="name">Name <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('name',  old('name') , array('id' => 'name', 'class' => 'form-control' )) !!}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-30 pb-2">
                            <label class="col-form-label mt-0">Search Ranking</label>
                            <a href="javascript:;" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('template.common.SearchEntityTools') }}" title="{{ trans('template.common.SearchEntityTools') }}"><i class="ri-question-line fs-16"></i></a>
                            <div class="wrapper search_rank">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="search_rank" id="yes_radio" value="1">
                                    <label class="form-check-label" for="yes_radio" id="yes-lbl">High</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="search_rank" id="maybe_radio" value="2">
                                    <label class="form-check-label" for="maybe_radio" id="maybe-lbl">Medium</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="search_rank" id="no_radio" value="3">
                                    <label class="form-check-label" for="no_radio" id="no-lbl">Low</label>
                                </div>
                                <div class="toggle"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">    
                    <div class="col-lg-6 col-sm-12">
                        <div class="cm-floating">
                            <label class="control-label form-label">
                                {{ trans('template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span>
                            </label>
                            <div class="input-group date form_meridian_datetime" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                {{-- <span class="input-group-text"><i class="ri-calendar-fill"></i></span> --}}
                                {!! Form::text('start_date_time', isset($TableName->dtDateTime)?date('Y-m-d H:i',strtotime($TableName->dtDateTime)):date('Y-m-d H:i'), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                            </div>
                        </div>
                    </div>                    
                    <div class="col-lg-6 col-sm-12">
                        <div class="expirydate" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                            <div class="cm-floating input-group date form_meridian_datetime" >
                                <label class="control-label form-label">
                                    {{ trans('template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span>
                                </label>
                                {{-- <span class="input-group-text"><i class="ri-calendar-fill"></i></span> --}}
                                {!! Form::text('end_date_time', isset($TableName->dtEndDateTime)?date('Y-m-d H:i',strtotime($TableName->dtEndDateTime)):date('Y-m-d H:i'), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> '','data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                            </div>
                        </div>
                        <label class="expdatelabel form-label m-0 no_expiry">
                            <a id="noexpiry" name="noexpiry" href="javascript:void(0);">
                                <b class="expiry_lbl"></b>
                            </a>
                        </label>
                    </div>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label" id="quick_submit" value="saveandexit" title="Submit">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-send-plane-line label-icon align-middle fs-20 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                Submit
                            </div>
                        </div>                        
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>