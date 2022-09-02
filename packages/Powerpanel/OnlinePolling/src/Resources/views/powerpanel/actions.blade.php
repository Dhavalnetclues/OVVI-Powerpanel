@extends('powerpanel.layouts.app')
@section('title')
    {{ Config::get('Constant.SITE_NAME') }} - PowerPanel
@stop
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp
@include('powerpanel.partials.breadcrumbs')
<div class="col-md-12 settings">
    <div class="row">
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
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="tabbable tabbable-tabdrop">
                    <div class="tab-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet-body form_pattern">
                                    {!! Form::open(['method' => 'post','id'=>'frmonlinepolling']) !!}
                                    {!! Form::hidden('fkMainRecord', isset($onlinepolling->fkMainRecord)?$onlinepolling->fkMainRecord:old('fkMainRecord')) !!}
                                    
                                    @if(isset($onlinepolling))
                                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                            @include('powerpanel.partials.lockedpage',['pagedata' => $onlinepolling])
                                        @endif
                                    @endif

                                    <div class="form-group @if($errors->first('tag_line')) has-error @endif form-md-line-input">
                                        @php if(isset($onlinepolling_highLight->varTitle) && ($onlinepolling_highLight->varTitle != $onlinepolling->varTitle)){
                                        $Class_title = " highlitetext";
                                        }else{
                                        $Class_title = "";
                                        } @endphp
                                        <label class="form_title {{ $Class_title }}" for="site_name">{{ trans('template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('title', isset($onlinepolling->varTitle) ? $onlinepolling->varTitle:old('title'), array('maxlength'=>'150','placeholder' => trans('template.common.title'),'class' => 'form-control seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                        <span class="help-block">
                                            {{ $errors->first('title') }}
                                        </span>
                                    </div>

                                    @php 
                                        if(isset($onlinepolling_highLight->txtQuestionData) && ($onlinepolling_highLight->txtQuestionData != $onlinepolling->txtQuestionData)){
                                            $Class_txtQuestionData = " highlitetext";
                                            $Class_txtCOLOR = "#ece743";

                                        }else{
                                            $Class_txtQuestionData = "";
                                            $Class_txtCOLOR = "";
                                        }
                                    @endphp
                                    <h3 class="form-section" style="background-color:{{ $Class_txtCOLOR }}">Poll Questions</h3> 
                                    <div style="padding:10px;border:1px solid #1080f2;border-radius:10px;margin:15px 0;">
                                        <div class="row">
                                            @if(isset($onlinepolling->txtQuestionData) && !empty($onlinepolling->txtQuestionData))
                                                @php 
                                                    $txtQuestionData = json_decode($onlinepolling->txtQuestionData, true);
                                                @endphp 
                                                <div id="question_list">
                                                    @foreach($txtQuestionData as $key => $value)
                                                        <div class="question" id="que_{{ $key }}">
                                                            <div class="col-md-8">
                                                                <div class="form-group form-md-line-input">
                                                                    <label class="form_title">Question <span aria-required="true" class="required"> * </span></label>
                                                                    <input maxlength='150' id="q_{{ $key }}" placeholder='Enter your question' value="{{ $value['question'] }}" class='form-control maxlength-handler' autocomplete='off' name='question[{{ $key }}][question]' type='text' />
                                                                    <span class="help-block"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group form-md-line-input">
                                                                    <label class="form_title">Answer Choice <span aria-required="true" class="required"> * </span></label>
                                                                    <select id="q_c_{{ $key }}" class='form-control answer_choice' data-id="{{ $key }}" name='question[{{ $key }}][question_choice]'>
                                                                        <option {{ ($value['question_choice'] == 'TX'?'selected':'')  }} value='TX'>Single Textbox</option>
                                                                        <option {{ ($value['question_choice'] == 'CB'?'selected':'')  }} value='CB'>Checkbox</option>
                                                                        <option {{ ($value['question_choice'] == 'RD'?'selected':'')  }} value='RD'>Radio</option>
                                                                    </select>
                                                                    <span class="help-block"></span>
                                                                </div> 
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" data-id="{{ $key }}" class="btn btn-green-drake" id="remove_question" style="margin-top:20px;">Remove</a>
                                                            </div>
                                                        </div>
                                                        <div id="answer_choice_{{ $key }}">
                                                            @if(isset($value['options']) && !empty($value['options'])) 
                                                                @foreach($value['options'] as $okey => $ovalue)
                                                                    <div class="choice_option" id="choice_opt_{{ $okey }}">
                                                                        <div class="col-md-10">
                                                                            <div class="form-group form-md-line-input">
                                                                                <input id="opt_{{ $okey }}" placeholder='Enter your answer' value="{{ $ovalue }}" class='form-control' autocomplete='off' name='question[{{ $key }}][options][]' type='text' />
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <button type="button" data-id="{{ $key }}" class="btn add_option"><i class="fa fa-plus" ></i></a>
                                                                            <button type="button" data-id="{{ $okey }}" class="btn remove_option"><i class="fa fa-minus"></i></a>
                                                                        </div>
                                                                    </div>
                                                                @endforeach 
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div id="question_list"></div>
                                            @endif    
                                            <div class="col-md-12 text-center">
                                                <input type=button id="add_question" value="Add New Question" class="btn btn-green-drake">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">    
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input">
                                                @php 
                                                    if(isset($onlinepolling_highLight->intAudienceLimit) && ($onlinepolling_highLight->intAudienceLimit != $onlinepolling->intAudienceLimit)){
                                                        $Class_intAudienceLimit = " highlitetext";
                                                    }else{
                                                        $Class_intAudienceLimit = "";
                                                    } 
                                                @endphp
                                                <label class="form_title {{ $Class_intAudienceLimit }}">Audience limit <span aria-required="true" class="required"> * </span></label>
                                                <select class='form-control' name='audience_limit'>
                                                    <option {{ (isset($onlinepolling->intAudienceLimit) && $onlinepolling->intAudienceLimit == '50'?'selected':'')  }} value='50'>50</option>
                                                    <option {{ (isset($onlinepolling->intAudienceLimit) && $onlinepolling->intAudienceLimit == '100'?'selected':'')  }} value='100'>100</option>
                                                    <option {{ (isset($onlinepolling->intAudienceLimit) && $onlinepolling->intAudienceLimit == '200'?'selected':'')  }} value='200'>200</option>
                                                    <option {{ (isset($onlinepolling->intAudienceLimit) && $onlinepolling->intAudienceLimit == '500'?'selected':'')  }} value='500'>500</option>
                                                    <option {{ (isset($onlinepolling->intAudienceLimit) && $onlinepolling->intAudienceLimit == '1000'?'selected':'')  }} value='1000'>1000</option>
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>    
                                    </div> 

                                    <h3 class="form-section">{{ trans('template.common.ContentScheduling') }}</h3>
                                    @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date('Y-m-d H:i'); @endphp
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input">
                                                @php if(isset($onlinepolling_highLight->dtDateTime) && ($onlinepolling_highLight->dtDateTime != $onlinepolling->dtDateTime)){
                                                $Class_date = " highlitetext";
                                                }else{
                                                $Class_date = "";
                                                } @endphp
                                                <label class="control-label form_title {!! $Class_date !!}">{{ trans('template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                                <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                    <span class="input-group-btn date_default">
                                                        <button class="btn date-set fromButton" type="button">
                                                            <i class="ri-calendar-line"></i>
                                                        </button>
                                                    </span>
                                                    {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($onlinepolling->dtDateTime)?$onlinepolling->dtDateTime:$defaultDt)), array('class' => 'form-control','maxlength'=>160,'size'=>'16','id'=>'poll_start_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                                </div>
                                                <span class="help-block">
                                                    {{ $errors->first('start_date_time') }}
                                                </span>
                                            </div>
                                        </div>
                                        @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                        @if ((isset($onlinepolling->dtEndDateTime)==null))
                                        @php
                                        $expChecked_yes = 1;
                                        $expclass='';
                                        @endphp
                                        @else
                                        @php
                                        $expChecked_yes = 0;
                                        $expclass='no_expiry';
                                        @endphp
                                        @endif
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input">
                                                <div class="input-group date  form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                     @php if(isset($onlinepolling_highLight->varTitle) && ($onlinepolling_highLight->dtEndDateTime != $onlinepolling->dtEndDateTime)){
                                                        $Class_end_date = " highlitetext";
                                                     }else{
                                                        $Class_end_date = "";
                                                     } @endphp
                                                     <label class="control-label form_title {!! $Class_end_date !!}" >{{ trans('template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                                    <div class="pos_cal">
                                                        <span class="input-group-btn date_default">
                                                            <button class="btn date-set toButton" type="button">
                                                                <i class="ri-calendar-line"></i>
                                                            </button>
                                                        </span>
                                                        {!! Form::text('end_date_time', isset($onlinepolling->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($onlinepolling->dtEndDateTime)):$defaultDt, array('class' => 'form-control','maxlength'=>160,'size'=>'16','id'=>'poll_end_date','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                                    </div>
                                                </div>
                                                <span class="help-block">
                                                    {{ $errors->first('end_date_time') }}
                                                </span>
                                                <label class="expdatelabel {{ $expclass }}">
                                                    <a id="noexpiry" name="noexpiry" href="javascript:void(0);">
                                                        <b class="expiry_lbl {!! $Class_end_date !!}"></b>
                                                    </a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="form-section">{{ trans('template.common.displayinformation') }}</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group @if($errors->first('display_order')) has-error @endif form-md-line-input">
                                                @php
                                                $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('template.common.displayorder'),'autocomplete'=>'off');
                                                @endphp
                                                @php if(isset($onlinepolling_highLight->intDisplayOrder) && ($onlinepolling_highLight->intDisplayOrder != $onlinepolling->intDisplayOrder)){
                                                $Class_displayorder = " highlitetext";
                                                }else{
                                                $Class_displayorder = "";
                                                } @endphp
                                                <label class="form_title {!! $Class_displayorder !!}" for="site_name">{{ trans('template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                                {!! Form::text('display_order', isset($onlinepolling->intDisplayOrder)?$onlinepolling->intDisplayOrder:$total, $display_order_attributes) !!}
                                                <span style="color: red;">
                                                    {{ $errors->first('display_order') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            @if(isset($onlinepolling_highLight->chrPublish) && ($onlinepolling_highLight->chrPublish != $onlinepolling->chrPublish))
                                                @php $Class_chrPublish = " highlitetext"; @endphp
                                            @else
                                                @php $Class_chrPublish = ""; @endphp
                                            @endif

                                            @if(isset($onlinepolling) && $onlinepolling->chrAddStar == 'Y')
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label form_title"> Publish/ Unpublish</label>
                                                        <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($onlinepolling->chrPublish) ? $onlinepolling->chrPublish : '' }}">
                                                        <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                                    </div>
                                                </div>
                                            @elseif(isset($onlinepolling) && $onlinepolling->chrDraft == 'D' && $onlinepolling->chrAddStar != 'Y')
                                                @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($onlinepolling->chrDraft)?$onlinepolling->chrDraft:'D')])
                                            @else
                                                @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($onlinepolling->chrPublish)?$onlinepolling->chrPublish:'Y')])
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @if(isset($onlinepolling->fkMainRecord) && $onlinepolling->fkMainRecord != 0)
                                                <button type="submit" name="saveandexit" class="btn btn-green-drake" value="saveandexit">{!! trans('template.common.approve') !!}</button>
                                                @else
                                                @if($userIsAdmin)
                                                <button type="submit" name="saveandedit" class="btn btn-green-drake" value="saveandedit">{!! trans('template.common.saveandedit') !!}</button>
                                                <button type="submit" name="saveandexit" class="btn btn-green-drake" value="saveandexit">{!! trans('template.common.saveandexit') !!}</button>
                                                @else
                                                <button type="submit" name="saveandexit" class="btn btn-green-drake" value="approvesaveandexit">{!! trans('template.common.approvesaveandexit') !!}</button>
                                                @endif  
                                                @endif
                                                <a class="btn red btn-outline" href="{{ url('powerpanel/online-polling') }}">{{ trans('template.common.cancel') }}</a>
                                            </div>
                                        </div>
                                    </div>    
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
@endsection
@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var user_action = "{{ isset($onlinepolling)?'edit':'add' }}";
    var moduleAlias = 'online-polling';
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/poll/poll_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/numbervalidation.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
@endsection