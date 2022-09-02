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
                                <li><a href="{{url('/')}}" title="Home">Home</a></li>
                                @php $i = 1; @endphp
                                @foreach($breadcrumb as $key => $value)
                                    @if(count($breadcrumb) == $i)
                                        @if (isset($value['record_title']))
                                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="active">
                                            <a href="{{ url('/') }}/{{ $value['url'] }}" title="{{ $value['record_title'] }}" itemtype="https://schema.org/Thing" itemprop="item">{{ $value['record_title'] }}</a>
                                            <meta itemprop="name" content="{{ $value['record_title'] }}"/>
                                            <meta itemprop="position" content="@php echo $i; @endphp" />
                                        </li>
                                        @else
                                             <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="active">                
                                                <a href="{{ url('/') }}/{{ $value['url'] }}" title="{{ $value['title'] }}" itemtype="https://schema.org/Thing" itemprop="item">{{ $value['title'] }}</a>                
                                                <meta itemprop="name" content="{{ $value['title'] }}"/>                
                                                <meta itemprop="position" content="@php echo $i; @endphp" />                
                                            </li>
                                        @endif
                                    @else
                                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                            <a href="{{ url('/') }}/{{ $value['url'] }}" title="{{ $value['title'] }}" itemtype="https://schema.org/Thing" itemprop="item">{{ $value['title'] }}</a>
                                            <meta itemprop="name" content="{{ $value['title'] }}" />
                                            <meta itemprop="position" content="@php echo $i; @endphp" />
                                        </li>
                                    @endif
                                    @php $i++; @endphp
                                @endforeach
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
                    <div class="ib-fill" style="background-image: url('{{ $CDN_PATH.'assets/images/inner-banner.png' }}'); background-size: cover;"></div>
                    <!-- <div class="ib-fill" style="background: #f5f5f5; background-size: cover;"></div> -->
                </div>
            </div>
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
                        <ul class="ac-breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                              <li><a title="Home" href="{{url('/')}}" class="link">Home</a></li>
                              <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="active">
                                  <a href="{{ url()->current() }}" title="{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}" itemtype="https://schema.org/Thing" itemprop="item">{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}</a>
                                  <meta itemprop="name" content="{{ (!empty($inner_banner_data->varTitle)?$inner_banner_data->varTitle:$currentPageTitle) }}"/>
                                  <meta itemprop="position" content="1" />
                              </li>
                        </ul>
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
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="firstName">Name <span class="req-sign">*</span></label>
                                    <input type="text" class="form-control ac-input" id="firstName" name="firstName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                    <span class="error">Error Massage Here</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="email">Email <span class="req-sign">*</span></label>
                                    <input type="text" class="form-control ac-input" id="email" name="email" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="friendName">Friend's Name <span class="req-sign">*</span></label>
                                    <input type="text" class="form-control ac-input" id="friendName" name="friendName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="friendEmail">Friend's Email <span class="req-sign">*</span></label>
                                    <input type="text" class="form-control ac-input" id="friendEmail" name="friendEmail" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="message">Message</label>
                                    <textarea class="form-control ac-textarea" id="message" name="message" rows="4" minlength="1" maxlength="600" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ac-form-group n-mb-sm-0">
                                    <img src="{{ $CDN_PATH.'assets/images/google-captcha.gif' }}" alt="Google Captcha">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ac-form-group n-mb-0 n-tar-sm n-tal">
                                    <button type="submit" title="Submit" class="ac-btn ac-btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Email To Friend E -->

    <!-- AddToAny BEGIN -->
    <script async src="https://static.addtoany.com/menu/page.js"></script>
    <!-- AddToAny END -->
@endsection