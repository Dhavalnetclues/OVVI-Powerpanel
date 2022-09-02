@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
<section class="inner-page-gap careers-listing">
    @include('layouts.share-email-print')
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-5" data-aos="fade-right">
                <h2 class="nqtitle">What is the aim of Online Polling? And how to benefit you?</h2>
                <div class="cms n-mt-15">
                    <p>Our aim is how can we improve your services, that's why we have created an online polling platform and you can vote online and tell us about what you are thinking so that we can work on it and make your service better.</p>
                    <p class="n-fc-black-500"><strong>Dr. The Hon. Linford A. Pierson</strong><br><span class="n-fc-a-500">- Chairman of the Board</span></p>
                </div>
            </div>
            <div class="col-lg-5 n-mt-25 n-mt-lg-0" data-aos="fade-left">
                <div class="thumbnail-container">
                    <div class="thumbnail">
                        <img src="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(isset($data['pollObj']) && !empty($data['pollObj']))
<section class="inner-page-gap careers-jobs n-pt-40 n-pt-lg-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 text-center" data-aos="fade-up">
                <h2 class="nqtitle">Online Polling</h2>
                <div class="cms n-mt-15">
                    <p>Give us your valuable vote and help us improve our service and your vote is very important for us, if you want, you can also share it with your friend and take the vote. And the vote you share will be kept confidential and will not be shared with anyone.</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center n-mt-25 n-mt-lg-50">
            @foreach($data['pollObj'] as $poldata)
            <div class="col-xl-3 col-lg-4 col-md-6 d-flex n-gapp-xl-5 n-gapm-xl-4 n-gapm-lg-3 n-gapm-md-1" data-aos="zoom-in">
                <article class="-items n-bs-1 w-100 n-pa-30 n-bgc-white-500 d-flex flex-column">
                    <h3 class="-title n-fs-22 n-fw-500 n-ff-1 n-fc-balck-500 n-lh-110 n-ti-05">{{$poldata->varTitle}}</h3>
                    <div class="n-fs-14 n-fw-500 n-fc-dark-500 n-lh-110 n-mt-5">Created:{{ date('M',strtotime($poldata->dtDateTime)) }} {{ date('d',strtotime($poldata->dtDateTime)) }}, {{ date('Y',strtotime($poldata->dtDateTime)) }}</div>

                    <div class="row mt-auto n-pt-45">
                        <div class="col-7">
                            <a href="javascript:void(0)" class="ac-btn ac-btn-primary ac-small" title="Start Polling" data-toggle="modal" data-target="#{{str_slug($poldata->varTitle)}}">Start Polling</a>
                        </div>
                        <div class="col-5 text-right">
                            <div class="d-inline-flex align-items-center">
                                <i class="n-icon" data-icon="s-users"></i>
                                <div class="n-ml-10 text-left">
                                    <span class="d-block n-fs-14 n-fw-500 n-lh-110 n-fc-dark-500">Audience</span>
                                    <div class="n-fs-14 n-fw-600 n-fc-dark-500">40/100</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
        @if($data['pollObj']->total() > $data['pollObj']->perPage())
            <div class="row">
                <div class="col-12">
                    <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
                        @include('partial.pagination', ['paginator' => $data['pollObj']->links()['paginator']])
                    </div>
                </div>
            </div>
        @endif    
    </div>
</section>
@endif
<!-- Online Polling S -->
@foreach($data['pollObj'] as $poldata)
<div class="modal fade ac-modal" id="{{str_slug($poldata->varTitle)}}" tabindex="-1" aria-labelledby="onlinepollinglabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="n-fs-18 n-fw-600 n-ff-2 n-fc-white-500 n-lh-130">Online Polling</div>
                <a href="javascript:void(0)" data-dismiss="modal" aria-label="Close" class="ac-close">&times;</a>
            </div>

            <div class="modal-body ac-form-wd">
                 {!! Form::open(['method' => 'post','class'=>'ac-form-wd online_poll_form','id'=>'online_poll_form']) !!}
                 <input type="hidden" id="{{$poldata->id}}" name="poll_id" value="{{$poldata->id}}" >

                <div class="row">
                    <div class="col-sm-12 n-mb-45 text-center">
                        <h2 class="nqtitle-small n-fw-500">{{$poldata->varTitle}}</h2>
                        <div class="n-fs-14 n-fw-500 n-fc-dark-500 n-lh-110 n-mt-5">Created: {{ date('M',strtotime($poldata->dtDateTime)) }} {{ date('d',strtotime($poldata->dtDateTime)) }}, {{ date('Y',strtotime($poldata->dtDateTime)) }}</div>
                    </div>
                    @php
                    $question= json_decode($poldata->txtQuestionData);
                    @endphp
                    @foreach($question as $key => $que)
                    @if($que->question_choice == 'RD')
                    <div class="col-12">
                        <div class="form-group ac-form-group ac-active-label">
                            <label class="ac-label">{{$que->question}}<span class="star">*</span></label>
                            <div class="ac-radio-inline">
                                @php
                                $options = array();
                                if(isset($que->options)){
                                $options = $que->options;
                                }
                                @endphp
                                @foreach($options as $key => $optval)
                                <label class="ac-radio">
                                    @php
                                    $var = str_slug($que->question,'_');
                                    
                                    @endphp
                                    <input type="radio" name="{{$var}}"  value="{{$optval}}"> {{$optval}}<span></span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @elseif(isset($que->question_choice) && $que->question_choice == 'CB')
                    <div class="col-12">
                        <div class="form-group ac-form-group ac-active-label">
                            <label class="ac-label">{{$que->question}}<span class="star">*</span></label>
                            <div class="ac-checkbox-inline">
                                @php
                                $options = array();
                                if(isset($que->options)){
                                $options = $que->options;
                                }
                                @endphp
                                @foreach($options as $key => $optval)

                                <label class="ac-checkbox">
                                    @php
                                    $var = str_slug($que->question,'_');
                                    @endphp
                                    <input type="checkbox" name="{{$var}}[]" value="{{$optval}}"> {{$optval}}<span></span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($que->question_choice == 'TX')

                    <div class="col-12">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="first_name"> {{$que->question}}<span class="star">*</span></label>
                             @php
                                    $var = str_slug($que->question,'_');
                                    @endphp
                            {!! Form::text($var, old($var), array('id'=> $var, 'class'=>'form-control ac-input','maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                           
                        </div>
                    </div>
                    @endif
                    @endforeach
                    <div class="col-12">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="message">Message</label>
                            <textarea class="form-control ac-textarea" id="message" name="message" rows="4" minlength="1" maxlength="600" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <img src="{{ $CDN_PATH.'assets/images/google-captcha.gif' }}" alt="Google Captcha">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group n-tar-sm n-tal">
                            <button type="submit" title="Submit Poll" class="ac-btn ac-btn-primary">Submit Poll</button>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group ac-form-group n-mb-0">
                            <div class="ac-note">
                                <b>Note:</b> The vote you share will be kept confidential and will not be shared with anyone and If you have any further queries, please do not hesitate to revert as it will be our pleasure to assist you. <a href="#" target="_blank" title="Contact Detail">Contact Detail</a>.
                            </div>
                        </div>
                    </div>
                </div>
                  {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
@section('page_scripts')
<!-- <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script> -->
@endsection