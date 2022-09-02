<!-- Inner Banner S -->
@if(isset($inner_banner_data) && count($inner_banner_data) > 0)
<section class="inner-banner" data-aos="fade-up">
    <div id="inner-banner" class="carousel slide" data-ride="carousel" data-interval="4500" data-pause="hover" data-wrap="true">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            @foreach($inner_banner_data as $key=>$inner_banner)
            <div class="carousel-item @if($key==0) active @endif">
                <div class="ib-fill" style="background-image:url('{!! App\Helpers\resize_image::resize($inner_banner->fkIntImgId,1920,247) !!}'); background-size: cover;"></div>
                <!-- <div class="ib-fill" style="background: #f5f5f5; background-size: cover;"></div> -->
            </div>
            @endforeach
        </div>

        {{-- <!-- Left and right controls S -->
                <a class="ib-control left waves-effect waves-light ac-btn ac-btn-primary" href="#inner-banner" data-slide="prev" title="Previous"><i class="fa fa-arrow-left"></i></a>
                <a class="ib-control right waves-effect waves-light ac-btn ac-btn-primary" href="#inner-banner" data-slide="next" title="Next"><i class="fa fa-arrow-right"></i></a>
            <!-- Left and right controls E --> --}}

    </div>

    <div class="ib-caption">
        <div class="container">
            <div class="row align-content-center justify-content-center gap">
                <div class="col-sm-12 text-center">
                    <h1 class="nqtitle text-uppercase n-fw-800 n-fc-white-500">{{ isset($detailPageTitle) ?$detailPageTitle:strtoupper($currentPageTitle) }}</h1>
                </div>
            </div>
            <div class="row ib-breadcrumb">
                <div class="col-sm-12 text-left n-mv-15">
                    @if(isset($breadcrumb) && count($breadcrumb)>0)

                    <ul class="ac-breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                        <li><a title="Home" href="{{url('/')}}" class="link">Home</a></li>

                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" >
                            <a href="{{ $breadcrumb['url'] }}" title="{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}" itemtype="https://schema.org/Thing" itemprop="item">{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}</a>
                            <meta itemprop="name" content="{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}"/>
                            <meta itemprop="position" content="1" />
                        </li>
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="active">
                            {{ $breadcrumb['title'] }}
                        </li>

                    </ul>
                    @else

                    <ul class="ac-breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                        <li><a title="Home" href="{{url('/')}}" class="link">Home</a></li>
                        @php  $segment1 = Request::segment(1);
                        @endphp
                        @if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment1))) 
                        <li><a title="Home" href="{{url($segment1)}}" class="link">{{$segment1}}</a></li>
                        @endif
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="active">
                            <a href="{{ url()->current() }}" title="{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}" itemtype="https://schema.org/Thing" itemprop="item">{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}</a>

                        </li>
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@else
<section class="inner-banner" data-aos="fade-up">
    <div id="inner-banner" class="carousel slide" data-ride="carousel" data-interval="4500" data-pause="hover" data-wrap="true">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="ib-fill" style="background-image: url('{{ $CDN_PATH.'assets/images/inner-banner-1.png' }}'); background-size: cover;"></div>
                <!-- <div class="ib-fill" style="background: #f5f5f5; background-size: cover;"></div> -->
            </div>
        </div>
    </div>

    <div class="ib-caption">
        <div class="container">
            <div class="row align-content-center justify-content-center gap">
                <div class="col-sm-12 text-center">
                    <?php // echo '<Pre>';print_r($detailPageTitle);exit;?>
                    <h1 class="nqtitle text-uppercase n-fw-800 n-fc-white-500">{{ strtoupper($currentPageTitle) }}</h1>
                </div>
            </div>
            <div class="row ib-breadcrumb">
                <div class="col-sm-12 text-left n-mv-15">
                    @if(isset($breadcrumb) && count($breadcrumb)>0)

                    <ul class="ac-breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                        <li><a title="Home" href="{{url('/')}}" class="link">Home</a></li>

                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" >
                            <a href="{{ $breadcrumb['url'] }}" title="{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}" itemtype="https://schema.org/Thing" itemprop="item">{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}</a>
                            <meta itemprop="name" content="{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}"/>
                            <meta itemprop="position" content="1" />
                        </li>
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="active">
                            {{ $breadcrumb['title'] }}
                        </li>

                    </ul>
                    @else

                    <ul class="ac-breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                        <li><a title="Home" href="{{url('/')}}" class="link">Home</a></li>
                        @php  $segment1 = Request::segment(1);

                        @endphp

                        @if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment1))) 

                        <li><a title="Home" href="{{url($segment1)}}" class="link">{{$segment1}}</a></li>
                        @endif
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="active">
                            <a href="{{ url()->current() }}" title="{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}" itemtype="https://schema.org/Thing" itemprop="item">{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}</a>

                        </li>
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!-- Inner Banner E -->
@section('footer_scripts')
<!-- Email To Friend S -->
<div class="modal fade ac-modal" id="emailtoFriendModal" tabindex="-1" aria-labelledby="emailtoFriendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="n-fs-18 n-fw-600 n-ff-2 n-fc-white-500 n-lh-130">Email To Friend</div>
                <a href="javascript:void(0)" data-dismiss="modal" aria-label="Close" class="ac-close">&times;</a>
            </div>

            <div class="modal-body ac-form-wd">
                {!! Form::open(['url' => '/emailToFriend', 'method' => 'post','class'=>'w-100 emailToFriend_form','id'=>'emailToFriend_form']) !!}
                    <div class="row">
                        <input class="form-control" type="hidden" id="CurrentPageUrl" name="CurrentPageUrl" value="{{Request::url()}}" />
                        <div class="col-sm-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="firstName">Name <span class="req-sign">*</span></label>
                                {!! Form::text('name', '', array('id'=>'name', 'class'=>'form-control ac-input', 'maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="email">Email <span class="req-sign">*</span></label>
                                {!! Form::email('email', '', array('id'=>'email', 'class'=>'form-control ac-input', 'maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="friendName">Friend's Name <span class="req-sign">*</span></label>
                                {!! Form::text('friendName', '', array('id'=>'friendName', 'class'=>'form-control ac-input', 'maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="friendEmail">Friend's Email <span class="req-sign">*</span></label>
                                {!! Form::email('friendEmail', '', array('id'=>'friendEmail', 'class'=>'form-control ac-input', 'maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="message">Message</label>
                                {!! Form::textarea('message', '', array('id'=>'message', 'class'=>'form-control ac-textarea', 'rows'=>'4', 'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'600', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ac-form-group n-mb-sm-0">
                                <div id="contactus_html_element" class="g-recaptcha"></div>
                                <div class="capphitcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ac-form-group n-mb-0 n-tar-sm n-tal">
                                <button type="submit" title="Submit" class="ac-btn ac-btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- Email To Friend E -->
<script type="text/javascript">
    var sitekey = '{{Config::get("Constant.GOOGLE_CAPCHA_KEY")}}';
    var onContactloadCallback = function () {
        grecaptcha.render('contactus_html_element', {
            'sitekey': sitekey
        });
    };
</script>

<script src="https://www.google.com/recaptcha/api.js?onload=onContactloadCallback&render=explicit" async defer></script>
<script src="{{ $CDN_PATH.'assets/js/emailtofriend.js' }}"></script>
<!-- AddToAny BEGIN -->
<script async src="https://static.addtoany.com/menu/page.js"></script>
<!-- AddToAny END -->
@endsection