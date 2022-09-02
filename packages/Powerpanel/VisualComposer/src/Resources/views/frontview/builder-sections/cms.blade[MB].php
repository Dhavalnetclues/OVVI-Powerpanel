@if(Request::segment(1) != '')
<div class="row">
    <div class='col-md-12 col-md-12 col-xs-12'>
        <!--        @php
                print_r($data);
                @endphp-->
        <!-- Only Image-->

        @if(isset($data['img']) && $data['img'] != '')
        @if(isset($data['alignment']) && $data['alignment'] == 'image-lft-txt')
        <div class="left_img_cms">
            <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
        </div>
        @elseif(isset($data['alignment']) && $data['alignment'] == 'image-rt-txt')
        <div class="right_img_cms">
            <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
        </div>
        @elseif(isset($data['alignment']) && $data['alignment'] == 'image-center-txt')
        <div class="center_img_cms">
            <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
        </div>
        @endif

        <!-- Only Document -->

        @elseif(isset($data['document']) && $data['document'] != '')

        @if(!empty($data['document']))
        <div class="ac-mb-xs-15"></div>
        <div class="download_files clearfix">
            @php
            $docsAray = explode(',', $data['document']);
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

        <!-- Only Left Text and Right Image -->

        @elseif(isset($data['alignment']) && $data['alignment'] == 'lft-txt')
        <div class="left_img_cms">
            <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
        </div>
        @if($data['title'] != '')
        <div class="same_title">
            <h2 class="title_div">{{ $data['title'] }}</h2>
        </div>
        @endif
        {!! $data['content'] !!}

        <!-- Only Right Text and Left Image -->

        @elseif(isset($data['alignment']) && $data['alignment'] == 'rt-txt')
        <div class="right_img_cms">
            <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
        </div>
        @if($data['title'] != '')
        <div class="same_title">
            <h2 class="title_div">{{ $data['title'] }}</h2>
        </div>
        @endif
        {!! $data['content'] !!}

        <!-- Only Top Image -->

        @elseif(isset($data['alignment']) && $data['alignment'] == 'top-txt')
        <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
        @if($data['title'] != '')
        <div class="same_title">
            <h2 class="title_div">{{ $data['title'] }}</h2>
        </div>
        @endif
        {!! $data['content'] !!}

        <!-- Only Bottom Image -->

        @elseif(isset($data['alignment']) && $data['alignment'] == 'bot-txt')
        @if($data['title'] != '')
        <div class="same_title">
            <h2 class="title_div">{{ $data['title'] }}</h2>
        </div>
        @endif
        {!! $data['content'] !!}
        <div class="ac-mb-xs-15"></div>
        <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
        
        @elseif(isset($data['alignment']) && $data['alignment'] == 'center-txt')
        @if($data['title'] != '')
        <div class="same_title">
            <h2 class="title_div">{{ $data['title'] }}</h2>
        </div>
        @endif
        {!! $data['content'] !!}
        <div class="ac-mb-xs-15"></div>
        <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">


        <!-- Only Left Text and Right Video -->

        @elseif(isset($data['videotitle']) && $data['videoalignment'] == 'lft-txt')
        <div class="row">
            <div class="col-sm-5 col-xs-12 cms about-left animated fadeInLeft">
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
            </div>
            <div class="col-sm-7 col-xs-12 cms about-left animated fadeInRight">
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
            </div>
        </div>


        <!-- Only Right Text and Left Video -->

        @elseif(isset($data['videotitle']) && $data['videoalignment'] == 'rt-txt')
        <div class="row">
            <div class="col-sm-7 col-xs-12 cms about-left animated fadeInLeft">
                <div class='visible-xs'>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                </div>
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
            </div>
            <div class="col-sm-5 col-xs-12 cms about-left animated fadeInRight hidden-xs">
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
            </div>
        </div>
        
          <!-- Only Center Video -->

        @elseif(isset($data['videotitle']) && $data['videoalignment'] == 'center-txt')

        <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
            <div class='about_full'>
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
            </div>
        </div>


        <!-- Only Top Video -->

        @elseif(isset($data['videotitle']) && $data['videoalignment'] == 'top-txt')

        <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
            <div class='about_full'>
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
            </div>
        </div>


        <!-- Only Bottom Video -->

        @elseif(isset($data['videotitle']) && $data['videoalignment'] == 'bot-txt')

        <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
            <div class='about_full'>
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
            </div>
        </div>



        <!-- Only Right Button -->

        @elseif(isset($data['btntitle']) && $data['btnalignment'] == 'button-rt-txt')
        <div class=" animated fadeInUp text-right load">               
            <a class="btn ac-border" href="{{ $data['btncotent'] }}"  target='{{ $data['target'] }}' title="{{ $data['btntitle'] }}">{{ $data['btntitle'] }}</a>               
        </div>

        <!-- Only Left Button -->

        @elseif(isset($data['btntitle']) && $data['btnalignment'] == 'button-lft-txt')
        <div class=" animated fadeInUp text-left load">               
            <a class="btn ac-border" href="{{ $data['btncotent'] }}"  target='{{ $data['target'] }}' title="{{ $data['btntitle'] }}">{{ $data['btntitle'] }}</a>               
        </div>

        <!-- Only Center Button -->

        @elseif(isset($data['btntitle']) && $data['btnalignment'] == 'button-center-txt')
        <div class=" animated fadeInUp text-center load">               
            <a class="btn ac-border" href="{{ $data['btncotent'] }}" target='{{ $data['target'] }}' title="{{ $data['btntitle'] }}">{{ $data['btntitle'] }}</a>               
        </div>

        <!-- 2 Part Content -->

        @elseif(isset($data['leftcontent']) && $data['leftcontent'] != '')
        <div class="row"> 
            <div class="col-sm-6 col-md-6">
                {!! $data['leftcontent'] !!}
            </div>  
            <div class="col-sm-6 col-md-6">
                {!! $data['rightcontent'] !!}
            </div>            
        </div>

        <!-- Only Content -->
        @elseif(isset($data['content']) && $data['content'] != '')
        {!! $data['content'] !!}


        <!-- Only Video -->
        @elseif(isset($data['vidId']) && $data['vidId'] != '')
        <h5>{{ $data['title'] }}</h5>
        <br/>
        <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>

        <!-- Only Map -->
        @elseif(isset($data['latitude']) && $data['latitude'] != '')
        <div class="location_map">
            <iframe src="http://maps.google.com/maps?q={{ $data['latitude'] }}, {{ $data['longitude'] }}&output=embed&zoom=9" width="100%" height="300" frameborder="0" style="border:0"></iframe>
        </div>

        <!-- Only Contact Info -->
        @elseif(isset($data['section_address']) && $data['section_address'] != '')

        <div class="mailing_box animated fadeInUp load">
            @if(isset($data['section_address']) && $data['section_address'] != '')
            <h4>Address</h4>
            <p>{{ $data['section_address'] }}</p>
            @endif
            <p>
                @if(isset($data['section_email']) && $data['section_email'] != '')
                <b>Email:-</b> <a href="mailto:{{ $data['section_email'] }}" title="{{ $data['section_email'] }}">{{ $data['section_email'] }}</a><br>
                @endif
                @if(isset($data['section_phone']) && $data['section_phone'] != '')
                <b>Phone:-</b><a href="tel:{{ $data['section_phone'] }}"> {{ $data['section_phone'] }}</a><br>
                @endif
            </p>
            @if(isset($data['othercontent']) && $data['othercontent'] != '')
            <p>{!! $data['othercontent'] !!}</p>
            @endif
        </div>


        <!-- Only Title -->
        @elseif(isset($data['title']) && $data['title'] != '')
        <h2>
            {!! $data['title'] !!}
        </h2>
        @endif

    </div>
</div>
@else
@if(isset($data['img']) && $data['img'] != '')
@if(isset($data['alignment']) && $data['alignment'] == 'image-lft-txt')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                <div class="left_img_cms">
                    <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                </div>
            </div>
        </div>
    </div>
</section>
@elseif(isset($data['alignment']) && $data['alignment'] == 'image-rt-txt')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                <div class="right_img_cms">
                    <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                </div>
            </div>
        </div>
    </div>
</section>
@elseif(isset($data['alignment']) && $data['alignment'] == 'image-center-txt')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                <div class="center_img_cms">
                    <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Only Document -->

@elseif(isset($data['document']) && $data['document'] != '')

@if(!empty($data['document']))
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                <div class="download_files clearfix">
                    @php
                    $docsAray = explode(',', $data['document']);
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
            </div>
        </div>
    </div>
</section>
@endif

<!-- Only Left Text and Right Image -->

@elseif(isset($data['alignment']) && $data['alignment'] == 'lft-txt')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                <div class="left_img_cms">
                    <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                </div>
                @if($data['title'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['title'] }}</h2>
                </div>
                @endif
                {!! $data['content'] !!}
            </div>
        </div>
    </div>
</section>
<!-- Only Right Text and Left Image -->

@elseif(isset($data['alignment']) && $data['alignment'] == 'rt-txt')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                <div class="right_img_cms">
                    <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                </div>
                @if($data['title'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['title'] }}</h2>
                </div>
                @endif
                {!! $data['content'] !!}
            </div>
        </div>
    </div>
</section>
<!-- Only Top Image -->

@elseif(isset($data['alignment']) && $data['alignment'] == 'top-txt')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                @if($data['title'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['title'] }}</h2>
                </div>
                @endif
                {!! $data['content'] !!}
            </div>
        </div>
    </div>
</section>
<!-- Only Bottom Image -->

@elseif(isset($data['alignment']) && $data['alignment'] == 'bot-txt')
<div class="same_title">
    <h2 class="title_div">{{ $data['title'] }}</h2>
</div>
{!! $data['content'] !!}
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
            </div>
        </div>
    </div>
</section>


@elseif(isset($data['alignment']) && $data['alignment'] == 'center-txt')
<div class="same_title">
    <h2 class="title_div">{{ $data['title'] }}</h2>
</div>
{!! $data['content'] !!}
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
            </div>
        </div>
    </div>
</section>

<!-- Only Left Text and Right Video -->

@elseif(isset($data['videotitle']) && $data['videoalignment'] == 'lft-txt')
<section class="about_sec">
    <div class="container">
        <div class="row">
            <div class="col-sm-5 col-xs-12 cms about-left animated fadeInLeft">
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
            </div>
            <div class="col-sm-7 col-xs-12 cms about-left animated fadeInRight">
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Only Right Text and Left Video -->

@elseif(isset($data['videotitle']) && $data['videoalignment'] == 'rt-txt')

<section class="about_sec">
    <div class="container">
        <div class="row">
            <div class="col-sm-7 col-xs-12 cms about-left animated fadeInLeft">
                <div class='visible-xs'>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                </div>
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
            </div>
            <div class="col-sm-5 col-xs-12 cms about-left animated fadeInRight hidden-xs">
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Only Top Video -->

@elseif(isset($data['videotitle']) && $data['videoalignment'] == 'top-txt')
<section class="about_sec">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
                <div class='about_full'>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                    @if($data['videotitle'] != '')
                    <div class="same_title">
                        <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                    </div>
                    @endif
                    <div class="info">
                        {!! $data['content'] !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Only Bottom Video -->

@elseif(isset($data['videotitle']) && $data['videoalignment'] == 'bot-txt')
<section class="about_sec">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
                <div class='about_full'>
                    @if($data['videotitle'] != '')
                    <div class="same_title">
                        <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                    </div>
                    @endif
                    <div class="info">
                        {!! $data['content'] !!}
                    </div>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Only Content -->
@elseif(isset($data['content']) && $data['content'] != '')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                {!! $data['content'] !!}
            </div>
        </div>
    </div>
</section>
<!-- Only Map -->
@elseif(isset($data['latitude']) && $data['latitude'] != '')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="location_map">
                <iframe src="http://maps.google.com/maps?q={{ $data['latitude'] }}, {{ $data['longitude'] }}&output=embed&zoom=9" width="100%" height="300" frameborder="0" style="border:0"></iframe>
            </div>
        </div>
    </div>
</section>
<!-- 2 Part Content -->

@elseif(isset($data['leftcontent']) && $data['leftcontent'] != '')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 animated fadeInUp text-center load"> 
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        {!! $data['leftcontent'] !!}
                    </div>  
                    <div class="col-sm-6 col-md-6">
                        {!! $data['rightcontent'] !!}
                    </div>  
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Only Contact Info -->
@elseif(isset($data['section_address']) && $data['section_address'] != '')
<section class="section">
    <div class="container">
        <div class="row">
            @if(isset($data['section_address']) && $data['section_address'] != '')
            <div class="col-sm-12">
                <div class="mailing_box animated fadeInUp load">
                    @if(isset($data['section_address']) && $data['section_address'] != '')
                    <h4>Address</h4>
                    <p>{{ $data['section_address'] }}</p>
                    @endif
                    <p>
                        @if(isset($data['section_email']) && $data['section_email'] != '')
                        <b>Email:-</b> <a href="mailto:{{ $data['section_email'] }}" title="{{ $data['section_email'] }}">{{ $data['section_email'] }}</a><br>
                        @endif
                        @if(isset($data['section_phone']) && $data['section_phone'] != '')
                        <b>Phone:-</b><a href="tel:{{ $data['section_phone'] }}"> {{ $data['section_phone'] }}</a><br>
                        @endif
                    </p>
                    @if(isset($data['othercontent']) && $data['othercontent'] != '')
                    <p>{!! $data['othercontent'] !!}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Only Right Button -->

@elseif(isset($data['btntitle']) && $data['btnalignment'] == 'button-rt-txt')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 animated fadeInUp text-right load">               
                <a class="btn ac-border btn-more" href="{{ $data['btncotent'] }}"  target='{{ $data['target'] }}' title="{{ $data['btntitle'] }}">{{ $data['btntitle'] }}</a>               
            </div>
        </div>
    </div>
</section>

<!-- Only Left Button -->

@elseif(isset($data['btntitle']) && $data['btnalignment'] == 'button-lft-txt')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 animated fadeInUp text-left load">               
                <a class="btn ac-border btn-more" href="{{ $data['btncotent'] }}"  target='{{ $data['target'] }}' title="{{ $data['btntitle'] }}">{{ $data['btntitle'] }}</a>               
            </div>
        </div>
    </div>
</section>

<!-- Only Center Button -->

@elseif(isset($data['btntitle']) && $data['btnalignment'] == 'button-center-txt')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 animated fadeInUp text-center load">               
                <a class="btn ac-border btn-more" href="{{ $data['btncotent'] }}" target='{{ $data['target'] }}' title="{{ $data['btntitle'] }}">{{ $data['btntitle'] }}</a>               
            </div>
        </div>
    </div>
</section>

<!-- Only Video -->
@elseif(isset($data['vidId']) && $data['vidId'] != '')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
            </div>
        </div>
    </div>
</section>
<!-- Only Title -->
@elseif(isset($data['title']) && $data['title'] != '')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms">
                <h2>
                    {!! $data['title'] !!}
                </h2>
            </div>
        </div>
    </div>
</section>
@endif
@endif
