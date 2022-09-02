@if($data['Columns'] == '1')
<div class='row'>
    @endif
    @if($data['subtype'] == 'TwoColumns_1')
    <div class='col-sm-4'>
        <div class='card-box'>
            @if($data['type'] == 'onlyimage')
            @if(isset($data['content']['image']) && $data['content']['image'] != '')
            @if(isset($data['content']['alignment']) && $data['content']['alignment'] == 'image-lft-txt')
            <div class="left_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'image-rt-txt')
            <div class="right_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'image-center-txt')
            <div class="center_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @endif
            @endif
            @endif


            @if($data['type'] == 'onlydocument')
            <!-- Only Document -->
            @if(isset($data['content']['document']) && $data['content']['document'] != '')
            @if(!empty($data['content']['document']))
            <div class="ac-mb-xs-15"></div>
            <div class="download_files clearfix">
                @php
                $docsAray = explode(',', $data['content']['document']);
                $docObj   = App\Document::getDocDataByIds($docsAray);
                @endphp
                @if(count($docObj) > 0)
                <ul>
                    @foreach($docObj as $key => $val)
                    @php
                    if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
                    $blank = 'target="_blank"';
                    }else{
                    $blank = '';
                    }
                    if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
                    $icon = "fi flaticon-pdf-file";
                    }elseif($val->varDocumentExtension == 'doc' || $val->varDocumentExtension == 'docx'){
                    $icon = "fi flaticon-doc-file";
                    }elseif($val->varDocumentExtension == 'xls' || $val->varDocumentExtension == 'xlsx'){
                    $icon = "fi flaticon-xls-file";
                    }else{
                    $icon = "fi flaticon-doc-file";
                    }
                    @endphp  
                    <li><a {!! $blank !!} href="{{ $CDN_PATH.'documents/'.$val->txtSrcDocumentName.'.'.$val->varDocumentExtension }}" title="{{ $val->txtDocumentName }}.{{ $val->varDocumentExtension }}"><i class="{{ $icon }}"></i>{{ $val->txtDocumentName }}.{{ $val->varDocumentExtension }}</a></li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endif
            @endif
            @endif


            @if($data['type'] == 'imgcontent')
            <!-- Only Left Text and Right Image -->
            @if(isset($data['content']['alignment']) && $data['content']['alignment'] == 'lft-txt')
            <div class="left_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <!-- Only Right Text and Left Image -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'rt-txt')
            <div class="right_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <!-- Only Top Image -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'top-txt')
            <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <!-- Only Bottom Image -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'bot-txt')
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <div class="ac-mb-xs-15"></div>
            <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'center-txt')
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <div class="ac-mb-xs-15"></div>
            <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            @endif
            @endif


            @if($data['type'] == 'videocontent')
            <!-- Only Left Text and Right Video -->
            @if(isset($data['content']['title']) && $data['content']['alignment'] == 'lft-txt')
            <div class='col-sm-12'>
                <div class="row">
                    <div class="col-sm-5 col-xs-12 cms about-left animated fadeInLeft">
                        <div class="about_image">
                            <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                        </div>
                    </div>
                    <div class="col-sm-7 col-xs-12 cms about-left animated fadeInRight">
                        @if($data['content']['title'] != '')
                        <div class="same_title">
                            <h2>{{ $data['content']['title'] }}</h2>
                        </div>
                        @endif
                        <div class="info">
                            {!! $data['content']['content'] !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Only Right Text and Left Video -->
            @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'rt-txt')
            <div class='col-sm-12'>
                <div class="row">
                    <div class="col-sm-7 col-xs-12 cms about-left animated fadeInLeft">
                        <div class='visible-xs'>
                            <div class="about_image">
                                <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                            </div>
                        </div>
                        @if($data['content']['title'] != '')
                        <div class="same_title">
                            <h2>{{ $data['content']['title'] }}</h2>
                        </div>
                        @endif
                        <div class="info">
                            {!! $data['content']['content'] !!}
                        </div>
                    </div>
                    <div class="col-sm-5 col-xs-12 cms about-left animated fadeInRight hidden-xs">
                        <div class="about_image">
                            <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Only Top Video -->
            @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'top-txt')
            <div class="cms about-left animated fadeInUp">
                <div class='about_full'>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                    @if($data['content']['title'] != '')
                    <div class="same_title">
                        <h2>{{ $data['content']['title'] }}</h2>
                    </div>
                    @endif
                    <div class="info">
                        {!! $data['content']['content'] !!}
                    </div>
                </div>
            </div>
            <!-- Only Bottom Video -->
            @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'bot-txt')
            <div class="cms about-left animated fadeInUp">
                <div class='about_full'>
                    @if($data['content']['title'] != '')
                    <div class="same_title">
                        <h2>{{ $data['content']['title'] }}</h2>
                    </div>
                    @endif
                    <div class="info">
                        {!! $data['content']['content'] !!}
                    </div>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                </div>
            </div>
            
            @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'center-txt')

            <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
                <div class='about_full'>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                    @if($data['content']['title'] != '')
                    <div class="same_title">
                        <h2 class="title_div">{{ $data['content']['title'] }}</h2>
                    </div>
                    @endif
                    <div class="info">
                        {!! $data['content']['content'] !!}
                    </div>
                </div>
            </div>
            @endif
            @endif

            @if($data['type'] == 'buttondata')
            <!-- Only Right Button -->
            @if(isset($data['content']['alignment']) && $data['content']['alignment'] == 'button-rt-txt')
            <div class="animated fadeInUp text-right load">               
                <a class="btn ac-border " href="{{ $data['content']['content'] }}"  target='{{ $data['content']['target'] }}' title="{{ $data['content']['title'] }}">{{ $data['content']['title'] }}</a>               
            </div>
            <!-- Only Left Button -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'button-lft-txt')
            <div class="animated fadeInUp text-left load">               
                <a class="btn ac-border " href="{{ $data['content']['content'] }}"  target='{{ $data['content']['target'] }}' title="{{ $data['content']['title'] }}">{{ $data['content']['title'] }}</a>               
            </div>
            <!-- Only Center Button -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'button-center-txt')
            <div class="animated fadeInUp text-center load">               
                <a class="btn ac-border " href="{{ $data['content']['content'] }}" target='{{ $data['content']['target'] }}' title="{{ $data['content']['title'] }}">{{ $data['content']['title'] }}</a>               
            </div>
            @endif
            @endif


            @if($data['type'] == 'twotextarea')
            <!-- 2 Part Content -->
            @if(isset($data['content']['leftcontent']) && $data['content']['leftcontent'] != '')
            <div class="row"> 
                <div class="col-sm-6 col-md-6">
                    {!! $data['content']['leftcontent'] !!}
                </div>  
                <div class="col-sm-6 col-md-6">
                    {!! $data['content']['rightcontent'] !!}
                </div>            
            </div>
            @endif
            @endif

            @if($data['type'] == 'textarea')
            <!-- Only Content -->
            @if(isset($data['content']['content']) && $data['content']['content'] != '')
            {!! $data['content']['content'] !!}
            @endif
            @endif


            @if($data['type'] == 'onlyvideo')
            <!-- Only Video -->
            @if(isset($data['content']['vidId']) && $data['content']['vidId'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            <br/>
            <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
            @endif
            @endif


            @if($data['type'] == 'mapdata')
            <!-- Only Map -->
            @if(isset($data['content']['latitude']) && $data['content']['latitude'] != '')
            <div class="location_map">
                <iframe src="http://maps.google.com/maps?q={{ $data['content']['latitude'] }}, {{ $data['content']['longitude'] }}&output=embed&zoom=9" width="100%" height="300" frameborder="0" style="border:0"></iframe>
            </div>
            @endif
            @endif


            @if($data['type'] == 'contactinfodata')
            <!-- Only Contact Info -->
            @if(isset($data['content']['section_address']) && $data['content']['section_address'] != '')

            <div class="mailing_box animated fadeInUp load">
                @if(isset($data['content']['section_address']) && $data['content']['section_address'] != '')
                <h4>Address</h4>
                <p>{{ $data['content']['section_address'] }}</p>
                @endif
                <p>
                    @if(isset($data['content']['section_email']) && $data['content']['section_email'] != '')
                    <b>Email:-</b> <a href="mailto:{{ $data['content']['section_email'] }}" title="{{ $data['content']['section_email'] }}">{{ $data['content']['section_email'] }}</a><br>
                    @endif
                    @if(isset($data['content']['section_phone']) && $data['content']['section_phone'] != '')
                    <b>Phone:-</b><a href="tel:{{ $data['content']['section_phone'] }}"> {{ $data['content']['section_phone'] }}</a><br>
                    @endif
                </p>
                @if(isset($data['content']['content']) && $data['content']['content'] != '')
                <p>{!! $data['content']['content'] !!}</p>
                @endif
            </div>

            @endif
            @endif


            @if($data['type'] == 'onlytitle')
            <!-- Only Title -->
            @if(isset($data['content']['content']) && $data['content']['content'] != '')
            <h2>
                {!! $data['content']['content'] !!}
            </h2>
            @endif
            @endif

            @if($data['type'] == 'formdata')
            @include('layouts.builder-sections.formbuilder')
            @endif
        </div>
    </div>
    @endif


    @if($data['subtype'] == 'TwoColumns_2')
    <div class='col-sm-4'>
        <div class='card-box'>
            @if($data['type'] == 'onlyimage')
            @if(isset($data['content']['image']) && $data['content']['image'] != '')
            @if(isset($data['content']['alignment']) && $data['content']['alignment'] == 'image-lft-txt')
            <div class="left_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'image-rt-txt')
            <div class="right_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'image-center-txt')
            <div class="center_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @endif
            @endif
            @endif


            @if($data['type'] == 'onlydocument')
            <!-- Only Document -->
            @if(isset($data['content']['document']) && $data['content']['document'] != '')
            @if(!empty($data['content']['document']))
            <div class="ac-mb-xs-15"></div>
            <div class="download_files clearfix">
                @php
                $docsAray = explode(',', $data['content']['document']);
                $docObj   = App\Document::getDocDataByIds($docsAray);
                @endphp
                @if(count($docObj) > 0)
                <ul>
                    @foreach($docObj as $key => $val)
                    @php
                    if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
                    $blank = 'target="_blank"';
                    }else{
                    $blank = '';
                    }
                    if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
                    $icon = "fi flaticon-pdf-file";
                    }elseif($val->varDocumentExtension == 'doc' || $val->varDocumentExtension == 'docx'){
                    $icon = "fi flaticon-doc-file";
                    }elseif($val->varDocumentExtension == 'xls' || $val->varDocumentExtension == 'xlsx'){
                    $icon = "fi flaticon-xls-file";
                    }else{
                    $icon = "fi flaticon-doc-file";
                    }
                    @endphp  
                    <li><a {!! $blank !!} href="{{ $CDN_PATH.'documents/'.$val->txtSrcDocumentName.'.'.$val->varDocumentExtension }}" title="{{ $val->txtDocumentName }}.{{ $val->varDocumentExtension }}"><i class="{{ $icon }}"></i>{{ $val->txtDocumentName }}.{{ $val->varDocumentExtension }}</a></li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endif
            @endif
            @endif


            @if($data['type'] == 'imgcontent')
            <!-- Only Left Text and Right Image -->
            @if(isset($data['content']['alignment']) && $data['content']['alignment'] == 'lft-txt')
            <div class="left_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <!-- Only Right Text and Left Image -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'rt-txt')
            <div class="right_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <!-- Only Top Image -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'top-txt')
            <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <!-- Only Bottom Image -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'bot-txt')
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <div class="ac-mb-xs-15"></div>
            <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'center-txt')
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <div class="ac-mb-xs-15"></div>
            <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            @endif
            @endif


           @if($data['type'] == 'videocontent')
            <!-- Only Left Text and Right Video -->
            @if(isset($data['content']['title']) && $data['content']['alignment'] == 'lft-txt')
            <div class='col-sm-12'>
                <div class="row">
                    <div class="col-sm-5 col-xs-12 cms about-left animated fadeInLeft">
                        <div class="about_image">
                            <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                        </div>
                    </div>
                    <div class="col-sm-7 col-xs-12 cms about-left animated fadeInRight">
                        @if($data['content']['title'] != '')
                        <div class="same_title">
                            <h2>{{ $data['content']['title'] }}</h2>
                        </div>
                        @endif
                        <div class="info">
                            {!! $data['content']['content'] !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Only Right Text and Left Video -->
            @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'rt-txt')
            <div class='col-sm-12'>
                <div class="row">
                    <div class="col-sm-7 col-xs-12 cms about-left animated fadeInLeft">
                        <div class='visible-xs'>
                            <div class="about_image">
                                <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                            </div>
                        </div>
                        @if($data['content']['title'] != '')
                        <div class="same_title">
                            <h2>{{ $data['content']['title'] }}</h2>
                        </div>
                        @endif
                        <div class="info">
                            {!! $data['content']['content'] !!}
                        </div>
                    </div>
                    <div class="col-sm-5 col-xs-12 cms about-left animated fadeInRight hidden-xs">
                        <div class="about_image">
                            <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Only Top Video -->
            @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'top-txt')
            <div class="cms about-left animated fadeInUp">
                <div class='about_full'>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                    @if($data['content']['title'] != '')
                    <div class="same_title">
                        <h2>{{ $data['content']['title'] }}</h2>
                    </div>
                    @endif
                    <div class="info">
                        {!! $data['content']['content'] !!}
                    </div>
                </div>
            </div>
            <!-- Only Bottom Video -->
            @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'bot-txt')
            <div class="cms about-left animated fadeInUp">
                <div class='about_full'>
                    @if($data['content']['title'] != '')
                    <div class="same_title">
                        <h2>{{ $data['content']['title'] }}</h2>
                    </div>
                    @endif
                    <div class="info">
                        {!! $data['content']['content'] !!}
                    </div>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                </div>
            </div>
             @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'center-txt')

            <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
                <div class='about_full'>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                    @if($data['content']['title'] != '')
                    <div class="same_title">
                        <h2 class="title_div">{{ $data['content']['title'] }}</h2>
                    </div>
                    @endif
                    <div class="info">
                        {!! $data['content']['content'] !!}
                    </div>
                </div>
            </div>
            @endif
            @endif

            @if($data['type'] == 'buttondata')
            <!-- Only Right Button -->
            @if(isset($data['content']['alignment']) && $data['content']['alignment'] == 'button-rt-txt')
            <div class="animated fadeInUp text-right load">               
                <a class="btn ac-border " href="{{ $data['content']['content'] }}"  target='{{ $data['content']['target'] }}' title="{{ $data['content']['title'] }}">{{ $data['content']['title'] }}</a>               
            </div>
            <!-- Only Left Button -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'button-lft-txt')
            <div class="animated fadeInUp text-left load">               
                <a class="btn ac-border " href="{{ $data['content']['content'] }}"  target='{{ $data['content']['target'] }}' title="{{ $data['content']['title'] }}">{{ $data['content']['title'] }}</a>               
            </div>
            <!-- Only Center Button -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'button-center-txt')
            <div class="animated fadeInUp text-center load">               
                <a class="btn ac-border " href="{{ $data['content']['content'] }}" target='{{ $data['content']['target'] }}' title="{{ $data['content']['title'] }}">{{ $data['content']['title'] }}</a>               
            </div>
            @endif
            @endif


            @if($data['type'] == 'twotextarea')
            <!-- 2 Part Content -->
            @if(isset($data['content']['leftcontent']) && $data['content']['leftcontent'] != '')
            <div class="row"> 
                <div class="col-sm-6 col-md-6">
                    {!! $data['content']['leftcontent'] !!}
                </div>  
                <div class="col-sm-6 col-md-6">
                    {!! $data['content']['rightcontent'] !!}
                </div>            
            </div>
            @endif
            @endif

            @if($data['type'] == 'textarea')
            <!-- Only Content -->
            @if(isset($data['content']['content']) && $data['content']['content'] != '')
            {!! $data['content']['content'] !!}
            @endif
            @endif


            @if($data['type'] == 'onlyvideo')
            <!-- Only Video -->
            @if(isset($data['content']['vidId']) && $data['content']['vidId'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            <br/>
            <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
            @endif
            @endif


            @if($data['type'] == 'mapdata')
            <!-- Only Map -->
            @if(isset($data['content']['latitude']) && $data['content']['latitude'] != '')
            <div class="location_map">
                <iframe src="http://maps.google.com/maps?q={{ $data['content']['latitude'] }}, {{ $data['content']['longitude'] }}&output=embed&zoom=9" width="100%" height="300" frameborder="0" style="border:0"></iframe>
            </div>
            @endif
            @endif


            @if($data['type'] == 'contactinfodata')
            <!-- Only Contact Info -->
            @if(isset($data['content']['section_address']) && $data['content']['section_address'] != '')

            <div class="mailing_box animated fadeInUp load">
                @if(isset($data['content']['section_address']) && $data['content']['section_address'] != '')
                <h4>Address</h4>
                <p>{{ $data['content']['section_address'] }}</p>
                @endif
                <p>
                    @if(isset($data['content']['section_email']) && $data['content']['section_email'] != '')
                    <b>Email:-</b> <a href="mailto:{{ $data['content']['section_email'] }}" title="{{ $data['content']['section_email'] }}">{{ $data['content']['section_email'] }}</a><br>
                    @endif
                    @if(isset($data['content']['section_phone']) && $data['content']['section_phone'] != '')
                    <b>Phone:-</b><a href="tel:{{ $data['content']['section_phone'] }}"> {{ $data['content']['section_phone'] }}</a><br>
                    @endif
                </p>
                @if(isset($data['content']['content']) && $data['content']['content'] != '')
                <p>{!! $data['content']['content'] !!}</p>
                @endif
            </div>

            @endif
            @endif


            @if($data['type'] == 'onlytitle')
            <!-- Only Title -->
            @if(isset($data['content']['content']) && $data['content']['content'] != '')
            <h2>
                {!! $data['content']['content'] !!}
            </h2>
            @endif
            @endif

            @if($data['type'] == 'formdata')
            @include('layouts.builder-sections.formbuilder')
            @endif
        </div>
    </div>
    @endif


    @if($data['subtype'] == 'TwoColumns_3')
    <div class='col-sm-4'>
        <div class='card-box'>
            @if($data['type'] == 'onlyimage')
            @if(isset($data['content']['image']) && $data['content']['image'] != '')
            @if(isset($data['content']['alignment']) && $data['content']['alignment'] == 'image-lft-txt')
            <div class="left_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'image-rt-txt')
            <div class="right_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'image-center-txt')
            <div class="center_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @endif
            @endif
            @endif


            @if($data['type'] == 'onlydocument')
            <!-- Only Document -->
            @if(isset($data['content']['document']) && $data['content']['document'] != '')
            @if(!empty($data['content']['document']))
            <div class="ac-mb-xs-15"></div>
            <div class="download_files clearfix">
                @php
                $docsAray = explode(',', $data['content']['document']);
                $docObj   = App\Document::getDocDataByIds($docsAray);
                @endphp
                @if(count($docObj) > 0)
                <ul>
                    @foreach($docObj as $key => $val)
                    @php
                    if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
                    $blank = 'target="_blank"';
                    }else{
                    $blank = '';
                    }
                    if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
                    $icon = "fi flaticon-pdf-file";
                    }elseif($val->varDocumentExtension == 'doc' || $val->varDocumentExtension == 'docx'){
                    $icon = "fi flaticon-doc-file";
                    }elseif($val->varDocumentExtension == 'xls' || $val->varDocumentExtension == 'xlsx'){
                    $icon = "fi flaticon-xls-file";
                    }else{
                    $icon = "fi flaticon-doc-file";
                    }
                    @endphp  
                    <li><a {!! $blank !!} href="{{ $CDN_PATH.'documents/'.$val->txtSrcDocumentName.'.'.$val->varDocumentExtension }}" title="{{ $val->txtDocumentName }}.{{ $val->varDocumentExtension }}"><i class="{{ $icon }}"></i>{{ $val->txtDocumentName }}.{{ $val->varDocumentExtension }}</a></li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endif
            @endif
            @endif


            @if($data['type'] == 'imgcontent')
            <!-- Only Left Text and Right Image -->
            @if(isset($data['content']['alignment']) && $data['content']['alignment'] == 'lft-txt')
            <div class="left_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <!-- Only Right Text and Left Image -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'rt-txt')
            <div class="right_img_cms">
                <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            </div>
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <!-- Only Top Image -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'top-txt')
            <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <!-- Only Bottom Image -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'bot-txt')
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <div class="ac-mb-xs-15"></div>
            <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'center-txt')
            @if($data['content']['title'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            @endif
            {!! $data['content']['content'] !!}
            <div class="ac-mb-xs-15"></div>
            <img src="{{  App\Helpers\resize_image::resize($data['content']['image']) }}" alt="{{ $data['content']['title'] }}">
            @endif
            @endif


            @if($data['type'] == 'videocontent')
            <!-- Only Left Text and Right Video -->
            @if(isset($data['content']['title']) && $data['content']['alignment'] == 'lft-txt')
            <div class='col-sm-12'>
                <div class="row">
                    <div class="col-sm-5 col-xs-12 cms about-left animated fadeInLeft">
                        <div class="about_image">
                            <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                        </div>
                    </div>
                    <div class="col-sm-7 col-xs-12 cms about-left animated fadeInRight">
                        @if($data['content']['title'] != '')
                        <div class="same_title">
                            <h2>{{ $data['content']['title'] }}</h2>
                        </div>
                        @endif
                        <div class="info">
                            {!! $data['content']['content'] !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Only Right Text and Left Video -->
            @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'rt-txt')
            <div class='col-sm-12'>
                <div class="row">
                    <div class="col-sm-7 col-xs-12 cms about-left animated fadeInLeft">
                        <div class='visible-xs'>
                            <div class="about_image">
                                <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                            </div>
                        </div>
                        @if($data['content']['title'] != '')
                        <div class="same_title">
                            <h2>{{ $data['content']['title'] }}</h2>
                        </div>
                        @endif
                        <div class="info">
                            {!! $data['content']['content'] !!}
                        </div>
                    </div>
                    <div class="col-sm-5 col-xs-12 cms about-left animated fadeInRight hidden-xs">
                        <div class="about_image">
                            <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Only Top Video -->
            @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'top-txt')
            <div class="cms about-left animated fadeInUp">
                <div class='about_full'>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                    @if($data['content']['title'] != '')
                    <div class="same_title">
                        <h2>{{ $data['content']['title'] }}</h2>
                    </div>
                    @endif
                    <div class="info">
                        {!! $data['content']['content'] !!}
                    </div>
                </div>
            </div>
            <!-- Only Bottom Video -->
            @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'bot-txt')
            <div class="cms about-left animated fadeInUp">
                <div class='about_full'>
                    @if($data['content']['title'] != '')
                    <div class="same_title">
                        <h2>{{ $data['content']['title'] }}</h2>
                    </div>
                    @endif
                    <div class="info">
                        {!! $data['content']['content'] !!}
                    </div>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                </div>
            </div>
             @elseif(isset($data['content']['title']) && $data['content']['alignment'] == 'center-txt')

            <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
                <div class='about_full'>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                    @if($data['content']['title'] != '')
                    <div class="same_title">
                        <h2 class="title_div">{{ $data['content']['title'] }}</h2>
                    </div>
                    @endif
                    <div class="info">
                        {!! $data['content']['content'] !!}
                    </div>
                </div>
            </div>
            @endif
            @endif

            @if($data['type'] == 'buttondata')
            <!-- Only Right Button -->
            @if(isset($data['content']['alignment']) && $data['content']['alignment'] == 'button-rt-txt')
            <div class="animated fadeInUp text-right load">               
                <a class="btn ac-border " href="{{ $data['content']['content'] }}"  target='{{ $data['content']['target'] }}' title="{{ $data['content']['title'] }}">{{ $data['content']['title'] }}</a>               
            </div>
            <!-- Only Left Button -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'button-lft-txt')
            <div class="animated fadeInUp text-left load">               
                <a class="btn ac-border " href="{{ $data['content']['content'] }}"  target='{{ $data['content']['target'] }}' title="{{ $data['content']['title'] }}">{{ $data['content']['title'] }}</a>               
            </div>
            <!-- Only Center Button -->
            @elseif(isset($data['content']['alignment']) && $data['content']['alignment'] == 'button-center-txt')
            <div class="animated fadeInUp text-center load">               
                <a class="btn ac-border " href="{{ $data['content']['content'] }}" target='{{ $data['content']['target'] }}' title="{{ $data['content']['title'] }}">{{ $data['content']['title'] }}</a>               
            </div>
            @endif
            @endif


            @if($data['type'] == 'twotextarea')
            <!-- 2 Part Content -->
            @if(isset($data['content']['leftcontent']) && $data['content']['leftcontent'] != '')
            <div class="row"> 
                <div class="col-sm-6 col-md-6">
                    {!! $data['content']['leftcontent'] !!}
                </div>  
                <div class="col-sm-6 col-md-6">
                    {!! $data['content']['rightcontent'] !!}
                </div>            
            </div>
            @endif
            @endif

            @if($data['type'] == 'textarea')
            <!-- Only Content -->
            @if(isset($data['content']['content']) && $data['content']['content'] != '')
            {!! $data['content']['content'] !!}
            @endif
            @endif


            @if($data['type'] == 'onlyvideo')
            <!-- Only Video -->
            @if(isset($data['content']['vidId']) && $data['content']['vidId'] != '')
            <h5>{{ $data['content']['title'] }}</h5>
            <br/>
            <iframe width="100%" height="315" src="{{ $data['content']['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
            @endif
            @endif


            @if($data['type'] == 'mapdata')
            <!-- Only Map -->
            @if(isset($data['content']['latitude']) && $data['content']['latitude'] != '')
            <div class="location_map">
                <iframe src="http://maps.google.com/maps?q={{ $data['content']['latitude'] }}, {{ $data['content']['longitude'] }}&output=embed&zoom=9" width="100%" height="300" frameborder="0" style="border:0"></iframe>
            </div>
            @endif
            @endif


            @if($data['type'] == 'contactinfodata')
            <!-- Only Contact Info -->
            @if(isset($data['content']['section_address']) && $data['content']['section_address'] != '')

            <div class="mailing_box animated fadeInUp load">
                @if(isset($data['content']['section_address']) && $data['content']['section_address'] != '')
                <h4>Address</h4>
                <p>{{ $data['content']['section_address'] }}</p>
                @endif
                <p>
                    @if(isset($data['content']['section_email']) && $data['content']['section_email'] != '')
                    <b>Email:-</b> <a href="mailto:{{ $data['content']['section_email'] }}" title="{{ $data['content']['section_email'] }}">{{ $data['content']['section_email'] }}</a><br>
                    @endif
                    @if(isset($data['content']['section_phone']) && $data['content']['section_phone'] != '')
                    <b>Phone:-</b><a href="tel:{{ $data['content']['section_phone'] }}"> {{ $data['content']['section_phone'] }}</a><br>
                    @endif
                </p>
                @if(isset($data['content']['content']) && $data['content']['content'] != '')
                <p>{!! $data['content']['content'] !!}</p>
                @endif
            </div>

            @endif
            @endif


            @if($data['type'] == 'onlytitle')
            <!-- Only Title -->
            @if(isset($data['content']['content']) && $data['content']['content'] != '')
            <h2>
                {!! $data['content']['content'] !!}
            </h2>
            @endif
            @endif

            @if($data['type'] == 'formdata')
            @include('layouts.builder-sections.formbuilder')
            @endif
        </div>
    </div>
    @endif
    @if($data['Columns'] == '3')
</div>
<hr/>
@endif