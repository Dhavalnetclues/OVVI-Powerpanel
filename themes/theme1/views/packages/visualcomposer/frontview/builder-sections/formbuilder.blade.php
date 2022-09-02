<link rel="stylesheet" href="{{ $CDN_PATH.'assets/css/form-builder.css' }}" media="all" />
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-timepicker/css/timepicki.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css' }}" rel="stylesheet" type="text/css" />
@php
if(isset($data['formTotalDetails']->fkIntImgId) && $data['formTotalDetails']->fkIntImgId != ''){
$formbackImg = App\Helpers\resize_image::resize($data['formTotalDetails']->fkIntImgId);
}
@endphp
@if(isset($data))
@if(isset($data['formTotalDetails']->fkIntImgId) && $data['formTotalDetails']->fkIntImgId != '')
<div style="background-image: url('{{ $formbackImg }}');background-repeat: no-repeat;min-height: 380px;background-size: cover;position: relative;background-position: center;">
    @endif
    {!! Form::open(['method' => 'POST','url'=> url('/formbuildersubmit'),'class'=>'form_builder','id'=>'formbuildername','enctype'=>'multipart/form-data']) !!}
    <!--<form class="form_builder" action="{{ url('/formbuildersubmit') }}" method="POST" name='formbuildername' id='formbuildername' enctype="multipart/form-data">-->
    {{ csrf_field() }}

    <input type="hidden" name='fkformbuilderid' id='fkformbuilderid' value='<?php echo $data['formid']; ?>'>
    @php
    /*echo "<pre/>";
    print_r($data); 
    exit; */
    @endphp
    @if(isset($data['formdata']))
    @foreach($data['formdata'] as $formdata)
    <!-- Hidden Input -->
    @if($formdata['type'] == 'hidden')
    <input type="{{ $formdata['type'] }}" name="{{ $formdata['name'] }}" value="{{ $formdata['value'] }}" class="form-control ac-input">  
    @endif

    <!-- Header Tag -->
    @if($formdata['type'] == 'header')
    <div class="form-group">
        @if(isset($formdata['subtype']) && $formdata['subtype'] == 'h1')
        <h1>{{ $formdata['label'] }}</h1>
        @elseif(isset($formdata['subtype']) && $formdata['subtype'] == 'h2')
        <h2>{{ $formdata['label'] }}</h2>
        @elseif(isset($formdata['subtype']) && $formdata['subtype'] == 'h3')
        <h3>{{ $formdata['label'] }}</h3>
        @elseif(isset($formdata['subtype']) && $formdata['subtype'] == 'h4')
        <h4>{{ $formdata['label'] }}</h4>
        @elseif(isset($formdata['subtype']) && $formdata['subtype'] == 'h5')
        <h5>{{ $formdata['label'] }}</h5>
        @elseif(isset($formdata['subtype']) && $formdata['subtype'] == 'h6')
        <h6>{{ $formdata['label'] }}</h6>
        @endif
    </div>  
    @endif

    <!-- Paragraph -->
    @if($formdata['type'] == 'paragraph')
    <div class="form-group paragraphtext">
        {!! $formdata['label'] !!}
    </div>
    @endif

    <!-- Checkbox Group -->
    @if($formdata['type'] == 'checkbox-group')
    <div class="form-group"> 
        @if(isset($formdata['className']) && $formdata['className'] == 'predefine')
        @else
        <label class="label-title">{{ $formdata['label'] }}
            @if($formdata['required'] == '1')
            <span>*</span>
            @endif
            @if(isset($formdata['description']))
            @php $description = $formdata['description'];@endphp
            <a href="#" data-bs-toggle="tooltip" class="tpbg" data-bs-placement="bottom" title="{{ $description }}"><i class="fa fa-question"></i></a>
            @else
            @php $description = '';@endphp
            @endif
        </label>
        @endif

        <div class="ac-checkbox-inline">
            @foreach($formdata['values'] as $checkboxdata)
            @php
            if(isset($checkboxdata['selected']) && $checkboxdata['selected'] == '1'){
            $checked = 'checked = "checked"';
            }else{
            $checked = '';
            }

            if($checkboxdata['label'] == 'Country' && isset($checkboxdata['selected']) && $checkboxdata['selected'] == '1'){
            @endphp
            <label class="label-title">Select {{ $checkboxdata['label'] }} <span>*</span></label>
            <select class="form-control ac-input" name='countries' id='countries' onchange="return countrychanges(this.value);">
                <option value=''> Select Country </option>
                @php
                $countriesdata = App\FormBuilder::getCountry();
                foreach($countriesdata as $cdata){
                @endphp
                <option value='{{ $cdata->id }}'> {{ $cdata->var_name }} </option>
                @php
                }
                @endphp
            </select><br/>
            @php } else if($checkboxdata['label'] == 'State' && isset($checkboxdata['selected']) && $checkboxdata['selected'] == '1'){ @endphp
            <label class="label-title">Select {{ $checkboxdata['label'] }} <span>*</span></label>

            <select class="form-control ac-input" name='states' id='states'>
                <option value=''> Select State </option>
                @php
                if($checkboxdata['label'] == 'Country'){
                $statedata = App\FormBuilder::getState();
                }else{
                $statedata = App\FormBuilder::getUsState('231');
                }
                foreach($statedata as $sdata){
                @endphp
                <option value='{{ $sdata->id }}'> {{ $sdata->var_name }} </option>
                @php
                }
                @endphp
            </select><br/>
            @php } else if($checkboxdata['label'] == 'Gender' && isset($checkboxdata['selected']) && $checkboxdata['selected'] == '1'){ @endphp
            <label class="label-title">Select {{ $checkboxdata['label'] }} <span>*</span></label>
            <select class="form-control ac-input" name='{{ $checkboxdata['value'] }}' id='{{ $checkboxdata['value'] }}'>
                <option value=''> Select Gender </option>
                <option value='male'> Male </option>
                <option value='female'> Female </option>
                <option value='transgender'> Trans Gender </option>
            </select><br/>
            @php }else if($checkboxdata['label'] == 'Month' && isset($checkboxdata['selected']) && $checkboxdata['selected'] == '1'){ @endphp
            <label class="label-title">Select {{ $checkboxdata['label'] }} <span>*</span></label>
            <select class="form-control ac-input" name='{{ $checkboxdata['value'] }}' id='{{ $checkboxdata['value'] }}'>
                <option value=''> Select Month </option>
                <option value='01'>January</option>
                <option value='02'>February</option>
                <option value='03'>March</option>
                <option value='04'>April</option>
                <option value='05'>May</option>
                <option value='06'>June</option>
                <option value='07'>July</option>
                <option value='08'>August</option>
                <option value='09'>September</option>
                <option value='10'>October</option>
                <option value='11'>November</option>
                <option value='12'>December</option>
            </select><br/>
            @php }  else {
            if($checkboxdata['label'] != 'Country' && $checkboxdata['label'] != 'State' && $checkboxdata['label'] != 'Gender'  && $checkboxdata['label'] != 'Month'){
            @endphp
            <label class="ac-checkbox">
                <input type="checkbox" id="checkbox" name='{{ $formdata['name'] }}[]' value='{{ $checkboxdata['value'].'*'.$checkboxdata['label'] }}' {{ $checked }}>               
                <span></span> {{ $checkboxdata['label'] }}
            </label>  
            @php } } @endphp
            @endforeach  
            @if(isset($formdata['className']) && $formdata['className'] == 'predefine')
            @else
            <span id="errorCheckbox"></span>
            @endif
        </div>
        @if (isset($errors) && $errors->has($formdata['name']))
        <span class="error">
            {{ $errors->first($formdata['name']) }}
        </span>
        @endif
    </div>
    @endif
    <!-- Radio Group -->
    @if($formdata['type'] == 'radio-group')
    <div class="form-group">      
        <label class="label-title">{{ $formdata['label'] }}
            @if($formdata['required'] == '1')
            <span>*</span>
            @endif
            @if(isset($formdata['description']))
            @php $description = $formdata['description'];@endphp
            <a href="#" data-bs-toggle="tooltip" class="tpbg" data-bs-placement="bottom" title="{{ $description }}"><i class="fa fa-question"></i></a>
            @else
            @php $description = '';@endphp
            @endif
        </label>
        <div class="ac-radio-inline">
            @foreach($formdata['values'] as $radiodata)
            @php
            if(isset($radiodata['selected']) && $radiodata['selected'] == '1'){
            $checked = 'checked = "checked"';
            }else{
            $checked = '';
            }
            @endphp
            <label class="ac-radio">
                <input type="radio" id="radio" name='{{ $formdata['name'] }}' value='{{ $radiodata['value'].'*'.$radiodata['label'] }}' {{ $checked }}>               
                <span></span> {{ $radiodata['label'] }}
            </label>
            @endforeach
            <span id="errorRadio"></span>

        </div>  
        @if (isset($errors) && $errors->has($formdata['name']))
        <span class="error">
            {{ $errors->first($formdata['name']) }}
        </span>
        @endif
    </div>

    @endif

    <!-- File Upload -->
    @if($formdata['type'] == 'file')
    <div class="form-group">
        <label class="label-title">{{ $formdata['label'] }}
            @if($formdata['required'] == '1')
            <span>*</span>
            @endif
            @if(isset($formdata['description']))
            @php $description = $formdata['description'];@endphp
            <a href="#" data-bs-toggle="tooltip" class="tpbg" data-bs-placement="bottom" title="{{ $description }}"><i class="fa fa-question"></i></a>
            @else
            @php $description = '';@endphp
            @endif
        </label>
        <input type="{{ $formdata['type'] }}" name='{{ $formdata['name'] }}' id='{{ $formdata['name'] }}' class="form-control ac-input">   
        @if (isset($errors) && $errors->has($formdata['name']))
        <span class="error">
            {{ $errors->first($formdata['name']) }}
        </span>
        @endif
    </div>
    @endif

    <!-- # Number -->
    @if($formdata['type'] == 'number')
    <div class="form-group">
        <label class="label-title">{{ $formdata['label'] }}
            @if($formdata['required'] == '1')
            <span>*</span>
            @endif
            @if(isset($formdata['description']))
            @php $description = $formdata['description'];@endphp
            <a href="#" data-bs-toggle="tooltip" class="tpbg" data-bs-placement="bottom" title="{{ $description }}"><i class="fa fa-question"></i></a>
            @else
            @php $description = '';@endphp
            @endif
        </label>
        @if(isset($formdata['max']))
        @php $maxlength = $formdata['max'];@endphp
        @else
        @php $maxlength = '25';@endphp
        @endif
        @if(isset($formdata['min']))
        @php $minlength = $formdata['min'];@endphp
        @else
        @php $minlength = '10';@endphp
        @endif
        <input type="{{ $formdata['type'] }}" value="{{ (!empty($formdata['value']) ? $formdata['value'] : '') }}" minlength="{{ $minlength }}" maxlength="{{ $maxlength }}" placeholder="{{ $formdata['label'] }}" name='{{ $formdata['name'] }}' id='{{ $formdata['name'] }}' class="form-control ac-input">
    </div>
    @endif

    <!-- Select Option -->
    @if($formdata['type'] == 'select')
    <div class="form-group">
        <label class="label-title">{{ $formdata['label'] }}
            @if($formdata['required'] == '1')
            <span>*</span>
            @endif
            @if(isset($formdata['description']))
            @php $description = $formdata['description'];@endphp
            <a href="#" data-bs-toggle="tooltip" class="tpbg" data-bs-placement="bottom" title="{{ $description }}"><i class="fa fa-question"></i></a>
            @else
            @php $description = '';@endphp
            @endif
        </label>
        <select class="form-control ac-input" name='{{ $formdata['name'] }}' id='{{ $formdata['name'] }}'>
            <option value=''> Select Option </option>
            @foreach($formdata['values'] as $selectdata)

            <option value='{{ $selectdata['value'].'*'.$selectdata['label'] }}' > {{ $selectdata['label'] }} </option>
            @endforeach
        </select>
        @if (isset($errors) && $errors->has($formdata['name']))
        <span class="error">
            {{ $errors->first($formdata['name']) }}
        </span>
        @endif
    </div>
    @endif

    <!-- Text Field -->
    @if($formdata['type'] == 'text')
    <div class="form-group">
        <label class="label-title">{{ $formdata['label'] }}
            @if($formdata['required'] == '1')
            <span>*</span>
            @endif
            @if(isset($formdata['description']))
            @php $description = $formdata['description'];@endphp
            <a href="#" data-bs-toggle="tooltip" class="tpbg" data-bs-placement="bottom" title="{{ $description }}"><i class="fa fa-question"></i></a>
            @else
            @php $description = '';@endphp
            @endif
        </label>
        @if(isset($formdata['maxlength']))
        @php $maxlength = $formdata['maxlength'];@endphp
        @else
        @php $maxlength = '150';@endphp
        @endif

        @if(isset($formdata['className']))
        @php $className = $formdata['className'];
        date_default_timezone_set('Asia/Kolkata');
        $currentDate=date('m/d/yyyy');
        $currentDateTime=date('m/d/Y H:i');
        $newDateTime = date('h : i A', strtotime($currentDateTime));
        @endphp
        @if($formdata['className'] == 'time_element')
        <div class="input_time"><input type="{{ $formdata['subtype'] }}" value="{{ $newDateTime }}"  maxlength="{{ $maxlength }}" placeholder="{{ $formdata['label'] }}" name='{{ $formdata['name'] }}' id='{{ $formdata['name'] }}' class="form-control ac-input {{ $className }}"><a class="close_time" href="javascript:;"><i class="fa fa-close"></i></a></div>
        @elseif($formdata['className'] == 'datetimepicker')
        @php
        $datetime = date('Y-m-d g:i A');
        if(isset($formdata['value'])){
        $newDate = $formdata['value'];
        }else{
        $newDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($datetime));
        }
        @endphp
        <input type="{{ $formdata['subtype'] }}"  value="{{ $newDate }}"  autocomplete="off"  maxlength="{{ $maxlength }}" placeholder="{{ $formdata['label'] }}" name='{{ $formdata['name'] }}' id='{{ $formdata['name'] }}' class="form-control ac-input {{ $className }}">                              
        @elseif($formdata['className'] == 'datepicker')
        @php
        if(isset($formdata['value'])){
        $newDate = $formdata['value'];
        }else{
        $newDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . '', strtotime($currentDate));
        }
        @endphp
        <input type="{{ $formdata['subtype'] }}"  value="{{ $newDate }}"  maxlength="{{ $maxlength }}" autocomplete="off" placeholder="{{ $formdata['label'] }}" name='{{ $formdata['name'] }}' id='{{ $formdata['name'] }}' class="form-control ac-input {{ $className }}">                              
        @else
        @if(isset($formdata['placeholder']) && $formdata['placeholder'])
        @php $placeholder = $formdata['placeholder']; @endphp
        @else
        @php $placeholder = $formdata['label']; @endphp
        @endif
        @if($formdata['subtype'] == 'tel')
        <input type="{{ $formdata['subtype'] }}" value="{{ (!empty($formdata['value']) ? $formdata['value'] : '') }}" autocomplete="off" onkeypress='return KeycheckOnlyPhonenumber(event);' maxlength="{{ $maxlength }}" placeholder="{{ $placeholder }}" name='{{ $formdata['name'] }}' id='{{ $formdata['name'] }}' class="form-control ac-input {{ $className }}"> 
        @elseif($formdata['subtype'] == 'url')
        <div class="row">
            <!--            <div class="col-sm-3">
                            <select class="form-control ac-input" onchange="UrlChange(this.value)" name='url' id='url'>
                                <option value='http://'> http:// </option>
                                <option value='https://'> https:// </option>
                            </select>
                        </div>-->
            <div class="col-sm-12">
                <input type="{{ $formdata['subtype'] }}" value="{{ (!empty($formdata['value']) ? $formdata['value'] : '') }}" autocomplete="off"  maxlength="{{ $maxlength }}" placeholder="{{ $placeholder }}" name='{{ $formdata['name'] }}' id='{{ $formdata['name'] }}' class="form-control ac-input {{ $className }} url"> 
            </div>
        </div>
        @else
        <input type="{{ $formdata['subtype'] }}" value="{{ (!empty($formdata['value']) ? $formdata['value'] : '') }}" autocomplete="off" maxlength="{{ $maxlength }}" placeholder="{{ $placeholder }}" name='{{ $formdata['name'] }}' id='{{ $formdata['name'] }}' class="form-control ac-input {{ $className }}"> 
        @endif
        @endif
        @else
        @php $className = '';@endphp
        @endif
        @if (isset($errors) && $errors->has($formdata['name']))
        <span class="error">
            {{ $errors->first($formdata['name']) }}
        </span>
        @endif
        @if(isset($formdata['subtype']) && $formdata['subtype'] == 'alphabetic')
        <span id="letterError" style="color: red"></span>
        @elseif (isset($formdata['subtype']) && $formdata['subtype'] == 'alphanumeric')
        <span id="alphaError" style="color: red"></span>
        @endif
    </div>
    @endif

    <!-- Text Area -->
    @if($formdata['type'] == 'textarea')
    <div class="form-group">
        <label class="label-title">{{ $formdata['label'] }}
            @if($formdata['required'] == '1')
            <span>*</span>
            @endif
            @if(isset($formdata['description']))
            @php $description = $formdata['description'];@endphp
            <a href="#" data-bs-toggle="tooltip" class="tpbg" data-bs-placement="bottom" title="{{ $description }}"><i class="fa fa-question"></i></a>
            @else
            @php $description = '';@endphp
            @endif
        </label>
        @if (isset($formdata['subtype']) && $formdata['subtype'] != 'quill')
        @if (isset($formdata['subtype']) && $formdata['subtype'] != 'tinymce')
        @php $maxlength = '500';@endphp
        @else
        @php $maxlength = '';@endphp
        @endif
        @else
        @php $maxlength = '';@endphp
        @endif

        @if(isset($formdata['className']))
        @php $className = $formdata['className'];@endphp
        @else
        @php $className = '';@endphp
        @endif

        @if(isset($formdata['placeholder']) && $formdata['placeholder'])
        @php $placeholder = $formdata['placeholder']; @endphp
        @else
        @php $placeholder = $formdata['label']; @endphp
        @endif
        <textarea type="{{ $formdata['subtype'] }}" class="form-control ac-textarea {{ $className }}" name='{{ $formdata['name'] }}' id='{{ $formdata['name'] }}' maxlength="{{ $maxlength }}" placeholder="{{ $placeholder }}">{{ (!empty($formdata['value']) ? $formdata['value'] : '') }}</textarea>
        @if($formdata['subtype'] == 'quill')
        <span id="errorquill"></span>
        @endif
        @if (isset($errors) && $errors->has($formdata['name']))
        <span class="error">
            {{ $errors->first($formdata['name']) }}
        </span>
        @endif
    </div>
    @endif

    @endforeach
    @endif
    <!-- Captcha -->
    <div class="captcha_builder clearfix">
        <div class="captcha_div" >
            <div id="recaptcha3"></div>
                                <!--<div class="g-recaptcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}"></div>-->
             <input type="hidden" class="hiddenRecaptcha" name="hiddenRecaptcha1" id="hiddenRecaptcha">
                            </div>
                            @if (isset($errors) && $errors->has('g-recaptcha-response'))
                            <span class="help-block">
                                {{ $errors->first('g-recaptcha-response') }}
                            </span>
                            @endif
        <button type="submit" class="btn ac-border" title="Submit">Submit</button>
    </div>
    {!! Form::close() !!}
    @if(isset($data['formTotalDetails']->fkIntImgId) && $data['formTotalDetails']->fkIntImgId != '')
</div>
@endif

<script>
    $(document).ready(function () {

    $("#formbuildername").validate({
    ignore: [],
            rules: {
<?php
foreach ($data['formdata'] as $formdata) {
    if (isset($formdata['className']) && $formdata['className'] == 'predefine') {
//        foreach ($formdata['values'] as $checkboxdata) {
            if (isset($formdata['values'][0]['label']) && $formdata['values'][0]['label'] == 'Country' && isset($formdata['values'][0]['selected'])) {
                ?>
                            countries: {
                            required: true
                            },
                <?php
            }  if (isset($formdata['values'][1]['label']) && $formdata['values'][1]['label'] == 'State' && isset($formdata['values'][1]['selected'])) {
                ?>
                            states: {
                            required: true
                            },
                <?php
            }  if (isset($formdata['values'][2]['label']) && $formdata['values'][2]['label'] == 'Gender' && isset($formdata['values'][2]['selected'])) {
                ?>
                            gender: {
                            required: true
                            },
                <?php
            }  if (isset($formdata['values'][3]['label']) && $formdata['values'][3]['label'] == 'Month' && isset($formdata['values'][3]['selected'])) {
                ?>
                            months: {
                            required: true
                            },
                <?php
            }
//        }
    } else {

        if ($formdata['type'] == 'checkbox-group') {
            if ($formdata['required'] == '1') {
                ?>
                            '<?php echo $formdata['name']; ?>[]': {
                            required: true
                            },
                <?php
            }
        }
    }
    if ($formdata['type'] == 'file') {
        if ($formdata['required'] == '1') {
            ?>
                        '<?php echo $formdata['name']; ?>': {
                        required: true
                        },
            <?php
        }
    }
    if ($formdata['type'] == 'number') {
        if ($formdata['required'] == '1') {
            ?>
                        '<?php echo $formdata['name']; ?>': {
                        required: true,
                                xssProtection: true,
                                no_url: true,
                                phonenumber: true,
                                noSpace: true
                        },
            <?php
        } else {
            ?>
                        '<?php echo $formdata['name']; ?>': {
                        xssProtection: true,
                                no_url: true
                        },
            <?php
        }
    }
    if ($formdata['type'] == 'radio-group') {
        if ($formdata['required'] == '1') {
            ?>
                        '<?php echo $formdata['name']; ?>': {
                        required: true
                        },
            <?php
        }
    }
    if ($formdata['type'] == 'select') {
        if ($formdata['required'] == '1') {
            ?>
                        '<?php echo $formdata['name']; ?>': {
                        required: true
                        },
            <?php
        }
    }
    if ($formdata['type'] == 'text') {
        if (isset($formdata['subtype']) && $formdata['subtype'] == 'url') {
            if ($formdata['required'] == '1') {
                ?>
                            '<?php echo $formdata['name']; ?>': {
                            required: true,
                                    xssProtection: true,
                                    noSpace: true
                            },
            <?php } else { ?>
                            '<?php echo $formdata['name']; ?>': {
                            xssProtection: true,
                                    noSpace: true
                            },
                <?php
            }
        } else if (isset($formdata['subtype']) && $formdata['subtype'] == 'alphabetic') {
            if ($formdata['required'] == '1') {
                ?>
                            '<?php echo $formdata['name']; ?>': {
                            required: true,
                                    lettersonly: true
                            },
            <?php } else { ?>
                            '<?php echo $formdata['name']; ?>': {
                            lettersonly: true
                            },
                <?php
            }
        } else if (isset($formdata['subtype']) && $formdata['subtype'] == 'alphanumeric') {
            if ($formdata['required'] == '1') {
                ?>
                            '<?php echo $formdata['name']; ?>': {
                            required: true,
                                    alphanumeric: true,
                                    xssProtection: true,
                                    noSpace: true
                            },
            <?php } else { ?>
                            '<?php echo $formdata['name']; ?>': {
                            xssProtection: true,
                                    alphanumeric: true
                            },
                <?php
            }
        } else if (isset($formdata['className']) && $formdata['className'] == 'form-control urlclass') {
            if ($formdata['required'] == '1') {
                ?>
                            '<?php echo $formdata['name']; ?>': {
                            required: true,
                                    xssProtection: true,
                                    noSpace: true,
                                    validUrl: true
                            },
            <?php } else { ?>
                            '<?php echo $formdata['name']; ?>': {
                            xssProtection: true,
                                    validUrl: true
                            },
                <?php
            }
        } else {
            if ($formdata['required'] == '1') {
                ?>
                            '<?php echo $formdata['name']; ?>': {
                            required: true,
                                    xssProtection: true,
                                    noSpace: true

                            },
            <?php } else { ?>
                            '<?php echo $formdata['name']; ?>': {
                            xssProtection: true,
                            },
                <?php
            }
        }
    }
    if ($formdata['type'] == 'textarea') {
        if ($formdata['required'] == '1') {
            if (isset($formdata['subtype']) && $formdata['subtype'] == 'quill') {
                ?>
                            '<?php echo $formdata['name']; ?>': {
                            required: true,
                                    quillemptyValidation:true
                            },
            <?php } else if (isset($formdata['subtype']) && $formdata['subtype'] == 'tinymce') {
                ?>
                            '<?php echo $formdata['name']; ?>': {
                            required: true
                            },
            <?php } else { ?>
                            '<?php echo $formdata['name']; ?>': {
                            required: true
                            },
            <?php } ?>
            <?php
        } else {
            if (isset($formdata['subtype']) && $formdata['subtype'] != 'quill') {
                if (isset($formdata['subtype']) && $formdata['subtype'] != 'tinymce') {
                    ?>
                                '<?php echo $formdata['name']; ?>': {
                                xssProtection: true,
                                        no_url: true
                                },
                    <?php
                }
            }
        }
    }
}
?>
                hiddenRecaptcha1: {
                    required: function () {
                        if (grecaptcha.getResponse(recaptcha3) == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            },
            messages: {
<?php
foreach ($data['formdata'] as $formdata) {
    if (isset($formdata['className']) && $formdata['className'] == 'predefine') {
        if (isset($formdata['values'][0]['label']) && $formdata['values'][0]['label'] == 'Country' && isset($formdata['values'][0]['selected'])) {
            ?>
                        countries: {
                        required: "Country field is required."
                        },
            <?php
        }  if (isset($formdata['values'][1]['label']) && $formdata['values'][1]['label'] == 'State' && isset($formdata['values'][1]['selected'])) {
            ?>
                        states: {
                        required: "State field is required."
                        },
            <?php
        }  if (isset($formdata['values'][2]['label']) && $formdata['values'][2]['label'] == 'Gender' && isset($formdata['values'][2]['selected'])) {
            ?>
                        gender: {
                        required: "Gender field is required."
                        },
            <?php
        }  if (isset($formdata['values'][3]['label']) && $formdata['values'][3]['label'] == 'Month' && isset($formdata['values'][3]['selected'])) {
            ?>
                        months: {
                        required: "Month field is required."
                        },
            <?php
        }
    } else {
        if ($formdata['type'] == 'checkbox-group') {
            if ($formdata['required'] == '1') {
                ?>
                            '<?php echo $formdata['name']; ?>[]': {
                            required: "<?php echo $formdata['label']; ?> field is required."
                            },
                <?php
            }
        }
    }

    if ($formdata['type'] == 'file') {
        if ($formdata['required'] == '1') {
            ?>
                        '<?php echo $formdata['name']; ?>': {
                        required: "Please select <?php echo $formdata['label']; ?>."
                        },
            <?php
        }
    }
    if ($formdata['type'] == 'number') {
        if ($formdata['required'] == '1') {
            ?>
                        '<?php echo $formdata['name']; ?>': {
                        required: "<?php echo $formdata['label']; ?> field is required."
                        },
            <?php
        }
    }
    if ($formdata['type'] == 'radio-group') {
        if ($formdata['required'] == '1') {
            ?>
                        '<?php echo $formdata['name']; ?>': {
                        required: "<?php echo $formdata['label']; ?> field is required."
                        },
            <?php
        }
    }
    if ($formdata['type'] == 'select') {
        if ($formdata['required'] == '1') {
            ?>
                        '<?php echo $formdata['name']; ?>': {
                        required: "<?php echo $formdata['label']; ?> field is required."
                        },
            <?php
        }
    }

    if ($formdata['type'] == 'text') {
        if ($formdata['required'] == '1') {
            ?>
                        '<?php echo $formdata['name']; ?>': {
                        required: "<?php echo $formdata['label']; ?> field is required."
                        },
            <?php
        }
    }
    if ($formdata['type'] == 'textarea') {
        if ($formdata['required'] == '1') {
            if ($formdata['subtype'] == 'quill') {
                ?>
                            '<?php echo $formdata['name']; ?>': {
                            required: "<?php echo $formdata['label']; ?> field is required.",
                                    quillemptyValidation: "<?php echo $formdata['label']; ?> field is required."
                            },
            <?php } else { ?>
                            '<?php echo $formdata['name']; ?>': {
                            required: "<?php echo $formdata['label']; ?> field is required."
                            },
            <?php } ?>
            <?php
        }
    }
}
?>
                hiddenRecaptcha1: {
                    required: "Please select I'm not a robot."
                },
            },
            errorPlacement: function (error, element) {
            error.insertAfter(element);
            if (element.attr("id") == "checkbox") {
            error.appendTo("#errorCheckbox");
            }
            else if (element.attr("id") == "radio") {
            error.appendTo("#errorRadio");
            }
            else if (element.attr("type") == "quill") {
            error.appendTo("#errorquill");
            }
            }
    });
    });</script>
<?php
foreach ($data['formdata'] as $formdata) {
    if (isset($formdata['subtype']) && $formdata['subtype'] == 'alphabetic') {
        ?>
        <script type="text/javascript">
            $(function () {
            $("#<?php echo $formdata['name'] ?>").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
            $("#letterError").html("");
            var regex = /^[A-Za-z]+$/;
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
            $("#letterError").show();
            $("#letterError").html("Letters only please");
            }
            return isValid;
            });
            });
            $(document).on('keyup', function(event) {
            if (event.keyCode == 8) {
            if ($('#<?php echo $formdata['name'] ?>').val() == ''){
            $("#letterError").hide();
            }
            }
            });</script>
    <?php } else if (isset($formdata['subtype']) && $formdata['subtype'] == 'alphanumeric') { ?>
        <script type="text/javascript">
            $(function () {
            $("#<?php echo $formdata['name'] ?>").keypress(function (e) {

            var keyCode = e.keyCode || e.which;
            $("#alphaError").html("");
            var regex = /^[\w.]+$/i;
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
            $("#alphaError").show();
            $("#alphaError").html("Letters, numbers, and underscores only please.");
            }
            return isValid;
            });
            });
            $(document).on('keyup', function(event) {
            if (event.keyCode == 8) {
            if ($('#<?php echo $formdata['name'] ?>').val() == ''){
            $("#alphaError").hide();
            }
            }
            });</script>
    <?php
    }
}
?>
<link href="https://cdn.quilljs.com/1.2.4/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.2.4/quill.js"></script>
<script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>
<script type="text/javascript" src="{{ $CDN_PATH.'assets/js/quill-textarea.js' }}"></script>
<!-- Initialize Quill editor -->
<script>

    $(document).ready(function(){
    var tynimaceisavail = false;
    $("form textarea").each(function(index) {
    var textareatype = $(this).attr('type');
    if (typeof textareatype != "undefined"){
    if (textareatype == "tinymce"){
    var selectorid = "textarea#" + $(this).attr('id');
    tinymce.init({
    selector: selectorid, theme: "modern",
            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
            toolbar2: "| link unlink anchor | responsivefilemanager | image media | forecolor backcolor  | print preview code ",
            image_advtab: true,
            setup: function (editor) {
            editor.on('change', function (e) {
            editor.save();
            });
            }
    });
    } else if (textareatype == "quill"){
    var selectorid = "textarea#" + $(this).attr('id');
    quilljs_textarea(selectorid, {
    modules: { toolbar: [
    [{ 'header': [1, 2, false] }],
    ['bold', 'italic', 'underline'], // toggled buttons
    ["code-block"]
    ]},
            theme: 'snow',
    });
    }
    }
    });
    });</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-timepicker/js/timepicki.js' }}"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    $('.close_time').on("click", function(event) {
    $(".timepicker_wrap").hide();
    });
    $(".time_element").timepicki();
    var DEFAULT_DT_FORMAT = '';
    var DEFAULT_DATE_FORMAT = "{{ Config::get('Constant.DEFAULT_DATE_FORMAT')  }}";
    var DEFAULT_DT_FMT_FOR_DATEPICKER = 'M/d/yyyy';
    if (DEFAULT_DATE_FORMAT == 'd/m/Y')
    {
    DEFAULT_DT_FORMAT = 'D/M/YYYY';
    DEFAULT_DT_FMT_FOR_DATEPICKER = 'd d/mm/yyyy';
    } else if (DEFAULT_DATE_FORMAT == 'm/d/Y') {
    DEFAULT_DT_FORMAT = 'M/D/YYYY';
    DEFAULT_DT_FMT_FOR_DATEPICKER = 'mm/dd/yyyy';
    } else if (DEFAULT_DATE_FORMAT == 'Y/m/d') {
    DEFAULT_DT_FORMAT = 'YYYY/M/D';
    DEFAULT_DT_FMT_FOR_DATEPICKER = 'yyyy/mm/dd';
    } else if (DEFAULT_DATE_FORMAT == 'Y/d/m') {
    DEFAULT_DT_FORMAT = 'YYYY/D/M';
    DEFAULT_DT_FMT_FOR_DATEPICKER = 'yyyy/dd/mm';
    } else if (DEFAULT_DATE_FORMAT == 'M/d/Y') {
    DEFAULT_DT_FORMAT = 'M/D/YYYY';
    DEFAULT_DT_FMT_FOR_DATEPICKER = 'M/dd/yyyy';
    }
    $('.datetimepicker').datetimepicker({
    autoclose: true,
            showMeridian: true,
            minuteStep: 5,
            format: DEFAULT_DT_FMT_FOR_DATEPICKER + ' HH:ii P'
    });
    $('.datepicker').datepicker({
    autoclose: true,
            minuteStep: 5,
            defaultDate:new Date(),
            format: DEFAULT_DT_FMT_FOR_DATEPICKER
    });
    $(document).ready(function(){
    $('[data-bs-toggle="tooltip" class="tpbg"]').tooltip();
    });
    function countrychanges(country_id) {
    var Country_URL = window.site_url + "/Country_Data";
    $.ajax({
    type: "POST",
            async: true,
            url: Country_URL,
            data: "country_id=" + country_id,
            async: true,
            success: function(url) {
            document.getElementById('states').style.display = '';
            document.getElementById('states').innerHTML = url;
            }
    });
    }

    function UrlChange(url){
    $(".url").val(url);
    }

</script>

@endif